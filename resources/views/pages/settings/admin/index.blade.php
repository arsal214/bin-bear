<x-app-layout title="Admin Settings">

    <x-breadcrumb title="Admin Settings" />


    <!-- Content area -->
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body">

                    <div class="d-lg-flex">
                        <ul class="nav nav-tabs nav-tabs-vertical nav-tabs-vertical-start wmin-lg-200 me-lg-3 mb-3 mb-lg-0"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <a href="#general-tab" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Truck Price
                                </a>
                            </li>


                            <li class="nav-item" role="presentation">
                                <a href="#appartment-tab" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Apartment Trash 
                                </a>
                            </li>

                             <li class="nav-item" role="presentation">
                                <a href="#drumpster-tab" class="nav-link " data-bs-toggle="tab" aria-selected="true"
                                    role="tab">
                                    Drumpster Size 
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content flex-lg-fill">
                            <div class="tab-pane fade active show" id="general-tab" role="tabpanel">
                                @include('pages.settings.admin.include.general')
                            </div>
                             <div class="tab-pane" id="appartment-tab" role="tabpanel">
                                @include('pages.settings.admin.include.appartment')
                            </div>

                             <div class="tab-pane" id="drumpster-tab" role="tabpanel">
                                @include('pages.settings.admin.include.drumpster')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>
