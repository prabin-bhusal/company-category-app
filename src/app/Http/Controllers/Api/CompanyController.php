<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    protected CompanyRepository $repository;

    public function __construct(CompanyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $companies = $this->repository->all();
        return CompanyResource::collection($companies)
            ->additional([
                'meta' => [
                    'current_page' => $companies->currentPage(),
                    'last_page' => $companies->lastPage(),
                    'per_page' => $companies->perPage(),
                    'total' => $companies->total(),
                ],
            ]);
    }

    public function show($id)
    {
        $company = $this->repository->find($id);
        return new CompanyResource($company);
    }

    public function store(StoreCompanyRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('companies', 'public');
            }

            $company = $this->repository->create($data);
            DB::commit();
            return new CompanyResource($company);
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json(["Something went wrong" => $exception->getMessage()], 500);
        }
    }

    public function update(UpdateCompanyRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            $company = $this->repository->find($id);

            if ($request->hasFile('image')) {
                if ($company->image && Storage::disk('public')->exists($company->image)) {
                    Storage::disk('public')->delete($company->image);
                }
                $data['image'] = $request->file('image')->store('companies', 'public');
            }

            $company = $this->repository->update($id, $data);
            DB::commit();
            return new CompanyResource($company);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return response()->json(["Something went wrong" => $exception->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try{
            DB::beginTransaction();
            $company = $this->repository->find($id);

            if ($company->image && Storage::disk('public')->exists($company->image)) {
                Storage::disk('public')->delete($company->image);
            }

            $isDelete = $this->repository->delete($id);
            if ($isDelete) return response()->json(['message' => 'Company deleted successfully'], 200);

            DB::commit();
            return response()->json(['message' => 'Company delete failed.'], 500);
        }catch(\Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
