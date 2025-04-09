<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // أجهزة حاسوب
        Asset::create([
            'old_id' => 'PC-001',
            'serial_number' => 'HP202401001',
            'class' => 'Electronic',
            'status' => 'Good',
            'description' => 'حاسوب مكتبي HP ProDesk',
            'in_service' => true,
            'is_gpr' => true,
            'real_price' => 950,
            'expected_price' => 800,
            'acquisition_date' => '2023-01-15',
            'acquisition_type' => 'Directed',
            'funded_by' => 'شركة ديبو تك',
            'note' => 'مخصص للمدير التنفيذي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // حاسوب محمول
        Asset::create([
            'old_id' => 'LT-001',
            'serial_number' => 'EL202401001',
            'class' => 'Electronic',
            'status' => 'Good',
            'description' => 'حاسوب محمول HP EliteBook',
            'in_service' => true,
            'is_gpr' => true,
            'real_price' => 1200,
            'expected_price' => 1000,
            'acquisition_date' => '2023-02-10',
            'acquisition_type' => 'Directed',
            'funded_by' => 'شركة ديبو تك',
            'note' => 'مخصص لمدير تقنية المعلومات',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // هاتف ذكي
        Asset::create([
            'old_id' => 'PH-001',
            'serial_number' => 'SM202401001',
            'class' => 'Electronic',
            'status' => 'Good',
            'description' => 'هاتف ذكي سامسونج S22',
            'in_service' => true,
            'is_gpr' => true,
            'real_price' => 800,
            'expected_price' => 600,
            'acquisition_date' => '2023-03-20',
            'acquisition_type' => 'Directed',
            'funded_by' => 'شركة ديبو تك',
            'note' => 'مخصص للمدير التنفيذي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // مكتب
        Asset::create([
            'old_id' => 'DS-001',
            'serial_number' => 'DE202401001',
            'class' => 'Furniture',
            'status' => 'Good',
            'description' => 'مكتب مدير تنفيذي',
            'in_service' => true,
            'is_gpr' => true,
            'real_price' => 650,
            'expected_price' => 500,
            'acquisition_date' => '2022-05-10',
            'acquisition_type' => 'Directed',
            'funded_by' => 'شركة ديبو تك',
            'note' => 'مكتب خشبي فاخر للمدير التنفيذي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // طابعة
        Asset::create([
            'old_id' => 'PR-001',
            'serial_number' => 'PR202401001',
            'class' => 'Electronic',
            'status' => 'Good',
            'description' => 'طابعة HP LaserJet',
            'in_service' => true,
            'is_gpr' => true,
            'real_price' => 350,
            'expected_price' => 250,
            'acquisition_date' => '2023-04-05',
            'acquisition_type' => 'Directed',
            'funded_by' => 'شركة ديبو تك',
            'note' => 'طابعة مشتركة للإدارة العليا',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // سيارة
        Asset::create([
            'old_id' => 'CR-001',
            'serial_number' => 'TC202401001',
            'class' => 'Gear',
            'status' => 'Good',
            'description' => 'تويوتا كورولا',
            'in_service' => true,
            'is_gpr' => true,
            'real_price' => 25000,
            'expected_price' => 20000,
            'acquisition_date' => '2022-10-15',
            'acquisition_type' => 'Directed',
            'funded_by' => 'شركة ديبو تك',
            'note' => 'سيارة المدير التنفيذي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 