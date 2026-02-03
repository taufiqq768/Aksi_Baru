<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekom extends Model
{
    use HasFactory;

    protected $table = 'tb_master_rekomendasi';
    protected $primaryKey = 'rekomen_id';
    public $timestamps = false;

    protected $fillable = [
        'judul'
    ];

    protected $casts = [
        'rekomen_id' => 'integer',
        'judul' => 'string'
    ];

    /**
     * Relasi dengan temuan
     */
    public function rekomendasi()
    {
        return $this->hasMany(Rekomendasi::class, 'rekomen_id', 'rekomen_id');
    }

    /**
     * Scope untuk pencarian berdasarkan kode
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('rekomen_id', $kode);
    }

    /**
     * Accessor untuk format kode temuan
     */
    public function getFormattedKodeAttribute()
    {
        return strtoupper($this->rekomen_id);
    }
}
