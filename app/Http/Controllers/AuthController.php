<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (! $token = JWTAuth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }
    
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            Log::error('Erro ao tentar autenticar', [
                'error' => $e->getMessage(),
                'credentials' => $request->only('email')
            ]);

            return response()->json(['error' => 'Erro ao tentar autenticar'], 500);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function me()
    {
        return response()->json(JWTAuth::user());
    }
}
