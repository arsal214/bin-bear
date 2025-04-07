<?php

namespace App\Interfaces;

interface RoleRepositoryInterface
{
    public function list();

    public function webList();

    public function apiList();

    public function storeOrUpdate(string $name, array $permissions, int $id = null, $guard = 'web');

    public function findById(int $id);
}
