<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Interfaces\ZipCodeRepositoryInterface;
use Illuminate\Http\Request;

class ZipCodeController extends BaseController
{
    public function __construct(
        private ZipCodeRepositoryInterface $zipCodeRepository,

    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $codes = $this->zipCodeRepository->activeList();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($codes, 'Data Get SuccessFully', 200);
    }
}
