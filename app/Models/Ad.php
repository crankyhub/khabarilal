<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title', 'type', 'content', 'link_url', 'position', 'is_active'
    ];
}
