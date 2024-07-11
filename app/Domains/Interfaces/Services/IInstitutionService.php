<?php

namespace App\Domains\Interfaces\Services;

use App\Application\Services\ResponseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IInstitutionService
{
    public function store(Request $request): ResponseService;

    public function findAll(Request $request): ResponseService;
}
