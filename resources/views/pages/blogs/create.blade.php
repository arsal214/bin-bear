<x-app-layout title="Create Blogs">

    <x-breadcrumb title="Create Blogs" :back-button="route('pages.blogs.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Create Blogs') }}</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('pages.blogs.store')">
                            @include('pages.blogs.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>

