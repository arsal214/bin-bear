<?php

namespace App\Repositories;

use App\Interfaces\BlogRepositoryInterface;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;


class BlogRepository implements BlogRepositoryInterface
{

    /**
     * All  category list.
     */
    public function list()
    {
        return Blog::latest();
    }

    public function limitList($limit): Collection
    {
        return Blog::where('status', 'Active')->latest()->limit($limit)->get();
    }

     /**
     * Active  Blog list.
     */
    public function activeList(): Collection
    {
        return Blog::where('status', 'Active')->get();
    }

    /**
     * Popular  Blog list.
     */
    public function isPopular(): Collection
    {
        $blogs = Blog::where('is_featured','Yes')->where('status', 'Active')->get();
        return $blogs ;
    }

    /**
     * Create & save  Blog.
     */
    public function storeOrUpdate(array $data, $id = null): Blog
    {
        $blog = Blog::updateOrCreate(
            ['id' => $id],
            $data
        );
        return $blog;
    }

    /**
     * Find  Blog by id.
     */
    public function findById($id): ?Blog
    {
        return Blog::find($id);
    }


    /**
     * Delete Blog by id.
     */
    public function destroyById($id): bool
    {
        return $this->findById($id)->delete();
    }
}
