<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lha extends Model
{
    use HasFactory;

    protected $table = 'tb_lha';
    protected $primaryKey = 'id_lha';
    public $timestamps = false;

    protected $fillable = [
        'no_lha',
        'file_lha',
        'tahun',
        'id_pemeriksaan',
        'status'
    ];

    protected $casts = [
        'id_lha' => 'integer',
        'no_lha' => 'string',
        'file_lha' => 'string',
        'tahun' => 'integer',
        'id_pemeriksaan' => 'integer',
        'status' => 'integer'
    ];

    /**
     * Relationship dengan model Pemeriksaan
     */
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'pemeriksaan_id');
    }

    /**
     * Scope untuk filter berdasarkan pemeriksaan
     */
    public function scopeByPemeriksaan($query, $pemeriksaanId)
    {
        return $query->where('id_pemeriksaan', $pemeriksaanId);
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessor untuk format nomor LHA
     */
    public function getFormattedNoLhaAttribute()
    {
        return strtoupper($this->no_lha);
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        $statusMap = [
            0 => 'Draft',
            1 => 'Aktif',
            2 => 'Arsip'
        ];

        return $statusMap[$this->status] ?? 'Unknown';
    }

    /**
     * Accessor untuk file path lengkap
     */
    public function getFilePathAttribute()
    {
        return $this->file_lha ? asset('storage/lha/' . $this->file_lha) : null;
    }

    /**
     * Scope untuk pencarian berdasarkan nomor LHA
     */
    public function scopeSearchByNo($query, $search)
    {
        return $query->where('no_lha', 'like', '%' . $search . '%');
    }
}
