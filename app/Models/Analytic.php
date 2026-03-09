<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'article_id', 
        'ip', 
        'user_agent', 
        'referrer', 
        'device_type', 
        'visited_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
