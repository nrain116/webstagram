<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'description', 'image_path'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function likedBy($user): bool
    {
        if (!$user) return false;

        $id = $user instanceof User ? $user->id : (int) $user;

        return $this->likes()->where('user_id', $id)->exists();
    }

    public function likedByUser($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function getLikeCount(): int
    {
        return $this->likes()->count();
    }

    // accessor used in views
    public function getImagePathAttribute(): string
    {
        return $this->attributes['image_path'] ?? '';
    }
}
