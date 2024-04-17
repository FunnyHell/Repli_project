<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function index();
    public function create();
    public function store(StoreProductRequest $request);
    public function show(Product $product);
    public function edit(Product $product);
    public function update(UpdateProductRequest $request, Product $product);
    public function destroy(Product $product);
}
