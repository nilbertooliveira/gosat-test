<?php

namespace App\Domains\Interfaces\Services;

use App\Application\Services\ResponseService;
use Illuminate\Http\Request;

interface IAuthService
{
    public function login(Request $request): ResponseService;

    public function logout(Request $request): ResponseService;
}
