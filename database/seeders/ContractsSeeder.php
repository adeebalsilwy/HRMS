<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contract::create([
            'name' => 'عقد دوام كامل',
            'work_rate' => 100,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Contract::create([
            'name' => 'عقد دوام جزئي',
            'work_rate' => 60,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Contract::create([
            'name' => 'عقد مؤقت',
            'work_rate' => 100,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Contract::create([
            'name' => 'عقد بالمشروع',
            'work_rate' => 100,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);

        Contract::create([
            'name' => 'عقد تدريب',
            'work_rate' => 50,
            'created_by' => 'System',
            'updated_by' => 'System',
        ]);
    }
}
