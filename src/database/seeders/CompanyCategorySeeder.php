<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyCategory;

class CompanyCategorySeeder extends Seeder
{
    public function run(): void
    {
        CompanyCategory::factory()->count(10)->create();
    }
}
