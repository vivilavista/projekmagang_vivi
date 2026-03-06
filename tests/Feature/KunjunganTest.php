<?php

namespace Tests\Feature;

use App\Models\Kunjungan;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KunjunganTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $petugas;
    private Tamu $tamu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'username' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->petugas = User::factory()->create([
            'username' => 'petugas_test',
            'password' => bcrypt('password'),
            'role' => 'petugas',
        ]);

        $this->tamu = Tamu::create([
            'nama' => 'Test Tamu',
            'alamat' => 'Jl. Test No.1',
            'no_hp' => '081234567890',
        ]);
    }

    public function test_petugas_can_create_kunjungan(): void
    {
        $this->actingAs($this->petugas)
            ->post(route('petugas.kunjungan.store'), [
                'tamu_id' => $this->tamu->id,
                'tujuan' => 'Testing keperluan',
                'instansi' => 'Instansi Test',
            ])->assertRedirect(route('petugas.kunjungan.index'));

        $this->assertDatabaseHas('kunjungan', [
            'tamu_id' => $this->tamu->id,
            'tujuan' => 'Testing keperluan',
            'status' => 'Aktif',
            'operator_id' => $this->petugas->id,
        ]);
    }

    public function test_petugas_can_checkout_kunjungan(): void
    {
        $kunjungan = Kunjungan::create([
            'tamu_id' => $this->tamu->id,
            'tujuan' => 'Test kunjungan',
            'jam_masuk' => now(),
            'operator_id' => $this->petugas->id,
            'status' => 'Aktif',
        ]);

        $this->actingAs($this->petugas)
            ->post(route('petugas.kunjungan.checkout', $kunjungan->id))
            ->assertRedirect();

        $this->assertDatabaseHas('kunjungan', [
            'id' => $kunjungan->id,
            'status' => 'Selesai',
        ]);

        $this->assertNotNull(Kunjungan::find($kunjungan->id)->jam_keluar);
    }

    public function test_admin_can_view_all_kunjungan(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.kunjungan.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_delete_kunjungan(): void
    {
        $kunjungan = Kunjungan::create([
            'tamu_id' => $this->tamu->id,
            'tujuan' => 'To be deleted',
            'jam_masuk' => now(),
            'operator_id' => $this->petugas->id,
            'status' => 'Aktif',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.kunjungan.destroy', $kunjungan->id))
            ->assertRedirect(route('admin.kunjungan.index'));

        $this->assertSoftDeleted('kunjungan', ['id' => $kunjungan->id]);
    }
}
