<!-- التقرير التفصيلي للرواتب -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">{{ __('Detailed Salary Report') }}</h5>
        <p class="card-subtitle mb-3 text-muted">
            {{ __('Period') }}: 
            @if($month && $year)
                {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
            @elseif($startDate && $endDate)
                {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}
            @else
                {{ __('All time') }}
            @endif
        </p>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Basic Salary') }}</th>
                        <th>{{ __('Allowances') }}</th>
                        <th>{{ __('Deductions') }}</th>
                        <th>{{ __('Net Salary') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Payment Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData['detailedSalaries'] as $salary)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if(isset($salary['employee']['profile_photo_url']))
                                        <img src="{{ $salary['employee']['profile_photo_url'] }}" alt="{{ $salary['employee']['name'] }}" 
                                            class="rounded-circle mr-2" width="40" height="40">
                                    @else
                                        <div class="avatar-circle mr-2">
                                            <span class="initials">{{ $salary['employee']['initials'] ?? '' }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $salary['employee']['name'] }}</strong><br>
                                        <small class="text-muted">{{ $salary['employee']['employee_id'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $salary['employee']['department'] ?? '' }}</td>
                            <td>{{ number_format($salary['basic_salary'], 2) }}</td>
                            <td>
                                {{ number_format($salary['total_allowances'], 2) }}
                                <button type="button" class="btn btn-link btn-sm text-primary p-0 ml-2" 
                                        data-toggle="tooltip" data-html="true" 
                                        title="@foreach($salary['allowances'] as $key => $value) {{ $key }}: {{ number_format($value, 2) }}<br> @endforeach">
                                    <i class="mdi mdi-information-outline"></i>
                                </button>
                            </td>
                            <td>
                                {{ number_format($salary['total_deductions'], 2) }}
                                <button type="button" class="btn btn-link btn-sm text-primary p-0 ml-2" 
                                        data-toggle="tooltip" data-html="true" 
                                        title="@foreach($salary['deductions'] as $key => $value) {{ $key }}: {{ number_format($value, 2) }}<br> @endforeach">
                                    <i class="mdi mdi-information-outline"></i>
                                </button>
                            </td>
                            <td>{{ number_format($salary['net_salary'], 2) }}</td>
                            <td>
                                @if($salary['status'] == 'paid')
                                    <span class="badge badge-success">{{ __('Paid') }}</span>
                                @elseif($salary['status'] == 'partial')
                                    <span class="badge badge-warning">{{ __('Partial') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('Pending') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($salary['payment_date'])
                                    {{ date('d M Y', strtotime($salary['payment_date'])) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('No data available') }}</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-secondary font-weight-bold">
                        <td colspan="2">{{ __('Total') }}</td>
                        <td>{{ number_format($reportData['totalBasicSalary'], 2) }}</td>
                        <td>{{ number_format($reportData['totalAllowances'], 2) }}</td>
                        <td>{{ number_format($reportData['totalDeductions'], 2) }}</td>
                        <td>{{ number_format($reportData['netSalaries'], 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- تفاصيل البدلات والحسومات -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Allowance Distribution') }}</h5>
                <div class="chart-container">
                    <canvas id="allowanceChart"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Allowance Type') }}</th>
                                <th class="text-right">{{ __('Amount') }}</th>
                                <th class="text-right">{{ __('Percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData['allowanceBreakdown'] as $type => $data)
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td class="text-right">{{ number_format($data['amount'], 2) }}</td>
                                    <td class="text-right">{{ number_format($data['percentage'], 1) }}%</td>
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
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('Deduction Distribution') }}</h5>
                <div class="chart-container">
                    <canvas id="deductionChart"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Deduction Type') }}</th>
                                <th class="text-right">{{ __('Amount') }}</th>
                                <th class="text-right">{{ __('Percentage') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData['deductionBreakdown'] as $type => $data)
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td class="text-right">{{ number_format($data['amount'], 2) }}</td>
                                    <td class="text-right">{{ number_format($data['percentage'], 1) }}%</td>
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
    @this.on('chartDataUpdated', (data) => {
        if (data && data.allowances && data.deductions) {
            createChart('allowanceChart', 'pie', data.allowances);
            createChart('deductionChart', 'pie', data.deductions);
        }
    });
    
    if (document.getElementById('allowanceChart') && document.getElementById('deductionChart')) {
        if (typeof $chartData !== 'undefined' && $chartData && $chartData.allowances && $chartData.deductions) {
            createChart('allowanceChart', 'pie', @js($chartData['allowances'] ?? []));
            createChart('deductionChart', 'pie', @js($chartData['deductions'] ?? []));
        }
    }
    
    // تفعيل التلميحات
    $('[data-toggle="tooltip"]').tooltip();
});
</script> 