<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyRepository implements CompanyRepositoryInterface
{
    protected Company $model;

    public function __construct(Company $model)
    {
        $this->model = $model;
    }

    public function all($perPage = 10): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->model->with('category')->paginate($perPage);
    }

    public function find($id): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|Company|null
    {
        return $this->model->with('category')->findOrFail($id);
    }

    public function create(array $data): Company
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Company
    {
        $company = $this->model->findOrFail($id);
        $company->update($data);
        return $company;
    }

    public function delete($id): bool
    {
        $company = $this->model->findOrFail($id);
        return $company->delete();
    }
}
