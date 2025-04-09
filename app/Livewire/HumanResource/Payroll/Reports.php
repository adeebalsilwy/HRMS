<?php

namespace App\Livewire\HumanResource\Payroll;

use App\Models\Employee;
use App\Models\Salary;
use App\Models\Department;
use App\Models\Center;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;
    
    // فلاتر التقارير
    public $reportType = 'summary'; // summary, detailed, monthly, department
    public $dateFrom;
    public $dateTo;
    public $year;
    public $month;
    public $departmentId;
    public $centerId;
    public $employeeId;
    public $status;
    public $chartType = 'bar'; // bar, line, pie
    
    // متغيرات إضافية
    public $perPage = 10;
    public $searchQuery = '';
    
    public function mount()
    {
        // اعداد القيم الافتراضية
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->year = Carbon::now()->year;
        $this->month = Carbon::now()->month;
    }
    
    public function render()
    {
        $departments = Department::all();
        $centers = Center::all();
        $employees = Employee::where('is_active', true)->get();
        $years = $this->getYearsList();
        $months = $this->getMonthsList();
        
        // استدعاء دوال التقارير بحسب النوع المطلوب
        $reportData = [];
        $chartData = [
            'allowances' => [],
            'deductions' => []
        ];
        
        switch ($this->reportType) {
            case 'summary':
                $reportData = $this->getSummaryReport();
                $chartData = $this->getSummaryChartData();
                break;
            case 'detailed':
                $reportData = $this->getDetailedReport();
                // For detailed report, chart data should be returned from the method
                if (isset($reportData['chartData'])) {
                    $chartData = $reportData['chartData'];
                }
                break;
            case 'monthly':
                $reportData = $this->getMonthlyReport();
                $chartData = $this->getMonthlyChartData();
                break;
            case 'department':
                $reportData = $this->getDepartmentReport();
                $chartData = $this->getDepartmentChartData();
                break;
        }
        
        // Ensure chart data always has the required keys to prevent errors
        if (!isset($chartData['allowances'])) {
            $chartData['allowances'] = [];
        }
        if (!isset($chartData['deductions'])) {
            $chartData['deductions'] = [];
        }
        
        return view('livewire.human-resource.payroll.reports', [
            'departments' => $departments,
            'centers' => $centers,
            'employees' => $employees,
            'years' => $years,
            'months' => $months,
            'reportData' => $reportData,
            'chartData' => $chartData
        ]);
    }
    
    private function getYearsList()
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($year = $currentYear - 2; $year <= $currentYear + 1; $year++) {
            $years[$year] = $year;
        }
        return $years;
    }

    private function getMonthsList()
    {
        return [
            1 => __('January'),
            2 => __('February'),
            3 => __('March'),
            4 => __('April'),
            5 => __('May'),
            6 => __('June'),
            7 => __('July'),
            8 => __('August'),
            9 => __('September'),
            10 => __('October'),
            11 => __('November'),
            12 => __('December'),
        ];
    }
    
    // ============ تقرير ملخص الرواتب ============
    private function getSummaryReport()
    {
        $query = Salary::with('employee')
            ->selectRaw('
                COUNT(*) as total_salaries,
                SUM(basic_salary) as total_basic_salary,
                SUM(housing_allowance) as total_housing_allowance,
                SUM(transportation_allowance) as total_transportation_allowance,
                SUM(food_allowance) as total_food_allowance,
                SUM(other_allowance) as total_other_allowance,
                SUM(bonus) as total_bonus,
                SUM(deductions) as total_deductions,
                SUM(basic_salary + housing_allowance + transportation_allowance + food_allowance + other_allowance + bonus - deductions) as total_amount,
                status,
                COUNT(CASE WHEN status = "paid" THEN 1 END) as paid_count,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count,
                COUNT(CASE WHEN status = "partial" THEN 1 END) as partial_count
            ')
            ->whereBetween('salary_date', [$this->dateFrom, $this->dateTo])
            ->when($this->status, function($q) {
                return $q->where('status', $this->status);
            })
            ->when($this->departmentId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('department_id', $this->departmentId)
                          ->where(function($q) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $this->dateFrom);
                          });
                });
            })
            ->when($this->centerId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('center_id', $this->centerId)
                          ->where(function($q) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $this->dateFrom);
                          });
                });
            })
            ->when($this->employeeId, function($q) {
                return $q->where('employee_id', $this->employeeId);
            })
            ->when($this->searchQuery, function($q) {
                return $q->whereHas('employee', function($query) {
                    $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.$this->searchQuery.'%');
                });
            })
            ->groupBy('status')
            ->get();
            
        // حساب الإجمالي العام
        $grandTotal = [
            'total_salaries' => $query->sum('total_salaries'),
            'total_basic_salary' => $query->sum('total_basic_salary'),
            'total_housing_allowance' => $query->sum('total_housing_allowance'),
            'total_transportation_allowance' => $query->sum('total_transportation_allowance'),
            'total_food_allowance' => $query->sum('total_food_allowance'),
            'total_other_allowance' => $query->sum('total_other_allowance'),
            'total_bonus' => $query->sum('total_bonus'),
            'total_deductions' => $query->sum('total_deductions'),
            'total_amount' => $query->sum('total_amount'),
            'paid_count' => $query->sum('paid_count'),
            'pending_count' => $query->sum('pending_count'),
            'partial_count' => $query->sum('partial_count')
        ];
        
        // Get department summary data
        $departmentSummary = $this->getDepartmentSummaryForReport();
        
        // Restructure data to match view expectations
        $totalAllowances = $grandTotal['total_housing_allowance'] + 
                          $grandTotal['total_transportation_allowance'] + 
                          $grandTotal['total_food_allowance'] + 
                          $grandTotal['total_other_allowance'] + 
                          $grandTotal['total_bonus'];
        
        return [
            'results' => $query,
            'grandTotal' => $grandTotal,
            // Add expected keys for the view
            'totalBasicSalary' => $grandTotal['total_basic_salary'],
            'totalAllowances' => $totalAllowances,
            'totalDeductions' => $grandTotal['total_deductions'],
            'netSalaries' => $grandTotal['total_amount'],
            'employeeCount' => $grandTotal['total_salaries'],
            'paidCount' => $grandTotal['paid_count'],
            'pendingCount' => $grandTotal['pending_count'],
            'partialCount' => $grandTotal['partial_count'],
            'departmentSummary' => $departmentSummary
        ];
    }
    
    // Helper method to get department summary for summary report
    private function getDepartmentSummaryForReport()
    {
        $departmentSummary = Salary::with('employee.timelines.department')
            ->join('employees', 'salaries.employee_id', '=', 'employees.id')
            ->join('timelines', function($join) {
                $join->on('employees.id', '=', 'timelines.employee_id')
                    ->where(function($q) {
                        $q->whereNull('timelines.end_date')
                          ->orWhere('timelines.end_date', '>=', DB::raw('salaries.salary_date'));
                    })
                    ->where('timelines.start_date', '<=', DB::raw('salaries.salary_date'));
            })
            ->join('departments', 'timelines.department_id', '=', 'departments.id')
            ->selectRaw('
                departments.name as name,
                COUNT(DISTINCT salaries.employee_id) as count,
                SUM(salaries.basic_salary) as basicSalary,
                SUM(salaries.housing_allowance + salaries.transportation_allowance + salaries.food_allowance + salaries.other_allowance + salaries.bonus) as allowances,
                SUM(salaries.deductions) as deductions,
                SUM(salaries.basic_salary + salaries.housing_allowance + salaries.transportation_allowance + salaries.food_allowance + salaries.other_allowance + salaries.bonus - salaries.deductions) as netSalary
            ')
            ->whereBetween('salaries.salary_date', [$this->dateFrom, $this->dateTo])
            ->when($this->status, function($q) {
                return $q->where('salaries.status', $this->status);
            })
            ->when($this->departmentId, function($q) {
                return $q->where('departments.id', $this->departmentId);
            })
            ->when($this->centerId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('center_id', $this->centerId);
                });
            })
            ->groupBy('departments.name')
            ->get()
            ->toArray();
            
        return $departmentSummary;
    }
    
    private function getSummaryChartData()
    {
        // Prepare data for salary distribution chart
        $salaries = Salary::selectRaw('
            status,
            SUM(basic_salary + housing_allowance + transportation_allowance + food_allowance + other_allowance + bonus - deductions) as total_amount
        ')
            ->whereBetween('salary_date', [$this->dateFrom, $this->dateTo])
            ->groupBy('status')
            ->get();
            
        $labels = [];
        $data = [];
        $colors = [
            'paid' => '#28a745',
            'pending' => '#ffc107',
            'partial' => '#17a2b8'
        ];
        $backgroundColors = [];
        
        foreach ($salaries as $salary) {
            $labels[] = __($salary->status);
            $data[] = $salary->total_amount;
            $backgroundColors[] = $colors[$salary->status] ?? '#6c757d';
        }
        
        $salaryDistribution = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Total Amount'),
                    'data' => $data,
                    'backgroundColor' => $backgroundColors
                ]
            ]
        ];
        
        // Prepare data for department salary chart
        $departmentSummary = $this->getDepartmentSummaryForReport();
        $deptLabels = array_column($departmentSummary, 'name');
        $deptData = array_column($departmentSummary, 'netSalary');
        $deptColors = [
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(123, 239, 178, 0.6)',
            'rgba(238, 130, 238, 0.6)'
        ];
        
        $departmentSalary = [
            'labels' => $deptLabels,
            'datasets' => [
                [
                    'label' => __('Net Salary'),
                    'data' => $deptData,
                    'backgroundColor' => array_slice($deptColors, 0, count($deptData))
                ]
            ]
        ];
        
        return [
            'salaryDistribution' => $salaryDistribution,
            'departmentSalary' => $departmentSalary
        ];
    }
    
    // ============ تقرير الرواتب التفصيلي ============
    private function getDetailedReport()
    {
        $query = Salary::with('employee')
            ->when($this->departmentId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('department_id', $this->departmentId)
                          ->where(function($q) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $this->dateFrom);
                          });
                });
            })
            ->when($this->centerId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('center_id', $this->centerId)
                          ->where(function($q) {
                              $q->whereNull('end_date')
                                ->orWhere('end_date', '>=', $this->dateFrom);
                          });
                });
            })
            ->when($this->employeeId, function($q) {
                return $q->where('employee_id', $this->employeeId);
            })
            ->when($this->status, function($q) {
                return $q->where('status', $this->status);
            })
            ->when($this->searchQuery, function($q) {
                return $q->whereHas('employee', function($query) {
                    $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.$this->searchQuery.'%');
                });
            })
            ->whereBetween('salary_date', [$this->dateFrom, $this->dateTo])
            ->orderBy('salary_date', 'desc')
            ->paginate($this->perPage);
            
        // حساب الإجمالي للبيانات الحالية
        $currentPageTotal = [
            'total_basic_salary' => $query->sum('basic_salary'),
            'total_housing_allowance' => $query->sum('housing_allowance'),
            'total_transportation_allowance' => $query->sum('transportation_allowance'),
            'total_food_allowance' => $query->sum('food_allowance'),
            'total_other_allowance' => $query->sum('other_allowance'),
            'total_bonus' => $query->sum('bonus'),
            'total_deductions' => $query->sum('deductions'),
            'total_amount' => $query->sum(function($salary) {
                return $salary->basic_salary + 
                       $salary->housing_allowance + 
                       $salary->transportation_allowance + 
                       $salary->food_allowance + 
                       $salary->other_allowance + 
                       $salary->bonus - 
                       $salary->deductions;
            })
        ];
            
        $allowanceBreakdown = [
            __('Housing') => [
                'amount' => $currentPageTotal['total_housing_allowance'],
                'percentage' => $currentPageTotal['total_housing_allowance'] / max(1, ($currentPageTotal['total_housing_allowance'] + $currentPageTotal['total_transportation_allowance'] + $currentPageTotal['total_food_allowance'] + $currentPageTotal['total_other_allowance'] + $currentPageTotal['total_bonus'])) * 100
            ],
            __('Transportation') => [
                'amount' => $currentPageTotal['total_transportation_allowance'],
                'percentage' => $currentPageTotal['total_transportation_allowance'] / max(1, ($currentPageTotal['total_housing_allowance'] + $currentPageTotal['total_transportation_allowance'] + $currentPageTotal['total_food_allowance'] + $currentPageTotal['total_other_allowance'] + $currentPageTotal['total_bonus'])) * 100
            ],
            __('Food') => [
                'amount' => $currentPageTotal['total_food_allowance'],
                'percentage' => $currentPageTotal['total_food_allowance'] / max(1, ($currentPageTotal['total_housing_allowance'] + $currentPageTotal['total_transportation_allowance'] + $currentPageTotal['total_food_allowance'] + $currentPageTotal['total_other_allowance'] + $currentPageTotal['total_bonus'])) * 100
            ],
            __('Other') => [
                'amount' => $currentPageTotal['total_other_allowance'],
                'percentage' => $currentPageTotal['total_other_allowance'] / max(1, ($currentPageTotal['total_housing_allowance'] + $currentPageTotal['total_transportation_allowance'] + $currentPageTotal['total_food_allowance'] + $currentPageTotal['total_other_allowance'] + $currentPageTotal['total_bonus'])) * 100
            ],
            __('Bonus') => [
                'amount' => $currentPageTotal['total_bonus'],
                'percentage' => $currentPageTotal['total_bonus'] / max(1, ($currentPageTotal['total_housing_allowance'] + $currentPageTotal['total_transportation_allowance'] + $currentPageTotal['total_food_allowance'] + $currentPageTotal['total_other_allowance'] + $currentPageTotal['total_bonus'])) * 100
            ]
        ];
        
        $deductionBreakdown = [
            __('Deductions') => [
                'amount' => $currentPageTotal['total_deductions'],
                'percentage' => 100
            ]
        ];
        
        // بيانات الرسوم البيانية
        $chartData = [
            'allowances' => [
                'labels' => array_keys($allowanceBreakdown),
                'datasets' => [
                    [
                        'data' => array_column($allowanceBreakdown, 'amount'),
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)'
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        'borderWidth' => 1
                    ]
                ]
            ],
            'deductions' => [
                'labels' => array_keys($deductionBreakdown),
                'datasets' => [
                    [
                        'data' => array_column($deductionBreakdown, 'amount'),
                        'backgroundColor' => [
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)'
                        ],
                        'borderWidth' => 1
                    ]
                ]
            ]
        ];
        
        return [
            'results' => $query,
            'currentPageTotal' => $currentPageTotal,
            'detailedSalaries' => $query->map(function($salary) {
                return [
                    'employee' => [
                        'name' => $salary->employee->getFullNameAttribute(),
                        'employee_id' => $salary->employee_id,
                        'profile_photo_url' => $salary->employee->getEmployeePhoto(),
                        'initials' => substr($salary->employee->first_name, 0, 1) . substr($salary->employee->last_name, 0, 1),
                        'department' => $this->getDepartmentName($salary->employee)
                    ],
                    'basic_salary' => $salary->basic_salary,
                    'total_allowances' => $salary->housing_allowance + $salary->transportation_allowance + $salary->food_allowance + $salary->other_allowance + $salary->bonus,
                    'allowances' => [
                        __('Housing') => $salary->housing_allowance,
                        __('Transportation') => $salary->transportation_allowance,
                        __('Food') => $salary->food_allowance,
                        __('Other') => $salary->other_allowance,
                        __('Bonus') => $salary->bonus,
                    ],
                    'total_deductions' => $salary->deductions,
                    'deductions' => [
                        __('Deductions') => $salary->deductions
                    ],
                    'net_salary' => $salary->basic_salary + $salary->housing_allowance + $salary->transportation_allowance + $salary->food_allowance + $salary->other_allowance + $salary->bonus - $salary->deductions,
                    'status' => $salary->status,
                    'payment_date' => $salary->payment_date
                ];
            }),
            // Add expected keys for the view
            'totalBasicSalary' => $currentPageTotal['total_basic_salary'],
            'totalAllowances' => $currentPageTotal['total_housing_allowance'] + $currentPageTotal['total_transportation_allowance'] + $currentPageTotal['total_food_allowance'] + $currentPageTotal['total_other_allowance'] + $currentPageTotal['total_bonus'],
            'totalDeductions' => $currentPageTotal['total_deductions'],
            'netSalaries' => $currentPageTotal['total_amount'],
            'allowanceBreakdown' => $allowanceBreakdown,
            'deductionBreakdown' => $deductionBreakdown,
            'chartData' => $chartData
        ];
    }
    
    // ============ تقرير الرواتب الشهرية ============
    private function getMonthlyReport()
    {
        $startDate = Carbon::createFromDate($this->year, 1, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($this->year, 12, 31)->endOfMonth();
        
        $query = Salary::with('employee')
            ->selectRaw('
                MONTH(salary_date) as month,
                COUNT(*) as total_salaries,
                SUM(basic_salary) as total_basic_salary,
                SUM(housing_allowance) as total_housing_allowance,
                SUM(transportation_allowance) as total_transportation_allowance,
                SUM(food_allowance) as total_food_allowance,
                SUM(other_allowance) as total_other_allowance,
                SUM(bonus) as total_bonus,
                SUM(deductions) as total_deductions,
                SUM(basic_salary + housing_allowance + transportation_allowance + food_allowance + other_allowance + bonus - deductions) as total_amount,
                COUNT(CASE WHEN status = "paid" THEN 1 END) as paid_count,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count,
                COUNT(CASE WHEN status = "partial" THEN 1 END) as partial_count
            ')
            ->whereBetween('salary_date', [$startDate, $endDate])
            ->when($this->departmentId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('department_id', $this->departmentId);
                });
            })
            ->when($this->centerId, function($q) {
                return $q->whereHas('employee.timelines', function($query) {
                    $query->where('center_id', $this->centerId);
                });
            })
            ->when($this->employeeId, function($q) {
                return $q->where('employee_id', $this->employeeId);
            })
            ->when($this->status, function($q) {
                return $q->where('status', $this->status);
            })
            ->groupBy(DB::raw('MONTH(salary_date)'))
            ->get();
            
        // تنظيم البيانات حسب الشهور
        $monthlyData = [];
        $months = $this->getMonthsList();
        
        // تهيئة البيانات لجميع الشهور
        foreach ($months as $monthNumber => $monthName) {
            $monthlyData[$monthNumber] = [
                'month_name' => $monthName,
                'month_number' => $monthNumber,
                'total_salaries' => 0,
                'total_basic_salary' => 0,
                'total_housing_allowance' => 0,
                'total_transportation_allowance' => 0,
                'total_food_allowance' => 0,
                'total_other_allowance' => 0,
                'total_bonus' => 0,
                'total_deductions' => 0,
                'total_amount' => 0,
                'paid_count' => 0,
                'pending_count' => 0,
                'partial_count' => 0
            ];
        }
        
        // ملء البيانات من النتائج
        foreach ($query as $item) {
            $monthlyData[$item->month] = [
                'month_name' => $months[$item->month],
                'month_number' => $item->month,
                'total_salaries' => $item->total_salaries,
                'total_basic_salary' => $item->total_basic_salary,
                'total_housing_allowance' => $item->total_housing_allowance,
                'total_transportation_allowance' => $item->total_transportation_allowance,
                'total_food_allowance' => $item->total_food_allowance,
                'total_other_allowance' => $item->total_other_allowance,
                'total_bonus' => $item->total_bonus,
                'total_deductions' => $item->total_deductions,
                'total_amount' => $item->total_amount,
                'paid_count' => $item->paid_count,
                'pending_count' => $item->pending_count,
                'partial_count' => $item->partial_count
            ];
        }
        
        // حساب الإجمالي السنوي
        $yearlyTotal = [
            'total_salaries' => $query->sum('total_salaries'),
            'total_basic_salary' => $query->sum('total_basic_salary'),
            'total_housing_allowance' => $query->sum('total_housing_allowance'),
            'total_transportation_allowance' => $query->sum('total_transportation_allowance'),
            'total_food_allowance' => $query->sum('total_food_allowance'),
            'total_other_allowance' => $query->sum('total_other_allowance'),
            'total_bonus' => $query->sum('total_bonus'),
            'total_deductions' => $query->sum('total_deductions'),
            'total_amount' => $query->sum('total_amount'),
            'paid_count' => $query->sum('paid_count'),
            'pending_count' => $query->sum('pending_count'),
            'partial_count' => $query->sum('partial_count')
        ];
        
        return [
            'results' => $monthlyData,
            'yearlyTotal' => $yearlyTotal
        ];
    }
    
    private function getMonthlyChartData()
    {
        $months = $this->getMonthsList();
        $monthlyReport = $this->getMonthlyReport();
        
        $labels = array_values($months);
        $datasets = [
            [
                'label' => __('Total Salaries'),
                'data' => [],
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1
            ],
            [
                'label' => __('Paid'),
                'data' => [],
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            ],
            [
                'label' => __('Pending'),
                'data' => [],
                'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                'borderColor' => 'rgba(255, 206, 86, 1)',
                'borderWidth' => 1
            ]
        ];
        
        foreach ($monthlyReport['results'] as $month => $data) {
            $datasets[0]['data'][] = $data['total_amount'];
            $datasets[1]['data'][] = $data['paid_count'];
            $datasets[2]['data'][] = $data['pending_count'];
        }
        
        return [
            'labels' => $labels,
            'datasets' => $datasets
        ];
    }
    
    // ============ تقرير الرواتب حسب القسم ============
    private function getDepartmentReport()
    {
        $query = Salary::with('employee.timelines.department')
            ->join('employees', 'salaries.employee_id', '=', 'employees.id')
            ->join('timelines', function($join) {
                $join->on('employees.id', '=', 'timelines.employee_id')
                    ->where(function($q) {
                        $q->whereNull('timelines.end_date')
                          ->orWhere('timelines.end_date', '>=', DB::raw('salaries.salary_date'));
                    })
                    ->where('timelines.start_date', '<=', DB::raw('salaries.salary_date'));
            })
            ->join('departments', 'timelines.department_id', '=', 'departments.id')
            ->selectRaw('
                departments.id as department_id,
                departments.name as department_name,
                COUNT(*) as total_salaries,
                SUM(salaries.basic_salary) as total_basic_salary,
                SUM(salaries.housing_allowance) as total_housing_allowance,
                SUM(salaries.transportation_allowance) as total_transportation_allowance,
                SUM(salaries.food_allowance) as total_food_allowance,
                SUM(salaries.other_allowance) as total_other_allowance,
                SUM(salaries.bonus) as total_bonus,
                SUM(salaries.deductions) as total_deductions,
                SUM(salaries.basic_salary + salaries.housing_allowance + salaries.transportation_allowance + salaries.food_allowance + salaries.other_allowance + salaries.bonus - salaries.deductions) as total_amount,
                COUNT(CASE WHEN salaries.status = "paid" THEN 1 END) as paid_count,
                COUNT(CASE WHEN salaries.status = "pending" THEN 1 END) as pending_count,
                COUNT(CASE WHEN salaries.status = "partial" THEN 1 END) as partial_count
            ')
            ->whereBetween('salaries.salary_date', [$this->dateFrom, $this->dateTo])
            ->when($this->centerId, function($q) {
                return $q->where('timelines.center_id', $this->centerId);
            })
            ->when($this->departmentId, function($q) {
                return $q->where('departments.id', $this->departmentId);
            })
            ->when($this->status, function($q) {
                return $q->where('salaries.status', $this->status);
            })
            ->groupBy('departments.id', 'departments.name')
            ->get();
        
        // حساب الإجمالي
        $totalsByDepartment = [
            'total_salaries' => $query->sum('total_salaries'),
            'total_basic_salary' => $query->sum('total_basic_salary'),
            'total_housing_allowance' => $query->sum('total_housing_allowance'),
            'total_transportation_allowance' => $query->sum('total_transportation_allowance'),
            'total_food_allowance' => $query->sum('total_food_allowance'),
            'total_other_allowance' => $query->sum('total_other_allowance'),
            'total_bonus' => $query->sum('total_bonus'),
            'total_deductions' => $query->sum('total_deductions'),
            'total_amount' => $query->sum('total_amount'),
            'paid_count' => $query->sum('paid_count'),
            'pending_count' => $query->sum('pending_count'),
            'partial_count' => $query->sum('partial_count')
        ];
        
        return [
            'results' => $query,
            'totalsByDepartment' => $totalsByDepartment
        ];
    }
    
    private function getDepartmentChartData()
    {
        $departmentReport = $this->getDepartmentReport();
        
        $labels = [];
        $data = [];
        $backgroundColor = [
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(123, 239, 178, 0.6)',
            'rgba(238, 130, 238, 0.6)'
        ];
        
        foreach ($departmentReport['results'] as $index => $dept) {
            $labels[] = $dept->department_name;
            $data[] = $dept->total_amount;
        }
        
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => __('Total Amount'),
                    'data' => $data,
                    'backgroundColor' => array_slice($backgroundColor, 0, count($data))
                ]
            ]
        ];
    }
    
    // دوال معالجة الحدث
    public function changeReportType($type)
    {
        $this->reportType = $type;
        $this->resetPage();
    }
    
    public function changeChartType($type)
    {
        $this->chartType = $type;
    }
    
    public function exportPdf()
    {
        $this->dispatch('toast', ['message' => __('PDF export feature will be implemented soon'), 'type' => 'info']);
    }
    
    public function exportExcel()
    {
        $this->dispatch('toast', ['message' => __('Excel export feature will be implemented soon'), 'type' => 'info']);
    }

    private function getDepartmentName($employee)
    {
        if (!$employee) {
            return '';
        }
        
        try {
            $department = $employee->getCurrentDepartmentAttribute();
            if ($department && is_object($department) && isset($department->name)) {
                return $department->name;
            }
            return '';
        } catch (\Exception $e) {
            // Log the error if needed
            // \Log::error('Error getting department name: ' . $e->getMessage());
            return '';
        }
    }
}
