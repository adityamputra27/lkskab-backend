<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function login_token()
    {
        return $this->hasOne(LoginToken::class);
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'creator_id');
    }

    protected $appends = ['initial'];

    // Membuat field static baru menggunakan accessor / mutators
    public function getInitialAttribute()
    {
        $initital = Str::upper(substr($this->first_name, 0, 1)).Str::upper(substr($this->last_name, 0, 1));
        return $initital;
    }

    // protected $hidden = array('pivot');
}
