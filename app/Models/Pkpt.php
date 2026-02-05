<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pkpt extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_pkpt';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pkpt_id';

    /**
     * Indicates if the primary key is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jenis_audit',
        'bulan',
        'jumlah',
        'tahun',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pkpt_id' => 'integer',
        'jumlah' => 'integer',
        'tahun' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
