<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    /**
     * The table associated with the model.
     * Assuming the table name is 'pemeriksaan'. If different, update this property.
     *
     * @var string
     */
    protected $table = 'tb_pemeriksaan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'pemeriksaan_id';

    /**
     * Primary key is not auto-incrementing (or is it?). If your primary key is auto-increment
     * set this to true. Assuming integer auto-increment exists, keep as true.
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
     * If your table doesn't have created_at/updated_at set to false.
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
        'pemeriksaan_judul',
        'pemeriksaan_jenis',
        'pemeriksaan_pkpt',
        'pemeriksaan_objek',
        'pemeriksaan_nomor_st',
        'pemeriksaan_tanggal_st',
        'pemeriksaan_tgl',
        'pemeriksaan_tgl_mulai',
        'pemeriksaan_tgl_akhir',
        'kebun_id',
        'unit_id',
        'pemeriksaan_ketua',
        'pemeriksaan_pengawas',
        'pemeriksaan_petugas',
        'pemeriksaan_doc',
        'pemeriksaan_aktif',
        'pemeriksaan_sebelumnya',
        'user_nik', // Re-added to fillable since it's required
        'mention_unit',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pemeriksaan_id' => 'integer',
        'pemeriksaan_tanggal_st' => 'date',
        'pemeriksaan_tgl_mulai' => 'date',
        'pemeriksaan_tgl_akhir' => 'date',
        'kebun_id' => 'integer',
        'unit_id' => 'integer',
        'pemeriksaan_aktif' => 'string',
        // 'pemeriksaan_ketua' => 'integer',
        // 'pemeriksaan_pengawas' => 'integer',
    ];

    /**
     * If you want to map any relationships, add them here. Examples:
     */
    // public function kebun()
    // {
    //     return $this->belongsTo(Kebun::class, 'kebun_id');
    // }

    /**
     * Ketua (user) - Legacy relationship
     */
    public function ketua()
    {
        return $this->belongsTo(User::class, 'pemeriksaan_ketua', 'user_nik');
    }

    /**
     * Pengawas (user) - Legacy relationship
     */
    public function pengawas()
    {
        return $this->belongsTo(User::class, 'pemeriksaan_pengawas', 'user_nik');
    }

    /**
     * If you later add App\Models\Kebun or App\Models\Unit, you can enable these relations.
     */
    // public function kebun()
    // {
    //     return $this->belongsTo(\App\Models\Kebun::class, 'kebun_id');
    // }

    // public function unit()
    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id');
    }

    // public function unit()
    // public function mention_unit()
    // {
    //     return $this->belongsTo(\App\Models\Unit::class, 'mention_unit');
    // }


    /**
     * Petugas - Parse from pemeriksaan_petugas field (stored as slash separated values)
     */
    public function getPetugasAttribute()
    {
        // Since petugas are stored as string in pemeriksaan_petugas field
        // We need to parse them and return as collection
        if (empty($this->attributes['pemeriksaan_petugas'])) {
            return collect([]);
        }

        // Split by slash and get user data
        $petugasIds = explode('/', $this->attributes['pemeriksaan_petugas']);
        $petugasIds = array_map('trim', $petugasIds);
        $petugasIds = array_filter($petugasIds);

        return User::whereIn('user_nik', $petugasIds)->get();
    }

    /**
     * Relasi dengan temuan
     */
    public function temuan()
    {
        return $this->hasMany(Temuan::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

    /**
     * Relasi dengan LHA
     */
    public function lha()
    {
        return $this->hasOne(Lha::class, 'id_pemeriksaan', 'pemeriksaan_id');
    }

    /**
     * Relasi dengan rekomendasi
     */
    public function rekomendasi()
    {
        return $this->hasMany(Rekomendasi::class, 'pemeriksaan_id', 'pemeriksaan_id');
    }

}
