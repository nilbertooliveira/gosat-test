<?php

namespace App\Application\Controllers;

use App\Application\Requests\LoginRequest;
use App\Domains\Interfaces\Services\IAuthService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    use AuthenticatesUsers;

    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    protected function authenticated(Request $request, $user): mixed
    {
        $response = $this->authService->login($request);

        return redirect()->intended(route('simulation.credit-view', absolute: false))->with('token', $response->getData()['token']);
    }

    public function customLogin(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request);

        if (!$result->isSuccess()) {
            return response()->json($result->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($result->toArray(), Response::HTTP_OK);
    }


    public function logout(Request $request): JsonResponse
    {
        $result = $this->authService->logout($request);

        if (!$result->isSuccess()) {
            return response()->json($result->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($result->toArray(), Response::HTTP_OK);
    }
}
