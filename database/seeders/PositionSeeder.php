<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::create([
            'name' => 'المدير التنفيذي',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'نائب المدير التنفيذي',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مدير تقنية المعلومات',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مهندس برمجيات',
            'vacancies_count' => 5,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مطور واجهات أمامية',
            'vacancies_count' => 3,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مطور خلفيات',
            'vacancies_count' => 3,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مدير الموارد البشرية',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'أخصائي موارد بشرية',
            'vacancies_count' => 2,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مدير المبيعات',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مندوب مبيعات',
            'vacancies_count' => 5,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مدير المالية',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'محاسب',
            'vacancies_count' => 3,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'مدير خدمة العملاء',
            'vacancies_count' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Position::create([
            'name' => 'ممثل خدمة العملاء',
            'vacancies_count' => 4,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
}
