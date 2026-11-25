<?php

namespace Modules\Authentication\Services;

use Modules\Authentication\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->createWithRole($data, 'user');
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function generateOTP(string $email): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        DB::table('password_reset_otps')
            ->where('email', $email)
            ->delete();

        DB::table('password_reset_otps')->insert([
            'email' => $email,
            'otp' => Hash::make($otp),
            'expires_at' => $expiresAt,
            'attempts' => 0,
            'used_at' => null,
            'reset_token_hash' => null,
            'reset_token_expires_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $otp;
    }

    public function verifyOTP(string $email, string $otp): ?string
    {
        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('used_at', null)
            ->latest('id')
            ->first();

        if (!$record) {
            return null;
        }

        if (now()->gt($record->expires_at)) {
            return null;
        }

        if ($record->attempts >= 3) {
            return null;
        }

        if (!Hash::check($otp, $record->otp)) {
            DB::table('password_reset_otps')
                ->where('id', $record->id)
                ->increment('attempts', 1, ['updated_at' => now()]);
            return null;
        }

        $resetToken = Str::random(60);

        DB::table('password_reset_otps')
            ->where('id', $record->id)
            ->update([
                'used_at' => now(),
                'reset_token_hash' => Hash::make($resetToken),
                'reset_token_expires_at' => now()->addMinutes(15),
                'updated_at' => now()
            ]);

        return $resetToken;
    }

    public function resetPassword(string $email, string $resetToken, string $newPassword): bool
    {
        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->whereNotNull('used_at')
            ->latest('id')
            ->first();

        if (!$record) {
            return false;
        }

        if (
            !$record->reset_token_hash
            || !$record->reset_token_expires_at
            || now()->gt($record->reset_token_expires_at)
            || !Hash::check($resetToken, $record->reset_token_hash)
        ) {
            return false;
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return false;
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        $user->tokens()->delete();

        DB::table('password_reset_otps')
            ->where('id', $record->id)
            ->delete();

        return true;
    }
}
