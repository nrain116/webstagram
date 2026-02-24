<?php

namespace App\Models;

use App\Events\UserFollowed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = ['follower_id', 'following_id'];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    protected static function booted()
    {
        static::created(function ($follow) {
            event(new UserFollowed(
                $follow->follower,
                $follow->following
            ));
        });
    }
}
