<?php

namespace Modules\Jobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCompanyIndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_company_industries')->delete();
        $company_industries = array(
            [
                'name' => 'Ambulances & Emergency Vehicles and Aircraft',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Anesthesiology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cardiology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cardiovascular surgery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Clinical, Biomedical Eng',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dentistry',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Dermatology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Emergency',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ENT',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Epidemiology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gastroenterology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Healthcare Information Technology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Hematology & Serology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Hospital management',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Intensive Care Unit ICU',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Internal Medicine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Intervention',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Laboratory',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Microbiology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Neonatal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nephrology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Neurology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nursing Services',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Obstetrics & Gynecology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Oncology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Ophthalmology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Orthopedics',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pathology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pediatric',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Physical Medicine & Rehabilitation (PMR)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Plastic Surgery & Aesthetics',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Psychiatry',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Radiology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Radiotherapy',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Respiratory',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Surgical',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sonographer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Toxicology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Urology & Urogenital surgery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vascular & vascular surgery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Veterinary',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Abdominal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pain Management',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nuclear Medicine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nutrition',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fetal medicine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Occupational medicine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'General practitioner',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Family medicine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Allergy & Clinical Immunology',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pharmacy',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Infectious diseases',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Clinical Pharmacist',
                'created_at' => now(),
                'updated_at' => now()
            ]
            );
        DB::table('job_company_industries')->insert($company_industries);
    }
}
