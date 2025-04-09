<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Holiday::create([
            'name' => 'عيد الفطر',
            'from_date' => '2024-04-09',
            'to_date' => '2024-04-13',
            'note' => 'عطلة رسمية بمناسبة عيد الفطر المبارك',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Holiday::create([
            'name' => 'عيد الأضحى',
            'from_date' => '2024-06-16',
            'to_date' => '2024-06-20',
            'note' => 'عطلة رسمية بمناسبة عيد الأضحى المبارك',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Holiday::create([
            'name' => 'عيد الثورة اليمنية ٢٦ سبتمبر',
            'from_date' => '2024-09-26',
            'to_date' => '2024-09-26',
            'note' => 'عطلة رسمية بمناسبة عيد الثورة اليمنية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Holiday::create([
            'name' => 'عيد الثورة اليمنية ١٤ أكتوبر',
            'from_date' => '2024-10-14',
            'to_date' => '2024-10-14',
            'note' => 'عطلة رسمية بمناسبة عيد الثورة اليمنية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Holiday::create([
            'name' => 'عيد الاستقلال اليمني ٣٠ نوفمبر',
            'from_date' => '2024-11-30',
            'to_date' => '2024-11-30',
            'note' => 'عطلة رسمية بمناسبة عيد الاستقلال',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Holiday::create([
            'name' => 'رأس السنة الهجرية',
            'from_date' => '2024-07-07',
            'to_date' => '2024-07-07',
            'note' => 'عطلة رسمية بمناسبة رأس السنة الهجرية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Holiday::create([
            'name' => 'المولد النبوي الشريف',
            'from_date' => '2024-09-15',
            'to_date' => '2024-09-15',
            'note' => 'عطلة رسمية بمناسبة المولد النبوي الشريف',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 