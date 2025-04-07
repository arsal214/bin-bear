<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function list();

    public function storeOrUpdate(array $data, array $roles, $id = null);

    public function updateApiUser(array $data, $id = null);

    public function findById($id);

    public function destroyById($id);
}
