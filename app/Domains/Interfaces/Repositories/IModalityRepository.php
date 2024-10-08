<?php

namespace App\Domains\Interfaces\Repositories;

use App\Infrastructure\Database\Models\Modality;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IModalityRepository
{
    public function store(array $data): Modality;

    public function findAll(Request $request): Collection;
}
