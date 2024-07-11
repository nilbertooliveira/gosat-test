<?php

namespace App\Domains\Interfaces\Repositories;

use App\Infrastructure\Database\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IUserRepository
{
    public function findByEmail(string $email): User;

    public function findById(int $id): User;

    public function store(Request $request): User;
}
