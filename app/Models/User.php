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
        'avatar',
        'bio',
        'is_online',
        'last_seen_at',
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
            'last_seen_at' => 'datetime',
            'is_online' => 'boolean',
        ];
    }


    // Friend relationships
    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    public function pendingFriendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', 'pending');
    }

    // Message relationships
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function conversations()
    {
        return $this->hasMany(Message::class, 'sender_id')
            ->orWhere('recipient_id', $this->id)
            ->orderBy('created_at', 'desc');
    }

    // Helper methods
    public function isFriendWith($userId)
    {
        return $this->friends()->where('users.id', $userId)->exists() ||
            $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', 'accepted')
            ->where('users.id', $userId)
            ->exists();
    }

    public function hasPendingFriendRequestFrom($userId)
    {
        return $this->receivedFriendRequests()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    public function hasSentFriendRequestTo($userId)
    {
        return $this->sentFriendRequests()
            ->where('friend_id', $userId)
            ->where('status', 'pending')
            ->exists();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
