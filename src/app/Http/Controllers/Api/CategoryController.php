<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $categories = $this->repository->all($request->keyword);
        return CategoryResource::collection($categories);
    }

    public function show($id)
    {
        $category = $this->repository->find($id);
        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->repository->create($request->validated());
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->repository->update($id, $request->validated());
        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        $isDelete = $this->repository->delete($id);
        if($isDelete) return response()->json(['message' => 'Category deleted successfully'], 200);

        return response()->json(['message' => 'Category delete failed.'], 500);

    }
}
