<?php

namespace App\Domains\Interfaces\Repositories;

use App\Infrastructure\Database\Models\Simulation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ISimulationRepository
{
    public function store(array $data): Simulation;

    public function findAll(Request $request): Collection;
}
