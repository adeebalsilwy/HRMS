<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();

        // Create salaries for January, February, and March 2025
        foreach ([1, 2, 3] as $month) {
            foreach ($employees as $employee) {
                // Basic salary varies by employee (sample values)
                $basicSalary = match($employee->id) {
                    1 => 3000, // المدير التنفيذي
                    2 => 2500, // مدير تقنية المعلومات
                    3 => 2200, // مديرة قسم الموارد البشرية
                    4 => 1500, // مطور واجهات أمامية
                    5 => 1800, // محاسبة
                    default => 1000
                };

                // Adjust for contract work rate
                $basicSalary = $basicSalary * ($employee->contract->work_rate / 100);

                // Create salary record
                Salary::create([
                    'employee_id' => $employee->id,
                    'basic_salary' => $basicSalary,
                    'housing_allowance' => $basicSalary * 0.25, // 25% of basic salary
                    'transportation_allowance' => 300,
                    'food_allowance' => 200,
                    'other_allowance' => 0,
                    'overtime_rate' => $basicSalary / 176, // hourly rate (22 days * 8 hours)
                    'bonus' => $month === 3 ? $basicSalary * 0.1 : 0, // 10% bonus in March
                    'deductions' => 0, // No deductions by default
                    'payment_method' => 'bank_transfer',
                    'bank_name' => 'البنك اليمني للإنشاء والتعمير',
                    'bank_account' => '10' . str_pad($employee->id, 8, '0', STR_PAD_LEFT),
                    'iban' => 'YE' . str_pad($employee->id, 10, '0', STR_PAD_LEFT),
                    'salary_date' => Carbon::create(2025, $month, 1)->endOfMonth()->format('Y-m-d'),
                    'payment_date' => $month < 3 ? Carbon::create(2025, $month, 1)->endOfMonth()->format('Y-m-d') : null,
                    'status' => $month < 3 ? 'paid' : 'pending',
                    'notes' => 'رواتب شهر ' . match($month) {
                        1 => 'يناير',
                        2 => 'فبراير',
                        3 => 'مارس',
                        default => ''
                    } . ' 2025',
                    'created_by' => 'System',
                    'updated_by' => 'System',
                ]);
            }
        }
    }
} 