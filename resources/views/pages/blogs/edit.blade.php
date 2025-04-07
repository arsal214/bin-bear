<x-app-layout title="Update Blogs">

    <x-breadcrumb title="Update Blogs" :back-button="route('pages.blogs.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update') }} Service Category</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('pages.blogs.update', $blog->id)">
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="old_img" value="{{ $blog->image ?? '' }}">
                            @include('pages.blogs.form')
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->


</x-app-layout>
