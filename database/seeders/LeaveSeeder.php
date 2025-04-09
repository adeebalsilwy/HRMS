<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Leave::create([
            'name' => 'إجازة سنوية',
            'is_instantly' => false,
            'is_accumulative' => true,
            'discount_rate' => 0,
            'days_limit' => 30,
            'minutes_limit' => 0,
            'notes' => 'إجازة سنوية مدفوعة الراتب',
            'sequence' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Leave::create([
            'name' => 'إجازة مرضية',
            'is_instantly' => true,
            'is_accumulative' => false,
            'discount_rate' => 0,
            'days_limit' => 14,
            'minutes_limit' => 0,
            'notes' => 'إجازة مرضية مدفوعة الراتب',
            'sequence' => 2,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Leave::create([
            'name' => 'إجازة زواج',
            'is_instantly' => true,
            'is_accumulative' => false,
            'discount_rate' => 0,
            'days_limit' => 5,
            'minutes_limit' => 0,
            'notes' => 'إجازة زواج مدفوعة الراتب',
            'sequence' => 3,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Leave::create([
            'name' => 'إجازة أمومة',
            'is_instantly' => true,
            'is_accumulative' => false,
            'discount_rate' => 0,
            'days_limit' => 90,
            'minutes_limit' => 0,
            'notes' => 'إجازة أمومة مدفوعة الراتب',
            'sequence' => 4,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Leave::create([
            'name' => 'إجازة حج',
            'is_instantly' => true,
            'is_accumulative' => false,
            'discount_rate' => 0,
            'days_limit' => 15,
            'minutes_limit' => 0,
            'notes' => 'إجازة حج مدفوعة الراتب لمرة واحدة في الخدمة',
            'sequence' => 5,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Leave::create([
            'name' => 'مهمة عمل',
            'is_instantly' => true,
            'is_accumulative' => false,
            'discount_rate' => 0,
            'days_limit' => 0,
            'minutes_limit' => 480,
            'notes' => 'مهمة عمل خارج الشركة',
            'sequence' => 6,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Leave::create([
            'name' => 'خروج مبكر',
            'is_instantly' => true,
            'is_accumulative' => false,
            'discount_rate' => 0,
            'days_limit' => 0,
            'minutes_limit' => 120,
            'notes' => 'خروج مبكر بإذن رسمي',
            'sequence' => 7,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
} 