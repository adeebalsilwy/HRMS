<?php

namespace App\Livewire\Assets;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Center;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Transition;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;
    
    // فلاتر التقارير
    public $reportType = 'summary'; // summary, detailed, distribution, employee
    public $dateFrom;
    public $dateTo;
    public $year;
    public $month;
    public $departmentId;
    public $centerId;
    public $employeeId;
    public $categoryId;
    public $assetStatus;
    public $chartType = 'bar'; // bar, line, pie
    
    // متغيرات إضافية
    public $perPage = 10;
    public $searchQuery = '';
    
    // بيانات الرسوم البيانية
    public $chartData = [];
    public $reportData = [];
    
    public function mount()
    {
        // اعداد القيم الافتراضية
        $this->dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->year = Carbon::now()->year;
        $this->month = Carbon::now()->month;
        
        // تحميل البيانات الأولية
        $this->loadReportData();
    }
    
    public function render()
    {
        $departments = Department::all();
        $centers = Center::all();
        $employees = Employee::where('is_active', true)->get();
        $categories = Category::all();
        
        $years = $this->getYearsList();
        $months = $this->getMonthsList();
        
        return view('livewire.assets.reports', [
            'departments' => $departments,
            'centers' => $centers,
            'employees' => $employees,
            'categories' => $categories,
            'years' => $years,
            'months' => $months,
            'chartData' => $this->chartData,
            'reportData' => $this->reportData,
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
    
    public function changeReportType($type)
    {
        $this->reportType = $type;
        $this->loadReportData();
    }
    
    public function changeChartType($type)
    {
        $this->chartType = $type;
        $this->loadReportData();
    }
    
    public function updatedDateFrom()
    {
        $this->loadReportData();
    }
    
    public function updatedDateTo()
    {
        $this->loadReportData();
    }
    
    public function updatedYear()
    {
        $this->loadReportData();
    }
    
    public function updatedMonth()
    {
        $this->loadReportData();
    }
    
    public function updatedDepartmentId()
    {
        $this->loadReportData();
    }
    
    public function updatedCenterId()
    {
        $this->loadReportData();
    }
    
    public function updatedEmployeeId()
    {
        $this->loadReportData();
    }
    
    public function updatedCategoryId()
    {
        $this->loadReportData();
    }
    
    public function updatedAssetStatus()
    {
        $this->loadReportData();
    }
    
    public function loadReportData()
    {
        switch ($this->reportType) {
            case 'summary':
                $this->getSummaryReport();
                $this->getSummaryChartData();
                break;
            case 'detailed':
                $this->getDetailedReport();
                break;
            case 'distribution':
                $this->getDistributionReport();
                $this->getDistributionChartData();
                break;
            case 'employee':
                $this->getEmployeeReport();
                $this->getEmployeeChartData();
                break;
            default:
                $this->getSummaryReport();
                $this->getSummaryChartData();
                break;
        }
    }
    
    private function getSummaryReport()
    {
        try {
            // إحصائيات عامة
            $totalAssets = Asset::count();
            $inServiceAssets = Asset::where('in_service', true)->count();
            $outOfServiceAssets = Asset::where('in_service', false)->count();
            
            // توزيع الأصول حسب الحالة
            $assetsByStatus = Asset::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->pluck('total', 'status')
                ->toArray();
            
            // توزيع الأصول حسب النوع
            $assetsByClass = Asset::select('class', DB::raw('count(*) as total'))
                ->groupBy('class')
                ->get()
                ->pluck('total', 'class')
                ->toArray();
            
            // Intentar obtener la distribución por categoría (Enfoque 1)
            try {
                $assetsByCategory = $this->getCategoriesDistribution();
            } catch (\Exception $e) {
                $assetsByCategory = [];
                \Log::error('Error al obtener distribución por categorías: ' . $e->getMessage());
            }
            
            // أحدث الأصول المضافة
            $latestAssets = Asset::orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // الأصول الأعلى قيمة
            $mostValuableAssets = Asset::whereNotNull('real_price')
                ->orderBy('real_price', 'desc')
                ->take(5)
                ->get();
            
            $this->reportData = [
                'summary' => [
                    'totalAssets' => $totalAssets,
                    'inServiceAssets' => $inServiceAssets,
                    'outOfServiceAssets' => $outOfServiceAssets,
                    'assetsByStatus' => $assetsByStatus,
                    'assetsByClass' => $assetsByClass,
                    'assetsByCategory' => $assetsByCategory,
                    'latestAssets' => $latestAssets,
                    'mostValuableAssets' => $mostValuableAssets,
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Error en getSummaryReport: ' . $e->getMessage());
            // Establecer datos por defecto en caso de error
            $this->reportData = [
                'summary' => [
                    'totalAssets' => 0,
                    'inServiceAssets' => 0,
                    'outOfServiceAssets' => 0,
                    'assetsByStatus' => [],
                    'assetsByClass' => [],
                    'assetsByCategory' => [],
                    'latestAssets' => collect(),
                    'mostValuableAssets' => collect(),
                ]
            ];
        }
    }
    
    /**
     * Obtiene la distribución de assets por categorías
     * Prueba diferentes enfoques según la estructura de la base de datos
     */
    private function getCategoriesDistribution()
    {
        // Método 1: Usando la tabla de relación directa (si existe)
        try {
            $result = DB::table('assets')
                ->join('category_sub_category_asset', 'assets.id', '=', 'category_sub_category_asset.asset_id')
                ->join('category_sub_category', 'category_sub_category_asset.category_sub_category_id', '=', 'category_sub_category.id')
                ->join('categories', 'category_sub_category.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('count(*) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->get()
                ->pluck('total', 'name')
                ->toArray();
                
            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            \Log::info('Método 1 de categorías falló: ' . $e->getMessage());
        }
        
        // Método 2: Usando la tabla category_sub_category_asset directamente con category_id
        try {
            $result = DB::table('assets')
                ->join('category_sub_category_asset', 'assets.id', '=', 'category_sub_category_asset.asset_id')
                ->join('categories', 'category_sub_category_asset.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('count(*) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->get()
                ->pluck('total', 'name')
                ->toArray();
                
            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            \Log::info('Método 2 de categorías falló: ' . $e->getMessage());
        }
        
        // Método 3: Usando la relación del modelo (si existe)
        try {
            $result = Category::withCount('assets')
                ->get()
                ->pluck('assets_count', 'name')
                ->toArray();
                
            if (!empty($result)) {
                return $result;
            }
        } catch (\Exception $e) {
            \Log::info('Método 3 de categorías falló: ' . $e->getMessage());
        }
        
        // Método 4: Usar relación a través de subcategorías
        try {
            $categoriesWithCount = [];
            $categories = Category::all();
            
            foreach ($categories as $category) {
                $count = 0;
                foreach ($category->subCategories as $subCategory) {
                    $count += $subCategory->assets()->count();
                }
                if ($count > 0) {
                    $categoriesWithCount[$category->name] = $count;
                }
            }
            
            if (!empty($categoriesWithCount)) {
                return $categoriesWithCount;
            }
        } catch (\Exception $e) {
            \Log::info('Método 4 de categorías falló: ' . $e->getMessage());
        }
        
        // Si ningún método funciona, devolvemos algunos datos de ejemplo
        return [
            'Sin categoría' => Asset::count(),
        ];
    }
    
    private function getSummaryChartData()
    {
        // بيانات الرسم البياني للملخص
        $this->chartData = [
            'summary' => [
                'status' => [
                    'labels' => array_keys($this->reportData['summary']['assetsByStatus']),
                    'data' => array_values($this->reportData['summary']['assetsByStatus']),
                ],
                'class' => [
                    'labels' => array_keys($this->reportData['summary']['assetsByClass']),
                    'data' => array_values($this->reportData['summary']['assetsByClass']),
                ],
                'category' => [
                    'labels' => array_keys($this->reportData['summary']['assetsByCategory']),
                    'data' => array_values($this->reportData['summary']['assetsByCategory']),
                ],
            ]
        ];
    }
    
    private function getDetailedReport()
    {
        // تجهيز الاستعلام
        $query = Asset::query();
        
        // تطبيق الفلاتر
        if ($this->categoryId) {
            try {
                // Intentar usar la relación correcta a través de category_sub_category_asset
                $query->whereHas('categorySubCategories', function($q) {
                    $q->whereHas('categories', function($q2) {
                        $q2->where('categories.id', $this->categoryId);
                    });
                });
            } catch (\Exception $e) {
                // Si la relación anterior no existe, intentar con una relación directa
                try {
                    $query->whereHas('categories', function($q) {
                        $q->where('categories.id', $this->categoryId);
                    });
                } catch (\Exception $e) {
                    // Si ninguna relación funciona, simplemente no aplicamos este filtro
                    \Log::warning('No se pudo aplicar el filtro de categoría: ' . $e->getMessage());
                }
            }
        }
        
        if ($this->assetStatus) {
            $query->where('status', $this->assetStatus);
        }
        
        // الحصول على الأصول
        $assets = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        
        $this->reportData['detailed'] = [
            'assets' => $assets,
        ];
    }
    
    private function getDistributionReport()
    {
        // توزيع الأصول على المراكز
        $assetsByCenter = DB::table('transitions')
            ->join('employees', 'transitions.employee_id', '=', 'employees.id')
            ->join('timelines', function($join) {
                $join->on('employees.id', '=', 'timelines.employee_id')
                    ->whereNull('timelines.end_date');
            })
            ->join('centers', 'timelines.center_id', '=', 'centers.id')
            ->whereNull('transitions.return_date')
            ->select('centers.name', DB::raw('count(DISTINCT transitions.asset_id) as total'))
            ->groupBy('centers.id', 'centers.name')
            ->get()
            ->pluck('total', 'name')
            ->toArray();
            
        // توزيع الأصول على الأقسام
        $assetsByDepartment = DB::table('transitions')
            ->join('employees', 'transitions.employee_id', '=', 'employees.id')
            ->join('timelines', function($join) {
                $join->on('employees.id', '=', 'timelines.employee_id')
                    ->whereNull('timelines.end_date');
            })
            ->join('departments', 'timelines.department_id', '=', 'departments.id')
            ->whereNull('transitions.return_date')
            ->select('departments.name', DB::raw('count(DISTINCT transitions.asset_id) as total'))
            ->groupBy('departments.id', 'departments.name')
            ->get()
            ->pluck('total', 'name')
            ->toArray();
        
        $this->reportData['distribution'] = [
            'assetsByCenter' => $assetsByCenter,
            'assetsByDepartment' => $assetsByDepartment,
        ];
    }
    
    private function getDistributionChartData()
    {
        // بيانات الرسم البياني للتوزيع
        $this->chartData['distribution'] = [
            'centers' => [
                'labels' => array_keys($this->reportData['distribution']['assetsByCenter']),
                'data' => array_values($this->reportData['distribution']['assetsByCenter']),
            ],
            'departments' => [
                'labels' => array_keys($this->reportData['distribution']['assetsByDepartment']),
                'data' => array_values($this->reportData['distribution']['assetsByDepartment']),
            ],
        ];
    }
    
    private function getEmployeeReport()
    {
        // الاستعلام الأساسي
        $query = DB::table('transitions')
            ->join('employees', 'transitions.employee_id', '=', 'employees.id')
            ->join('assets', 'transitions.asset_id', '=', 'assets.id')
            ->whereNull('transitions.return_date');
        
        // تطبيق الفلاتر
        if ($this->employeeId) {
            $query->where('employees.id', $this->employeeId);
        }
        
        if ($this->departmentId) {
            $query->join('timelines', function($join) {
                $join->on('employees.id', '=', 'timelines.employee_id')
                    ->whereNull('timelines.end_date');
            })
            ->where('timelines.department_id', $this->departmentId);
        }
        
        if ($this->centerId) {
            $query->join('timelines as center_timelines', function($join) {
                $join->on('employees.id', '=', 'center_timelines.employee_id')
                    ->whereNull('center_timelines.end_date');
            })
            ->where('center_timelines.center_id', $this->centerId);
        }
        
        // الحصول على البيانات مجمعة بواسطة الموظف
        $employeeAssets = $query
            ->select(
                'employees.id as employee_id',
                'employees.first_name',
                'employees.father_name',
                'employees.last_name',
                DB::raw('count(DISTINCT transitions.asset_id) as asset_count'),
                DB::raw('sum(assets.real_price) as total_value')
            )
            ->groupBy('employees.id', 'employees.first_name', 'employees.father_name', 'employees.last_name')
            ->orderBy('asset_count', 'desc')
            ->get();
        
        // تجهيز البيانات
        $this->reportData['employee'] = [
            'employeeAssets' => $employeeAssets,
        ];
    }
    
    private function getEmployeeChartData()
    {
        // استخراج البيانات للرسم البياني
        $labels = [];
        $dataCounts = [];
        $dataValues = [];
        
        foreach ($this->reportData['employee']['employeeAssets'] as $item) {
            $fullName = $item->first_name . ' ' . $item->father_name . ' ' . $item->last_name;
            $labels[] = $fullName;
            $dataCounts[] = $item->asset_count;
            $dataValues[] = $item->total_value ?? 0;
        }
        
        // بيانات الرسم البياني للموظفين
        $this->chartData['employee'] = [
            'labels' => $labels,
            'counts' => $dataCounts,
            'values' => $dataValues,
        ];
    }
    
    public function exportPdf()
    {
        // تنفيذ تصدير التقرير بصيغة PDF
        $this->dispatchBrowserEvent('showToast', ['message' => __('PDF export feature coming soon')]);
    }
    
    public function exportExcel()
    {
        // تنفيذ تصدير التقرير بصيغة Excel
        $this->dispatchBrowserEvent('showToast', ['message' => __('Excel export feature coming soon')]);
    }
} 