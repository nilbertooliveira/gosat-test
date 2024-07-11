<?php

namespace App\Application\Controllers;

use App\Application\DTOs\OfferDTO;
use App\Application\Requests\CalculateRequest;
use App\Application\Requests\CreditRequest;
use App\Application\Requests\OfferRequest;
use App\Domains\Interfaces\Services\ISimulationService;
use App\Domains\ValueObjects\Cpf;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class SimulationController extends Controller
{

    /**
     * @var ISimulationService
     */
    private ISimulationService $simulationService;

    /**
     * @param ISimulationService $simulationService
     */
    public function __construct(ISimulationService $simulationService)
    {
        $this->simulationService = $simulationService;
    }


    /**
     * @param CreditRequest $request
     * @return JsonResponse
     */
    public function credit(CreditRequest $request): JsonResponse
    {
        $cpf = Cpf::createFromRequest($request);
        $result = $this->simulationService->credit($cpf);

        if (!$result->isSuccess()) {
            return response()->json($result->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($result->toArray(), Response::HTTP_OK);
    }

    /**
     * @param OfferRequest $request
     * @return JsonResponse
     */
    public function offer(OfferRequest $request): JsonResponse
    {
        $dtoOffer = OfferDTO::createFromRequest($request);

        $result = $this->simulationService->offer($dtoOffer);

        if (!$result->isSuccess()) {
            return response()->json($result->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($result->toArray(), Response::HTTP_OK);
    }

    /**
     * @param CalculateRequest $request
     * @return JsonResponse
     */
    public function calculate(CalculateRequest $request): JsonResponse
    {
        $result = $this->simulationService->calculate($request);

        if (!$result->isSuccess()) {
            return response()->json($result->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response()->json($result->toArray(), Response::HTTP_OK);
    }

    public function creditView(): View
    {
        return view('simulation.credit');
    }
}
