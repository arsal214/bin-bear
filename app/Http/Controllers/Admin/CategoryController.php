<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use App\Traits\UploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends BaseController
{
    use UploadTrait;

    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    )
    {
        // $this->middleware('permission:category-list', ['only' => ['index', 'show']]);
        // $this->middleware('permission:category-create', ['only' => ['store']]);
        // $this->middleware('permission:category-edit', ['only' => ['edit', 'update', 'change']]);
        // $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryRepository->parentCategory();
        return view('pages.catalog.services-category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $parentCategories = $this->categoryRepository->activeCategory();

        return view('pages.catalog.services-category.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            if ($request->parent_id === null) {

                $parentCategories = $this->categoryRepository->list();
                $parentNames = $parentCategories->pluck('name');
                if ($parentNames->contains($request->name)) {
                    return $this->redirectError('Category Name Already Taken');
                }
            }
            if ($request->parent_id !== null) {
                $childCategories = $this->categoryRepository->subCategory($request->parent_id);
                $childNames = $childCategories->pluck('name');
                if ($childNames->contains($request->name)) {
                    return $this->redirectError('Category Name Already Taken');
                }
            }

            $data = $request->except('image');
            $data['image'] = $request->hasFile('image') ? $this->uploadFile($request->file('image'), 'categories') : 'https://png.pngtree.com/element_our/20200610/ourmid/pngtree-character-default-avatar-image_2237203.jpg';
            $this->categoryRepository->storeOrUpdate($data);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('catalog.category.index'), 'Category created successfully.');
    }

//    /**
//     * Display the specified resource.
//     */
//    public function show($id)
//    {
////        $childCategory = $this->categoryRepository->nestedCategory($id);
//        $catId = $id;
//        return view('pages.catalog.services-category.child-category.index', compact('catId'));
//    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): RedirectResponse|View
    {
        $category = $this->categoryRepository->findById($id);
        $parentCategories = $this->categoryRepository->activeCategory();
        return view('pages.catalog.services-category.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'status' => 'required'
            ]);

            if ($request->parent_id === null) {
                // Fetch all parent categories except the current one being updated
                $parentCategories = $this->categoryRepository->list()->where('id', '!=', $id);
                $parentNames = $parentCategories->pluck('name');
                // Check if the name already exists in the parent categories
                if ($parentNames->contains($request->name)) {
                    return $this->redirectError('Category Name Already Taken');
                }
            }

            if ($request->parent_id !== null) {
                // Fetch all child categories of the parent except the current one being updated
                $childCategories = $this->categoryRepository->subCategory($request->parent_id)->where('id', '!=', $id);
                $childNames = $childCategories->pluck('name');
                // Check if the name already exists in the child categories
                if ($childNames->contains($request->name)) {
                    return $this->redirectError('Category Name Already Taken');
                }
            }
            $data = $request->except('image');
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadFile($request->file('image'), 'categories');
            }
            $this->categoryRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('catalog.category.index'), 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->categoryRepository->destroyById($id);
        } catch (\Throwable $th) {
            return $this->redirectError($th->getMessage());
        }
        return $this->redirectSuccess(route('catalog.category.index'), 'Category deleted successfully');
    }

    public function change(Request $request, string $id)
    {
        try {
            $data = [];
            if ($request->field == 'status') {
                $data['status'] = $request->boolean('status'); // Use boolean to handle checkbox
            }
            $this->categoryRepository->storeOrUpdate($data, $id);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['msg' => $th->getMessage()]);
        }
        return $this->redirectSuccess(route('catalog.category.index'), 'Category changed successfully.');
    }


    public function getSubcategories($categoryId){

        $subcategory = $this->categoryRepository->nestedCategory($categoryId);

        if ($subcategory) {
            return response()->json([
                'subcategories' => $subcategory->children // Assuming 'subcategories' is a relationship
            ]);
        }

        return response()->json([
            'subcategories' => []
        ]);


    }
}
