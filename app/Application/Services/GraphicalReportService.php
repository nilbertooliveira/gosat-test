<?php

namespace App\Application\Services;

use App\Domains\Interfaces\Repositories\IInstitutionRepository;
use App\Domains\Interfaces\Services\IGraphicalReportService;
use Illuminate\Http\Request;


class GraphicalReportService implements  IGraphicalReportService
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
    public function generate(Request $request): ResponseService
    {
        $this->institutionRepository->findAll();
        return new ResponseService('Simulação criada!');
    }
}
