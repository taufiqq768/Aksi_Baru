<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temu extends Model
{
    use HasFactory;

    protected $table = 'tb_master_temuan';
    protected $primaryKey = 'temu_id';
    public $timestamps = false;

    protected $fillable = [
        'kode_temuan',
        'klasifikasi_temuan'
    ];

    protected $casts = [
        'temu_id' => 'integer',
        'kode_temuan' => 'string',
        'klasifikasi_temuan' => 'string'
    ];

    /**
     * Relasi dengan temuan
     */
    public function temuan()
    {
        return $this->hasMany(Temuan::class, 'temu_id', 'temu_id');
    }

    /**
     * Scope untuk pencarian berdasarkan kode
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_temuan', $kode);
    }

    /**
     * Accessor untuk format kode temuan
     */
    public function getFormattedKodeAttribute()
    {
        return strtoupper($this->kode_temuan);
    }
}
