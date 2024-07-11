<?php

namespace App\Domains\Interfaces\Services;

use App\Application\Services\ResponseService;
use Illuminate\Http\Request;

interface IModalityService
{
    public function store(Request $request): ResponseService;

    public function findAll(Request $request): ResponseService;
}
