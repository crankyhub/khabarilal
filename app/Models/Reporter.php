<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporter extends Model
{
    protected $fillable = [
        'user_id', 
        'bio', 
        'photo_path', 
        'beat', 
        'social_links', 
        'category_id', 
        'revenue_share', 
        'rating_average'
    ];

    protected $casts = [
        'social_links' => 'array',
        'revenue_share' => 'decimal:2',
        'rating_average' => 'decimal:2'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->hasMany(ReporterRating::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'user_id', 'user_id');
    }
}

