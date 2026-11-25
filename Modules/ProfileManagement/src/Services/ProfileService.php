<?php

namespace Modules\ProfileManagement\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Modules\ProfileManagement\Repositories\ProfileRepository;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function updateProfile(int $userId, array $data): User
    {
        return $this->profileRepository->updateProfile($userId, $data);
    }

    public function updateAvatar(int $userId, $file): User
    {
        return $this->profileRepository->updateAvatar($userId, $file);
    }

    public function deleteAvatar(int $userId): User
    {
        return $this->profileRepository->deleteAvatar($userId);
    }
}
