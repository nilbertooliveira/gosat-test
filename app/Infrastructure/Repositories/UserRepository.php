<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Interfaces\Repositories\IUserRepository;
use App\Infrastructure\Database\Models\User;
use Illuminate\Http\Request;


/**
 * @property User $user
 */
class UserRepository implements IUserRepository
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function findByEmail(string $email): User
    {
        return $this->user->where('email', $email)->first();
    }

    public function findById(int $id): User
    {
        return $this->user->findOrFail($id);
    }

    public function store(Request $request): User
    {
        return $this->user->create($request->all());
    }
}
