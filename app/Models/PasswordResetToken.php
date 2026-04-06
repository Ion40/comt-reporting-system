<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'expires_at',
        'is_used'
    ];
}
