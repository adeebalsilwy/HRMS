@push('styles')
<style>
    /* تعديل خصائص النماذج لتحسين التمرير */
    .modal-dialog-scrollable .modal-content {
        max-height: 85vh !important;
    }
    
    .modal-body {
        overflow-y: auto !important;
        max-height: calc(85vh - 120px) !important;
    }
    
    /* تنسيقات للنماذج على الشاشات الصغيرة */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem !important;
        }
        
        .modal-dialog-scrollable .modal-content {
            max-height: 90vh !important;
        }
    }
</style>
@endpush

<div>
    @section('title', __('Salaries'))
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>{{ __('Salary Management') }}</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSalaryModal">
            <i class="ti ti-plus me-1"></i> {{ __('Add New Salary') }}
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <label for="search" class="form-label">{{ __('Search') }}</label>
                    <input type="text" class="form-control" id="search" wire:model.debounce.300ms="search" placeholder="{{ __('Search by employee name') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="employeeId" class="form-label">{{ __('Employee') }}</label>
                    <select class="form-select" id="employeeId" wire:model.live="employeeId">
                        <option value="">{{ __('All Employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="month" class="form-label">{{ __('Month') }}</label>
                    <select class="form-select" id="month" wire:model.live="month">
                        <option value="">{{ __('All Months') }}</option>
                        @foreach($months as $key => $monthName)
                            <option value="{{ $key }}">{{ $monthName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="year" class="form-label">{{ __('Year') }}</label>
                    <select class="form-select" id="year" wire:model.live="year">
                        <option value="">{{ __('All Years') }}</option>
                        @foreach($years as $yearValue)
                            <option value="{{ $yearValue }}">{{ $yearValue }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label for="statusFilter" class="form-label">{{ __('Status') }}</label>
                    <select class="form-select" id="statusFilter" wire:model.live="statusFilter">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="paid">{{ __('Paid') }}</option>
                        <option value="partial">{{ __('Partial') }}</option>
                    </select>
                </div>
                <div class="col-md-1 mb-2">
                    <label for="perPage" class="form-label">{{ __('Per Page') }}</label>
                    <select class="form-select" id="perPage" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Salary Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th wire:click="sortBy('employee_id')" style="cursor: pointer;">
                                {{ __('Employee') }}
                                @if($sortField === 'employee_id')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('basic_salary')" style="cursor: pointer;">
                                {{ __('Basic Salary') }}
                                @if($sortField === 'basic_salary')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th>{{ __('Allowances') }}</th>
                            <th>{{ __('Bonus') }}</th>
                            <th>{{ __('Deductions') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th wire:click="sortBy('salary_date')" style="cursor: pointer;">
                                {{ __('Salary Date') }}
                                @if($sortField === 'salary_date')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('payment_date')" style="cursor: pointer;">
                                {{ __('Payment Date') }}
                                @if($sortField === 'payment_date')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;">
                                {{ __('Status') }}
                                @if($sortField === 'status')
                                    <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $salary)
                            <tr>
                                <td>{{ $salary->id }}</td>
                                <td>{{ $salary->employee->full_name }}</td>
                                <td>{{ number_format($salary->basic_salary, 2) }}</td>
                                <td>
                                    {{ number_format(
                                        $salary->housing_allowance + 
                                        $salary->transportation_allowance + 
                                        $salary->food_allowance + 
                                        $salary->other_allowance, 2)
                                    }}
                                </td>
                                <td>{{ number_format($salary->bonus, 2) }}</td>
                                <td>{{ number_format($salary->deductions, 2) }}</td>
                                <td><strong>{{ number_format($salary->total_salary, 2) }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($salary->salary_date)->format('Y-m-d') }}</td>
                                <td>{{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $salary->status_color }}">
                                        {{ __($salary->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if($salary->status !== 'paid')
                                            <button type="button" class="btn btn-sm btn-success me-1" wire:click="openPaySalaryModal({{ $salary->id }})" data-bs-toggle="modal" data-bs-target="#paySalaryModal">
                                                <i class="ti ti-cash"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-primary me-1" wire:click="openEditSalaryModal({{ $salary->id }})" data-bs-toggle="modal" data-bs-target="#editSalaryModal">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" wire:click="openDeleteSalaryModal({{ $salary->id }})" data-bs-toggle="modal" data-bs-target="#deleteSalaryModal">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">{{ __('No salary records found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $salaries->links() }}
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('_partials._modals.modal-salary-add')
    @include('_partials._modals.modal-salary-edit')
    @include('_partials._modals.modal-salary-delete')
    @include('_partials._modals.modal-salary-pay')
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', function () {
        // Toast notifications
        Livewire.on('toast', (event) => {
            if (typeof toastr !== 'undefined') {
                toastr[event.type](event.message);
            }
        });

        // Close modals on success
        Livewire.on('close-modal', () => {
            // Close all modals
            const modals = [
                '#addSalaryModal',
                '#editSalaryModal',
                '#deleteSalaryModal',
                '#paySalaryModal'
            ];
            
            modals.forEach(modalId => {
                const modalElement = document.querySelector(modalId);
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });
    });
</script>
@endpush 