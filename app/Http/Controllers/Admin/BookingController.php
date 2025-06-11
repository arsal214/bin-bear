<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Interfaces\BookingRepositoryInterface;
use App\Traits\UploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends BaseController
{
    use UploadTrait;
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
    ) {
        // $this->middleware('permission:blogs-list', ['only' => ['index', 'show']]);
        // $this->middleware('permission:blogs-create', ['only' => ['store']]);
        // $this->middleware('permission:blogs-edit', ['only' => ['edit', 'update','change']]);
        // $this->middleware('permission:blogs-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.bookings.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(): JsonResponse
    {
        $data = $this->bookingRepository->list();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('pages.bookings.actions', compact('row'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function show(string $id)
    {
        $booking = $this->bookingRepository->findById($id);
        return view('pages.bookings.show', compact('booking'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->bookingRepository->destroyById($id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return  $this->redirectSuccess(route('pages.bookings.index'), 'Blog deleted successfully');
    }


}
