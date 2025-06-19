<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryFactory> */
    use HasFactory;

    protected $primaryKey = 'historyId';

    protected $fillable = ['description', 'date', 'userId'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
