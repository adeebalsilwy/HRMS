<?php

namespace App\Livewire\HumanResource\Structure;

use App\Models\Center;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Timeline;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeInfo extends Component
{
    public $centers;

    public $departments;

    public $positions;

    public $employee;

    public $timeline;

    public $employeeTimelines;

    public $employeeTimelineInfo = [];

    public $employeeAssets;

    public $employeeSalaries;

    public $isEdit = false;

    public $confirmedId;

    public $selectedCenter;

    public $selectedDepartment;

    public $selectedPosition;

    // 👉 Mount
    public function mount($id)
    {
        $this->employee = Employee::with([
            'timelines.center',
            'timelines.department',
            'timelines.position',
            'contract'
        ])->find($id);
        
        if (!$this->employee) {
            session()->flash('error', __('Employee not found'));
            return redirect()->route('structure-employees');
        }
        
        $this->employeeAssets = $this->employee
            ->transitions()
            ->with('asset')
            ->orderBy('handed_date', 'desc')
            ->get();
            
        // Cargamos los salarios del empleado
        $this->employeeSalaries = $this->employee
            ->salaries()
            ->orderBy('salary_date', 'desc')
            ->get();
            
        $this->centers = Center::all();
        $this->departments = Department::all();
        $this->positions = Position::all();
    }

    // 👉 Render
    public function render()
    {
        try {
            // Load timelines with eager loading of related models
            $this->employeeTimelines = Timeline::with(['center', 'department', 'position'])
                ->where('employee_id', $this->employee->id)
                ->orderBy('id', 'desc')
                ->get();
                
            // Verify data integrity for debugging
            foreach ($this->employeeTimelines as $index => $timeline) {
                if (!isset($timeline->center) || !isset($timeline->center->name)) {
                    \Log::warning("Timeline {$timeline->id} is missing center relation", [
                        'timeline_id' => $timeline->id,
                        'center_id' => $timeline->center_id
                    ]);
                }
                
                if (!isset($timeline->department) || !isset($timeline->department->name)) {
                    \Log::warning("Timeline {$timeline->id} is missing department relation", [
                        'timeline_id' => $timeline->id,
                        'department_id' => $timeline->department_id
                    ]);
                }
                
                if (!isset($timeline->position) || !isset($timeline->position->name)) {
                    \Log::warning("Timeline {$timeline->id} is missing position relation", [
                        'timeline_id' => $timeline->id,
                        'position_id' => $timeline->position_id
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            \Log::error("Error loading timelines: " . $e->getMessage(), [
                'employee_id' => $this->employee->id,
                'exception' => $e
            ]);
            
            // Provide empty collection if there's an error
            $this->employeeTimelines = collect();
        }

        // For debugging purposes
        if (empty($this->employee->current_center) || empty($this->employee->current_department) || empty($this->employee->current_position)) {
            $presentTimeline = Timeline::with(['center', 'department', 'position'])
                ->where('employee_id', $this->employee->id)
                ->whereNull('end_date')
                ->first();
                
            if ($presentTimeline) {
                \Log::info('Timeline found but relationships may be missing', [
                    'employee_id' => $this->employee->id,
                    'timeline_id' => $presentTimeline->id,
                    'has_center' => !empty($presentTimeline->center),
                    'has_department' => !empty($presentTimeline->department),
                    'has_position' => !empty($presentTimeline->position)
                ]);
            } else {
                \Log::info('No present timeline found for employee', [
                    'employee_id' => $this->employee->id
                ]);
            }
        }

        return view('livewire.human-resource.structure.employee-info');
    }

    // 👉 Toggle active status
    public function toggleActive()
    {
        $presentTimeline = $this->employee
            ->timelines()
            ->orderBy('timelines.id', 'desc')
            ->first();

        if ($this->employee->is_active == true) {
            $this->employee->is_active = false;
            $presentTimeline->end_date = Carbon::now();
        } else {
            $this->employee->is_active = true;
            $presentTimeline->end_date = null;
        }

        $this->employee->save();
        $presentTimeline->save();

        $this->dispatch('toastr', type: 'success' /* , title: 'Done!' */, message: __('Going Well!'));
    }

    // 👉 Submit timeline
    public function submitTimeline()
    {
        $this->employeeTimelineInfo['centerId'] = $this->selectedCenter;
        $this->employeeTimelineInfo['departmentId'] = $this->selectedDepartment;
        $this->employeeTimelineInfo['positionId'] = $this->selectedPosition;

        $this->validate([
            'employeeTimelineInfo.centerId' => 'required',
            'employeeTimelineInfo.departmentId' => 'required',
            'employeeTimelineInfo.positionId' => 'required',
            'employeeTimelineInfo.startDate' => 'required',
            'employeeTimelineInfo.isSequent' => 'required',
        ]);

        $this->isEdit ? $this->updateTimeline() : $this->storeTimeline();
    }

    // 👉 Store timeline
    public function showStoreTimelineModal()
    {
        $this->reset('isEdit', 'selectedCenter', 'selectedDepartment', 'selectedPosition', 'employeeTimelineInfo');
        $this->dispatch('clearSelect2Values');
    }

    public function storeTimeline()
    {
        DB::beginTransaction();
        try {
            $presentTimeline = $this->employee
                ->timelines()
                ->orderBy('timelines.id', 'desc')
                ->first();

            if ($presentTimeline) {
                $presentTimeline->end_date = Carbon::now();
                $presentTimeline->save();
            }

            Timeline::create([
                'employee_id' => $this->employee->id,
                'center_id' => $this->employeeTimelineInfo['centerId'],
                'department_id' => $this->employeeTimelineInfo['departmentId'],
                'position_id' => $this->employeeTimelineInfo['positionId'],
                'start_date' => $this->employeeTimelineInfo['startDate'],
                'end_date' => isset($this->employeeTimelineInfo['endDate']) ? $this->employeeTimelineInfo['endDate'] : null,
                'is_sequent' => $this->employeeTimelineInfo['isSequent'],
                'notes' => isset($this->employeeTimelineInfo['notes']) ? $this->employeeTimelineInfo['notes'] : null,
            ]);

            $this->dispatch('closeModal', elementId: '#timelineModal');
            $this->dispatch('toastr', type: 'success' /* , title: 'Done!' */, message: __('Going Well!'));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch(
                'toastr',
                type: 'success' /* , title: 'Done!' */,
                message: 'Something is going wrong, check the log file!'
            );
            throw $e;
        }
    }

    // 👉 Update timeline
    public function showUpdateTimelineModal(Timeline $timeline)
    {
        $this->isEdit = true;

        $this->timeline = $timeline;

        $this->employeeTimelineInfo['centerId'] = $timeline->center_id;
        $this->employeeTimelineInfo['departmentId'] = $timeline->department_id;
        $this->employeeTimelineInfo['positionId'] = $timeline->position_id;
        $this->employeeTimelineInfo['startDate'] = $timeline->start_date;
        $this->employeeTimelineInfo['endDate'] = $timeline->end_date;
        $this->employeeTimelineInfo['isSequent'] = $timeline->is_sequent;
        $this->employeeTimelineInfo['notes'] = $timeline->notes;

        $this->dispatch(
            'setSelect2Values',
            centerId: $timeline->center_id,
            departmentId: $timeline->department_id,
            positionId: $timeline->position_id
        );
    }

    public function updateTimeline()
    {
        $this->timeline->update([
            'center_id' => $this->employeeTimelineInfo['centerId'],
            'department_id' => $this->employeeTimelineInfo['departmentId'],
            'position_id' => $this->employeeTimelineInfo['positionId'],
            'start_date' => $this->employeeTimelineInfo['startDate'],
            'end_date' => $this->employeeTimelineInfo['endDate'],
            'is_sequent' => $this->employeeTimelineInfo['isSequent'],
            'notes' => $this->employeeTimelineInfo['notes'],
        ]);

        $this->dispatch('closeModal', elementId: '#timelineModal');
        $this->dispatch('toastr', type: 'success' /* , title: 'Done!' */, message: __('Going Well!'));
    }

    // 👉 Delete timeline
    public function confirmDeleteTimeline(Timeline $timeline)
    {
        $this->confirmedId = $timeline->id;
    }

    public function deleteTimeline(Timeline $timeline)
    {
        $timeline->delete();

        $this->dispatch('toastr', type: 'success' /* , title: 'Done!' */, message: __('Going Well!'));
    }

    // 👉 Set present timeline
    public function setPresentTimeline(Timeline $timeline)
    {
        $timeline->end_date = null;
        $timeline->save();

        session()->flash('success', __('The current position assigned successfully.'));
    }

    // 👉 Calcular estadísticas de salario
    public function getSalaryStats()
    {
        if (count($this->employeeSalaries) === 0) {
            return [
                'avg_salary' => 0,
                'min_salary' => 0,
                'max_salary' => 0,
                'total_paid' => 0,
                'pending_count' => 0,
                'paid_count' => 0,
                'partial_count' => 0,
                'latest_month' => __('No data')
            ];
        }

        $totalSalaries = $this->employeeSalaries->count();
        $avgSalary = $this->employeeSalaries->avg('basic_salary');
        $minSalary = $this->employeeSalaries->min('basic_salary');
        $maxSalary = $this->employeeSalaries->max('basic_salary');
        
        $totalPaid = $this->employeeSalaries
            ->where('status', 'paid')
            ->sum('basic_salary');
            
        $pendingCount = $this->employeeSalaries->where('status', 'pending')->count();
        $paidCount = $this->employeeSalaries->where('status', 'paid')->count();
        $partialCount = $this->employeeSalaries->where('status', 'partial')->count();
        
        $latestSalary = $this->employeeSalaries->first();
        $latestMonth = $latestSalary ? \Carbon\Carbon::parse($latestSalary->salary_date)->format('F Y') : __('No data');
        
        return [
            'avg_salary' => number_format($avgSalary, 2),
            'min_salary' => number_format($minSalary, 2),
            'max_salary' => number_format($maxSalary, 2),
            'total_paid' => number_format($totalPaid, 2),
            'pending_count' => $pendingCount,
            'paid_count' => $paidCount,
            'partial_count' => $partialCount,
            'latest_month' => $latestMonth
        ];
    }
}
