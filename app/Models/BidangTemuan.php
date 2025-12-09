<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangTemuan extends Model
{
    use HasFactory;

    protected $table = 'tb_bidangtemuan';
    protected $primaryKey = 'bidangtemuan_id';
    public $timestamps = false;

    protected $fillable = [
        'bidangtemuan_nama'
    ];

    protected $casts = [
        'bidangtemuan_id' => 'integer',
        'bidangtemuan_nama' => 'string'
    ];

    /**
     * Relasi dengan temuan
     */
    public function temuan()
    {
        return $this->hasMany(Temuan::class, 'bidangtemuan_id', 'bidangtemuan_id');
    }

    /**
     * Scope untuk pencarian berdasarkan nama
     */
    public function scopeByNama($query, $nama)
    {
        return $query->where('bidangtemuan_nama', 'like', '%' . $nama . '%');
    }

    /**
     * Accessor untuk format nama bidang temuan
     */
    public function getFormattedNamaAttribute()
    {
        return ucwords(strtolower($this->bidangtemuan_nama));
    }
}