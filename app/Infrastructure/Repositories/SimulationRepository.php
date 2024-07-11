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
     * @param Request $request
     * @return Simulation
     */
    public function store(Request $request): Simulation
    {
        return $this->simulation->create($request->all());
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function findAll(Request $request): Collection
    {
        return $this->simulation->get($request->all());
    }
}
