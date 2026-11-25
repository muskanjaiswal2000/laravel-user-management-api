<?php

namespace Modules\Admin\Repositories;

use App\Models\User;

class AdminRepository
{
    public function getDashboardStats(): array
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalNormalUsers = User::where('role', 'user')->count();

        return [
            'total_users' => $totalUsers,
            'total_admins' => $totalAdmins,
            'total_normal_users' => $totalNormalUsers
        ];
    }

    public function getAllUsers($search = null, $perPage = 10)
    {
        $query = User::where('role', 'user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->select('id', 'name', 'email', 'avatar', 'created_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
    }

    public function getUserById(int $id): ?User
    {
        return User::where('role', 'user')
                   ->where('id', $id)
                   ->select('id', 'name', 'email', 'avatar', 'created_at')
                   ->first();
    }
}
