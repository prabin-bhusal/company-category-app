<?php

namespace App\Repositories\Interfaces;

interface CompanyRepositoryInterface
{
    public function all($perPage = 10);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
