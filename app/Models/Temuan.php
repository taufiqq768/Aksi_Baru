<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temuan extends Model
{
    use HasFactory;

    protected $table = 'tb_temuan';
    protected $primaryKey = 'temuan_id';
    public $timestamps = false;

    protected $fillable = [
        'pemeriksaan_id',
        'bidangtemuan_id',
        'temuan_judul',
        'temuan_obyek',
        'temuan_tgl',
        'temuan_kirim',
        'temuan_publish_kabag',
        'temuan_pmr_sebelumnya',
        'user_nik',
        'temu_id',
        'sebab_id',
        'coso_id',
        'nominal',
        'penyebab',
        'id_klasifikasi_ab',
        'temuan_kriteria',
        'temuan_doc_pendukung'
    ];

    protected $casts = [
        'temuan_id' => 'integer',
        'pemeriksaan_id' => 'integer',
        'bidangtemuan_id' => 'integer',
        'temuan_judul' => 'string',
        'temuan_obyek' => 'string',
        'temuan_tgl' => 'date',
        'temuan_kirim' => 'string',
        'temuan_publish_kabag' => 'string',
        'temuan_pmr_sebelumnya' => 'integer',
        'user_nik' => 'string',
        'temu_id' => 'integer',
        'sebab_id' => 'string',
        'coso_id' => 'integer',
        'nominal' => 'integer',
        'penyebab' => 'string',
        'id_klasifikasi_ab' => 'integer',
        'temuan_kriteria' => 'string',
        'temuan_doc_pendukung' => 'string'
    ];

    /**
     * Relationship dengan model Pemeriksaan
     */
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    /**
     * Relationship dengan model BidangTemuan (jika ada)
     */
    public function bidangTemuan()
    {
        return $this->belongsTo(BidangTemuan::class, 'bidangtemuan_id', 'bidangtemuan_id');
    }

    /**
     * Relationship dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_nik', 'user_nik');
    }

    /**
     * Relationship dengan model Temu (jika ada)
     */
    public function temu()
    {
        return $this->belongsTo(Temu::class, 'temu_id', 'temu_id');
    }

    /**
     * Relationship dengan model Sebab (jika ada)
     */
    public function sebab()
    {
        return $this->belongsTo(Sebab::class, 'sebab_id', 'sebab_id');
    }

    /**
     * Relationship dengan model Coso (jika ada)
     */
    public function coso()
    {
        return $this->belongsTo(Coso::class, 'coso_id', 'coso_id');
    }

    /**
     * Relationship dengan model KlasifikasiAb (jika ada)
     */
    public function klasifikasiAb()
    {
        return $this->belongsTo(KlasifikasiAb::class, 'id_klasifikasi_ab', 'id_klasifikasi_ab');
    }

    /**
     * Relationship dengan model Tl (Tindak Lanjut)
     */
    public function tindakLanjut()
    {
        return $this->hasMany(Tl::class, 'temuan_id', 'temuan_id');
    }

    /**
     * Scope untuk filter berdasarkan pemeriksaan
     */
    public function scopeByPemeriksaan($query, $pemeriksaanId)
    {
        return $query->where('pemeriksaan_id', $pemeriksaanId);
    }

    /**
     * Scope untuk filter berdasarkan bidang temuan
     */
    public function scopeByBidangTemuan($query, $bidangTemuanId)
    {
        return $query->where('bidangtemuan_id', $bidangTemuanId);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userNik)
    {
        return $query->where('user_nik', $userNik);
    }

    /**
     * Scope untuk filter berdasarkan status kirim
     */
    public function scopeByStatusKirim($query, $status)
    {
        return $query->where('temuan_kirim', $status);
    }

    /**
     * Scope untuk filter berdasarkan publish kabag
     */
    public function scopeByPublishKabag($query, $status)
    {
        return $query->where('temuan_publish_kabag', $status);
    }

    /**
     * Accessor untuk format tanggal temuan_tgl
     */
    public function getTemuanTglFormattedAttribute()
    {
        return $this->temuan_tgl ? $this->temuan_tgl->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk format nominal dengan separator ribuan
     */
    public function getNominalFormattedAttribute()
    {
        return $this->nominal ? number_format($this->nominal, 0, ',', '.') : '0';
    }

    /**
     * Accessor untuk format nominal dengan mata uang
     */
    public function getNominalCurrencyAttribute()
    {
        return $this->nominal ? 'Rp ' . number_format($this->nominal, 0, ',', '.') : 'Rp 0';
    }

    /**
     * Mutator untuk nominal (menghilangkan separator sebelum disimpan)
     */
    public function setNominalAttribute($value)
    {
        $this->attributes['nominal'] = (int) str_replace(['.', ','], '', $value);
    }
}
