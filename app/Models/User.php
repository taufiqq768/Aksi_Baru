<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     * Change if your users table has a different name (for example 'user').
     *
     * @var string
     */
    protected $table = 'tb_users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_users';

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
        'user_nik',
        'user_nama',
        'user_email',
        'user_password',
        'user_tlp',
        'user_level',
        'user_aktif',
        'user_count',
        'user_foto',
        'role_id',
        'unit_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'user_password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_users' => 'integer',
        'role_id' => 'integer',
        'unit_id' => 'integer',
    ];

    /**
     * If you have a Unit model, link it here.
     */
    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id');
    }

    /**
     * Automatically hash the user_password when setting it.
     * Preserves existing MD5/plain text passwords for backward compatibility.
     *
     * @param  string  $value
     * @return void
     */
    public function setUserPasswordAttribute($value)
    {
        if ($value === null) {
            $this->attributes['user_password'] = null;
            return;
        }

        // If the value is already a bcrypt hash (starts with $2y$ or $2a$), don't rehash
        if (str_starts_with($value, '$2y$') || str_starts_with($value, '$2a$')) {
            $this->attributes['user_password'] = $value;
            return;
        }

        // If it looks like MD5 (32 character hex string), keep as is
        if (preg_match('/^[a-f0-9]{32}$/i', $value)) {
            $this->attributes['user_password'] = $value;
            return;
        }

        // For new passwords or updates from forms, hash them
        // Only hash if this is being set from a form/API (not from database)
        // We check if the model exists (has been loaded from DB) and if password hasn't changed
        if ($this->exists && isset($this->attributes['user_password']) && $this->attributes['user_password'] === $value) {
            // Password hasn't changed, keep as is (supports plain text/MD5 from DB)
            return;
        }

        // New password or password being updated - hash it with bcrypt
        $this->attributes['user_password'] = Hash::make($value);
    }

    /**
     * Return the password for the auth system.
     *
     * @return string|null
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the name of the unique identifier for the user.
     * This tells Laravel to use 'user_nik' instead of 'email' for authentication.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }

}
