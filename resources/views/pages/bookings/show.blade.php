<x-app-layout title="Booking">

    <x-breadcrumb :title="'Bookings Detail (' . $booking->service_name . ')'" :backButton="route('bookings.index')">
    </x-breadcrumb>

    <!-- Content area -->
    <div class="content">
        <div class="row g-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Booking Info.</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-7 col-12">
                                    <div class="row mb-0">
                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Service Name:
                                        </div>
                                        <div class="col-sm-8">{{ $booking->service_name ?? 'N/A' }}</div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Service Option:
                                        </div>
                                        <div class="col-sm-8">{{ $booking?->service_option ?? 'N/A' }}</div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Name:
                                        </div>
                                        <div class="col-sm-8">{{ $booking->name }}</div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Date:
                                        </div>
                                        <div class="col-sm-8">{{ $booking->date }}</div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Time:
                                        </div>
                                        <div class="col-sm-8">{{ $booking->time }}</div>
                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">zip code:
                                        </div>
                                        <div class="col-sm-8">{!! $booking->zip_code ?? 'N/A' !!} </div>
                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">detail:
                                        </div>
                                        <div class="col-sm-8">{!! $booking->detail ?? 'N/A' !!} </div>
                                    </div>
                                </div>
                                <div class="col-xl-5 col-12">
                                    <div class="row mb-0">
                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Address:</div>
                                        <div class="col-sm-8">{{ $booking->address ?? 'N/A' }}</div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Email:
                                        </div>
                                        <div class="col-sm-8">{{ $booking->email ?? 'N/A' }}</div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">Phone number:
                                        </div>
                                        <div class="col-sm-8">{!! $booking->phone_number ?? 'N/A' !!} </div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">city:
                                        </div>
                                        <div class="col-sm-8">{!! $booking->city ?? 'N/A' !!} </div>

                                        <div class="col-sm-4 mb-3 text-nowrap fw-semibold text-heading">state:
                                        </div>
                                        <div class="col-sm-8">{!! $booking->state ?? 'N/A' !!} </div>
                                    </div>
                                </div>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->

</x-app-layout>
