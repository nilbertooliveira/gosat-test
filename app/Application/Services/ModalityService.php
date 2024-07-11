<?php

namespace App\Application\Services;

use App\Domains\Interfaces\Repositories\IModalityRepository;
use App\Domains\Interfaces\Services\IModalityService;
use Illuminate\Http\Request;

class ModalityService implements IModalityService
{
    private IModalityRepository $modalityRepository;

    /**
     * @param IModalityRepository $modalityRepository
     */
    public function __construct(IModalityRepository $modalityRepository)
    {
        $this->modalityRepository = $modalityRepository;
    }

    /**
     * @param Request $request
     * @return ResponseService
     */
    public function store(Request $request): ResponseService
    {
        try {
            $result = $this->modalityRepository->store($request->all());

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
            $result = $this->modalityRepository->findAll($request);

            return new ResponseService($result->jsonSerialize());
        } catch (\Throwable $e) {
            return new ResponseService($e->getMessage(), false);
        }
    }
}
