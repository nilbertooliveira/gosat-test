<?php

namespace App\Application\Services;

use App\Domains\Interfaces\Repositories\IInstitutionRepository;
use App\Domains\Interfaces\Services\IInstitutionService;
use App\Infrastructure\Database\Models\Institution;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class InstitutionService implements IInstitutionService
{
    private IInstitutionRepository $institutionRepository;

    /**
     * @param IInstitutionRepository $institutionRepository
     */
    public function __construct(IInstitutionRepository $institutionRepository)
    {
        $this->institutionRepository = $institutionRepository;
    }

    /**
     * @param Request $request
     * @return ResponseService
     */
    public function store(Request $request): ResponseService
    {
        try {
            $result = $this->institutionRepository->store($request);

            return new ResponseService($result->jsonSerialize());
        } catch (\Throwable $e) {
            return new ResponseService($e->getMessage(), false);
        }
    }

    /**
     * @param Request $request
     * @return ResponseService
     */
    public function findAll(Request $request): ResponseService
    {
        try {
            $result = $this->institutionRepository->findAll($request);

            return new ResponseService($result->jsonSerialize());
        } catch (\Throwable $e) {
            return new ResponseService($e->getMessage(), false);
        }
    }
}
