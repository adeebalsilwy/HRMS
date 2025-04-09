<?php

namespace App\Livewire\HumanResource\Payroll;

use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Salaries extends Component
{
    use WithPagination;

    public $employeeId;
    public $month;
    public $year;
    public $search = '';
    public $sortField = 'salary_date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $statusFilter = '';
    
    // Salary form fields
    public $salaryId;
    public $employee_id;
    public $basic_salary;
    public $housing_allowance;
    public $transportation_allowance;
    public $food_allowance;
    public $other_allowance;
    public $overtime_rate;
    public $bonus;
    public $deductions;
    public $payment_method = 'bank_transfer';
    public $bank_name;
    public $bank_account;
    public $iban;
    public $salary_date;
    public $payment_date;
    public $status = 'pending';
    public $notes;
    
    public $showAddSalaryModal = false;
    public $showEditSalaryModal = false;
    public $showDeleteSalaryModal = false;
    public $showPaySalaryModal = false;

    protected $rules = [
        'employee_id' => 'required|exists:employees,id',
        'basic_salary' => 'required|numeric|min:0',
        'housing_allowance' => 'nullable|numeric|min:0',
        'transportation_allowance' => 'nullable|numeric|min:0',
        'food_allowance' => 'nullable|numeric|min:0',
        'other_allowance' => 'nullable|numeric|min:0',
        'overtime_rate' => 'nullable|numeric|min:0',
        'bonus' => 'nullable|numeric|min:0',
        'deductions' => 'nullable|numeric|min:0',
        'payment_method' => 'required|in:cash,bank_transfer,cheque',
        'bank_name' => 'nullable|string',
        'bank_account' => 'nullable|string',
        'iban' => 'nullable|string',
        'salary_date' => 'required|date',
        'payment_date' => 'nullable|date',
        'status' => 'required|in:pending,paid,partial',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $query = Salary::with('employee')
            ->when($this->employeeId, function($q) {
                return $q->where('employee_id', $this->employeeId);
            })
            ->when($this->month, function($q) {
                return $q->whereMonth('salary_date', $this->month);
            })
            ->when($this->year, function($q) {
                return $q->whereYear('salary_date', $this->year);
            })
            ->when($this->statusFilter, function($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->search, function($q) {
                return $q->whereHas('employee', function($query) {
                    $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $salaries = $query->paginate($this->perPage);
        $employees = Employee::where('is_active', true)->get();
        
        return view('livewire.human-resource.payroll.salaries', [
            'salaries' => $salaries,
            'employees' => $employees,
            'months' => $this->getMonthsList(),
            'years' => $this->getYearsList(),
        ]);
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

    private function getYearsList()
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($year = $currentYear - 2; $year <= $currentYear + 1; $year++) {
            $years[$year] = $year;
        }
        return $years;
    }

    public function openAddSalaryModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->salary_date = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function openEditSalaryModal($salaryId)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->salaryId = $salaryId;
        
        $salary = Salary::find($salaryId);
        if ($salary) {
            $this->employee_id = $salary->employee_id;
            $this->basic_salary = $salary->basic_salary;
            $this->housing_allowance = $salary->housing_allowance;
            $this->transportation_allowance = $salary->transportation_allowance;
            $this->food_allowance = $salary->food_allowance;
            $this->other_allowance = $salary->other_allowance;
            $this->overtime_rate = $salary->overtime_rate;
            $this->bonus = $salary->bonus;
            $this->deductions = $salary->deductions;
            $this->payment_method = $salary->payment_method;
            $this->bank_name = $salary->bank_name;
            $this->bank_account = $salary->bank_account;
            $this->iban = $salary->iban;
            $this->salary_date = $salary->salary_date;
            $this->payment_date = $salary->payment_date;
            $this->status = $salary->status;
            $this->notes = $salary->notes;
        }
    }

    public function openDeleteSalaryModal($salaryId)
    {
        $this->salaryId = $salaryId;
    }

    public function openPaySalaryModal($salaryId)
    {
        $this->salaryId = $salaryId;
        $salary = Salary::find($salaryId);
        if ($salary) {
            $this->payment_date = Carbon::now()->format('Y-m-d');
            $this->status = 'paid';
        }
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->salaryId = null;
        $this->employee_id = '';
        $this->basic_salary = 0;
        $this->housing_allowance = 0;
        $this->transportation_allowance = 0;
        $this->food_allowance = 0;
        $this->other_allowance = 0;
        $this->overtime_rate = 0;
        $this->bonus = 0;
        $this->deductions = 0;
        $this->payment_method = 'bank_transfer';
        $this->bank_name = '';
        $this->bank_account = '';
        $this->iban = '';
        $this->salary_date = '';
        $this->payment_date = null;
        $this->status = 'pending';
        $this->notes = '';
    }

    public function saveSalary()
    {
        $this->validate();
        
        Salary::create([
            'employee_id' => $this->employee_id,
            'basic_salary' => $this->basic_salary,
            'housing_allowance' => $this->housing_allowance,
            'transportation_allowance' => $this->transportation_allowance,
            'food_allowance' => $this->food_allowance,
            'other_allowance' => $this->other_allowance,
            'overtime_rate' => $this->overtime_rate,
            'bonus' => $this->bonus,
            'deductions' => $this->deductions,
            'payment_method' => $this->payment_method,
            'bank_name' => $this->bank_name,
            'bank_account' => $this->bank_account,
            'iban' => $this->iban,
            'salary_date' => $this->salary_date,
            'payment_date' => $this->payment_date,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_by' => auth()->user()->name,
            'updated_by' => auth()->user()->name,
        ]);
        
        $this->dispatch('toast', ['message' => __('Salary added successfully'), 'type' => 'success']);
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function updateSalary()
    {
        $this->validate();
        
        $salary = Salary::find($this->salaryId);
        if ($salary) {
            $salary->update([
                'employee_id' => $this->employee_id,
                'basic_salary' => $this->basic_salary,
                'housing_allowance' => $this->housing_allowance,
                'transportation_allowance' => $this->transportation_allowance,
                'food_allowance' => $this->food_allowance,
                'other_allowance' => $this->other_allowance,
                'overtime_rate' => $this->overtime_rate,
                'bonus' => $this->bonus,
                'deductions' => $this->deductions,
                'payment_method' => $this->payment_method,
                'bank_name' => $this->bank_name,
                'bank_account' => $this->bank_account,
                'iban' => $this->iban,
                'salary_date' => $this->salary_date,
                'payment_date' => $this->payment_date,
                'status' => $this->status,
                'notes' => $this->notes,
                'updated_by' => auth()->user()->name,
            ]);
            
            $this->dispatch('toast', ['message' => __('Salary updated successfully'), 'type' => 'success']);
            $this->dispatch('close-modal');
            $this->resetForm();
        }
    }

    public function deleteSalary()
    {
        $salary = Salary::find($this->salaryId);
        if ($salary) {
            $salary->update(['deleted_by' => auth()->user()->name]);
            $salary->delete();
            
            $this->dispatch('toast', ['message' => __('Salary deleted successfully'), 'type' => 'success']);
            $this->dispatch('close-modal');
            $this->resetForm();
        }
    }

    public function paySalary()
    {
        $salary = Salary::find($this->salaryId);
        if ($salary) {
            $salary->update([
                'payment_date' => $this->payment_date,
                'status' => $this->status,
                'updated_by' => auth()->user()->name,
            ]);
            
            $this->dispatch('toast', ['message' => __('Salary marked as paid'), 'type' => 'success']);
            $this->dispatch('close-modal');
            $this->resetForm();
        }
    }
} 