<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'nama' => 'Admin Test',
            'username' => 'admin_test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }

    private function createPetugas(): User
    {
        return User::factory()->create([
            'nama' => 'Petugas Test',
            'username' => 'petugas_test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
        ]);
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))->assertStatus(200);
    }

    public function test_authenticated_user_redirected_from_login(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin)->get(route('login'))->assertRedirect();
    }

    public function test_admin_can_login_and_redirect_to_dashboard(): void
    {
        $admin = $this->createAdmin();

        $this->post(route('login.post'), [
            'username' => 'admin_test',
            'password' => 'password123',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_petugas_can_login_and_redirect_to_dashboard(): void
    {
        $petugas = $this->createPetugas();

        $this->post(route('login.post'), [
            'username' => 'petugas_test',
            'password' => 'password123',
        ])->assertRedirect(route('petugas.dashboard'));
    }

    public function test_invalid_credentials_return_error(): void
    {
        $this->post(route('login.post'), [
            'username' => 'wrong',
            'password' => 'wrong',
        ])->assertSessionHasErrors('username');
    }

    public function test_logout_redirects_to_login(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post(route('logout'))
            ->assertRedirect(route('login'));
    }

    public function test_petugas_cannot_access_admin_routes(): void
    {
        $petugas = $this->createPetugas();
        $this->actingAs($petugas)->get(route('admin.dashboard'))->assertStatus(403);
    }

    public function test_admin_cannot_access_petugas_routes(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin)->get(route('petugas.dashboard'))->assertStatus(403);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
