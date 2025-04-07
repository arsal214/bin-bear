<x-app-layout title="Update Coupon">

    <x-breadcrumb title="Update Coupon" :back-button="route('coupons.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update') }} Coupon</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('coupons.update', $coupon->id)">
                            {{ method_field('PATCH') }}
                            @include('pages.coupon.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->


</x-app-layout>
