<x-app-layout title="Category">

    <x-breadcrumb title="Category">
        {{-- @can('category-create') --}}
            <a href="{{ route('catalog.category.create') }}"
               class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill">
            <span class="btn-labeled-icon bg-primary text-white rounded-pill">
                <i class="ph-plus"></i>
            </span>
                Create New
            </a>
        {{-- @endcan --}}
    </x-breadcrumb>

    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Parent Category</h5>
                    </div>

                    <table class="table table-striped">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            <x-category-tree :categories="$categories" :level="1" />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

    <div id="result"></div>

</x-app-layout>
