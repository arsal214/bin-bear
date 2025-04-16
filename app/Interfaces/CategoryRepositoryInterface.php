<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function list();
    public function allList();

    public function activeList();

    public function parentCategory();

    public function activeCategory();

    public function isPopular();

    public function storeOrUpdate(array $data, $id = null);

    public function findById($id);

    public function nestedCategory($id);

    public function nestedCategories();

    public function destroyById($id);

    public function subCategory($id);

    public function selectParentCategory($search);
}
