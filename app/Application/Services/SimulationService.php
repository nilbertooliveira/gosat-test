<?php

namespace App\Application\Services;

use App\Application\DTOs\OfferDTO;
use App\Domains\Interfaces\DTOInterface;
use App\Domains\Interfaces\Repositories\IInstitutionRepository;
use App\Domains\Interfaces\Repositories\IModalityRepository;
use App\Domains\Interfaces\Repositories\ISimulationRepository;
use App\Domains\Interfaces\Services\ISimulationService;
use App\Domains\ValueObjects\Cpf;
use App\Infrastructure\Database\Models\Simulation;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class SimulationService implements ISimulationService
{
    private Collection $simulationsCollection;
    private IInstitutionRepository $institutionRepository;
    private IModalityRepository $modalityRepository;
    private ISimulationRepository $simulationRepository;

    /**
     * @param IInstitutionRepository $institutionRepository
     * @param IModalityRepository $modalityRepository
     * @param ISimulationRepository $simulationRepository
     */
    public function __construct(
        IInstitutionRepository $institutionRepository,
        IModalityRepository    $modalityRepository,
        ISimulationRepository  $simulationRepository)
    {
        $this->simulationRepository = $simulationRepository;
        $this->institutionRepository = $institutionRepository;
        $this->modalityRepository = $modalityRepository;
    }

    /**
     * @param Cpf $cpf
     * @return ResponseService
     */
    public function credit(Cpf $cpf): ResponseService
    {
        try {
            $endpoint = config('simulation.url_gosat') . config('simulation.uri.credit');

            $response = Http::acceptJson()
                ->asJson()
                ->post($endpoint, ['cpf' => $cpf->getValue()]);

            $result = new ResponseService($response->json());

        } catch (Throwable $e) {
            $result = new ResponseService($e->getMessage(), false);
        }
        return $result;
    }

    /**
     * @param OfferDTO $dtoOffer
     * @return ResponseService
     */
    public function offer(DTOInterface $dtoOffer): ResponseService
    {
        try {
            $endpoint = config('simulation.url_gosat') . config('simulation.uri.offer');

            $response = Http::acceptJson()
                ->asJson()
                ->post($endpoint, $dtoOffer->jsonSerialize());

            return new ResponseService($response->json());

        } catch (Throwable $e) {
            return new ResponseService($e->getMessage(), false);
        }
    }

    /**
     * @param Request $request
     * @return Collection|null
     */
    private function getCache(Request $request): ?Collection
    {
        $cacheKey = $this->getCacheKey($request);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        return null;
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getCacheKey(Request $request): string
    {
        return $request->input('cpf') . $request->input('valorSolicitado') . $request->input('qntParcelas');
    }

    private function setCache(Collection $collection, Request $request)
    {
        $cacheKey = $this->getCacheKey($request);

        Cache::set($cacheKey, $collection, 60);
    }

    /**
     * @param Request $request
     * @return ResponseService
     * @throws Throwable
     */
    public function calculate(Request $request): ResponseService
    {
        try {
            $result = $this->getCache($request);
            if (!is_null($result)) {
                $this->store($result->jsonSerialize());//caso queira historico full
                return new ResponseService($result->jsonSerialize());
            }

            $this->simulationsCollection = new Collection();

            $cpf = Cpf::createFromRequest($request);

            $response = $this->credit($cpf);

            foreach ($response->getData()['instituicoes'] as $institution) {
                $this->processInstitutionModalities(
                    modalities: $institution['modalidades'],
                    idInstitution: $institution['id'],
                    nameInstitution: $institution['nome'],
                    request: $request
                );
            }
            $collection = $this->getSimulationsOrdered();

            $this->setCache($collection, $request);

            $this->store($collection->jsonSerialize());

            return new ResponseService($collection->jsonSerialize());
        } catch (Throwable $e) {
            return new ResponseService($e->getMessage(), false);
        }
    }

    /**
     * @param array $modalities
     * @param int $idInstitution
     * @param string $nameInstitution
     * @param Request $request
     * @return void
     * @throws ConnectionException
     */
    private function processInstitutionModalities(array $modalities, int $idInstitution, string $nameInstitution, Request $request): void
    {
        $endpoint = config('simulation.url_gosat') . config('simulation.uri.offer');

        foreach ($modalities as $modality) {
            $post = [
                'cpf' => $request->input('cpf'),
                'instituicao_id' => $idInstitution,
                'codModalidade' => $modality['cod'],
            ];
            $response = Http::acceptJson()
                ->asJson()
                ->post($endpoint, $post);

            $data = [
                'instituicaoFinanceira' => $nameInstitution,
                'instituicao_id' => $idInstitution,
                'modalidadeCredito' => $modality['nome'],
                'cod' => $modality['cod'],
            ];

            $credits = $response->json();

            $result = $this->filterSimulation(
                loanValue: $request->float('valorSolicitado'),
                minimumValue: $credits['valorMin'],
                maximumValue: $credits['valorMax'],
                quantityMinimumInstallment: $credits['QntParcelaMin'],
                quantityMaximumInstallment: $credits['QntParcelaMax'],
                quantityInstallment: $request->integer('qntParcelas'),
            );

            if (!$result) {
                continue;
            }

            $calculationDetails = $this->calculateLoanDetails(
                $request->float('valorSolicitado'),
                $request->integer('qntParcelas'),
                $credits['jurosMes']
            );
            $this->simulationsCollection->push(array_merge($data, $calculationDetails));
        }
    }

    /**
     * @param $loanValue
     * @param $minimumValue
     * @param $maximumValue
     * @param $quantityMinimumInstallment
     * @param $quantityMaximumInstallment
     * @param int|null $quantityInstallment
     * @return bool
     */
    private function filterSimulation(
        $loanValue,
        $minimumValue,
        $maximumValue,
        $quantityMinimumInstallment,
        $quantityMaximumInstallment,
        ?int $quantityInstallment): bool
    {
        if ($loanValue < $minimumValue || $loanValue > $maximumValue) {
            return false;
        }
        if (!is_null($quantityInstallment)) {
            if ($quantityInstallment < $quantityMinimumInstallment || $quantityInstallment > $quantityMaximumInstallment) {
                return false;
            }
        }
        return true;
    }

    /**
     *  M = C * (1 + i)^t
     *  M: Montante total a pagar (valor final do empréstimo)
     *  C: Capital inicial (valor emprestado)
     *  i: Taxa de juros ao mês (em formato decimal)
     *  t: Número total de meses (número de parcelas)
     *
     * @param float $amountRequested
     * @param int $quantityInstallment
     * @param float $monthlyInterestRate
     * @return array
     */
    private function calculateLoanDetails(float $amountRequested, int $quantityInstallment, float $monthlyInterestRate): array
    {
        $rateInterestDecimal = 1 + $monthlyInterestRate;

        $totalAmount = $amountRequested * pow($rateInterestDecimal, $quantityInstallment);

        return [
            "valorAPagar" => round($totalAmount, 2),
            "valorSolicitado" => $amountRequested,
            "taxaJuros" => $monthlyInterestRate,
            "qntParcelas" => $quantityInstallment,
        ];
    }

    /**
     * @return Collection
     */
    private function getSimulationsOrdered(): Collection
    {
        return $this->simulationsCollection
            ->sortBy('valorAPagar')
            ->values()
            ->take(3);
    }


    /**
     * @param array $simulations
     * @return ResponseService
     * @throws Exception
     */
    public function store(array $simulations): ResponseService
    {
        DB::beginTransaction();

        try {
            foreach ($simulations as $simulation) {
                $data = [
                    'name' => $simulation['instituicaoFinanceira'],
                    'code' => $simulation['instituicao_id'],
                ];
                $institution = $this->institutionRepository->store($data);

                $data = [
                    'name' => $simulation['modalidadeCredito'],
                    'code' => $simulation['cod'],
                    'instituicao_id' => $institution->id,
                    'instituicao_code' => $simulation['instituicao_id']
                ];
                $modality = $this->modalityRepository->store($data);

                $data = [
                    'modality_id' => $modality->id,
                    'total_amount' => $simulation['valorAPagar'],
                    'amount_requested' => $simulation['valorSolicitado'],
                    'monthly_interest_rate' => $simulation['taxaJuros'],
                    'quantity_installment' => $simulation['qntParcelas']
                ];
                $this->simulationRepository->store($data);
            }
            DB::commit();

            return new ResponseService('Simulação criada!');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @return ResponseService
     */
    public function findAll(Request $request): ResponseService
    {
        try {
            $result = $this->simulationRepository->findAll($request);

            return new ResponseService($result->jsonSerialize());
        } catch (\Throwable $e) {
            return new ResponseService($e->getMessage(), false);
        }
    }

    /**
     * @return ResponseService
     */
    public function getDatasets(): ResponseService
    {
        $collections = $this->simulationRepository->findAll();

        return new ResponseService($collections->jsonSerialize());
    }
}
