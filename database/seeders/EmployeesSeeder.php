<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'contract_id' => 1, // عقد دوام كامل
            'first_name' => 'محمد',
            'father_name' => 'أحمد',
            'last_name' => 'العامري',
            'mother_name' => 'فاطمة السقاف',
            'birth_and_place' => 'صنعاء، 1980-05-15',
            'national_number' => '12345678901',
            'mobile_number' => '773456789',
            'degree' => 'بكالوريوس هندسة برمجيات',
            'gender' => 1, // ذكر
            'address' => 'صنعاء - شارع حدة',
            'notes' => 'المدير التنفيذي لشركة ديبو تك',
            'max_leave_allowed' => 30,
            'delay_counter' => '00:00:00',
            'hourly_counter' => '00:00:00',
            'is_active' => true,
            'profile_photo_path' => 'profile-photos/.default-photo.jpg',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Employee::create([
            'contract_id' => 1, // عقد دوام كامل
            'first_name' => 'عبدالله',
            'father_name' => 'علي',
            'last_name' => 'السقاف',
            'mother_name' => 'سميرة الأغبري',
            'birth_and_place' => 'تعز، 1985-08-22',
            'national_number' => '23456789012',
            'mobile_number' => '773123456',
            'degree' => 'ماجستير إدارة أعمال',
            'gender' => 1, // ذكر
            'address' => 'صنعاء - شارع الستين',
            'notes' => 'مدير قسم تقنية المعلومات',
            'max_leave_allowed' => 30,
            'delay_counter' => '00:00:00',
            'hourly_counter' => '00:00:00',
            'is_active' => true,
            'profile_photo_path' => 'profile-photos/.default-photo.jpg',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Employee::create([
            'contract_id' => 1, // عقد دوام كامل
            'first_name' => 'نور',
            'father_name' => 'محمد',
            'last_name' => 'الهمداني',
            'mother_name' => 'هدى العنسي',
            'birth_and_place' => 'صنعاء، 1990-03-10',
            'national_number' => '34567890123',
            'mobile_number' => '712345678',
            'degree' => 'بكالوريوس موارد بشرية',
            'gender' => 0, // أنثى
            'address' => 'صنعاء - حي الجراف',
            'notes' => 'مديرة قسم الموارد البشرية',
            'max_leave_allowed' => 30,
            'delay_counter' => '00:00:00',
            'hourly_counter' => '00:00:00',
            'is_active' => true,
            'profile_photo_path' => 'profile-photos/.default-photo.jpg',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Employee::create([
            'contract_id' => 2, // عقد دوام جزئي
            'first_name' => 'سعيد',
            'father_name' => 'عبدالله',
            'last_name' => 'العولقي',
            'mother_name' => 'مريم القحطاني',
            'birth_and_place' => 'عدن، 1988-11-17',
            'national_number' => '45678901234',
            'mobile_number' => '734567890',
            'degree' => 'بكالوريوس علوم حاسوب',
            'gender' => 1, // ذكر
            'address' => 'عدن - المنصورة',
            'notes' => 'مطور واجهات أمامية في فرع عدن',
            'max_leave_allowed' => 20,
            'delay_counter' => '00:00:00',
            'hourly_counter' => '00:00:00',
            'is_active' => true,
            'profile_photo_path' => 'profile-photos/.default-photo.jpg',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

            Employee::create([
            'contract_id' => 1, // عقد دوام كامل
            'first_name' => 'فاطمة',
            'father_name' => 'خالد',
            'last_name' => 'الشميري',
            'mother_name' => 'زينب الصلوي',
            'birth_and_place' => 'الحديدة، 1992-04-05',
            'national_number' => '56789012345',
            'mobile_number' => '712345698',
            'degree' => 'بكالوريوس محاسبة',
            'gender' => 0, // أنثى
            'address' => 'صنعاء - شارع تعز',
            'notes' => 'محاسبة في قسم المالية',
            'max_leave_allowed' => 30,
                'delay_counter' => '00:00:00',
                'hourly_counter' => '00:00:00',
            'is_active' => true,
                'profile_photo_path' => 'profile-photos/.default-photo.jpg',
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
}
