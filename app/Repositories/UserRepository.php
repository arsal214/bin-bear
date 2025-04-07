<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class UserRepository implements UserRepositoryInterface
{

    /**
     * User List.
     */
    public function list(): Collection
    {
        return User::where('is_admin',0)->latest()->get();
    }

    /**
     * User List.
     */
    public function adminList(): Collection
    {
        return User::where('is_admin',1)->whereNot('email', 'superadmin@gmail.com')->latest()->get();
    }

    /**
     * Create or update user.
     */
    public function storeOrUpdate(array $data, array $roles, $id = null): User
    {
        $user = User::updateOrCreate(
            ['id' => $id],
            $data
        );
        if (count($roles) > 0) {
            $user->syncRoles($roles);
        }
        return $user;
    }

    /**
     * Create or update user.
     */
    public function updateApiUser(array $data, $id = null): User
    {
        $user = User::updateOrCreate(
            ['id' => $id],
            $data
        );
        return $user;
    }

    /**
     * Find user by id.
     */
    public function findById($id): User
    {
        return User::find($id);
    }

    /**
     * Delete user by id.
     */
    public function destroyById($id): bool
    {
        return $this->findById($id)->delete();
    }
}
