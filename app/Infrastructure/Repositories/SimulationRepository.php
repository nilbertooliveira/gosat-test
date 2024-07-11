<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Interfaces\Repositories\ISimulationRepository;
use App\Infrastructure\Database\Models\Simulation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SimulationRepository implements ISimulationRepository
{
    private Simulation $simulation;

    /**
     * @param Simulation $simulation
     */
    public function __construct(Simulation $simulation)
    {
        $this->simulation = $simulation;
    }

    /**
     * @param array $data
     * @return Simulation
     */
    public function store(array $data): Simulation
    {
        return $this->simulation->create($data);
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function findAll(Request $request): Collection
    {
        return $this->simulation->get();
    }
}
