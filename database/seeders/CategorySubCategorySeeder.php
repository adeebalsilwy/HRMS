<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ربط أجهزة الحاسوب بالفئات الفرعية
        DB::table('category_sub_category')->insert([
            'category_id' => 1, // أجهزة الحاسوب
            'sub_category_id' => 1, // حاسوب مكتبي
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 1, // أجهزة الحاسوب
            'sub_category_id' => 2, // حاسوب محمول
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 1, // أجهزة الحاسوب
            'sub_category_id' => 3, // محطة عمل
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // ربط الأجهزة المحمولة بالفئات الفرعية
        DB::table('category_sub_category')->insert([
            'category_id' => 2, // الأجهزة المحمولة
            'sub_category_id' => 4, // هاتف ذكي
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 2, // الأجهزة المحمولة
            'sub_category_id' => 5, // جهاز لوحي
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // ربط الأثاث المكتبي بالفئات الفرعية
        DB::table('category_sub_category')->insert([
            'category_id' => 3, // الأثاث المكتبي
            'sub_category_id' => 6, // مكتب
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 3, // الأثاث المكتبي
            'sub_category_id' => 7, // كرسي
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 3, // الأثاث المكتبي
            'sub_category_id' => 8, // خزانة
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // ربط المعدات الكهربائية بالفئات الفرعية
        DB::table('category_sub_category')->insert([
            'category_id' => 4, // المعدات الكهربائية
            'sub_category_id' => 9, // طابعة
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 4, // المعدات الكهربائية
            'sub_category_id' => 10, // ماسح ضوئي
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 4, // المعدات الكهربائية
            'sub_category_id' => 11, // جهاز عرض
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // ربط السيارات بالفئات الفرعية
        DB::table('category_sub_category')->insert([
            'category_id' => 5, // السيارات
            'sub_category_id' => 12, // سيارة صالون
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        DB::table('category_sub_category')->insert([
            'category_id' => 5, // السيارات
            'sub_category_id' => 13, // شاحنة صغيرة
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 