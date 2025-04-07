<x-app-layout title="Create Coupons">

    <x-breadcrumb title="Create Coupon" :back-button="route('coupons.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Create Coupon') }}</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('coupons.store')">
                            @include('pages.coupon.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>
