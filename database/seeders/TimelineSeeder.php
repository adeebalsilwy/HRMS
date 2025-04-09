<?php

namespace Database\Seeders;

use App\Models\Timeline;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // محمد أحمد العامري - المدير التنفيذي - المقر الرئيسي (صنعاء)
        Timeline::create([
            'employee_id' => 1, // الموظف الأول
            'center_id' => 1,  // المقر الرئيسي - صنعاء
            'department_id' => 1,  // الإدارة العليا
            'position_id' => 1,  // المدير التنفيذي
            'start_date' => '2020-01-01',
            'end_date' => null,
            'notes' => 'منصب المدير التنفيذي لشركة ديبو تك',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // عبدالله علي السقاف - مدير تقنية المعلومات - المقر الرئيسي (صنعاء)
        Timeline::create([
            'employee_id' => 2, // الموظف الثاني
            'center_id' => 1,  // المقر الرئيسي - صنعاء
            'department_id' => 2,  // تقنية المعلومات
            'position_id' => 3,  // مدير تقنية المعلومات
            'start_date' => '2020-03-15',
            'end_date' => null,
            'notes' => 'مدير قسم تقنية المعلومات',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // نور محمد الهمداني - مدير الموارد البشرية - المقر الرئيسي (صنعاء)
        Timeline::create([
            'employee_id' => 3, // الموظف الثالث
            'center_id' => 1,  // المقر الرئيسي - صنعاء
            'department_id' => 3,  // الموارد البشرية
            'position_id' => 7,  // مدير الموارد البشرية
            'start_date' => '2021-02-10',
            'end_date' => null,
            'notes' => 'مديرة قسم الموارد البشرية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // سعيد عبدالله العولقي - مطور واجهات أمامية - فرع عدن
        Timeline::create([
            'employee_id' => 4, // الموظف الرابع
            'center_id' => 2,  // فرع عدن
            'department_id' => 2,  // تقنية المعلومات
            'position_id' => 5,  // مطور واجهات أمامية
            'start_date' => '2022-01-20',
            'end_date' => null,
            'notes' => 'مطور واجهات أمامية للتطبيقات',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        // فاطمة خالد الشميري - محاسب - المقر الرئيسي (صنعاء)
        Timeline::create([
            'employee_id' => 5, // الموظف الخامس
            'center_id' => 1,  // المقر الرئيسي - صنعاء
            'department_id' => 6,  // المحاسبة والمالية
            'position_id' => 12,  // محاسب
            'start_date' => '2022-05-15',
            'end_date' => null,
            'notes' => 'محاسبة في قسم المالية',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
}
