<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Interfaces\CouponRepositoryInterface;
use App\Traits\UploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends BaseController
{
    use UploadTrait;

    public function __construct(
        private CouponRepositoryInterface $couponRepository,
    ) {
       $this->middleware('permission:coupons-list', ['only' => ['index', 'show']]);
       $this->middleware('permission:coupons-create', ['only' => ['create', 'store']]);
       $this->middleware('permission:coupons-edit', ['only' => ['edit', 'update', 'popular']]);
       $this->middleware('permission:coupons-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.coupon.index');
    }

    /**
     * Staff List
     */
    public function list(): JsonResponse
    {
        $data = $this->couponRepository->list();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('pages.coupon.actions.actions', compact('row'));
            })
            ->rawColumns(['action',])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);

            $data = $request->all();
            $this->couponRepository->storeOrUpdate($data);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => $th->getMessage()]);
        }
        return $this->redirectSuccess(route('coupons.index'), 'Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = $this->couponRepository->findById($id);
        return view('content.coupon.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = $this->couponRepository->findById($id);
        return view('pages.coupon.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'status' => 'required',
            ]);

            $data = $request->all();
            $this->couponRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => $th->getMessage()]);
        }
        return $this->redirectSuccess(route('coupons.index'), 'Coupon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $coupon = $this->couponRepository->findById($id);
            $coupon->delete();
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return  $this->redirectSuccess(route('coupons.index'), 'Coupon deleted successfully');
    }

}

