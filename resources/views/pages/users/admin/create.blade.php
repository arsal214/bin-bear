<x-app-layout title="Create Staff">

    <x-breadcrumb title="Create Staff" :back-button="route('users.staff.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Add Staff') }}</h5>
                    </div>
                    <div class="card-body">

                        <x-form :route="route('users.staff.store')">
                            @include('pages.users.admin.form')
                        </x-form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>

