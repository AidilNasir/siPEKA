<?php

namespace Tests\Feature;

use App\Models\AnalisisKesenjangan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            $this->actingAs($admin);
        }
    }

    /**
     * Test the root URL renders the dashboard with live DB data.
     */
    public function test_dashboard_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('siPEKA');
        $response->assertSee('BPVP KENDARI');
        $response->assertSee('Total ASN Terpantau');
    }

    /**
     * Test Pegawai index route.
     */
    public function test_pegawai_index_renders(): void
    {
        $response = $this->get('/pegawai');

        $response->assertStatus(200);
        $response->assertSee('Master Data Pegawai ASN');
        $response->assertSee('BPVP KENDARI');
        $response->assertSee('ANSHARI');
    }

    /**
     * Test Analisis Kesenjangan index route.
     */
    public function test_analisis_kesenjangan_index_renders(): void
    {
        $response = $this->get('/analisis');

        $response->assertStatus(200);
        $response->assertSee('Analisis &amp; Pemantauan Kesenjangan ASN', false);
        $response->assertSee('GAP-1794805930765');
    }

    /**
     * Test Standar Kompetensi Jabatan catalog renders.
     */
    public function test_standar_kompetensi_renders(): void
    {
        $response = $this->get('/standar-kompetensi');

        $response->assertStatus(200);
        $response->assertSee('Standar Kompetensi Jabatan (SKJ)');
        $response->assertSee('INSTRUKTUR AHLI MADYA');
    }

    /**
     * Test updating Standar Kompetensi Jabatan.
     */
    public function test_admin_can_update_standar_kompetensi_jabatan(): void
    {
        $response = $this->post('/standar-kompetensi/update', [
            'jabatan' => 'INSTRUKTUR AHLI MADYA',
            'standar_kompetensi_jabatan' => 'SKJ Baru Instruktur Madya Vokasi Berkelanjutan',
        ]);

        $response->assertRedirect('/standar-kompetensi');
        $this->assertDatabaseHas('pegawais', [
            'jabatan' => 'INSTRUKTUR AHLI MADYA',
            'standar_kompetensi_jabatan' => 'SKJ Baru Instruktur Madya Vokasi Berkelanjutan',
        ]);
    }

    /**
     * Test Rencana Pengembangan 20 JP tracker renders.
     */
    public function test_rencana_pengembangan_renders(): void
    {
        $response = $this->get('/rencana-pengembangan');

        $response->assertStatus(200);
        $response->assertSee('Monitoring Rencana Pengembangan &amp; Hak 20 JP ASN', false);
    }

    /**
     * Test Laporan, Excel 2-Sheet Export, and CSV Export.
     */
    public function test_laporan_and_csv_export(): void
    {
        $response = $this->get('/laporan');
        $response->assertStatus(200);
        $response->assertSee('Balai Pelatihan Vokasi dan Produktivitas Kendari');

        // Test CSV Monev export
        $exportResponse = $this->get('/laporan/export-csv');
        $exportResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $exportResponse->headers->get('Content-Type'));

        // Test Excel 2-Sheet export
        $excelResponse = $this->get('/laporan/export-excel');
        $excelResponse->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', (string) $excelResponse->headers->get('Content-Type'));

        // Test CSV Pegawai export
        $pegawaiCsvResponse = $this->get('/laporan/export-pegawai-csv');
        $pegawaiCsvResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $pegawaiCsvResponse->headers->get('Content-Type'));
    }

    /**
     * Test Laporan dynamic naskah dinas inputs and bindings.
     */
    public function test_laporan_page_has_dynamic_naskah_dinas_inputs(): void
    {
        $response = $this->get('/laporan');
        $response->assertStatus(200);
        $response->assertSee('Input Nomor Dokumen');
        $response->assertSee('Sistem Input & Penomoran Naskah Dinas BPVP Kendari', false);
        $response->assertSee('nomorSurat', false);
        $response->assertSee('tanggalSurat', false);
        $response->assertSee('kotaSurat', false);
    }

    /**
     * Test creating a new gap analysis record.
     */
    public function test_create_analisis_kesenjangan(): void
    {
        $pegawai = Pegawai::first();

        $response = $this->post('/analisis', [
            'nip' => $pegawai->nip,
            'standar_kompetensi' => $pegawai->standar_kompetensi_jabatan,
            'kompetensi_saat_ini' => 'Pemahaman teknis dasar',
            'gap_kesenjangan' => 'Bimtek Transformasi Digital Vokasi',
            'rencana_pengembangan' => 'Pelatihan Klasikal / Bimtek',
            'target_jp' => 20,
            'jp_terealisasi' => 20,
        ]);

        $response->assertRedirect('/analisis');
        $this->assertDatabaseHas('analisis_kesenjangans', [
            'nip' => $pegawai->nip,
            'gap_kesenjangan' => 'Bimtek Transformasi Digital Vokasi',
            'status' => 'Memenuhi Hak 20 JP',
        ]);
    }

    /**
     * Test updating a gap analysis record.
     */
    public function test_admin_can_update_analisis_kesenjangan(): void
    {
        $analisis = AnalisisKesenjangan::first();

        $response = $this->put('/analisis/'.$analisis->id, [
            'standar_kompetensi' => 'Testing SKJ',
            'kompetensi_saat_ini' => 'Testing Saat Ini',
            'gap_kesenjangan' => 'Testing Gap',
            'rencana_pengembangan' => 'Workshop / Seminar',
            'target_jp' => 24,
            'jp_terealisasi' => 24,
            'status' => 'Memenuhi Hak 20 JP',
            'catatan' => 'Updated catatan',
        ]);

        $response->assertRedirect('/analisis');
        $this->assertDatabaseHas('analisis_kesenjangans', [
            'id' => $analisis->id,
            'jp_terealisasi' => 24,
            'status' => 'Memenuhi Hak 20 JP',
        ]);
    }

    /**
     * Test quick updating realisasi JP from rencana pengembangan.
     */
    public function test_admin_can_update_jp_in_rencana_pengembangan(): void
    {
        $analisis = AnalisisKesenjangan::first();

        $response = $this->patch('/rencana-pengembangan/'.$analisis->id.'/jp', [
            'jp_terealisasi' => 22,
        ]);

        $response->assertRedirect('/rencana-pengembangan');
        $this->assertDatabaseHas('analisis_kesenjangans', [
            'id' => $analisis->id,
            'jp_terealisasi' => 22,
        ]);
    }

    /**
     * Test admin can delete an analisis record.
     */
    public function test_admin_can_delete_analisis(): void
    {
        $analisis = AnalisisKesenjangan::first();

        $response = $this->delete('/analisis/'.$analisis->id);
        $response->assertRedirect('/analisis');
        $this->assertDatabaseMissing('analisis_kesenjangans', [
            'id' => $analisis->id,
        ]);
    }

    /**
     * Test admin can delete a pegawai.
     */
    public function test_admin_can_delete_pegawai(): void
    {
        $pegawai = Pegawai::create([
            'nip' => '199999999999999999',
            'nama' => 'Pegawai Dummy Untuk Delete',
            'jabatan' => 'Staff Dummy',
            'unit_kerja' => 'BPVP KENDARI',
        ]);

        $response = $this->delete('/pegawai/'.$pegawai->id);
        $response->assertRedirect('/pegawai');
        $this->assertDatabaseMissing('pegawais', [
            'id' => $pegawai->id,
        ]);
    }
}
