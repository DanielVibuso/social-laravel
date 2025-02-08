<?php

use App\Enums\ProfileEnum;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(Tests\TestCase::class);

beforeEach(function () {
    Profile::create(['name' => ProfileEnum::ADMIN->value, 'description' => 'Total access']);
    Profile::create(['name' => ProfileEnum::USER->value, 'description' => 'Partial access']);

    User::factory()->create([
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
        'email' => 'admin@curotec.com.br',
        'password' => Hash::make('qwe123'),
    ]);

    User::factory()->create([
        'profile_id' => Profile::where('name', ProfileEnum::USER->value)->first()->id,
        'email' => 'user@curotec.com.br',
        'password' => Hash::make('qwe123'),
    ]);


    $permissionAdminRouteForAdmin = Permission::create(['name' => 'auth.test', 'description' => 'Only admin can access']);
    Profile::where('name', 'Admin')->first()->permissions()->attach($permissionAdminRouteForAdmin);

    $permissionAdminAndUserRouteForAdminAndUser = Permission::create(['name' => 'auth.test.admin.user', 'description' => 'Admin and user can access']);
    Profile::where('name', 'Admin')->first()->permissions()->attach($permissionAdminAndUserRouteForAdminAndUser);
    Profile::where('name', 'User')->first()->permissions()->attach($permissionAdminAndUserRouteForAdminAndUser);
});

it('Login with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'email_valido@email.com',
        'password' => Hash::make('wrong_pass'),
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
    ]);
    
    $data = [
        'email' => $user->email,
        'password' => '111',
        'device_name' => 'deskfront',
    ];
    $response = $this->postJson('/api/auth/login', $data);
    $response->assertStatus(401);
});

it('Login successfull case', function () {
    $user = User::factory()->create([
        'email' => 'email_valido@email.com',
        'password' => 'qwe123',
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
    ]);

    $data = [
        'email' => $user->email,
        'password' => 'qwe123',
        'device_name' => 'SPA',
    ];
    $response = $this->postJson('/api/auth/login', $data);
    $response->assertStatus(200);
});

it('can make logout', function () {
    Sanctum::actingAs(User::factory()->create([
        'email' => 'email_valido@email.com',
        'password' => Hash::make('qwe123'),
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
    ]));

    $response = $this->postJson('/api/auth/logout', []);
    $response->assertStatus(204);
});

it('not logged user cant logout', function () {
    $response = $this->postJson('/api/auth/logout', []);
    $response->assertStatus(401);
});

it('forgot password with valid mail', function () {
    $user = User::factory()->create([
        'email' => 'admin@curotec.com',
        'password' => Hash::make('qwe123'),
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
    ]);

    $response = $this->postJson('/api/auth/forgot-password', ['email' => $user->email]);
    $response->assertStatus(200);
});

it('reset password with valid token', function () {
    $user = User::factory()->create();

    $token = Password::broker()->createToken($user);

    $password = 'my_new_password123';

    $newData = [
        'email' => $user->email,
        'token' => $token,
        'password' => 'newpass1234',
        'password_confirmation' => 'newpass1234',
    ];

    $response = $this->postJson('/api/auth/update-password', $newData);
    $response->assertStatus(200);
});

it('reset password with invalid email', function () {
    $user = User::factory()->create();

    $token = Password::broker()->createToken($user);

    $password = 'my_new_password123';

    $newData = [
        'email' => 'email@false.com.br',
        'token' => $token,
        'password' => 'newpass1234',
        'password_confirmation' => 'newpass1234',
    ];

    $response = $this->postJson('/api/auth/update-password', $newData);
    $response->assertStatus(422);
});
