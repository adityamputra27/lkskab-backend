<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardList extends Model
{
    use HasFactory;

    protected $table = 'board_lists';

    protected $fillable = [
        'board_id', 'order', 'name'
    ];

    public function boards()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class, 'list_id');
    }
}
