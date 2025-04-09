<!-- Reporte de distribución de activos -->
<div class="row">
    <!-- Distribución por Centro -->
    <div class="col-md-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Assets Distribution by Center') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="centerDistributionChart"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Center') }}</th>
                                <th>{{ __('Assets Count') }}</th>
                                <th>{{ __('Percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCenterAssets = array_sum($reportData['distribution']['assetsByCenter'] ?? []);
                            @endphp
                            @forelse($reportData['distribution']['assetsByCenter'] ?? [] as $centerName => $count)
                                <tr>
                                    <td>{{ $centerName }}</td>
                                    <td>{{ $count }}</td>
                                    <td>
                                        @if($totalCenterAssets > 0)
                                            {{ round(($count / $totalCenterAssets) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ __('No data available') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distribución por Departamento -->
    <div class="col-md-6 mb-4">
        <div class="card report-card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Assets Distribution by Department') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="departmentDistributionChart"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Assets Count') }}</th>
                                <th>{{ __('Percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalDeptAssets = array_sum($reportData['distribution']['assetsByDepartment'] ?? []);
                            @endphp
                            @forelse($reportData['distribution']['assetsByDepartment'] ?? [] as $deptName => $count)
                                <tr>
                                    <td>{{ $deptName }}</td>
                                    <td>{{ $count }}</td>
                                    <td>
                                        @if($totalDeptAssets > 0)
                                            {{ round(($count / $totalDeptAssets) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ __('No data available') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        function createDistributionCharts() {
            // Centers Chart
            const centerLabels = @json($chartData['distribution']['centers']['labels'] ?? []);
            const centerData = @json($chartData['distribution']['centers']['data'] ?? []);
            
            if (centerLabels.length > 0 && document.getElementById('centerDistributionChart')) {
                createChart('centerDistributionChart', '{{ $chartType }}', centerLabels, [{
                    label: '{{ __("Assets by Center") }}',
                    data: centerData,
                    backgroundColor: [
                        'rgba(105, 108, 255, 0.7)',
                        'rgba(3, 195, 236, 0.7)',
                        'rgba(255, 171, 0, 0.7)',
                        'rgba(40, 208, 148, 0.7)'
                    ],
                    borderColor: [
                        'rgba(105, 108, 255, 1)',
                        'rgba(3, 195, 236, 1)',
                        'rgba(255, 171, 0, 1)',
                        'rgba(40, 208, 148, 1)'
                    ],
                    borderWidth: 1
                }]);
            }
            
            // Departments Chart
            const deptLabels = @json($chartData['distribution']['departments']['labels'] ?? []);
            const deptData = @json($chartData['distribution']['departments']['data'] ?? []);
            
            if (deptLabels.length > 0 && document.getElementById('departmentDistributionChart')) {
                createChart('departmentDistributionChart', '{{ $chartType }}', deptLabels, [{
                    label: '{{ __("Assets by Department") }}',
                    data: deptData,
                    backgroundColor: [
                        'rgba(255, 171, 0, 0.7)',
                        'rgba(40, 208, 148, 0.7)',
                        'rgba(3, 195, 236, 0.7)',
                        'rgba(105, 108, 255, 0.7)',
                        'rgba(113, 221, 55, 0.7)',
                        'rgba(253, 126, 20, 0.7)',
                        'rgba(28, 132, 198, 0.7)',
                        'rgba(102, 16, 242, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 171, 0, 1)',
                        'rgba(40, 208, 148, 1)',
                        'rgba(3, 195, 236, 1)',
                        'rgba(105, 108, 255, 1)',
                        'rgba(113, 221, 55, 1)',
                        'rgba(253, 126, 20, 1)',
                        'rgba(28, 132, 198, 1)',
                        'rgba(102, 16, 242, 1)'
                    ],
                    borderWidth: 1
                }]);
            }
        }
        
        @this.on('initCharts', () => {
            createDistributionCharts();
        });
        
        if (document.getElementById('centerDistributionChart') && 
            document.getElementById('departmentDistributionChart')) {
            createDistributionCharts();
        }
    });
</script> 