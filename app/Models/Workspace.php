<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    /** @use HasFactory<\Database\Factories\WorkspaceFactory> */
    use HasFactory;

    protected $primaryKey = 'workspaceId';

    protected $fillable = ['workspaceName', 'ownerId'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerId');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'workspace_member', 'workspaceId', 'userId');
    }

    public function boards()
    {
        return $this->hasMany(Board::class, 'workspaceId');
    }
}
