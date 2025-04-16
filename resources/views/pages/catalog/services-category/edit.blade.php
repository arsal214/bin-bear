<x-app-layout title="Update Service Category">

    <x-breadcrumb title="Update Service Category" :back-button="route('catalog.category.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update') }} Service Category</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('catalog.category.update', $category->id)">
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="old_img" value="{{ $category->image ?? '' }}">
                            @include('pages.catalog.services-category.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->


</x-app-layout>
