<?php

namespace Database\Seeders;

use App\Models\Transition;
use Illuminate\Database\Seeder;

class TransitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تسليم حاسوب مكتبي للمدير التنفيذي    
        Transition::create([
            'asset_id' => 1, // حاسوب مكتبي HP ProDesk (السجل الأول)
            'employee_id' => 1, // محمد أحمد العامري - المدير التنفيذي
            'handed_date' => '2023-01-20',
            'return_date' => null,
            'center_document_number' => 'TRNS-2023-001',
            'reason' => 'تسليم أصل جديد',
            'note' => 'تم تسليم الحاسوب المكتبي للمدير التنفيذي لاستخدامه في العمل اليومي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // تسليم حاسوب محمول لمدير تقنية المعلومات
        Transition::create([
            'asset_id' => 2, // حاسوب محمول HP EliteBook (السجل الثاني)
            'employee_id' => 2, // عبدالله علي السقاف - مدير تقنية المعلومات
            'handed_date' => '2023-02-15',
            'return_date' => null,
            'center_document_number' => 'TRNS-2023-002',
            'reason' => 'تسليم أصل جديد',
            'note' => 'تم تسليم الحاسوب المحمول لمدير تقنية المعلومات لاستخدامه في العمل اليومي والاجتماعات',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // تسليم هاتف ذكي للمدير التنفيذي
        Transition::create([
            'asset_id' => 3, // هاتف ذكي سامسونج S22 (السجل الثالث)
            'employee_id' => 1, // محمد أحمد العامري - المدير التنفيذي
            'handed_date' => '2023-03-25',
            'return_date' => null,
            'center_document_number' => 'TRNS-2023-003',
            'reason' => 'تسليم أصل جديد',
            'note' => 'تم تسليم الهاتف الذكي للمدير التنفيذي لاستخدامه في التواصل مع العملاء والموظفين',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // تسليم المكتب للمدير التنفيذي
        Transition::create([
            'asset_id' => 4, // مكتب مدير تنفيذي (السجل الرابع)
            'employee_id' => 1, // محمد أحمد العامري - المدير التنفيذي
            'handed_date' => '2022-05-15',
            'return_date' => null,
            'center_document_number' => 'TRNS-2022-001',
            'reason' => 'تسليم أصل جديد',
            'note' => 'تم تجهيز مكتب المدير التنفيذي',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 