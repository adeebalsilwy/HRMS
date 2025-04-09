<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Fingerprint;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FingerprintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 3, 31);
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Skip Friday (day of week = 5)
            if ($currentDate->dayOfWeek !== 5) {
                foreach ($employees as $employee) {
                    // Random check-in time between 7:45 AM and 8:15 AM
                    $randomMinutes = rand(-15, 15);
                    $checkInTime = Carbon::create($currentDate->year, $currentDate->month, $currentDate->day, 8, 0, 0)
                        ->addMinutes($randomMinutes);
                    
                    // Random check-out time between 3:45 PM and 4:15 PM
                    $randomMinutes = rand(-15, 15);
                    $checkOutTime = Carbon::create($currentDate->year, $currentDate->month, $currentDate->day, 16, 0, 0)
                        ->addMinutes($randomMinutes);
                    
                    // Random occasional absences (5% chance)
                    if (rand(1, 100) > 95) {
                        // Employee absent that day
                        Fingerprint::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'log' => null,
                            'check_in' => null,
                            'check_out' => null,
                            'is_checked' => 0,
                            'excuse' => null,
                            'created_by' => 'System',
                            'updated_by' => 'System',
                        ]);
                    } 
                    // Random occasional half day (10% chance)
                    else if (rand(1, 100) > 90) {
                        // Employee only checked in
                        Fingerprint::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'log' => $checkInTime->format('H:i:s'),
                            'check_in' => $checkInTime->format('H:i:s'),
                            'check_out' => null,
                            'is_checked' => 0,
                            'excuse' => null,
                            'created_by' => 'System',
                            'updated_by' => 'System',
                        ]);
                    } else {
                        // Normal day with check-in and check-out
                        Fingerprint::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'log' => $checkInTime->format('H:i:s') . ' - ' . $checkOutTime->format('H:i:s'),
                            'check_in' => $checkInTime->format('H:i:s'),
                            'check_out' => $checkOutTime->format('H:i:s'),
                            'is_checked' => 0,
                            'excuse' => null,
                            'created_by' => 'System',
                            'updated_by' => 'System',
                        ]);
                    }
                }
            }
            
            $currentDate->addDay();
        }
    }
}
