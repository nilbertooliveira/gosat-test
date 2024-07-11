<?php

namespace App\Domains\Interfaces\Services;

use App\Application\Services\ResponseService;
use Illuminate\Http\Request;

interface IUserService
{
    public function store(Request $request): ResponseService;
}
