<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadTl extends Model
{
    use HasFactory;

    protected $table = 'tb_upload_tl';
    protected $primaryKey = 'uploadtl_id';
    public $timestamps = false;

    protected $fillable = [
        'tl_id',
        'uploadtl_nama',
        'uploadtl_tgl',
        'token',
    ];

    protected $casts = [
        'uploadtl_id' => 'integer',
        'tl_id' => 'integer',
        'uploadtl_nama' => 'string',
        'uploadtl_tgl' => 'date',
        'token' => 'string',
    ];

    public function tindakLanjut()
    {
        return $this->belongsTo(Tl::class, 'tl_id', 'tl_id');
    }

    public function getUploadtlTglFormattedAttribute()
    {
        return $this->uploadtl_tgl ? $this->uploadtl_tgl->format('d/m/Y') : null;
    }
}