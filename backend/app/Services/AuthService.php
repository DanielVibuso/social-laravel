<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * @param LoginRequest $request
     *
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new InvalidCredentialsException('wrong user or password');
        }

        return [
            'token' => $user->createToken($request->device_name ?? '', [...$user->getPermissions()])->plainTextToken,
            'permissions' => $user->getPermissions(),
            'user' => array_merge(
                $user->toArray(),
                ['profile' => $user->profile?->name, 'name' => $user->name ?? 'Admin']
            )];
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function logout(Request $request)
    {
        return $request->user()->currentAccessToken()->delete();
    }

    /**
     * @param ForgotPasswordRequest $request
     *
     * @return bool
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        return Password::sendResetLink($request->only('email')) === Password::RESET_LINK_SENT;
    }

    /**
     * @param UpdatePasswordRequest $request
     *
     * @return bool
     */
    public function updatePassword(UpdatePasswordRequest $request): bool
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET;
    }
}
