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
     * @param array $data
     * @return Institution
     */
    public function store(array $data): Institution
    {
        return $this->institution->firstOrCreate(
            [
                'code' => $data['code']
            ],
            $data
        );
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
