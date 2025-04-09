<!-- تقرير المقارنة بين الفترات -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">{{ __('Salary Comparison') }}</h5>
        
        <div class="row mb-4">
            <div class="col-md-10">
                <div class="form-row">
                    <div class="col-md-3 mb-2">
                        <label for="comparisonPeriod">{{ __('Comparison Period') }}</label>
                        <select wire:model.live="comparisonPeriod" id="comparisonPeriod" class="form-control">
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="quarterly">{{ __('Quarterly') }}</option>
                            <option value="yearly">{{ __('Yearly') }}</option>
                            <option value="custom">{{ __('Custom') }}</option>
                        </select>
                    </div>
                    
                    @if($comparisonPeriod == 'custom')
                        <div class="col-md-3 mb-2">
                            <label for="periodStart">{{ __('Start Period') }}</label>
                            <input type="date" wire:model.live="periodStart" id="periodStart" class="form-control">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="periodEnd">{{ __('End Period') }}</label>
                            <input type="date" wire:model.live="periodEnd" id="periodEnd" class="form-control">
                        </div>
                    @endif
                    
                    <div class="col-md-3 mb-2">
                        <label for="comparisonType">{{ __('Data Type') }}</label>
                        <select wire:model.live="comparisonType" id="comparisonType" class="form-control">
                            <option value="all">{{ __('All Data') }}</option>
                            <option value="department">{{ __('By Department') }}</option>
                            <option value="employee">{{ __('By Employee') }}</option>
                        </select>
                    </div>
                    
                    @if($comparisonType == 'department' || $comparisonType == 'employee')
                        <div class="col-md-3 mb-2">
                            <label for="comparisonFilter">
                                {{ $comparisonType == 'department' ? __('Department') : __('Employee') }}
                            </label>
                            <select wire:model.live="comparisonFilter" id="comparisonFilter" class="form-control">
                                @if($comparisonType == 'department')
                                    @foreach($departments as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @else
                                    @foreach($employees as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end justify-content-end">
                <button type="button" wire:click="updateComparisonReport" class="btn btn-primary">
                    <i class="mdi mdi-refresh mr-1"></i> {{ __('Update') }}
                </button>
            </div>
        </div>
        
        <!-- الرسم البياني للمقارنة -->
        <div class="chart-container mb-4" style="height: 300px;">
            <canvas id="comparisonChart"></canvas>
        </div>
        
        <!-- جدول البيانات المقارنة -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Period') }}</th>
                        <th>{{ __('Basic Salary') }}</th>
                        <th>{{ __('Allowances') }}</th>
                        <th>{{ __('Deductions') }}</th>
                        <th>{{ __('Net Salary') }}</th>
                        <th>{{ __('Change %') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['comparisonData'] as $period => $data)
                        <tr>
                            <td>{{ $period }}</td>
                            <td>{{ number_format($data['basic_salary'], 2) }}</td>
                            <td>{{ number_format($data['allowances'], 2) }}</td>
                            <td>{{ number_format($data['deductions'], 2) }}</td>
                            <td>{{ number_format($data['net_salary'], 2) }}</td>
                            <td class="{{ $data['percentage_change'] > 0 ? 'text-success' : ($data['percentage_change'] < 0 ? 'text-danger' : '') }}">
                                @if(isset($data['percentage_change']))
                                    {{ $data['percentage_change'] > 0 ? '+' : '' }}{{ number_format($data['percentage_change'], 1) }}%
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('No comparison data available') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- تحليل الاتجاهات والنمو -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ __('Trend Analysis') }}</h5>
        
        <div class="row">
            <div class="col-md-8">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="trend-stats">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('Growth Analysis') }}</h6>
                            <div class="stat-item d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('Average Growth Rate') }}:</span>
                                <span class="{{ $reportData['trends']['averageGrowth'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $reportData['trends']['averageGrowth'] > 0 ? '+' : '' }}{{ number_format($reportData['trends']['averageGrowth'], 1) }}%
                                </span>
                            </div>
                            <div class="stat-item d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('Max Increase') }}:</span>
                                <span class="text-success">
                                    +{{ number_format($reportData['trends']['maxIncrease'], 1) }}%
                                    <small class="text-muted">({{ $reportData['trends']['maxIncreaseDate'] }})</small>
                                </span>
                            </div>
                            <div class="stat-item d-flex justify-content-between align-items-center mb-2">
                                <span>{{ __('Max Decrease') }}:</span>
                                <span class="text-danger">
                                    {{ number_format($reportData['trends']['maxDecrease'], 1) }}%
                                    <small class="text-muted">({{ $reportData['trends']['maxDecreaseDate'] }})</small>
                                </span>
                            </div>
                            <div class="stat-item d-flex justify-content-between align-items-center">
                                <span>{{ __('Projection Next Period') }}:</span>
                                <span class="{{ $reportData['trends']['projection'] > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $reportData['trends']['projection'] > 0 ? '+' : '' }}{{ number_format($reportData['trends']['projection'], 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    @this.on('comparisonDataUpdated', (data) => {
        createComparisonChart();
        createTrendChart();
    });
    
    function createComparisonChart() {
        const ctx = document.getElementById('comparisonChart');
        if (!ctx) return;
        
        const chartData = @js($chartData['comparison']);
        if (!chartData) return;
        
        // تدمير الرسم البياني السابق إذا كان موجودًا
        if (window.comparisonChart) {
            window.comparisonChart.destroy();
        }
        
        window.comparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: '{{ __("Basic Salary") }}',
                        data: chartData.basicSalary,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ __("Allowances") }}',
                        data: chartData.allowances,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ __("Deductions") }}',
                        data: chartData.deductions,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '{{ __("Net Salary") }}',
                        data: chartData.netSalary,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        type: 'line'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function createTrendChart() {
        const ctx = document.getElementById('trendChart');
        if (!ctx) return;
        
        const chartData = @js($chartData['trend']);
        if (!chartData) return;
        
        // تدمير الرسم البياني السابق إذا كان موجودًا
        if (window.trendChart) {
            window.trendChart.destroy();
        }
        
        window.trendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: '{{ __("Net Salary") }}',
                        data: chartData.values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: '{{ __("Trend Line") }}',
                        data: chartData.trendLine,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        borderDash: [6, 6],
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    }
    
    // تهيئة الرسوم البيانية عند تحميل الصفحة
    if (document.getElementById('comparisonChart') && document.getElementById('trendChart')) {
        createComparisonChart();
        createTrendChart();
    }
});
</script>