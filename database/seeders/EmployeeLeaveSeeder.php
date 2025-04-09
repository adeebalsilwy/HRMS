<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $leaves = Leave::all();
        
        foreach ($employees as $employee) {
            // إجازة سنوية لكل موظف (مرة واحدة في الفترة)
            $annualLeave = $leaves->where('name', 'إجازة سنوية')->first();
            if ($annualLeave) {
                $randomMonth = rand(1, 3); // شهر عشوائي من 1 إلى 3
                $randomDay = rand(1, 28); // يوم عشوائي (تجنب نهاية الشهر)
                $startDate = Carbon::create(2025, $randomMonth, $randomDay);
                $endDate = $startDate->copy()->addDays(rand(1, 5)); // إجازة من 1 إلى 5 أيام
                
                DB::table('employee_leave')->insert([
                    'employee_id' => $employee->id,
                    'leave_id' => $annualLeave->id,
                    'from_date' => $startDate->format('Y-m-d'),
                    'to_date' => $endDate->format('Y-m-d'),
                    'start_at' => null,
                    'end_at' => null,
                    'note' => 'إجازة سنوية',
                    'is_authorized' => 1,
                    'is_checked' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // إجازة مرضية لبعض الموظفين (30% احتمالية)
            if (rand(1, 100) <= 30) {
                $sickLeave = $leaves->where('name', 'إجازة مرضية')->first();
                if ($sickLeave) {
                    $randomMonth = rand(1, 3);
                    $randomDay = rand(1, 28);
                    $startDate = Carbon::create(2025, $randomMonth, $randomDay);
                    $endDate = $startDate->copy()->addDays(rand(1, 3)); // إجازة مرضية من 1 إلى 3 أيام
                    
                    DB::table('employee_leave')->insert([
                        'employee_id' => $employee->id,
                        'leave_id' => $sickLeave->id,
                        'from_date' => $startDate->format('Y-m-d'),
                        'to_date' => $endDate->format('Y-m-d'),
                        'start_at' => null,
                        'end_at' => null,
                        'note' => 'إجازة مرضية',
                        'is_authorized' => 1,
                        'is_checked' => 0,
                        'created_by' => 'System',
                        'updated_by' => 'System',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // مهام عمل لبعض الموظفين (50% احتمالية)
            if (rand(1, 100) <= 50) {
                $taskLeave = $leaves->where('name', 'مهمة عمل')->first();
                if ($taskLeave) {
                    // مهمة عمل واحدة
                    $randomMonth = rand(1, 3);
                    $randomDay = rand(1, 28);
                    $taskDate = Carbon::create(2025, $randomMonth, $randomDay);
                    
                    // وقت للمهمة (ساعتين تقريباً)
                    $startHour = rand(9, 13); // من الساعة 9 صباحاً إلى 1 ظهراً
                    $startTime = Carbon::create(2025, $randomMonth, $randomDay, $startHour, 0, 0);
                    $endTime = $startTime->copy()->addHours(2);
                    
                    DB::table('employee_leave')->insert([
                        'employee_id' => $employee->id,
                        'leave_id' => $taskLeave->id,
                        'from_date' => $taskDate->format('Y-m-d'),
                        'to_date' => $taskDate->format('Y-m-d'),
                        'start_at' => $startTime->format('H:i:s'),
                        'end_at' => $endTime->format('H:i:s'),
                        'note' => 'مهمة عمل خارج المكتب',
                        'is_authorized' => 1,
                        'is_checked' => 0,
                        'created_by' => 'System',
                        'updated_by' => 'System',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // خروج مبكر لبعض الموظفين (40% احتمالية)
            if (rand(1, 100) <= 40) {
                $earlyLeave = $leaves->where('name', 'خروج مبكر')->first();
                if ($earlyLeave) {
                    $randomMonth = rand(1, 3);
                    $randomDay = rand(1, 28);
                    $leaveDate = Carbon::create(2025, $randomMonth, $randomDay);
                    
                    // خروج مبكر (ساعة أو ساعتين قبل نهاية الدوام)
                    $startTime = Carbon::create(2025, $randomMonth, $randomDay, 14, 0, 0); // الساعة 2 ظهراً
                    $endTime = Carbon::create(2025, $randomMonth, $randomDay, 16, 0, 0); // الساعة 4 عصراً (نهاية الدوام)
                    
                    DB::table('employee_leave')->insert([
                        'employee_id' => $employee->id,
                        'leave_id' => $earlyLeave->id,
                        'from_date' => $leaveDate->format('Y-m-d'),
                        'to_date' => $leaveDate->format('Y-m-d'),
                        'start_at' => $startTime->format('H:i:s'),
                        'end_at' => $endTime->format('H:i:s'),
                        'note' => 'خروج مبكر لظرف شخصي',
                        'is_authorized' => 1,
                        'is_checked' => 0,
                        'created_by' => 'System',
                        'updated_by' => 'System',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
