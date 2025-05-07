<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Traits\UploadTrait;
use App\Models\BookingDetail;
use App\Models\Setting;

class BookingController extends BaseController
{
    use UploadTrait;

    public function store(Request $request)
    {
        
        $booking = Booking::create($request->all());

        if (isset($request->details)) {
            foreach ($request->details as $detail) {
                $bookingDetails = new BookingDetail();
                $bookingDetails->booking_id = $booking->id;
                $bookingDetails->category_id = $detail['category_id'];
                $bookingDetails->subcategory_id = $detail['subcategory_id'];
                $data['image'] = $request->hasFile('image') ? $this->uploadFile($request->file('image'), 'categories') : 'https://png.pngtree.com/element_our/20200610/ourmid/pngtree-character-default-avatar-image_2237203.jpg';
                $bookingDetails->subcategory_id = $data['image'];
                $bookingDetails->save();
            }
        }

         return $this->sendResponse($booking->load('details'), 'Data Get SuccessFully', 200);
    }


    public function getPrice(){
        try {
            $setting = Setting::all();
        } catch (\Throwable $th) {
            return $this->sendException([$th->getMessage()]);
        }
        return $this->sendResponse($setting, 'Data Get SuccessFully', 200);
    }
}
