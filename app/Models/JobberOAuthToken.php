<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobberOAuthToken extends Model
{
    use HasFactory;

    protected $table = 'jobber_o_auth_tokens';
    protected $guarded = [];
}
