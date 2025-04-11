<x-app-layout title="Create Zip Code">

    <x-breadcrumb title="Create Zip Code" :back-button="route('zip-codes.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Create Zip Code') }}</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('zip-codes.store')">
                            @include('pages.zipCode.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>
