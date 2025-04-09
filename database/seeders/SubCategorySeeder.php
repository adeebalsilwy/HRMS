<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الفئات الفرعية لأجهزة الحاسوب
        SubCategory::create([
            'category_id' => 1, // أجهزة الحاسوب
            'name' => 'حاسوب مكتبي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 1, // أجهزة الحاسوب
            'name' => 'حاسوب محمول',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 1, // أجهزة الحاسوب
            'name' => 'محطة عمل',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // الفئات الفرعية للأجهزة المحمولة
        SubCategory::create([
            'category_id' => 2, // الأجهزة المحمولة
            'name' => 'هاتف ذكي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 2, // الأجهزة المحمولة
            'name' => 'جهاز لوحي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // الفئات الفرعية للأثاث المكتبي
        SubCategory::create([
            'category_id' => 3, // الأثاث المكتبي
            'name' => 'مكتب',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 3, // الأثاث المكتبي
            'name' => 'كرسي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 3, // الأثاث المكتبي
            'name' => 'خزانة',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // الفئات الفرعية للمعدات الكهربائية
        SubCategory::create([
            'category_id' => 4, // المعدات الكهربائية
            'name' => 'طابعة',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 4, // المعدات الكهربائية
            'name' => 'ماسح ضوئي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 4, // المعدات الكهربائية
            'name' => 'جهاز عرض',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // الفئات الفرعية للسيارات
        SubCategory::create([
            'category_id' => 5, // السيارات
            'name' => 'سيارة صالون',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        SubCategory::create([
            'category_id' => 5, // السيارات
            'name' => 'شاحنة صغيرة',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 