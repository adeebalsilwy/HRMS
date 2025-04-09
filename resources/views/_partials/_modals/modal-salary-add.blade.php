<!-- Add Salary Modal -->
<div class="modal fade" id="addSalaryModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add New Salary') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="saveSalary">
                <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_id" class="form-label">{{ __('Employee') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" wire:model="employee_id">
                                <option value="">{{ __('Select Employee') }}</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="salary_date" class="form-label">{{ __('Salary Date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('salary_date') is-invalid @enderror" id="salary_date" wire:model="salary_date">
                            @error('salary_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="mt-3 mb-2">{{ __('Salary Details') }}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="basic_salary" class="form-label">{{ __('Basic Salary') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('basic_salary') is-invalid @enderror" id="basic_salary" wire:model="basic_salary">
                            @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="housing_allowance" class="form-label">{{ __('Housing Allowance') }}</label>
                            <input type="number" step="0.01" class="form-control @error('housing_allowance') is-invalid @enderror" id="housing_allowance" wire:model="housing_allowance">
                            @error('housing_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="transportation_allowance" class="form-label">{{ __('Transportation Allowance') }}</label>
                            <input type="number" step="0.01" class="form-control @error('transportation_allowance') is-invalid @enderror" id="transportation_allowance" wire:model="transportation_allowance">
                            @error('transportation_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="food_allowance" class="form-label">{{ __('Food Allowance') }}</label>
                            <input type="number" step="0.01" class="form-control @error('food_allowance') is-invalid @enderror" id="food_allowance" wire:model="food_allowance">
                            @error('food_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="other_allowance" class="form-label">{{ __('Other Allowance') }}</label>
                            <input type="number" step="0.01" class="form-control @error('other_allowance') is-invalid @enderror" id="other_allowance" wire:model="other_allowance">
                            @error('other_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="overtime_rate" class="form-label">{{ __('Overtime Rate (Per Hour)') }}</label>
                            <input type="number" step="0.01" class="form-control @error('overtime_rate') is-invalid @enderror" id="overtime_rate" wire:model="overtime_rate">
                            @error('overtime_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="bonus" class="form-label">{{ __('Bonus') }}</label>
                            <input type="number" step="0.01" class="form-control @error('bonus') is-invalid @enderror" id="bonus" wire:model="bonus">
                            @error('bonus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="deductions" class="form-label">{{ __('Deductions') }}</label>
                            <input type="number" step="0.01" class="form-control @error('deductions') is-invalid @enderror" id="deductions" wire:model="deductions">
                            @error('deductions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h6 class="mt-3 mb-2">{{ __('Payment Details') }}</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" wire:model="payment_method">
                                <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="cheque">{{ __('Cheque') }}</option>
                            </select>
                            @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" wire:model="status">
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="paid">{{ __('Paid') }}</option>
                                <option value="partial">{{ __('Partial') }}</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        @if($status === 'paid' || $status === 'partial')
                        <div class="col-md-6 mb-3">
                            <label for="payment_date" class="form-label">{{ __('Payment Date') }}</label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" wire:model="payment_date">
                            @error('payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif
                        
                        @if($payment_method === 'bank_transfer')
                        <div class="col-md-6 mb-3">
                            <label for="bank_name" class="form-label">{{ __('Bank Name') }}</label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" wire:model="bank_name">
                            @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="bank_account" class="form-label">{{ __('Bank Account') }}</label>
                            <input type="text" class="form-control @error('bank_account') is-invalid @enderror" id="bank_account" wire:model="bank_account">
                            @error('bank_account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="iban" class="form-label">{{ __('IBAN') }}</label>
                            <input type="text" class="form-control @error('iban') is-invalid @enderror" id="iban" wire:model="iban">
                            @error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" wire:model="notes" rows="3"></textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div> 