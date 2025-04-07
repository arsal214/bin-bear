<div class="row g-2">

    <div class="col-md-12">

        <div class="row g-2">


            <x-input col="12" name="name" :value="$coupon->name ?? null" :required="true" />


            <x-input col="6" name="discount_type" type="select" :required="true">
                <option value="Fixed" @selected(isset($coupon->discount_type) && $coupon->discount_type == 'Fixed')>Fixed</option>
                <option value="Percentage" @selected(isset($coupon->discount_type) && $coupon->discount_type == 'Percentage')>Percentage</option>
            </x-input>
            <x-input col="6" name="discount_value" type="number" :value="$coupon->discount_value ?? null" :required="true" />

            <x-input col="6" name="valid_from" type="date"
                     :value="$coupon->valid_from ?? null"
                     :min="now()->toDateString()"
                     :required="true" />
            <x-input col="6" name="valid_till" type="date" :value="$coupon->valid_till ?? null"  :min="now()->toDateString()"  />


        </div>
        <div class="row g-2">

            <x-input col="6" name="maximum_usage" type="number" :value="$coupon->maximum_usage ?? null" :required="true" />
            <x-input col="6" name="status" type="select" :required="true">
                <option value="Active" @selected(isset($coupon->status) && $coupon->status == 'Active')>Active</option>
                <option value="DeActive" @selected(isset($coupon->status) && $coupon->status == 'DeActive')>DeActive</option>

            </x-input>




        </div>
    </div>

</div>
