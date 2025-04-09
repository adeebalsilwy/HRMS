<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. البنية التنظيمية الأساسية (الجداول المستقلة أولا)
        // $this->call(ContractsSeeder::class);
        // $this->call(CenterSeeder::class);
        // $this->call(DepartmentSeeder::class);
        // $this->call(PositionSeeder::class);
        
        // // 2. بيانات الموظفين (تعتمد على العقود)
        // $this->call(EmployeesSeeder::class);
        
        // // 3. المستخدمين والأدوار (تعتمد على الموظفين)
        // $this->call(AdminUserSeeder::class);
        
        // // 4. سجلات الجداول الزمنية (تعتمد على الموظفين والمراكز والأقسام والمناصب)
        // $this->call(TimelineSeeder::class);
        
        // // 5. بيانات الإجازات والعطلات
        // $this->call(LeaveSeeder::class);
        // $this->call(HolidaySeeder::class);
        // $this->call(CenterHolidaySeeder::class);
        
        // // 6. بيانات الأصول (تعتمد على الموظفين)
        // $this->call(CategorySeeder::class);
        // $this->call(SubCategorySeeder::class);
        // $this->call(CategorySubCategorySeeder::class);
        // $this->call(AssetSeeder::class);
        // $this->call(TransitionSeeder::class);

        // // 7. بيانات الحضور والإجازات للموظفين 2025
        // $this->call(FingerprintSeeder::class);
        // $this->call(EmployeeLeaveSeeder::class);

        // 8. إعدادات النظام إذا كانت موجودة
        if (file_exists('database/seeders/SettingsSeeder.php')) {
            $this->call([
                SettingsSeeder::class,
            ]);
        }

        // 8a. بيانات الرواتب
        $this->call(SalarySeeder::class);

        // 9. إنشاء الأدوار وتعيينها للمستخدمين
        // إنشاء الأدوار
        // $adminRole = Role::create(['name' => 'Admin']);
        // Role::create(['name' => 'HR']);
        // Role::create(['name' => 'AM']);
        // Role::create(['name' => 'CC']);
        // Role::create(['name' => 'CR']);

        // // تعيين دور للمستخدم الإداري
        // $admin = User::find(1);
        // $admin->assignRole($adminRole);
    }
}
