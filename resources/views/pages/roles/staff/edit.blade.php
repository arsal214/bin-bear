<x-app-layout title="Update Staff Role">

    <x-breadcrumb title="Update Role" :back-button="route('roles.staff.index')"/>


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update') }} Role</h5>
                    </div>
                    <div class="card-body">
                            <x-form :route="route('roles.staff.update', $role->id)" :button="true">
                            {{ method_field('PATCH') }}
                            @csrf
                            @include('pages.roles.staff.form')
                            </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->


</x-app-layout>
