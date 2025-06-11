<?php

namespace App\Interfaces;

interface BookingRepositoryInterface
{
    public function list();

    public function findById($id);

    public function destroyById($id);

}
