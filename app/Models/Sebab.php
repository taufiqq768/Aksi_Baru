<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sebab extends Model
{
    use HasFactory;

    protected $table = 'tb_master_penyebab';
    protected $primaryKey = 'sebab_id';
    public $timestamps = false;

    protected $fillable = [
        'sebab_kode',
        'klasifikasi_sebab'
    ];

    protected $casts = [
        'sebab_id' => 'integer',
        'sebab_kode' => 'string',
        'klasifikasi_sebab' => 'string'
    ];

    /**
     * Relasi dengan temuan
     */
    public function temuan()
    {
        return $this->hasMany(Temuan::class, 'sebab_id', 'sebab_id');
    }

    /**
     * Scope untuk pencarian berdasarkan kode
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('sebab_kode', $kode);
    }

    /**
     * Accessor untuk format kode sebab
     */
    public function getFormattedKodeAttribute()
    {
        return strtoupper($this->sebab_kode);
    }

    /**
     * Accessor untuk format klasifikasi sebab
     */
    public function getFormattedKlasifikasiAttribute()
    {
        return ucfirst(strtolower($this->klasifikasi_sebab));
    }
}
