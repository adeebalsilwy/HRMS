<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'الإدارة العليا',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'تقنية المعلومات',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'الموارد البشرية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'المبيعات والتسويق',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'خدمة العملاء',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'المحاسبة والمالية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'البحث والتطوير',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Department::create([
            'name' => 'الإنتاج',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
}
