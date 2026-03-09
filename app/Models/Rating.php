<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['article_id', 'stars', 'ip_address'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
