<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $primaryKey = 'taskId';

    protected $fillable = ['taskName', 'post_date', 'due_date', 'status', 'boardId'];

    public function board()
    {
        return $this->belongsTo(Board::class, 'boardId');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'responsibility', 'taskId', 'userId');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'taskId');
    }
}
