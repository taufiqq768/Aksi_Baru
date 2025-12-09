<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coso extends Model
{
    use HasFactory;

    protected $table = 'tb_master_coso';
    protected $primaryKey = 'coso_id';
    public $timestamps = false;

    protected $fillable = [
        'kode_coso',
        'klasifikasi_coso'
    ];

    protected $casts = [
        'coso_id' => 'integer',
        'kode_coso' => 'string',
        'klasifikasi_coso' => 'string'
    ];

    /**
     * Relasi dengan temuan
     */
    public function temuan()
    {
        return $this->hasMany(Temuan::class, 'coso_id', 'coso_id');
    }

    /**
     * Scope untuk pencarian berdasarkan kode
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_coso', $kode);
    }

    /**
     * Accessor untuk format kode COSO
     */
    public function getFormattedKodeAttribute()
    {
        return strtoupper($this->kode_coso);
    }

    /**
     * Accessor untuk format klasifikasi COSO
     */
    public function getFormattedKlasifikasiAttribute()
    {
        return ucfirst(strtolower($this->klasifikasi_coso));
    }
}
