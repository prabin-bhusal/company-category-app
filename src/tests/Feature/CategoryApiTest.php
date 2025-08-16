<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\CompanyCategory;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_categories_with_pagination(): void
    {
        CompanyCategory::factory()->count(25)->create();

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->getJson('/api/v1/category');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id','title','created_at','updated_at']
                ],
                'meta' => ['current_page','last_page','per_page','total']
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    public function test_index_categories_with_keyword(): void
    {
        CompanyCategory::factory()->create(['title' => 'UniqueCategoryName']);
        CompanyCategory::factory()->count(5)->create();

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->getJson('/api/v1/category?keyword=UniqueCategoryName');

        $response->assertStatus(200);
        $this->assertEquals('UniqueCategoryName', $response->json('data')[0]['title']);
    }

    public function test_store_category(): void
    {
        $data = ['title' => 'NewCategory'];

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->postJson('/api/v1/category', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'NewCategory']);

        $this->assertDatabaseHas('company_category', ['title' => 'NewCategory']);
    }

    public function test_update_category(): void
    {
        $category = CompanyCategory::factory()->create();
        $data = ['title' => 'UpdatedCategory'];

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->putJson("/api/v1/category/{$category->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'UpdatedCategory']);

        $this->assertDatabaseHas('company_category', ['title' => 'UpdatedCategory']);
    }

    public function test_delete_category(): void
    {
        $category = CompanyCategory::factory()->create();

        $response = $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])->deleteJson("/api/v1/category/{$category->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Category deleted successfully']);

        $this->assertDatabaseMissing('company_category', ['id' => $category->id]);
    }
}
