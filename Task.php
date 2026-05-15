<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // A task belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: only current user's tasks
    public function scopeForUser($query, $userId = null)
    {
        return $query->where('user_id', $userId ?? auth()->id());
    }

    // Scope: filter by status
    public function scopeWithStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope: filter by priority
    public function scopeWithPriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }
}