<?php

namespace App\Domains\Interfaces\Repositories;

use App\Infrastructure\Database\Models\Simulation;
use Illuminate\Support\Collection;

interface ISimulationRepository
{
    public function store(array $data): Simulation;

    public function findAll();
}
