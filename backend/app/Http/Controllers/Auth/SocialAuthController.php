<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ProfileEnum;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

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
                // Se não encontrar, procura pelo email na tabela de usuários
                //$user = User::where('email', $socialUser->getEmail())->first();
    
                //if (!$user) {
                    // Cria um novo usuário sem nome e sem senha
                    $user = User::create([
                        'profile_id' => Profile::where('name', ProfileEnum::USER->value)->first()->id,
                        'email' => $socialUser->getEmail() ?? null,
                        'name' => null, // Nome será definido depois
                        'password' => null, // Não precisa de senha para login social
                    ]);
                //}
    
                // Associa a conta social ao novo usuário
                SocialAccount::create([
                    'user_id' => $user->id,
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'token' => $socialUser->token,
                ]);
            }
    
            // Gera um token de autenticação
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Responde com o token, dados do usuário e se precisa ou não definir o nome
            return response()->json([
                'token' => $token,
                'user' => $user,
                'needs_name' => is_null($user->name) // Indica se precisa definir um nome
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage() . $e->getLine()], 500);
        }
    }
}