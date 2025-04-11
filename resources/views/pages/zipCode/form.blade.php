<div class="row g-2">

    <div class="col-md-12">

        <div class="row g-2">


            <x-input col="12" name="city_name" :value="$coupon->city_name ?? null" :required="true" />

            <x-input col="12" name="zip_code" :value="$coupon->zip_code ?? null" :required="true" />


        </div>
        <div class="row g-2">
            <x-input col="6" name="status" type="select" :required="true">
                <option value="Active" @selected(isset($coupon->status) && $coupon->status == 'Active')>Active</option>
                <option value="DeActive" @selected(isset($coupon->status) && $coupon->status == 'DeActive')>DeActive</option>

            </x-input>




        </div>
    </div>

</div>
