<?php

namespace App\Repositories;

use App\Interfaces\ZipCodeRepositoryInterface;
use App\Models\ZipCode;
use Illuminate\Database\Eloquent\Collection;


class ZipCodeRepository implements ZipCodeRepositoryInterface
{
    /**
     * All ZipCode list.
     */
    public function list(): Collection
    {
        return ZipCode::latest()->get();
    }

    /**
     * Active ZipCode list.
     */
    public function activeList(): Collection
    {
        return ZipCode::where('status','Active')->latest()->get();
    }

    /**
     * Create or update ZipCode.
     */
    public function storeOrUpdate(array $data, $id = null): ZipCode
    {
        $zipCode = ZipCode::updateOrCreate(
            ['id' => $id],
            $data
        );
        return $zipCode;
    }

    /**
     * Find ZipCode by id.
     */
    public function findById($id): ZipCode
    {
        return ZipCode::find($id);
    }

    /**
     * Delete ZipCode by id.
     */
    public function destroyById($id): bool
    {
        return $this->findById($id)->delete();
    }
}
