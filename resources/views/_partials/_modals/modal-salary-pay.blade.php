<!-- Pay Salary Modal -->
<div class="modal fade" id="paySalaryModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Pay Salary') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="paySalary">
                <div class="modal-body">
                    <p>{{ __('Set the payment details for this salary.') }}</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pay_payment_date" class="form-label">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="pay_payment_date" wire:model="payment_date">
                            @error('payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pay_status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="pay_status" wire:model="status">
                                <option value="paid">{{ __('Paid') }}</option>
                                <option value="partial">{{ __('Partial') }}</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Process Payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div> 