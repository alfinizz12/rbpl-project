<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roleId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roleId');
    }

    public function workspaces()
    {
        return $this->hasMany(Workspace::class, 'ownerId');
    }

    public function workspaceMemberships()
    {
        return $this->belongsToMany(Workspace::class, 'workspace_member', 'userId', 'workspaceId');
    }

    public function responsibilities()
    {
        return $this->belongsToMany(Task::class, 'responsibility', 'userId', 'taskId');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'userId');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'userId');
    }
}
