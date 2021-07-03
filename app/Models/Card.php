<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';

    protected $fillable = [
        'list_id', 'order', 'task'
    ];

    public function boardLists()
    {
        return $this->belongsTo(BoardList::class, 'list_id');
    }
}
