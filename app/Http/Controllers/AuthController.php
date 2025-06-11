<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);
            if (! $token) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }
    
            return response()->json([
                'token' => $token,
                'date' => Carbon::now()->format('d/m/Y H:i:s'),
                'email' => $credentials['email'],
            ]);
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
