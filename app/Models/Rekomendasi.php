<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    use HasFactory;

    protected $table = 'tb_rekomendasi';
    protected $primaryKey = 'rekomendasi_id';
    public $timestamps = false;

    protected $fillable = [
        'temuan_id',
        'pemeriksaan_id',
        'rekomendasi_judul',
        'rekomendasi_tgl',
        'rekomendasi_tgl_deadline',
        'rekomendasi_status',
        'rekomendasi_status_tanggal',
        'rekomendasi_status_cache',
        'rekomendasi_status_terbaru',
        'rekomendasi_status_publish_kabag',
        'rekomendasi_status_kirim',
        'rekomendasi_aktif',
        'rekomendasi_kirim',
        'rekomendasi_publish_kabag',
        'rekomendasi_pmr_sebelumnya',
        'user_nik',
        'rekomen_id',
        'unit_id'
    ];

    protected $casts = [
        'rekomendasi_id' => 'integer',
        'temuan_id' => 'integer',
        'pemeriksaan_id' => 'integer',
        'rekomendasi_judul' => 'string',
        'rekomendasi_tgl' => 'date',
        'rekomendasi_tgl_deadline' => 'date',
        'rekomendasi_status' => 'string',
        'rekomendasi_status_tanggal' => 'date',
        'rekomendasi_status_cache' => 'string',
        'rekomendasi_status_terbaru' => 'string',
        'rekomendasi_status_publish_kabag' => 'string',
        'rekomendasi_status_kirim' => 'string',
        'rekomendasi_aktif' => 'string',
        'rekomendasi_kirim' => 'string',
        'rekomendasi_publish_kabag' => 'string',
        'rekomendasi_pmr_sebelumnya' => 'integer',
        'user_nik' => 'string',
        'rekomen_id' => 'integer',
        'unit_id' => 'integer'
    ];

    /**
     * Relationship dengan model Temuan
     */
    public function temuan()
    {
        return $this->belongsTo(Temuan::class, 'temuan_id', 'temuan_id');
    }

    /**
     * Relationship dengan model Pemeriksaan
     */
    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    /**
     * Relationship dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_nik', 'user_nik');
    }

    /**
     * Relationship dengan model Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    /**
     * Relationship dengan model Rekomen (jika ada)
     */
    public function rekomen()
    {
        return $this->belongsTo(Rekomen::class, 'rekomen_id', 'rekomen_id');
    }

    /**
     * Relationship dengan model Tl (Tindak Lanjut)
     */
    public function tindakLanjut()
    {
        return $this->hasMany(Tl::class, 'rekomendasi_id', 'rekomendasi_id');
    }

    /**
     * Scope untuk filter berdasarkan temuan
     */
    public function scopeByTemuan($query, $temuanId)
    {
        return $query->where('temuan_id', $temuanId);
    }

    /**
     * Scope untuk filter berdasarkan pemeriksaan
     */
    public function scopeByPemeriksaan($query, $pemeriksaanId)
    {
        return $query->where('pemeriksaan_id', $pemeriksaanId);
    }

    /**
     * Scope untuk filter berdasarkan unit
     */
    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userNik)
    {
        return $query->where('user_nik', $userNik);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('rekomendasi_status', $status);
    }

    /**
     * Scope untuk filter berdasarkan status terbaru
     */
    public function scopeByStatusTerbaru($query, $status)
    {
        return $query->where('rekomendasi_status_terbaru', $status);
    }

    /**
     * Scope untuk filter rekomendasi aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('rekomendasi_aktif', 'Y');
    }

    /**
     * Scope untuk filter rekomendasi yang sudah dikirim
     */
    public function scopeKirim($query)
    {
        return $query->where('rekomendasi_kirim', 'Y');
    }

    /**
     * Scope untuk filter rekomendasi yang sudah dipublish kabag
     */
    public function scopePublishKabag($query)
    {
        return $query->where('rekomendasi_publish_kabag', 'Y');
    }

    /**
     * Scope untuk filter berdasarkan deadline
     */
    public function scopeDeadlineBefore($query, $date)
    {
        return $query->where('rekomendasi_tgl_deadline', '<=', $date);
    }

    /**
     * Scope untuk filter berdasarkan deadline
     */
    public function scopeDeadlineAfter($query, $date)
    {
        return $query->where('rekomendasi_tgl_deadline', '>=', $date);
    }

    /**
     * Accessor untuk format tanggal rekomendasi_tgl
     */
    public function getRekomendasiTglFormattedAttribute()
    {
        return $this->rekomendasi_tgl ? $this->rekomendasi_tgl->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk format tanggal rekomendasi_tgl_deadline
     */
    public function getRekomendasiTglDeadlineFormattedAttribute()
    {
        return $this->rekomendasi_tgl_deadline ? $this->rekomendasi_tgl_deadline->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk format tanggal rekomendasi_status_tanggal
     */
    public function getRekomendasiStatusTanggalFormattedAttribute()
    {
        return $this->rekomendasi_status_tanggal ? $this->rekomendasi_status_tanggal->format('d/m/Y') : null;
    }

    /**
     * Accessor untuk cek apakah rekomendasi sudah melewati deadline
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->rekomendasi_tgl_deadline) {
            return false;
        }
        return $this->rekomendasi_tgl_deadline->isPast();
    }

    /**
     * Accessor untuk menghitung sisa hari deadline
     */
    public function getDaysToDeadlineAttribute()
    {
        if (!$this->rekomendasi_tgl_deadline) {
            return null;
        }
        return now()->diffInDays($this->rekomendasi_tgl_deadline, false);
    }

    /**
     * Accessor untuk status deadline (overdue, warning, safe)
     */
    public function getDeadlineStatusAttribute()
    {
        $daysToDeadline = $this->days_to_deadline;

        if ($daysToDeadline === null) {
            return 'no_deadline';
        }

        if ($daysToDeadline < 0) {
            return 'overdue';
        } elseif ($daysToDeadline <= 7) {
            return 'warning';
        } else {
            return 'safe';
        }
    }
}
