<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';
    protected $fillable = [
        'name',
        'discount_value',
        'maximum_usage',
        'valid_from',
        'valid_till',
        'discount_type',
        'status'
    ];


    public function plans()
    {
        return $this->hasMany(Plan::class, 'coupon_id');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
