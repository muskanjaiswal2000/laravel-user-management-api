<?php

namespace Modules\Admin\Services;

use Modules\Admin\Repositories\AdminRepository;

class AdminService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function getDashboardStats(): array
    {
        return $this->adminRepository->getDashboardStats();
    }

    public function getAllUsers($search = null, $perPage = 10)
    {
        $users = $this->adminRepository->getAllUsers($search, $perPage);

        $users->getCollection()->transform(function ($user) {
            $user->avatar = $user->avatar ? asset('storage/' . $user->avatar) : null;
            return $user;
        });

        return $users;
    }

    public function getUserById(int $id): ?array
    {
        $user = $this->adminRepository->getUserById($id);

        if (!$user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'created_at' => $user->created_at
        ];
    }
}
