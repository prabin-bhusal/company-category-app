<?php

namespace App\Repositories;

use App\Models\CompanyCategory;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected CompanyCategory $model;

    public function __construct(CompanyCategory $model)
    {
        $this->model = $model;
    }

    public function all($keyword = null, $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($keyword) {
            $query->where('title', 'like', '%'.$keyword.'%');
        }

        return $query->paginate($perPage);
    }

    public function find($id): Model|Collection|CompanyCategory|null
    {
        return $this->model->with('companies')->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Model
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id): bool
    {
        $category = $this->model->findOrFail($id);
        return $category->delete();
    }
}
