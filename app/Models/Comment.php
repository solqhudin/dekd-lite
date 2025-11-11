<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
        'parent_id',
    ];

    // เจ้าของ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // โพสต์
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // parent comment (ถ้าเป็น reply)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // replies
    public function replies()
    {
        // with('replies') = eager load ซ้อน ทำให้ reply ของ reply โผล่ด้วย
        return $this->hasMany(Comment::class, 'parent_id')
            ->with(['user', 'reactions', 'replies']);
    }

    // reactions
    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }


    public function likes()
    {
        return $this->reactions()->where('type', 'like');
    }

    public function dislikes()
    {
        return $this->reactions()->where('type', 'dislike');
    }

    // helper: เช็คว่าเป็นของ user นี้ไหม
    public function isOwnedBy($user): bool
    {
        return $user && $this->user_id === $user->id;
    }
}
