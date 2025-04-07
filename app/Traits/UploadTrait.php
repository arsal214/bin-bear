<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadFile($file, $path)
    {

        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('images/'.$path), $filename);
        return $filename;
    }

    public function uploadDocuments($file, $path)
    {

        $filename = date('YmdHi') . $file->getClientOriginalName();
        $file->move(public_path('documents/'.$path), $filename);
        return $filename;
    }

    public function deleteFile($url)
    {
        if ($url) {
            return Storage::disk('s3')->delete(parse_url($url)['path']);
        }
        return false;
    }
}
