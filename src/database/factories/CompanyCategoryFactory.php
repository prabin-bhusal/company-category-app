<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompanyCategory;

class CompanyCategoryFactory extends Factory
{
    protected $model = CompanyCategory::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->word(),
        ];
    }
}
