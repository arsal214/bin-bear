@props(['id', 'type', 'labels', 'datasets', 'options', 'dataHeight' => 500, 'col' => 12])

<div class="col-md-{{ $col }}">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 text-capitalize">{{ str_replace('_', ' ', $id)}}</h5>
        </div>
        <div class="card-body">
            <canvas id="{{ $id }}" class="chartjs" data-height="{{ $dataHeight }}"></canvas>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = {
                labels: {!! json_encode($labels) !!},
                datasets: {!! json_encode($datasets) !!}
            };

            const chartOptions = {!! json_encode($options) !!};

            const ctx = document.getElementById('{{ $id }}').getContext('2d');
            new Chart(ctx, {
                type: '{{ $type }}',
                data: chartData,
                options: chartOptions
            });
        });
    </script>
@endpush
