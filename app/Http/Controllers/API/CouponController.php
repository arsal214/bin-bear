<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Interfaces\CouponRepositoryInterface;


class CouponController extends BaseController
{
    public function __construct(
        private CouponRepositoryInterface $couponRepository,

    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $blogs = $this->couponRepository->activeList();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($blogs, 'Data Get SuccessFully', 200);
    }


    /**
     * Display a listing of the resource.
     */
    public function show($id)
    {

        try {
            $blog = $this->couponRepository->findById($id);
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($blog, 'Data Get SuccessFully', 200);
    }
}
