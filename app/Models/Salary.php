<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use CreatedUpdatedDeletedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'housing_allowance',
        'transportation_allowance',
        'food_allowance',
        'other_allowance',
        'overtime_rate',
        'bonus',
        'deductions',
        'payment_method',
        'bank_name',
        'bank_account',
        'iban',
        'salary_date',
        'payment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transportation_allowance' => 'decimal:2',
        'food_allowance' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'salary_date' => 'date',
        'payment_date' => 'date',
    ];

    // 👉 Links
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // 👉 Attributes
    public function getTotalSalaryAttribute()
    {
        return $this->basic_salary + 
               $this->housing_allowance + 
               $this->transportation_allowance + 
               $this->food_allowance + 
               $this->other_allowance + 
               $this->bonus - 
               $this->deductions;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'success',
            'partial' => 'info',
            default => 'secondary'
        };
    }
}
