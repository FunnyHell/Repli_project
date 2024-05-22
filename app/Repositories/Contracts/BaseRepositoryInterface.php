<?php

namespace App\Repositories\Contracts;
interface BaseRepositoryInterface
{
    public function index();
    public function create();
    public function show($id);
    public function edit($id);
    public function destroy($id);
}
