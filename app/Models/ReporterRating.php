<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporterRating extends Model
{
    protected $fillable = ['reporter_id', 'admin_id', 'rating', 'feedback'];

    public function reporter()
    {
        return $this->belongsTo(Reporter::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
