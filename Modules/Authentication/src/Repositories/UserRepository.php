<?php

namespace Modules\Authentication\Repositories;

use App\Models\User;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createWithRole(array $data, string $role = 'user'): User
    {
        $data['role'] = $role;
        return User::create($data);
    }
}
