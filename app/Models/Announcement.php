<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id','title','slug','category','excerpt','body',
        'cover_image','is_published','published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ตั้ง slug อัตโนมัติถ้าไม่ส่งมา
    public static function booted() {
        static::creating(function ($a) {
            if (empty($a->slug)) {
                $a->slug = Str::slug(Str::limit($a->title, 60, ''), '-').'-'.Str::random(6);
            }
        });
    }
}