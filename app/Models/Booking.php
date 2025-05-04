<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name', 'service_option', 'name', 'address', 'email', 'phone_number',
        'date', 'time', 'full_pickup_truck_load', 'half_pickup_truck_load',
        'price', 'units', 'estimated_price', 'dumpster_size', 'city',
        'state', 'zip_code', 'detail'
    ];

    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }


}
