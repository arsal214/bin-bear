<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Interfaces\BlogRepositoryInterface;


class BlogController extends BaseController
{
    public function __construct(
        private BlogRepositoryInterface $blogRepository,

    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $blogs = $this->blogRepository->activeList();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($blogs, 'Data Get SuccessFully', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function isPopular()
    {
        try {
            $blogs = $this->blogRepository->isPopular();
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
            $blog = $this->blogRepository->findById($id);
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($blog, 'Data Get SuccessFully', 200);
    }
}
