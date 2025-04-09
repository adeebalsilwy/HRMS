<!-- ملخص الإحصائيات -->
<div class="row mb-4">
    <!-- إجمالي الرواتب -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title text-white">{{ __('Total Salaries') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ number_format($reportData['totalBasicSalary'], 2) }}</span>
                    <i class="mdi mdi-cash-multiple mdi-36px opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- إجمالي البدلات -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title text-white">{{ __('Total Allowances') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ number_format($reportData['totalAllowances'], 2) }}</span>
                    <i class="mdi mdi-plus-circle mdi-36px opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- إجمالي الحسومات -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title text-white">{{ __('Total Deductions') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ number_format($reportData['totalDeductions'], 2) }}</span>
                    <i class="mdi mdi-minus-circle mdi-36px opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- صافي الرواتب -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title text-white">{{ __('Net Salaries') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ number_format($reportData['netSalaries'], 2) }}</span>
                    <i class="mdi mdi-bank-transfer mdi-36px opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات إضافية -->
<div class="row mb-4">
    <!-- عدد الموظفين -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Total Employees') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ $reportData['employeeCount'] }}</span>
                    <i class="mdi mdi-account-group mdi-36px text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- رواتب مدفوعة -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Paid Salaries') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ $reportData['paidCount'] }}</span>
                    <i class="mdi mdi-check-circle mdi-36px text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- قيد الانتظار -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Pending Salaries') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ $reportData['pendingCount'] }}</span>
                    <i class="mdi mdi-clock mdi-36px text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- مدفوعة جزئياً -->
    <div class="col-md-3 col-sm-6">
        <div class="card summary-card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Partial Payments') }}</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="amount">{{ $reportData['partialCount'] }}</span>
                    <i class="mdi mdi-circle-half-full mdi-36px text-info opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الرسم البياني -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Salary Distribution') }}</h5>
                <div class="chart-container">
                    <canvas id="salaryDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Department Salary Comparison') }}</h5>
                <div class="chart-container">
                    <canvas id="departmentSalaryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الجدول التوضيحي -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Summary by Department') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Employees') }}</th>
                                <th>{{ __('Basic Salary') }}</th>
                                <th>{{ __('Allowances') }}</th>
                                <th>{{ __('Deductions') }}</th>
                                <th>{{ __('Net Salary') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData['departmentSummary'] as $dept)
                                <tr>
                                    <td>{{ $dept['name'] }}</td>
                                    <td>{{ $dept['count'] }}</td>
                                    <td>{{ number_format($dept['basicSalary'], 2) }}</td>
                                    <td>{{ number_format($dept['allowances'], 2) }}</td>
                                    <td>{{ number_format($dept['deductions'], 2) }}</td>
                                    <td>{{ number_format($dept['netSalary'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('No data available') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary font-weight-bold">
                                <td>{{ __('Total') }}</td>
                                <td>{{ $reportData['employeeCount'] }}</td>
                                <td>{{ number_format($reportData['totalBasicSalary'], 2) }}</td>
                                <td>{{ number_format($reportData['totalAllowances'], 2) }}</td>
                                <td>{{ number_format($reportData['totalDeductions'], 2) }}</td>
                                <td>{{ number_format($reportData['netSalaries'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    // تحديث الرسوم البيانية عند تحديث البيانات
    @this.on('chartDataUpdated', (data) => {
        createChart('salaryDistributionChart', '{{ $chartType }}', @js($chartData['salaryDistribution']));
        createChart('departmentSalaryChart', '{{ $chartType }}', @js($chartData['departmentSalary']));
    });
    
    // إنشاء الرسوم البيانية عند تحميل الصفحة
    if (document.getElementById('salaryDistributionChart') && document.getElementById('departmentSalaryChart')) {
        createChart('salaryDistributionChart', '{{ $chartType }}', @js($chartData['salaryDistribution']));
        createChart('departmentSalaryChart', '{{ $chartType }}', @js($chartData['departmentSalary']));
    }
});
</script> 