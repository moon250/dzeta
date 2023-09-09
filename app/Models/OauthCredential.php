<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthCredential extends Model
{
    protected $table = 'oauth_credentials';

    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'expires_at',
    ];
}
