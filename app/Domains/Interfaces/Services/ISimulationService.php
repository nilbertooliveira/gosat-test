<?php

namespace App\Domains\Interfaces\Services;

use App\Application\Services\ResponseService;
use App\Domains\Interfaces\DTOInterface;
use App\Domains\ValueObjects\Cpf;
use App\Infrastructure\Database\Models\Simulation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ISimulationService
{
    public function credit(Cpf $cpf): ResponseService;

    public function offer(DTOInterface $dtoOffer): ResponseService;

    public function calculate(Request $request): ResponseService;

    public function store(Request $request): ResponseService;

    public function findAll(Request $request): ResponseService;
}
