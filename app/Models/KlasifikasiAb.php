<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiAb extends Model
{
    use HasFactory;

    protected $table = 'tb_master_ab';
    protected $primaryKey = 'id_ab';
    public $timestamps = false;

    protected $fillable = [
        'kode_ab',
        'judul_ab'
    ];

    protected $casts = [
        'id_ab' => 'integer',
        'kode_ab' => 'string',
        'judul_ab' => 'string'
    ];

    /**
     * Relasi dengan temuan
     */
    public function temuan()
    {
        return $this->hasMany(Temuan::class, 'id_klasifikasi_ab', 'id_ab');
    }

    /**
     * Scope untuk pencarian berdasarkan kode
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_ab', $kode);
    }

    /**
     * Scope untuk pencarian berdasarkan judul
     */
    public function scopeByJudul($query, $judul)
    {
        return $query->where('judul_ab', 'like', '%' . $judul . '%');
    }

    /**
     * Accessor untuk format kode AB
     */
    public function getFormattedKodeAttribute()
    {
        return strtoupper($this->kode_ab);
    }

    /**
     * Accessor untuk format judul AB
     */
    public function getFormattedJudulAttribute()
    {
        return ucwords(strtolower($this->judul_ab));
    }
}
