<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:D, M j, Y g:i A'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
