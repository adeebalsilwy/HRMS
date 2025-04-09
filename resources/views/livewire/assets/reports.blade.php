@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid #696cff;
    }
    .report-card {
        transition: all 0.3s ease;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush

<div>
    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">{{ __('Assets') }} /</span> {{ __('Reports') }}
        </h4>
        <div>
            <button class="btn btn-primary btn-sm me-1" wire:click="exportPdf">
                <i class="ti ti-file-type-pdf me-1"></i>{{ __('Export PDF') }}
            </button>
            <button class="btn btn-success btn-sm" wire:click="exportExcel">
                <i class="ti ti-table-export me-1"></i>{{ __('Export Excel') }}
            </button>
        </div>
    </div>

    <!-- Filtros del reporte -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">{{ __('Report Filters') }}</h5>
            <div class="row g-3">
                <div class="col-md-3 mb-3">
                    <label for="reportType" class="form-label">{{ __('Report Type') }}</label>
                    <select id="reportType" class="form-select" wire:model.live="reportType">
                        <option value="summary">{{ __('Summary Report') }}</option>
                        <option value="detailed">{{ __('Detailed Assets Report') }}</option>
                        <option value="distribution">{{ __('Distribution Report') }}</option>
                        <option value="employee">{{ __('Employee Assets Report') }}</option>
                    </select>
                </div>

                @if($reportType == 'detailed')
                    <div class="col-md-3 mb-3">
                        <label for="categoryId" class="form-label">{{ __('Category') }}</label>
                        <select id="categoryId" class="form-select" wire:model.live="categoryId">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="assetStatus" class="form-label">{{ __('Asset Status') }}</label>
                        <select id="assetStatus" class="form-select" wire:model.live="assetStatus">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="Good">{{ __('Good') }}</option>
                            <option value="Fine">{{ __('Fine') }}</option>
                            <option value="Bad">{{ __('Bad') }}</option>
                            <option value="Damaged">{{ __('Damaged') }}</option>
                        </select>
                    </div>
                @endif

                @if($reportType == 'distribution' || $reportType == 'employee')
                    <div class="col-md-3 mb-3">
                        <label for="centerId" class="form-label">{{ __('Center') }}</label>
                        <select id="centerId" class="form-select" wire:model.live="centerId">
                            <option value="">{{ __('All Centers') }}</option>
                            @foreach($centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="departmentId" class="form-label">{{ __('Department') }}</label>
                        <select id="departmentId" class="form-select" wire:model.live="departmentId">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($reportType == 'employee')
                    <div class="col-md-3 mb-3">
                        <label for="employeeId" class="form-label">{{ __('Employee') }}</label>
                        <select id="employeeId" class="form-select" wire:model.live="employeeId">
                            <option value="">{{ __('All Employees') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-3 mb-3">
                    <label for="chartType" class="form-label">{{ __('Chart Type') }}</label>
                    <select id="chartType" class="form-select" wire:model.live="chartType">
                        <option value="bar">{{ __('Bar Chart') }}</option>
                        <option value="line">{{ __('Line Chart') }}</option>
                        <option value="pie">{{ __('Pie Chart') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido del reporte -->
    <div>
        @if($reportType == 'summary')
            @include('livewire.assets._partials.summary-report')
        @elseif($reportType == 'detailed')
            @include('livewire.assets._partials.detailed-report')
        @elseif($reportType == 'distribution')
            @include('livewire.assets._partials.distribution-report')
        @elseif($reportType == 'employee')
            @include('livewire.assets._partials.employee-report')
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        function createChart(canvasId, type, labels, datasets) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;
            
            // Destruir el gráfico existente si existe
            if (window[canvasId + 'Chart']) {
                window[canvasId + 'Chart'].destroy();
            }
            
            window[canvasId + 'Chart'] = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }
        
        // Escuchar eventos para actualizar gráficos
        @this.on('updateCharts', (data) => {
            if (data.charts) {
                for (const [chartId, chartData] of Object.entries(data.charts)) {
                    createChart(
                        chartId, 
                        chartData.type, 
                        chartData.labels, 
                        chartData.datasets
                    );
                }
            }
        });
        
        // Inicializar gráficos si hay datos disponibles
        if (document.querySelector('.chart-container')) {
            @this.dispatch('initCharts');
        }
    });
</script>
@endpush 