<?php

use App\Enums\ProfileEnum;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;

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

test('create new user', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);

    $newUser = [
        'name' => 'lorem',
        'email' => 'lorem@ipsum.com.br',
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
        'password' => 123456789,
    ];

    $response = $this->postJson('api/user', $newUser);
    $response->assertStatus(201);
});

test('create user missing required fields must return 422', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);

    $newUser = [
        'profile_id' => Profile::where('name', ProfileEnum::ADMIN->value)->first()->id,
        'password' => 123456789,
    ];

    $response = $this->postJson('api/user', $newUser);

    $response->assertStatus(422);
});

test('updating user successfully', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);

    $userToBeUpdate = User::factory()->create();

    $oldEmail = $userToBeUpdate->email;

    $newEmail = 'LoremIpsum@curotec.com.br';

    $this->assertNotEquals($oldEmail, $newEmail);

    $response = $this->putJson("api/user/{$userToBeUpdate->id}", ['email' => $newEmail]);

    $this->assertNotEquals($userToBeUpdate->email, $newEmail);

    $response->assertStatus(200);
});

test('delete user successfully', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);

    $userToBeDeleted = User::factory()->create();

    $this->assertDatabaseHas('users', [
        'email' => $userToBeDeleted->email,
    ]);

    $response = $this->delete("api/user/{$userToBeDeleted->id}");

    $response->assertStatus(204);
});

test('delete user that dont exists should return 404', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);

    $uuid = Str::uuid();
    $response = $this->delete("api/user/{$uuid}");

    $response->assertStatus(404);
});

test('get user by id successfully', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);

    $user = User::factory()->create();

    $this->assertDatabaseHas('users', [
        'email' => $user->email,
    ]);

    $response = $this->call('get', "api/user/{$user->id}");

    $response->assertStatus(200);
});

test('get user by id undefined give 404 ', function () {
    $adminUser = User::where('email', 'admin@curotec.com.br')->first();

    Sanctum::actingAs($adminUser, ['*']);
    $uuid = Str::uuid();
    $this->assertDatabaseMissing('users', [
        'id' => $uuid
    ]);

    $response = $this->delete("api/user/{$uuid}");

    $response->assertStatus(404);
});
