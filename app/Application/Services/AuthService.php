<?php

namespace App\Application\Services;

use App\Application\Requests\LoginRequest;
use App\Domains\Interfaces\Services\IAuthService;
use App\Infrastructure\Database\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthService implements IAuthService
{
    /**
     * @throws AuthenticationException
     */
    public function login(Request $request): ResponseService
    {
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                throw new AuthenticationException('Credenciais inválidas!');
            }

            $user = Auth::user();

            $token = $user->createToken('token')->plainTextToken;
            $result = new ResponseService([
                'token' => $token
            ]);

        } catch (\Throwable $e) {
            $result = new ResponseService($e->getMessage(), false);
        }
        return $result;
    }

    public function logout(Request $request): ResponseService
    {
        try {
            /** @var User $user */
            $user = Auth::guard('sanctum')->user();

            $user->tokens()->delete();

            $result = new ResponseService('Successfully logged out!');

        } catch (\Throwable $e) {
            $result = new ResponseService($e->getMessage(), false);
        }
        return $result;
    }
}
