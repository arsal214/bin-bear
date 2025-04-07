<?php

namespace App\Interfaces;

interface BlogRepositoryInterface
{
    public function list();

    public function activeList();

    public function limitList($limit);

    public function isPopular();

    public function storeOrUpdate(array $data, $id = null);

    public function findById($id);

    public function destroyById($id);

}
