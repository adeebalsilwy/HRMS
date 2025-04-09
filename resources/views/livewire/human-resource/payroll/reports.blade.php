@section('title', __('Reports'))

<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('payroll-salaries') }}">{{ __('Payroll') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Reports') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('Salary Reports') }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="row">
                            <!-- نوع التقرير -->
                            <div class="col-md-12 mb-3">
                                <div class="d-flex justify-content-start report-type-tabs">
                                    <button wire:click="changeReportType('summary')" class="btn {{ $reportType == 'summary' ? 'btn-primary' : 'btn-light' }} me-2">
                                        <i class="mdi mdi-chart-pie"></i> {{ __('Summary Report') }}
                                    </button>
                                    <button wire:click="changeReportType('detailed')" class="btn {{ $reportType == 'detailed' ? 'btn-primary' : 'btn-light' }} me-2">
                                        <i class="mdi mdi-table-large"></i> {{ __('Detailed Report') }}
                                    </button>
                                    <!-- <button wire:click="changeReportType('monthly')" class="btn {{ $reportType == 'monthly' ? 'btn-primary' : 'btn-light' }} me-2">
                                        <i class="mdi mdi-calendar-month"></i> {{ __('Monthly Report') }}
                                    </button>
                                    <button wire:click="changeReportType('department')" class="btn {{ $reportType == 'department' ? 'btn-primary' : 'btn-light' }}">
                                        <i class="mdi mdi-office-building"></i> {{ __('Department Report') }}
                                    </button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- فلاتر التقارير -->
                    <div class="row mb-4">
                        <!-- بحث مشترك -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <input type="text" class="form-control" placeholder="{{ __('Search') }}" wire:model.debounce.300ms="searchQuery">
                        </div>

                        @if($reportType != 'monthly')
                        <!-- تاريخ من -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <input type="date" class="form-control" wire:model.live="dateFrom">
                        </div>

                        <!-- تاريخ إلى -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <input type="date" class="form-control" wire:model.live="dateTo">
                        </div>
                        @else
                        <!-- السنة للتقرير الشهري -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-select" wire:model.live="year">
                                @foreach($years as $yr)
                                    <option value="{{ $yr }}">{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- حالة الراتب -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-select" wire:model.live="status">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="paid">{{ __('Paid') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="partial">{{ __('Partial') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- فلاتر إضافية -->
                    <div class="row mb-4">
                        <!-- القسم -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-select" wire:model.live="departmentId">
                                <option value="">{{ __('All Departments') }}</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- المركز -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-select" wire:model.live="centerId">
                                <option value="">{{ __('All Centers') }}</option>
                                @foreach($centers as $center)
                                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- الموظف -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-select" wire:model.live="employeeId">
                                <option value="">{{ __('All Employees') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- نوع الرسم البياني، فقط في التقارير التي تحتوي على رسوم بيانية -->
                        @if($reportType != 'detailed')
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-select" wire:model.live="chartType">
                                <option value="bar">{{ __('Bar Chart') }}</option>
                                <option value="line">{{ __('Line Chart') }}</option>
                                <option value="pie">{{ __('Pie Chart') }}</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    <!-- أزرار التصدير -->
                    <div class="row mb-4">
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-success me-2" wire:click="exportExcel">
                                <i class="mdi mdi-file-excel"></i> {{ __('Export to Excel') }}
                            </button>
                            <button class="btn btn-danger" wire:click="exportPdf">
                                <i class="mdi mdi-file-pdf"></i> {{ __('Export to PDF') }}
                            </button>
                        </div>
                    </div>

                    <!-- عرض محتوى التقرير حسب النوع -->
                    <div class="report-content mt-4">
                        @if($reportType == 'summary')
                            @include('livewire.human-resource.payroll._partials.summary-report', ['reportData' => $reportData, 'chartData' => $chartData, 'chartType' => $chartType])
                        @elseif($reportType == 'detailed')
                            @include('livewire.human-resource.payroll._partials.detailed-report', ['reportData' => $reportData])
                        @elseif($reportType == 'monthly')
                            @include('livewire.human-resource.payroll._partials.monthly-report', ['reportData' => $reportData, 'chartData' => $chartData, 'chartType' => $chartType])
                        @elseif($reportType == 'department')
                            @include('livewire.human-resource.payroll._partials.department-report', ['reportData' => $reportData, 'chartData' => $chartData, 'chartType' => $chartType])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // دالة إنشاء الرسوم البيانية
    function createChart(chartId, chartType, chartData) {
        const ctx = document.getElementById(chartId).getContext('2d');
        
        // إذا كان هناك رسم بياني سابق، قم بتدميره
        if (window.reportChart) {
            window.reportChart.destroy();
        }
        
        // إنشاء رسم بياني جديد
        window.reportChart = new Chart(ctx, {
            type: chartType,
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('ar-SA', { style: 'currency', currency: 'SAR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('ar-SA', { style: 'currency', currency: 'SAR' }).format(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // استدعاء الدالة عندما تكون البيانات جاهزة
    document.addEventListener('livewire:init', () => {
        Livewire.on('chartDataUpdated', (data) => {
            if (data.chartId && data.chartData && data.chartType) {
                createChart(data.chartId, data.chartType, data.chartData);
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .report-type-tabs .btn {
        min-width: 150px;
    }
    .chart-container {
        height: 350px;
        margin-bottom: 30px;
    }
    .summary-card {
        border-radius: 10px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .summary-card .card-title {
        font-size: 1rem;
        font-weight: 500;
    }
    .summary-card .amount {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .table th {
        background-color: #f8f9fa;
    }
</style>
@endpush
