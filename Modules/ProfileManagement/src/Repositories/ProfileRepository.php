<?php

namespace Modules\ProfileManagement\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileRepository
{
    public function updateProfile(int $userId, array $data): User
    {
        $user = User::find($userId);
        $user->update($data);
        return $user->fresh();
    }

    public function updateAvatar(int $userId, $file): User
    {
        $user = User::find($userId);

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $path = $file->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return $user->fresh();
    }

    public function deleteAvatar(int $userId): User
    {
        $user = User::find($userId);

        if ($user->avatar) {
            Storage::delete($user->avatar);
            $user->avatar = null;
            $user->save();
        }

        return $user->fresh();
    }
}
