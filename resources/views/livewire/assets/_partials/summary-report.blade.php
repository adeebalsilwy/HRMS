<!-- Reporte de resumen de activos -->
<div class="row">
    <!-- Tarjetas de resumen -->
    <div class="col-md-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">{{ __('Total Assets') }}</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">{{ $reportData['summary']['totalAssets'] ?? 0 }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-device-laptop text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">{{ __('In Service Assets') }}</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">{{ $reportData['summary']['inServiceAssets'] ?? 0 }}</h4>
                            @if(isset($reportData['summary']['totalAssets']) && $reportData['summary']['totalAssets'] > 0)
                                <small class="text-success fw-medium">
                                    ({{ round(($reportData['summary']['inServiceAssets'] / $reportData['summary']['totalAssets']) * 100) }}%)
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti ti-check text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card report-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="fw-medium d-block mb-1">{{ __('Out of Service Assets') }}</span>
                        <div class="d-flex align-items-center mt-1">
                            <h4 class="mb-0 me-2">{{ $reportData['summary']['outOfServiceAssets'] ?? 0 }}</h4>
                            @if(isset($reportData['summary']['totalAssets']) && $reportData['summary']['totalAssets'] > 0)
                                <small class="text-danger fw-medium">
                                    ({{ round(($reportData['summary']['outOfServiceAssets'] / $reportData['summary']['totalAssets']) * 100) }}%)
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="ti ti-x text-danger"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="col-md-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Assets by Status') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Assets by Class') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12 mb-4">
        <div class="card report-card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Assets by Category') }}</h5>
            </div>
            <div class="card-body">
                @if(!empty($chartData['summary']['category']['labels'] ?? []))
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">{{ __('No category data available') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Últimos activos añadidos -->
    <div class="col-md-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Latest Added Assets') }}</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($reportData['summary']['latestAssets'] ?? [] as $asset)
                        <li class="d-flex mb-3 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded 
                                    @if($asset->class == 'Electronic') bg-label-info
                                    @elseif($asset->class == 'Furniture') bg-label-warning
                                    @elseif($asset->class == 'Gear') bg-label-secondary
                                    @endif">
                                    <i class="ti
                                        @if($asset->class == 'Electronic') ti-device-laptop
                                        @elseif($asset->class == 'Furniture') ti-chair
                                        @elseif($asset->class == 'Gear') ti-tools
                                        @endif"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $asset->serial_number ?? 'N/A' }}</h6>
                                    <small class="text-muted d-block">{{ $asset->description }}</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-medium">{{ $asset->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-3">{{ __('No assets found') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Activos más valiosos -->
    <div class="col-md-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Most Valuable Assets') }}</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($reportData['summary']['mostValuableAssets'] ?? [] as $asset)
                        <li class="d-flex mb-3 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-currency-dollar"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $asset->serial_number ?? 'N/A' }}</h6>
                                    <small class="text-muted d-block">{{ $asset->description }}</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-medium">{{ number_format($asset->real_price, 2) }}</small>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-3">{{ __('No assets found') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        function createSummaryCharts() {
            // Status Chart
            const statusLabels = @json($chartData['summary']['status']['labels'] ?? []);
            const statusData = @json($chartData['summary']['status']['data'] ?? []);
            
            if (statusLabels.length > 0 && document.getElementById('statusChart')) {
                createChart('statusChart', '{{ $chartType }}', statusLabels, [{
                    label: '{{ __("Assets by Status") }}',
                    data: statusData,
                    backgroundColor: ['rgba(40, 208, 148, 0.7)', 'rgba(3, 195, 236, 0.7)', 
                                      'rgba(105, 108, 255, 0.7)', 'rgba(255, 171, 0, 0.7)'],
                    borderColor: ['rgba(40, 208, 148, 1)', 'rgba(3, 195, 236, 1)', 
                                  'rgba(105, 108, 255, 1)', 'rgba(255, 171, 0, 1)'],
                    borderWidth: 1
                }]);
            }
            
            // Class Chart
            const classLabels = @json($chartData['summary']['class']['labels'] ?? []);
            const classData = @json($chartData['summary']['class']['data'] ?? []);
            
            if (classLabels.length > 0 && document.getElementById('classChart')) {
                createChart('classChart', '{{ $chartType }}', classLabels, [{
                    label: '{{ __("Assets by Class") }}',
                    data: classData,
                    backgroundColor: ['rgba(105, 108, 255, 0.7)', 'rgba(3, 195, 236, 0.7)', 'rgba(255, 171, 0, 0.7)'],
                    borderColor: ['rgba(105, 108, 255, 1)', 'rgba(3, 195, 236, 1)', 'rgba(255, 171, 0, 1)'],
                    borderWidth: 1
                }]);
            }
            
            // Category Chart
            const categoryLabels = @json($chartData['summary']['category']['labels'] ?? []);
            const categoryData = @json($chartData['summary']['category']['data'] ?? []);
            
            if (categoryLabels.length > 0 && document.getElementById('categoryChart')) {
                createChart('categoryChart', '{{ $chartType }}', categoryLabels, [{
                    label: '{{ __("Assets by Category") }}',
                    data: categoryData,
                    backgroundColor: [
                        'rgba(105, 108, 255, 0.7)',
                        'rgba(3, 195, 236, 0.7)',
                        'rgba(255, 171, 0, 0.7)',
                        'rgba(40, 208, 148, 0.7)',
                        'rgba(113, 221, 55, 0.7)',
                        'rgba(253, 126, 20, 0.7)'
                    ],
                    borderColor: [
                        'rgba(105, 108, 255, 1)',
                        'rgba(3, 195, 236, 1)',
                        'rgba(255, 171, 0, 1)',
                        'rgba(40, 208, 148, 1)',
                        'rgba(113, 221, 55, 1)',
                        'rgba(253, 126, 20, 1)'
                    ],
                    borderWidth: 1
                }]);
            }
        }
        
        @this.on('initCharts', () => {
            createSummaryCharts();
        });
        
        if (document.getElementById('statusChart') && 
            document.getElementById('classChart') && 
            document.getElementById('categoryChart')) {
            createSummaryCharts();
        }
    });
</script> 