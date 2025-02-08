<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ProfileEnum;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $socialAccount = SocialAccount::where('provider', $provider)
                                      ->where('provider_id', $socialUser->getId())
                                      ->first();

            if ($socialAccount) {
                $user = $socialAccount->user;
            } else {
                    $user = User::create([
                        'profile_id' => Profile::where('name', ProfileEnum::USER->value)->first()->id,
                        'email' => $socialUser->getEmail() ?? null,
                        'name' => null, 
                        'password' => null,
                    ]);
    
                SocialAccount::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'token' => $socialUser->token,
                ]);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'token' => $token,
                'user' => $user,
                'needs_name' => is_null($user->name) 
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage() . $e->getLine()], 500);
        }
    }

    public function setName(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
        ]);

        $user = auth()->user();
        $user->name = $validated['name'];
        $user->save();

        return response()->json([
            'user' => $user,
            'needs_name' => false, 
        ]);
    }
}