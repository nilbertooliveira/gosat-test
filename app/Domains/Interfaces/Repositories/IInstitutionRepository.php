<?php

namespace App\Domains\Interfaces\Repositories;

use App\Infrastructure\Database\Models\Institution;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IInstitutionRepository
{
    public function store(array $data): Institution;

    public function findAll(): Collection;
}
