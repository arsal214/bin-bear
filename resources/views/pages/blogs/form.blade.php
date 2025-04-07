<div class="row g-2">

    <div class="col-md-8">

        <div class="row g-2">

            <x-input name="title" :value="$blog->title ?? null" :required="true" />


            <x-input col="6" name="status" type="select" :required="true">
                <option value="Active" @selected(isset($blog->status) && $blog->status == 'Active')>Active</option>
                <option value="DeActive" @selected(isset($blog->status) && $blog->status == 'DeActive')>InActive</option>
            </x-input>

            <x-input col="6" name="is_featured" type="select" :required="true">
                <option value="Yes" @selected(isset($blog->is_featured) && $blog->is_featured == 'Yes')>Yes</option>
                <option value="No" @selected(isset($blog->is_featured) && $blog->is_featured == 'No')>No</option>
            </x-input>

            <x-input col="12" type="textarea" title="Content" name="body" :value="$blog->body ?? null"  class="tinymce"/>

        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <x-input name="image" type="dropify" :defaultFile="$blog->image ?? null" dropifyHeight="202" />
        </div>
    </div>

</div>
@push('scripts')
    <script src="{{ asset('backend/assets/js/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/tinymce.js') }}"></script>
@endpush



