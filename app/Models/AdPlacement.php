<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPlacement extends Model
{
    protected $fillable = [
        'ad_id', 'position', 'type', 'image_path', 'content', 'link_url'
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
