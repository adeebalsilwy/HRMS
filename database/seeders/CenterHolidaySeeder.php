<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CenterHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تطبيق جميع العطلات على جميع المراكز
        for ($center = 1; $center <= 4; $center++) {
            for ($holiday = 1; $holiday <= 7; $holiday++) {
                DB::table('center_holiday')->insert([
                    'center_id' => $center, // رقم المركز
                    'holiday_id' => $holiday, // رقم العطلة
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 