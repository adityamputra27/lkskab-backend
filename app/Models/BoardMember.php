<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardMember extends Model
{
    use HasFactory;

    protected $table = 'board_members';

    protected $fillable = [
        'board_id', 'user_id'
    ];  

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
