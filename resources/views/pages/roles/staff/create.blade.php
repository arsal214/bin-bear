<x-app-layout title="Create Staff Role">

    <x-breadcrumb title="Create Role" :back-button="route('roles.staff.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Create Role') }}</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('roles.staff.store')" :button="true">
                            @include('pages.roles.staff.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>
