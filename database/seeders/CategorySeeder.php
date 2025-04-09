<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'أجهزة الحاسوب',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Category::create([
            'name' => 'الأجهزة المحمولة',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Category::create([
            'name' => 'الأثاث المكتبي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Category::create([
            'name' => 'المعدات الكهربائية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Category::create([
            'name' => 'السيارات',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 