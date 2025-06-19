<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    /** @use HasFactory<\Database\Factories\BoardFactory> */
    use HasFactory;

    protected $primaryKey = 'boardId';

    protected $fillable = ['boardName', 'workspaceId'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspaceId');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'boardId');
    }
}
