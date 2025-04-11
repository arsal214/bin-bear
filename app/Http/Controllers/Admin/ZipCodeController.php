<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Interfaces\ZipCodeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class ZipCodeController extends BaseController
{

    public function __construct(
        private ZipCodeRepositoryInterface $zipCodeRepository,
    ) {
    //    $this->middleware('permission:zipCodes-list', ['only' => ['index', 'show']]);
    //    $this->middleware('permission:zipCodes-create', ['only' => ['create', 'store']]);
    //    $this->middleware('permission:zipCodes-edit', ['only' => ['edit', 'update', 'popular']]);
    //    $this->middleware('permission:zipCodes-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.zipCode.index');
    }

    /**
     * Staff List
     */
    public function list(): JsonResponse
    {
        $data = $this->zipCodeRepository->list();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('pages.zipCode.actions.actions', compact('row'));
            })
            ->rawColumns(['action',])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('pages.zipCode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'city_name' => 'required',
                'zip_code' => 'required',
                'status' => 'required',
            ]);

            $data = $request->all();
            $this->zipCodeRepository->storeOrUpdate($data);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => $th->getMessage()]);
        }
        return $this->redirectSuccess(route('zip-codes.index'), 'Zip Code created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $coupon = $this->zipCodeRepository->findById($id);
        return view('pages.zipCode.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'city_name' => 'required',
                'zip_code' => 'required',
                'status' => 'required',
            ]);

            $data = $request->all();
            $this->zipCodeRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => $th->getMessage()]);
        }
        return $this->redirectSuccess(route('zip-codes.index'), 'Data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $coupon = $this->zipCodeRepository->findById($id);
            $coupon->delete();
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return  $this->redirectSuccess(route('zip-codes.index'), 'Data deleted successfully');
    }

}

