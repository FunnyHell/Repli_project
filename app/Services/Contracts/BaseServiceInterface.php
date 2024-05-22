<?php

namespace App\Services\Contracts;

interface BaseServiceInterface
{
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function all();
    public function find($id);
}
