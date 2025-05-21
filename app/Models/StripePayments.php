<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayments extends Model
{
    use HasFactory;


    protected $table = 'stripe_payments';

    protected $fillable = [
        'user_id',
        'coupon_id',
        'customer_email',
        'stripe_customer_id',
        'price',
        'stripe_payment_id',
        'currency',
        'last_4_digit' ,
        'card_exp_month',
        'card_exp_year',
        'stripe_response'
    ];
}
