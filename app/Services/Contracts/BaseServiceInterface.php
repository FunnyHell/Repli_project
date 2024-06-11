<?php

namespace App\Services\Contracts;

interface BaseServiceInterface
{
    public function create(array $data, $imagePath = null);
    public function update(array $data, $id, $imagePath = null);
    public function delete($id);
    public function all();
    public function find($id);
}
