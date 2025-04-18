<div class="row g-2">

    <div class="col-md-8">

        <div class="row g-2">

            <x-input col="6" name="name" :value="$category->name ?? null" :required="true" />
                <x-input col="6" name="price" :value="$category->price ?? null" :required="true" />

            <div>
                <input type="checkbox" id="showInputCheckbox" onchange="toggleInput()"
                    {{ isset($category) && $category->parent_id ? 'checked' : '' }} />
                <label for="showInputCheckbox">Select Parent Category</label>
            </div>

            <div id="categoryInput" style="display: {{ isset($category) && $category->parent_id ? 'block' : 'none' }};">
                <x-input col="6" title="Category" name="parent_id" type="select">

                    {{ displayCategories($parentCategories, isset($category) ? $category->parent_id : null) }}

                </x-input>
            </div>

            <x-input col="6" name="status" type="select" :required="true">
                <option value="1" @selected(isset($category->status) && $category->status == 1)>Active</option>
                <option value="0" @selected(isset($category->status) && $category->status == 0)>InActive</option>
            </x-input>

            <x-input col="6" name="is_popular" type="select" :required="true">
                <option value="Yes" @selected(isset($category->is_popular) && $category->is_popular == 'Yes')>Yes</option>
                <option value="No" @selected(isset($category->is_popular) && $category->is_popular == 'No')>No</option>
            </x-input>

        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <x-input name="image" type="dropify" :defaultFile="$category->image ?? null" dropifyHeight="202"  />
        </div>
    </div>

</div>

@push('scripts')
    <script>
        function toggleInput() {
            var checkbox = document.getElementById('showInputCheckbox');
            var inputDiv = document.getElementById('categoryInput');

            // Toggle visibility based on checkbox state
            if (checkbox.checked) {
                inputDiv.style.display = 'block';
            } else {
                inputDiv.style.display = 'none';
            }
        }

    </script>
@endpush


