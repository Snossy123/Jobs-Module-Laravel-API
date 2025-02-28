<?php

namespace Modules\Jobs\Database\Seeders;

use Illuminate\Database\Seeder;

class JobEmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('job_employment_types')->delete();
        \DB::table('job_employment_types')->insert([
            ['type' => 'Full-time', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Part-time', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Contract', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Temporary', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Internship', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Volunteer', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Remote', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Commission', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'Working-hours', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
