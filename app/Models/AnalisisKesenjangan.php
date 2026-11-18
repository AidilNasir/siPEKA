<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisKesenjangan extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id_monev',
        'nip',
        'standar_kompetensi',
        'kompetensi_saat_ini',
        'gap_kesenjangan',
        'rencana_pengembangan',
        'target_jp',
        'jp_terealisasi',
        'status',
        'catatan',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'target_jp' => 'integer',
        'jp_terealisasi' => 'integer',
    ];

    /**
     * Get the ASN who owns this gap analysis.
     *
     * @return BelongsTo<Pegawai, $this>
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'nip', 'nip');
    }

    /**
     * Calculate progress percentage of JP.
     */
    public function getJpProgressAttribute(): int
    {
        if ($this->target_jp <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->jp_terealisasi / $this->target_jp) * 100));
    }
}
