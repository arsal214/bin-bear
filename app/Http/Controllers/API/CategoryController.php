<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Interfaces\BlogRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,

    )
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = $this->categoryRepository->activeList();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($categories, 'Data Get SuccessFully', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function allList()
    {
        try {
            $categories = $this->categoryRepository->allList();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($categories, 'Data Get SuccessFully', 200);
    }


    /**
     * Display a listing of the resource.
     */
    public function isPopular()
    {

        try {
            $categories = $this->categoryRepository->isPopular();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($categories, 'Data Get SuccessFully', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function subCategory($id)
    {
        try {
            $category = $this->categoryRepository->nestedCategory($id);
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($category, 'Data Get SuccessFully', 200);
    }

    public function serviceCategory($id)
    {
        try {
            $category = $this->categoryRepository->nestedCategory($id);
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($category, 'Data Get SuccessFully', 200);
    }


    /**
     * Display a listing of the resource.
     */
    public function subCategoryByID($id)
    {
        try {
            $category = $this->categoryRepository->nestedCategory($id);
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($category, 'Data Get SuccessFully', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function allCategories()
    {
        try {
            $category = $this->categoryRepository->nestedCategories();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($category, 'Data Get SuccessFully', 200);
    }


}
