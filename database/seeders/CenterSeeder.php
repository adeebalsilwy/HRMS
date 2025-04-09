<?php

namespace Database\Seeders;

use App\Models\Center;
use Illuminate\Database\Seeder;

class CenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Center::create([
            'name' => 'المقر الرئيسي - صنعاء',
            'start_work_hour' => '08:00:00',
            'end_work_hour' => '16:00:00',
            'weekends' => ['الجمعة'],
            'is_active' => true,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Center::create([
            'name' => 'فرع عدن',
            'start_work_hour' => '08:00:00',
            'end_work_hour' => '16:00:00',
            'weekends' => ['الجمعة', 'السبت'],
            'is_active' => true,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Center::create([
            'name' => 'فرع الحديدة',
            'start_work_hour' => '08:30:00',
            'end_work_hour' => '16:30:00',
            'weekends' => ['الجمعة'],
            'is_active' => true,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Center::create([
            'name' => 'فرع تعز',
            'start_work_hour' => '08:00:00',
            'end_work_hour' => '16:00:00',
            'weekends' => ['الجمعة'],
            'is_active' => true,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
}
