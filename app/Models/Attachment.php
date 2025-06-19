<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /** @use HasFactory<\Database\Factories\AttachmentFactory> */
    use HasFactory;

    protected $primaryKey = 'attachmentId';

    protected $fillable = ['note','fileName', 'filePath', 'posted_date', 'userId', 'taskId'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'taskId');
    }
}
