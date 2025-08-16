<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\CompanyCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_companies(): void
    {
        Company::factory()->count(5)->create();

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->getJson('/api/v1/company');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id','title','description','status','category','image','created_at','updated_at']
                ],
                'meta' => ['current_page','last_page','per_page','total']
            ]);
    }

    public function test_store_company(): void
    {
        $category = CompanyCategory::factory()->create();
        $data = [
            'title' => 'New Company',
            'description' => 'Test description',
            'status' => true,
            'category_id' => $category->id,
        ];

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->postJson('/api/v1/company', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Company']);

        $this->assertDatabaseHas('company', ['title' => 'New Company']);
    }

    public function test_show_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->getJson("/api/v1/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $company->id]);
    }

    public function test_delete_company(): void
    {
        $company = Company::factory()->create();

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->deleteJson("/api/v1/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Company deleted successfully']);

        $this->assertDatabaseMissing('company', ['id' => $company->id]);
    }
}
