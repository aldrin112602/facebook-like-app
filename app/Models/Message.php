<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'is_read',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_FILE = 'file';

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId1)
                ->where('recipient_id', $userId2);
        })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('sender_id', $userId2)
                    ->where('recipient_id', $userId1);
            });
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage()
    {
        return $this->type === self::TYPE_IMAGE;
    }

    public function isVideo()
    {
        return $this->type === self::TYPE_VIDEO;
    }

    public function isFile()
    {
        return $this->type === self::TYPE_FILE;
    }

    public function hasFile()
    {
        return !empty($this->file_path);
    }
}