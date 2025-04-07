<x-app-layout>

    <x-breadcrumb title="Profile"/>


    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update') }} Profile Information</h5>
                        <p class="m-0">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('profile.update')">
                            {{ method_field('PATCH') }}
                            @include('profile.partials.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>

    </div>


</x-app-layout>
