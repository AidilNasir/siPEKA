<?php

namespace Tests\Feature;

use App\Models\Pegawai;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
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
     * Test Rencana Pengembangan 20 JP tracker renders.
     */
    public function test_rencana_pengembangan_renders(): void
    {
        $response = $this->get('/rencana-pengembangan');

        $response->assertStatus(200);
        $response->assertSee('Monitoring Rencana Pengembangan &amp; Hak 20 JP ASN', false);
    }

    /**
     * Test Laporan and CSV Export.
     */
    public function test_laporan_and_csv_export(): void
    {
        $response = $this->get('/laporan');
        $response->assertStatus(200);
        $response->assertSee('Balai Pelatihan Vokasi dan Produktivitas Kendari');

        $exportResponse = $this->get('/laporan/export-csv');
        $exportResponse->assertStatus(200);
        $this->assertStringContainsString('text/csv', (string) $exportResponse->headers->get('Content-Type'));
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
}
