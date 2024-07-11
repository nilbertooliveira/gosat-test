<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Interfaces\Repositories\ISimulationRepository;
use App\Infrastructure\Database\Models\Simulation;
use Illuminate\Support\Collection;


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


    public function findAll(): Collection
    {
        $simulations = $this->simulation->with('modality.institution')->get();

        return $simulations->groupBy('modality.institution.name')
            ->map(function ($institutionGroup) {
                return $institutionGroup->sum('total_amount');
            });
    }
}
