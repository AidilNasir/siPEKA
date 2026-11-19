<?php

namespace Tests\Feature;

use App\Models\AnalisisKesenjangan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test halaman login renders dengan sukses dan memuat elemen UI Figma-grade.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('siPEKA');
        $response->assertSee('BPVP KENDARI');
        $response->assertSee('Sign in');
        $response->assertSee('User Name');
        $response->assertSee('Password');
    }

    /**
     * Test pengguna belum login diarahkan ke halaman login saat akses dashboard.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    /**
     * Test admin dapat login dengan username 'admin' dan password 'admin'.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin',
            'role' => 'admin',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertNotNull($user);
        $this->assertEquals('admin', $user->role);
    }

    /**
     * Test login admin gagal jika password salah.
     */
    public function test_admin_login_fails_with_invalid_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => 'password_salah',
            'role' => 'admin',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /**
     * Test pegawai dapat login dengan username 'pegawai' dan password berupa NIP terdaftar.
     */
    public function test_pegawai_can_login_with_valid_nip(): void
    {
        $pegawai = Pegawai::first();
        $this->assertNotNull($pegawai);

        $response = $this->post('/login', [
            'username' => 'pegawai',
            'password' => $pegawai->nip,
            'role' => 'pegawai',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertNotNull($user);
        $this->assertEquals('pegawai', $user->role);
        $this->assertEquals($pegawai->nip, $user->nip);
    }

    /**
     * Test pegawai dengan format NIP berspasi tetap berhasil login.
     */
    public function test_pegawai_can_login_with_spaced_nip(): void
    {
        $pegawai = Pegawai::where('nip', 'like', '% %')->first();
        if ($pegawai) {
            $response = $this->post('/login', [
                'username' => 'pegawai',
                'password' => $pegawai->nip,
                'role' => 'pegawai',
            ]);

            $response->assertRedirect('/');
            $this->assertAuthenticated();
        }
    }

    /**
     * Test pegawai dapat login dengan mengetikkan nama parsial (contoh: 'habibi') dan NIP sebagai password.
     */
    public function test_pegawai_can_login_with_partial_name(): void
    {
        $pegawai = Pegawai::where('nama', 'like', '%HABIBI%')->first();
        $this->assertNotNull($pegawai);

        $response = $this->post('/login', [
            'username' => 'habibi',
            'password' => $pegawai->nip,
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertNotNull($user);
        $this->assertEquals('pegawai', $user->role);
        $this->assertEquals($pegawai->nip, $user->nip);
    }

    /**
     * Test pegawai login gagal jika NIP tidak terdaftar.
     */
    public function test_pegawai_login_fails_with_invalid_nip(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'pegawai',
            'password' => '999999999999999999',
            'role' => 'pegawai',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    /**
     * Test pengguna dapat keluar sistem (logout).
     */
    public function test_user_can_logout(): void
    {
        $admin = User::where('username', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->get('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test pegawai role sees personalized dashboard banner and personal 20 JP metrics.
     */
    public function test_pegawai_sees_personal_portal_dashboard(): void
    {
        $pegawai = Pegawai::first();
        $user = User::factory()->create([
            'role' => 'pegawai',
            'nip' => $pegawai->nip,
            'name' => $pegawai->nama,
        ]);

        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang, '.$pegawai->nama);
        $response->assertSee($pegawai->nama);
        $response->assertSee('Realisasi Hak 20 JP');
        $response->assertDontSee('Entri Analisis Kesenjangan');
    }

    /**
     * Test pegawai role cannot create new ASN.
     */
    public function test_pegawai_cannot_create_asn(): void
    {
        $pegawai = Pegawai::first();
        $user = User::factory()->create([
            'role' => 'pegawai',
            'nip' => $pegawai->nip,
        ]);

        $this->actingAs($user);

        $response = $this->post('/pegawai', [
            'nip' => '199901012020011001',
            'nama' => 'Test ASN',
            'jabatan' => 'Instruktur',
            'unit_kerja' => 'BPVP KENDARI',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('pegawais', ['nip' => '199901012020011001']);
    }

    /**
     * Test pegawai role cannot create analisis kesenjangan.
     */
    public function test_pegawai_cannot_create_analisis_kesenjangan(): void
    {
        $pegawai = Pegawai::first();
        $user = User::factory()->create([
            'role' => 'pegawai',
            'nip' => $pegawai->nip,
        ]);

        $this->actingAs($user);

        $response = $this->post('/analisis', [
            'nip' => $pegawai->nip,
            'kompetensi_saat_ini' => 'Dasar',
            'gap_kesenjangan' => 'Tingkat Lanjut',
            'rencana_pengembangan' => 'Bimtek',
            'target_jp' => 20,
            'jp_terealisasi' => 0,
        ]);

        $response->assertSessionHas('error');
    }

    /**
     * Test pegawai hanya melihat data dirinya sendiri di halaman Analisis Kesenjangan.
     */
    public function test_pegawai_only_sees_their_own_data_on_analisis_page(): void
    {
        $analisisA = AnalisisKesenjangan::with('pegawai')->first();
        $this->assertNotNull($analisisA);
        $pegawaiA = $analisisA->pegawai;

        $analisisB = AnalisisKesenjangan::with('pegawai')->where('nip', '!=', $pegawaiA->nip)->first();
        $this->assertNotNull($analisisB);
        $pegawaiB = $analisisB->pegawai;

        $userA = User::factory()->create([
            'role' => 'pegawai',
            'nip' => $pegawaiA->nip,
            'name' => $pegawaiA->nama,
        ]);

        $this->actingAs($userA);

        $response = $this->get('/analisis');
        $response->assertStatus(200);
        $response->assertSee($pegawaiA->nama);
        $response->assertDontSee($pegawaiB->nama);
    }

    /**
     * Test pegawai hanya melihat data dirinya sendiri di halaman Pemenuhan 20 JP.
     */
    public function test_pegawai_only_sees_their_own_data_on_pengembangan_page(): void
    {
        $analisisA = AnalisisKesenjangan::with('pegawai')->first();
        $this->assertNotNull($analisisA);
        $pegawaiA = $analisisA->pegawai;

        $analisisB = AnalisisKesenjangan::with('pegawai')->where('nip', '!=', $pegawaiA->nip)->first();
        $this->assertNotNull($analisisB);
        $pegawaiB = $analisisB->pegawai;

        $userA = User::factory()->create([
            'role' => 'pegawai',
            'nip' => $pegawaiA->nip,
            'name' => $pegawaiA->nama,
        ]);

        $this->actingAs($userA);

        $response = $this->get('/rencana-pengembangan');
        $response->assertStatus(200);
        $response->assertSee($pegawaiA->nama);
        $response->assertDontSee($pegawaiB->nama);
    }

    /**
     * Test pegawai hanya melihat profil dirinya sendiri di halaman Data Pegawai.
     */
    public function test_pegawai_only_sees_their_own_profile_on_pegawai_page(): void
    {
        $pegawaiA = Pegawai::first();
        $pegawaiB = Pegawai::where('nip', '!=', $pegawaiA->nip)->first();

        $userA = User::factory()->create([
            'role' => 'pegawai',
            'nip' => $pegawaiA->nip,
            'name' => $pegawaiA->nama,
        ]);

        $this->actingAs($userA);

        $response = $this->get('/pegawai');
        $response->assertStatus(200);
        $response->assertSee($pegawaiA->nama);
        $response->assertDontSee($pegawaiB->nama);
    }

    /**
     * Test administrator melihat seluruh pegawai dan seluruh analisis.
     */
    public function test_admin_sees_all_pegawais_and_analisis(): void
    {
        $admin = User::where('username', 'admin')->first();
        $pegawaiFirst = Pegawai::orderBy('nama', 'asc')->first();

        $this->actingAs($admin);

        $responsePegawai = $this->get('/pegawai');
        $responsePegawai->assertStatus(200);
        $responsePegawai->assertSee($pegawaiFirst->nama);
        $responsePegawai->assertSee('Tambah ASN Baru');

        $responseAnalisis = $this->get('/analisis');
        $responseAnalisis->assertStatus(200);
        $responseAnalisis->assertSee('Input Asesmen Monev');
    }
}
