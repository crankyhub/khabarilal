<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['filename', 'path', 'mime_type', 'size', 'disk'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_media');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            Storage::disk($media->disk)->delete($media->path);
        });
    }
}
