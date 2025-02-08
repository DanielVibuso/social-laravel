<?php

use App\Enums\ProfileEnum;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

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

it('can be access only for admin', function () {
    $admin = User::where('email', 'admin@curotec.com.br')->first();
    $user = User::where('email', 'user@curotec.com.br')->first();

    Sanctum::actingAs($admin, $admin->getPermissions());
    $this->json('get', 'api/auth/test')->assertStatus(200);

    Sanctum::actingAs($user, $user->getPermissions());
    $this->json('get', 'api/auth/test')->assertStatus(403);
});

it('can be access only for admin and user', function () {
    $admin = User::where('email', 'admin@curotec.com.br')->first();
    $user = User::where('email', 'user@curotec.com.br')->first();

    Sanctum::actingAs($admin, $admin->getPermissions());
    $this->json('get', 'api/auth/test-admin-user')->assertStatus(200);

    Sanctum::actingAs($user, $user->getPermissions());
    $this->json('get', 'api/auth/test-admin-user')->assertStatus(200);
});
