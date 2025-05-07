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
                                    General
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content flex-lg-fill">
                            <div class="tab-pane fade active show" id="general-tab" role="tabpanel">
                                @include('pages.settings.admin.include.general')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->
</x-app-layout>
