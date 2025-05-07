<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\UploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    use UploadTrait;

    public function __construct() {
        // $this->middleware('permission:adminSettings-list', ['only' => ['index', 'show']]);
        // $this->middleware('permission:adminSettings-create', ['only' => ['store']]);
        // $this->middleware('permission:adminSettings-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:adminSettings-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function admin(): View
    {
        return view('pages.settings.admin.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'type' => 'required',
            'values' => 'required|array',
        ]);

        $data = [];

        foreach ($validatedData['values'] as $key => $value) {
            if ($request->file('values.' . $key)) {

             $value = $this->uploadFile($request->file('values.' . $key), 'settings');
            }
            $data[] = [
                'name' => $key,
                'value' => $value,
            ];
        }

        Setting::set($data);

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    public function deleteSettingsFile($key)
    {
        $value = Setting::get($key);
        // Parse the URL to extract the file name
        $fileName = pathinfo(parse_url($value, PHP_URL_PATH), PATHINFO_BASENAME);

        $this->deleteFile($value);

        $localFilePath = public_path('upload/json/' . $fileName);
        if (File::exists($localFilePath)) {
            File::delete($localFilePath);
        }
    }
}
