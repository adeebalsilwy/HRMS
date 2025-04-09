<!-- Reporte de activos por empleado -->
<div class="row">
    <!-- Gráfico de activos por empleado -->
    <div class="col-md-12 mb-4">
        <div class="card report-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ __('Assets by Employee') }}</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="countBtn" onclick="toggleChartData('count')">
                        {{ __('Count') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="valueBtn" onclick="toggleChartData('value')">
                        {{ __('Value') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="employeeAssetsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla detallada de activos por empleado -->
    <div class="col-md-12">
        <div class="card report-card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ __('Employee Assets Details') }}</h5>
            </div>
            <div class="card-datatable table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Assets Count') }}</th>
                            <th>{{ __('Total Value') }}</th>
                            <th>{{ __('Percentage of Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalCount = 0;
                            $totalValue = 0;
                            foreach ($reportData['employee']['employeeAssets'] ?? [] as $item) {
                                $totalCount += $item->asset_count;
                                $totalValue += $item->total_value ?? 0;
                            }
                        @endphp
                        @forelse($reportData['employee']['employeeAssets'] ?? [] as $item)
                            <tr>
                                <td>{{ $item->first_name }} {{ $item->father_name }} {{ $item->last_name }}</td>
                                <td>{{ $item->asset_count }}</td>
                                <td>{{ number_format($item->total_value ?? 0, 2) }}</td>
                                <td>
                                    @if($totalCount > 0)
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                    style="width: {{ round(($item->asset_count / $totalCount) * 100) }}%" 
                                                    aria-valuenow="{{ round(($item->asset_count / $totalCount) * 100) }}" 
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small>{{ round(($item->asset_count / $totalCount) * 100, 1) }}%</small>
                                        </div>
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('No data available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <th>{{ __('Total') }}</th>
                            <th>{{ $totalCount }}</th>
                            <th>{{ number_format($totalValue, 2) }}</th>
                            <th>100%</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDataType = 'count';
    let employeeChart = null;
    
    document.addEventListener('livewire:initialized', () => {
        function createEmployeeCharts() {
            const labels = @json($chartData['employee']['labels'] ?? []);
            const countData = @json($chartData['employee']['counts'] ?? []);
            const valueData = @json($chartData['employee']['values'] ?? []);
            
            if (labels.length > 0 && document.getElementById('employeeAssetsChart')) {
                employeeChart = createChart('employeeAssetsChart', '{{ $chartType }}', labels, [{
                    label: '{{ __("Assets Count") }}',
                    data: countData,
                    backgroundColor: [
                        'rgba(105, 108, 255, 0.7)',
                        'rgba(3, 195, 236, 0.7)',
                        'rgba(255, 171, 0, 0.7)',
                        'rgba(40, 208, 148, 0.7)',
                        'rgba(113, 221, 55, 0.7)'
                    ],
                    borderColor: [
                        'rgba(105, 108, 255, 1)',
                        'rgba(3, 195, 236, 1)',
                        'rgba(255, 171, 0, 1)',
                        'rgba(40, 208, 148, 1)',
                        'rgba(113, 221, 55, 1)'
                    ],
                    borderWidth: 1
                }]);
            }
        }
        
        @this.on('initCharts', () => {
            createEmployeeCharts();
        });
        
        if (document.getElementById('employeeAssetsChart')) {
            createEmployeeCharts();
        }
    });
    
    function toggleChartData(dataType) {
        if (currentDataType === dataType || !employeeChart) return;
        
        currentDataType = dataType;
        
        // Actualizar estado de los botones
        document.getElementById('countBtn').classList.toggle('active');
        document.getElementById('valueBtn').classList.toggle('active');
        
        const labels = @json($chartData['employee']['labels'] ?? []);
        let newData, newLabel;
        
        if (dataType === 'count') {
            newData = @json($chartData['employee']['counts'] ?? []);
            newLabel = '{{ __("Assets Count") }}';
        } else {
            newData = @json($chartData['employee']['values'] ?? []);
            newLabel = '{{ __("Assets Value") }}';
        }
        
        employeeChart.data.datasets[0].data = newData;
        employeeChart.data.datasets[0].label = newLabel;
        employeeChart.update();
    }
</script> 