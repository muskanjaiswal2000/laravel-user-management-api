<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Modules\Authentication\Services\AuthService;
use Tests\TestCase;

class ApiUserManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \DB::table('personal_access_tokens')->delete();
        \DB::table('password_reset_otps')->delete();
        \DB::table('users')->delete();
    }

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'user')
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'Password123', 'role' => 'user']);

        $login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Password123',
        ]);
        $login->assertOk()->assertJsonStructure(['data' => ['token']]);

        Sanctum::actingAs($user);
        $this->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_profile_can_be_retrieved_and_updated(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $this->getJson('/api/profile')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);

        $this->putJson('/api/profile', [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => 'admin',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Name')
            ->assertJsonPath('data.role', 'user');
    }

    public function test_avatar_upload_and_delete(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'user']);
        Sanctum::actingAs($user);

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $this->postJson('/api/profile/avatar', [
            'avatar' => UploadedFile::fake()->createWithContent('avatar.png', $png)->mimeType('image/png'),
        ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $this->deleteJson('/api/profile/avatar')
            ->assertOk()
            ->assertJsonPath('data.avatar', null);
    }

    public function test_otp_generation_verification_and_password_reset(): void
    {
        Mail::fake();
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => 'Password123',
        ]);

        $this->postJson('/api/forgot-password', ['email' => $user->email])
            ->assertOk();

        $service = app(AuthService::class);
        $otp = $service->generateOTP($user->email);
        $resetToken = $service->verifyOTP($user->email, $otp);

        $this->assertNotNull($resetToken);

        $this->postJson('/api/reset-password', [
            'email' => $user->email,
            'reset_token' => $resetToken,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ])->assertOk()->assertJsonPath('success', true);

        $this->assertTrue(auth()->validate([
            'email' => $user->email,
            'password' => 'NewPassword123',
        ]));
    }

    public function test_admin_and_user_access_restrictions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->getJson('/api/admin/dashboard')->assertUnauthorized();

        Sanctum::actingAs($user);
        $this->getJson('/api/admin/dashboard')->assertForbidden();

        Sanctum::actingAs($admin);
        $this->getJson('/api/admin/dashboard')
            ->assertOk()
            ->assertJsonStructure(['data' => ['total_users', 'total_admins', 'total_normal_users']]);
    }
}
