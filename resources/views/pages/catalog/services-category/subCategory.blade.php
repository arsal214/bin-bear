<x-app-layout title="Create Sub Category">

    <x-breadcrumb title="Create Sub Category" :back-button="route('catalog.category.index')" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Create Sub Category') }}</h5>
                    </div>
                    <div class="card-body">
                        <x-form :route="route('catalog.sub-category')">
                            <div class="row g-2">

                                <div class="col-md-8">

                                    <div class="row g-2">
                                        <div id="categoryInput">
                                            <x-input col="12" title="Select Parent Category" name="parent_id"
                                                type="select">

                                                {{ displayCategories($parentCategories, isset($category) ? $category->parent_id : null) }}

                                            </x-input>
                                        </div>

                                        <!-- Add sub Section -->

                                        <div id="sub-category">
                                            <div id="sub-category">
                                                <!-- Initially the first sub field is present -->
                                                <div class="sub-item" id="sub-item-0">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <x-input title="Sub Category" name="subCategory[0][name]" type="text"
                                                                placeholder="Enter Sub Cate Name" required />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-danger remove-sub"
                                                                data-sub-id="0">Remove</button>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" id="add-sub"
                                                                class="btn btn-primary">Add More</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>



                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->




    <script>

    document.addEventListener('DOMContentLoaded', function() {
        let subIndex = 1; // Start from 1 since we already have one sub in the HTML

        // Add sub button click handler
        document.getElementById('add-sub').addEventListener('click', function() {
            // Create a new sub item div
            const newSubItem = document.createElement('div');
            newSubItem.classList.add('sub-item');
            newSubItem.id = `sub-item-${subIndex}`; // Unique ID for each sub

            // Create the new sub fields (Question & Answer) and Remove button
            newSubItem.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <x-input title="Sub Category" name="subCategory[${subIndex}][name]" type="text" placeholder="Enter Sub Category Name" required />
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger remove-sub" data-sub-id="${subIndex}">Remove</button>
                </div>
            </div>
        `;

            // Append the new sub item to the container
            document.getElementById('sub-category').appendChild(newSubItem);

            // Increment the index for the next sub
            subIndex++;
        });

        // Event delegation to handle the remove sub functionality
        document.getElementById('sub-category').addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('remove-sub')) {
                // Get the sub ID from the button's data attribute
                const subId = event.target.getAttribute('data-sub-id');

                // Remove the sub item from the DOM
                const subItem = document.getElementById(`sub-item-${subId}`);
                subItem.remove();
            }
        });
    });

</script>
</x-app-layout>
