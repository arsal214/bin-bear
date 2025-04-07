<?php

namespace App\Repositories;

use App\Interfaces\CouponRepositoryInterface;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Collection;


class CouponRepository implements CouponRepositoryInterface
{
    /**
     * All Coupon list.
     */
    public function list(): Collection
    {
        return Coupon::latest()->get();
    }

    /**
     * Active Coupon list.
     */
    public function activeList(): Collection
    {
        return Coupon::where('status','Active')->latest()->get();
    }

    /**
     * Create or update Coupon.
     */
    public function storeOrUpdate(array $data, $id = null): Coupon
    {
        $coupon = Coupon::updateOrCreate(
            ['id' => $id],
            $data
        );
        return $coupon;
    }

    /**
     * Find Coupon by id.
     */
    public function findById($id): Coupon
    {
        return Coupon::find($id);
    }

    /**
     * Delete Coupon by id.
     */
    public function destroyById($id): bool
    {
        return $this->findById($id)->delete();
    }
}
