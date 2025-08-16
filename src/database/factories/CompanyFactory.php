<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\CompanyCategory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'category_id' => CompanyCategory::factory(),
            'title' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->boolean(),
            'image' => null,
        ];
    }
}
