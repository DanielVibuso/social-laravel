<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request);

            return response()->json(['data' => $data], 200);
        } catch (InvalidCredentialsException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json('erro interno', 422);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request);

            return response()->json([], 204);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json('erro interno', 422);
        }
    }

    /**
     * @param ForgotPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $this->authService->forgotPassword($request);

            return response()->json(['message' => 'Enviado email para recuperação de senha'], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json('erro interno', 422);
        }
    }

    /**
     * @param UpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            if ($this->authService->updatePassword($request)) {
                return response()->json(['message' => 'senha alterada com sucesso'], 200);
            }

            return response()->json(['message' => 'não foi possível redefinir a senha'], 422);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response()->json('erro interno', 422);
        }
    }
}
