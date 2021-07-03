<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';

    protected $fillable = [
        'creator_id', 'name'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function creators()
    {
        return $this->hasMany(User::class);
    }

    public function boardLists()
    {
        return $this->hasMany(BoardList::class, 'board_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'board_members');
    }

}
