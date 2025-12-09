<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /**
     * The table associated with the model.
     * If your table name is not 'unit' change this value.
     *
     * @var string
     */
    protected $table = 'tb_unit';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'unit_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Disable timestamps if table doesn't have created_at/updated_at
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_nama',
        'kode_unit',
        'jenis',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'unit_id' => 'integer',
    ];

    // Optional: if you want to link back to pemeriksaan
    // public function pemeriksaans()
    // {
    //     return $this->hasMany(Pemeriksaan::class, 'unit_id');
    // }
}
