<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 
        'slug', 
        'body', 
        'summary', 
        'meta_title', 
        'meta_description', 
        'image_path', 
        'media_id',
        'category_id', 
        'user_id', 
        'status', 
        'moderation_status',
        'rejection_reason',
        'is_ai_generated',
        'published_at'
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function gallery()
    {
        return $this->belongsToMany(Media::class, 'article_media');
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('stars'), 1);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
