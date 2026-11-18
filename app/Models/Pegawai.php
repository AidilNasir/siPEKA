<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
        'unit_kerja',
        'standar_kompetensi_jabatan',
    ];

    /**
     * Get the gap analyses for this ASN.
     *
     * @return HasMany<AnalisisKesenjangan, $this>
     */
    public function analisisKesenjangan(): HasMany
    {
        return $this->hasMany(AnalisisKesenjangan::class, 'nip', 'nip');
    }

    /**
     * Get the latest gap analysis record.
     */
    public function latestAnalisis(): ?AnalisisKesenjangan
    {
        return $this->analisisKesenjangan()->latest()->first();
    }
}
