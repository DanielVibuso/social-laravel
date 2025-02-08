<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//authentication and authorization verified routes
Route::middleware('auth:sanctum')->prefix('auth')->name('auth.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*authorization tests*/
    Route::name('authorization.')->group(function () {
        Route::get('/test', function () {
            return response()->json('Access right granted');
        })->name('teste')->middleware('ability:auth.test');

        Route::get('/test-admin', function () {
            return response()->json('Access right granted');
        })->name('teste-admin')->middleware('ability:auth.test.admin');

        Route::get('/test-admin-user', function () {
            return response()->json('Access right granted');
        })->name('teste-admin-user')->middleware('ability:auth.test.admin.user');
    });
});

//free routes/guest routes
Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/forgot-password', 'forgotPassword')->name('forgot.password');
    Route::post('/update-password', 'updatePassword')->name('update.password');
});


Route::middleware('auth:sanctum')->post('auth/set-name', [SocialAuthController::class, 'setName']);


//users
Route::middleware('auth:sanctum')->prefix('user')->name('user.')->controller(UserController::class)->group(function () {
    Route::get('/me', 'me')->name('me');
    Route::post('/', 'store')->name('store')->middleware('ability:user.store');
    Route::put('/{id}', 'update')->name('update')->middleware('ability:user.update');
    Route::get('/', 'index')->name('index')->middleware('ability:user.index');
    Route::get('/{id}', 'show')->name('show')->middleware('ability:user.show');
    Route::delete('/{id}', 'destroy')->name('destroy')->middleware('ability:user.destroy');
});

//Profiles
Route::middleware('auth:sanctum')->prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
    Route::middleware('ability:profile.index')->get('/', 'index')->name('index');
    Route::middleware('ability:profile.store')->post('/', 'store')->name('store');
    Route::middleware('ability:profile.update')->put('/{id}', 'update')->name('update');
    Route::middleware('ability:profile.show')->get('/{id}', 'show')->name('show');
    Route::middleware('ability:profile.destroy')->delete('/{id}', 'destroy')->name('delete');
    Route::middleware('ability:profile.permissions')->put('/{id}/permissions', 'updatePermissions')->name('updatePermissions');
});

//Permissions
Route::middleware('auth:sanctum')->prefix('permission')->name('permission.')->controller(PermissionController::class)->group(function () {
    Route::middleware('ability:permission.index')->get('/', 'index')->name('index');
    Route::middleware('ability:permission.store')->post('/', 'store')->name('store');
    Route::middleware('ability:permission.update')->put('/{id}', 'update')->name('update');
    Route::middleware('ability:permission.show')->get('/{id}', 'show')->name('show');
    Route::middleware('ability:permission.destroy')->delete('/{id}', 'destroy')->name('delete');
    Route::middleware('ability:permission.profileBinded')->get('/{id}/profileBinded', 'profileBinded')->name('profileBinded');
});


Route::prefix('system')->middleware('auth:sanctum')->group(function () {
    Route::get('health-check', function () {
        return response()->json(['message' => 'OK']);
    });
});

Route::fallback(function () {
    return response()->json([
        'message' => 'INVALID URL',
    ], 404);
});
