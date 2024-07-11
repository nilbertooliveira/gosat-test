<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Interfaces\Repositories\IModalityRepository;
use App\Infrastructure\Database\Models\Modality;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ModalityRepository implements IModalityRepository
{
    private Modality $modality;

    /**
     * @param Modality $modality
     */
    public function __construct(Modality $modality)
    {
        //
        $this->modality = $modality;
    }

    /**
     * @param Request $request
     * @return Modality
     */
    public function store(Request $request): Modality
    {
        return $this->modality->create($request->all());
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function findAll(Request $request): Collection
    {
        return $this->modality->get($request->all());
    }
}
