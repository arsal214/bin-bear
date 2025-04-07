<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Interfaces\BlogRepositoryInterface;
use App\Traits\UploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends BaseController
{
    use UploadTrait;
    public function __construct(
        private BlogRepositoryInterface $blogRepository,
    ) {
        $this->middleware('permission:blogs-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:blogs-create', ['only' => ['store']]);
        $this->middleware('permission:blogs-edit', ['only' => ['edit', 'update','change']]);
        $this->middleware('permission:blogs-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.blogs.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(): JsonResponse
    {
        $data = $this->blogRepository->list();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('pages.blogs.actions', compact('row'));
            })->editColumn('status', function ($row) {
                return view('pages.blogs.status', compact('row'));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'title' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
                'body' => 'required',
                'status' => 'required',
            ]);

            $data = $request->except('image');
            $data['image'] = $request->hasFile('image') ? $this->uploadFile($request->file('image'), 'blogs') : 'https://png.pngtree.com/element_our/20200610/ourmid/pngtree-character-default-avatar-image_2237203.jpg';
            $this->blogRepository->storeOrUpdate($data);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('pages.blogs.index'), 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): RedirectResponse|View
    {
        $blog = $this->blogRepository->findById($id);
        return view('pages.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $request->validate([
                'title' => 'required',
                'body' => 'required',
                'status' => 'required'
            ]);

            $data = $request->except('image');
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadFile($request->file('image'), 'blogs');
            }
            $this->blogRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('pages.blogs.index'), 'Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->blogRepository->destroyById($id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return  $this->redirectSuccess(route('pages.blogs.index'), 'Blog deleted successfully');
    }

    public function change(Request $request, string $id)
    {
        try {
            $data = [];

            if ($request->field == 'status') {

                $data['status'] = ($request->boolean('status')) == true ? 'Active'  : 'DeActive';
            }
            $this->blogRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => $th->getMessage()]);
        }
        return $this->redirectSuccess(route('pages.blogs.index'), 'Blog changed successfully.');
    }
}
