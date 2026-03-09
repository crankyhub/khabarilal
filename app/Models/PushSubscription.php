<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = ['endpoint', 'endpoint_hash', 'p256dh', 'auth'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            $subscription->endpoint_hash = md5($subscription->endpoint);
        });
    }
}
