<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tl extends Model
{
    use HasFactory;

    protected $table = 'tb_tl';
    protected $primaryKey = 'tl_id';
    public $timestamps = false;

    protected $fillable = [
        'pemeriksaan_id',
        'temuan_id',
        'rekomendasi_id',
        'tl_deskripsi',
        'tl_tgl',
        'tl_tanggapan',
        'tl_tanggapan_publish_kabag',
        'tl_tanggapan_kirim',
        'tl_catatan',
        'tl_catatan_publish_vrf',
        'tl_catatan_publish_spi',
        'tl_tanggapan_tgl',
        'tl_catatan_tgl',
        'tl_status',
        'tl_status_cache',
        'tl_status_tgl',
        'tl_status_publish_kabag',
        'tl_status_kirim',
        'tl_publish_verif',
        'tl_publish_spi',
        'tl_publish_kabag',
        'tl_status_from_vrf',
        'tl_status_from_spi',
        'tl_pmr_sebelumnya',
        'tl_open_spi',
        'tl_open_opr',
        'user_opr',
        'user_vrf',
        'tl_link'
    ];

    protected $casts = [
        'tl_id' => 'integer',
        'pemeriksaan_id' => 'integer',
        'temuan_id' => 'integer',
        'rekomendasi_id' => 'integer',
        'tl_deskripsi' => 'string',
        'tl_tgl' => 'date',
        'tl_tanggapan' => 'string',
        'tl_tanggapan_publish_kabag' => 'string',
        'tl_tanggapan_kirim' => 'string',
        'tl_catatan' => 'string',
        'tl_catatan_publish_vrf' => 'string',
        'tl_catatan_publish_spi' => 'string',
        'tl_tanggapan_tgl' => 'date',
        'tl_catatan_tgl' => 'date',
        'tl_status' => 'string',
        'tl_status_cache' => 'string',
        'tl_status_tgl' => 'date',
        'tl_status_publish_kabag' => 'string',
        'tl_status_kirim' => 'string',
        'tl_publish_verif' => 'string',
        'tl_publish_spi' => 'string',
        'tl_publish_kabag' => 'string',
        'tl_status_from_vrf' => 'string',
        'tl_status_from_spi' => 'string',
        'tl_pmr_sebelumnya' => 'integer',
        'tl_open_spi' => 'string',
        'tl_open_opr' => 'string',
        'user_opr' => 'string',
        'user_vrf' => 'string',
        'tl_link' => 'string'
    ];

    /**
     * Relationship dengan model Pemeriksaan
     */
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    /**
     * Relationship dengan model Temuan (jika ada)
     */
    public function temuan()
    {
        return $this->belongsTo(Temuan::class, 'temuan_id', 'temuan_id');
    }

    /**
     * Relationship dengan model Rekomendasi (jika ada)
     */
    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class, 'rekomendasi_id', 'rekomendasi_id');
    }

    /**
     * Relationship dengan User untuk operator
     */
    public function userOperator()
    {
        return $this->belongsTo(User::class, 'user_opr', 'user_nik');
    }

    /**
     * Relationship dengan User untuk verifikator
     */
    public function userVerifikator()
    {
        return $this->belongsTo(User::class, 'user_vrf', 'user_nik');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('tl_status', $status);
    }

    /**
     * Scope untuk filter berdasarkan pemeriksaan
     */
    public function scopeByPemeriksaan($query, $pemeriksaanId)
    {
        return $query->where('pemeriksaan_id', $pemeriksaanId);
    }

    /**
     * Accessor untuk format tanggal tl_tgl
     */
    public function getTlTglFormattedAttribute()
    {
        return $this->tl_tgl ? $this->tl_tgl->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk format tanggal tl_tanggapan_tgl
     */
    public function getTlTanggapanTglFormattedAttribute()
    {
        return $this->tl_tanggapan_tgl ? $this->tl_tanggapan_tgl->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk format tanggal tl_catatan_tgl
     */
    public function getTlCatatanTglFormattedAttribute()
    {
        return $this->tl_catatan_tgl ? $this->tl_catatan_tgl->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk format tanggal tl_status_tgl
     */
    public function getTlStatusTglFormattedAttribute()
    {
        return $this->tl_status_tgl ? $this->tl_status_tgl->format('d/m/Y') : null;
    }

    public function uploads()
    {
        return $this->hasMany(UploadTl::class, 'tl_id', 'tl_id');
    }
}
