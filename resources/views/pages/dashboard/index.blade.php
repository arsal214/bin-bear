<x-app-layout>
    <x-breadcrumb/>
    <div class="content">

        <div class="row">

            <div class="col-sm-6 col-md-4 col-lg-3 ">
                <div class="card card-body">
                    <div class="d-flex align-items-center">
                        <i class="ph-currency-dollar ph-2x text-success me-3"></i>

                        <div class="flex-fill text-end">
                            <h4 class="mb-0">{{ $stats['suppliers'] ?? 0 }}</h4>
                            <span class="text-muted">Total Suppliers</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-body">
                    <div class="d-flex align-items-center">
                        <i class="ph-trend-down ph-2x text-danger me-3"></i>

                        <div class="flex-fill text-end">
                            <h4 class="mb-0">{{ $stats['captains'] ?? 0 }}</h4>
                            <span class="text-muted">Total Captains</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-body">
                    <div class="d-flex align-items-center">
                        <i class="ph-trend-up ph-2x text-primary me-3"></i>

                        <div class="flex-fill text-end">
                            <h4 class="mb-0">{{ $stats['users'] ?? 0 }}</h4>
                            <span class="text-muted">Total Admin </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card card-body">
                    <div class="d-flex align-items-center">
                        <i class="ph-calendar-blank ph-2x text-indigo me-3"></i>

                        <div class="flex-fill text-end">
                            <h4 class="mb-0">{{ $stats['crews'] ?? 0 }}</h4>
                            <span class="text-muted">Total Crews</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="row gy-4">

            <!-- Congratulations card -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-8 col-12">
                <div class="card">
                    <div class="card-body text-nowrap">
                        <h4 class="card-title mb-1 d-flex gap-2 flex-wrap">
                            Welcome to <strong>{{ config('app.name') }}</strong> 🎉
                        </h4>
                        <p class="pb-0">Total Subscription Revenue</p>
                        <h4 class="text-primary mb-2 mt-4">${{ $stats['total_sub_rev'] ?? 0 }}</h4>

                        <a href="#"
                           class="btn btn-sm btn-primary waves-effect waves-light">View Subscriptions</a>
                    </div>
                    <img src="#"
                         class="position-absolute bottom-0 end-0 me-3" height="140" alt="view sales">
                </div>
            </div>
            <!--/ Congratulations card -->

            <!-- Total Profit -->
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="avatar-initial bg-primary bg-opacity-20 text-primary rounded p-2">
                            <i class="ph-shopping-cart-simple ph-1x"></i>
                        </div>
                    </div>
                    <div class="card-info mt-4 pt-1">
                        <h5 class="mb-1">${{ $stats['month_sub_rev'] ?? 0 }}</h5>
                        <p class="text-muted">Total Revenue</p>
                        <div class="badge bg-secondary bg-opacity-20 text-secondary rounded-pill">Current Month
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Profit -->

            <!-- Total Expenses -->
            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div class="badge bg-success bg-opacity-20 text-success rounded p-2">
                            <i class="ph-currency-dollar-simple ph-1x"></i>
                        </div>
                    </div>
                    <div class="card-info mt-4 pt-1">
                        <h5 class="mb-1">${{ $stats['last_month_sub_rev'] ?? 0 }}</h5>
                        <p class="text-muted">Total Revenue</p>
                        <div class="badge bg-secondary bg-opacity-20 text-secondary rounded-pill">Last Month</div>
                    </div>
                </div>
            </div>
            <!--/ Total Expenses -->

            <!-- Total Profit chart -->
            <div class="col-lg-4 col-sm-6 order-sm-2 order-lg-0">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between mb-1">
                            <h4 class="mb-0">${{ $stats['year_sub_rev'] ?? 0 }}</h4>
                            {{-- <div class="d-flex text-success">
                                <p class="mb-0 me-2">+78.2%</p>
                                <i class="mdi mdi-chevron-up"></i>
                            </div> --}}
                        </div>
                        <small class="text-muted">Total Revenue of Year</small>
                    </div>
                    <div class="card-body">
                        <div id="liveVisitors"></div>
                    </div>
                </div>
            </div>
            <!--/ Total Profit chart -->
        </div>

        <div class="row">
            <!-- Meeting Schedule -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 me-2">Meeting Schedule</h5>
                    </div>
                    <div class="card-body pt-2">
                        <ul class="p-0 m-0">
                            {{-- @dd($meetings) --}}
                            @if (!empty($meetings))

                                @foreach ($meetings as $meeting)
                                    <li class="d-flex mb-4 pb-1">
                                        <div
                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <h6 class="mb-0 fw-semibold">{{ $meeting['invitee']['name'] }} <a
                                                        href="{{ $meeting['location']['join_url'] ?? '#' }}"
                                                        class="ph-link"></a></h6>
                                                <small class="text-muted">
                                                    <i class="ph-calendar mdi-14px"></i>
                                                    <span>{{ date('d M', strtotime($meeting['start_time'])) }} |
                                                        {{ date('H:i', strtotime($meeting['start_time'])) }} -
                                                        {{ date('H:i', strtotime($meeting['end_time'])) }}</span>
                                                </small>
                                            </div>
                                            <div class="badge bg-primary bg-opacity-20 text-primary rounded-pill">
                                                {{ $meeting['invitee']['email'] ?? '' }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <p class="text-muted text-center mt-3">No Meeting Found</p>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Meeting Schedule -->
        </div>

    </div>


    @push('scripts')
        <script src="{{ asset('backend/assets/demo/pages/dashboard.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/streamgraph.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/sparklines.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/lines.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/areas.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/donuts.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/bars.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/progress.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/heatmaps.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/pies.js') }}"></script>
        <script src="{{ asset('backend/assets/demo/charts/pages/dashboard/bullets.js') }}"></script>
    @endpush
</x-app-layout>
