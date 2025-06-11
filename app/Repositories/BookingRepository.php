<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;


class BookingRepository implements BookingRepositoryInterface
{

    /**
     * All  category list.
     */
    public function list()
    {
        return Booking::latest();
    }

   

    
    /**
     * Find  Blog by id.
     */
    public function findById($id): ?Booking
    {
        return Booking::find($id);
    }


    /**
     * Delete Blog by id.
     */
    public function destroyById($id): bool
    {
        return $this->findById($id)->delete();
    }
}
