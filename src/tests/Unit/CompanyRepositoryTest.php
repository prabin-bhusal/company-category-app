<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\CompanyRepository;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CompanyRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CompanyRepository(new Company());
    }

    public function test_can_create_company(): void
    {
        $data = Company::factory()->make()->toArray();
        $company = $this->repo->create($data);

        $this->assertDatabaseHas('company', ['id' => $company->id]);
    }

    public function test_can_update_company(): void
    {
        $company = Company::factory()->create();
        $data = ['title' => 'Updated Title'];

        $updated = $this->repo->update($company->id, $data);
        $this->assertEquals('Updated Title', $updated->title);
    }

    public function test_can_delete_company(): void
    {
        $company = Company::factory()->create();
        $this->repo->delete($company->id);

        $this->assertDatabaseMissing('company', ['id' => $company->id]);
    }
}
