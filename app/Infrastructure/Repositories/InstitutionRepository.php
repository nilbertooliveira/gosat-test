<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Interfaces\Repositories\IInstitutionRepository;
use App\Infrastructure\Database\Models\Institution;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class InstitutionRepository implements IInstitutionRepository
{
    private Institution $institution;

    /**
     * @param Institution $institution
     */
    public function __construct(Institution $institution)
    {
        $this->institution = $institution;
    }

    /**
     * @param Request $request
     * @return Institution
     */
    public function store(Request $request): Institution
    {
        return $this->institution->create($request->all());
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function findAll(Request $request): Collection
    {
        return $this->institution->get($request->all());
    }
}
