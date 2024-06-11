<?php

namespace App\Repositories\Contracts;
interface BaseRepositoryInterface
{
    public function index();
    public function create($data, $imagePath = null);
    public function show($id);
    public function edit($id);
    public function update(array $data, $id, $imagePath = null);
    public function destroy($id);
}
