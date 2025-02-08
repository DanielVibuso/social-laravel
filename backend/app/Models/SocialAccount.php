<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use UsesUuid;

    protected $fillable = [
        'user_id', 
        'provider', 
        'provider_id',
        'token',
        'token_secret'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}