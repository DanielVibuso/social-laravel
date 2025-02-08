<?php

namespace Tests\Feature\Auth;

use App\Enums\ProfileEnum;
use App\Models\Profile;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Tests\TestCase;
use Mockery;

class SocialAuthTest extends TestCase
{
    use RefreshDatabase; // Para garantir que o banco de dados seja reiniciado entre os testes

    public function testHandleProviderCallbackUserExists()
    {
        Profile::create(['name' => ProfileEnum::USER->value, 'description' => 'Partial access']);
        // Mocking do Socialite - agora mockamos diretamente o driver de Twitter
        $socialite = Mockery::mock(SocialiteFactory::class);
        $this->app->instance(SocialiteFactory::class, $socialite);

        $socialUser = Mockery::mock(SocialiteUser::class);
        $socialUser->shouldReceive('getId')->andReturn('12345');
        $socialUser->shouldReceive('getEmail')->andReturn('user@example.com');
        $socialUser->shouldReceive('token')->andReturn('mocked_token');
        
        $socialite->shouldReceive('driver')->with('twitter')->andReturnSelf();
        $socialite->shouldReceive('user')->andReturn($socialUser);

        // Cria um usuário na tabela User para simular a existência
        $user = User::create([
            'profile_id' => Profile::where('name', ProfileEnum::USER->value)->first()->id,
            'email' => 'user@example.com',
            'name' => 'John Doe',
            'password' => null,
        ]);

        // Cria uma conta social vinculada ao usuário
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => 'twitter',
            'provider_id' => '12345',
            'token' => 'mocked_token',
        ]);

        // Chama o método do controller que lida com o callback do Socialite
        $response = $this->json('GET', '/auth/twitter/callback');

        // Assertivas
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'user',
            'needs_name'
        ]);
        $this->assertEquals($user->id, $response->json('user.id'));
        $this->assertFalse($response->json('needs_name'));
    }

    public function testHandleProviderCallbackExistingUserWithoutName()
    {
        Profile::create(['name' => ProfileEnum::USER->value, 'description' => 'Partial access']);

        // Mocking do Socialite
        $socialite = Mockery::mock(SocialiteFactory::class);
        $this->app->instance(SocialiteFactory::class, $socialite);

        $socialUser = Mockery::mock(SocialiteUser::class);
        $socialUser->shouldReceive('getId')->andReturn('12345');
        $socialUser->shouldReceive('getEmail')->andReturn('userwithoutname@example.com');
        $socialUser->shouldReceive('token')->andReturn('mocked_token');
        
        $socialite->shouldReceive('driver')->with('twitter')->andReturnSelf();
        $socialite->shouldReceive('user')->andReturn($socialUser);

        // Cria um usuário sem nome
        $user = User::create([
            'profile_id' => Profile::where('name', ProfileEnum::USER->value)->first()->id,
            'email' => 'userwithoutname@example.com',
            'name' => null,  // Nome está faltando
            'password' => null,
        ]);

        // Cria uma conta social vinculada ao usuário
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => 'twitter',
            'provider_id' => '12345',
            'token' => 'mocked_token',
        ]);

        // Chama o método do controller que lida com o callback do Socialite
        $response = $this->json('GET', '/auth/twitter/callback');

        // Assertivas
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'user',
            'needs_name'
        ]);
        $this->assertEquals($user->id, $response->json('user.id'));
        $this->assertTrue($response->json('needs_name'));
    }
}
