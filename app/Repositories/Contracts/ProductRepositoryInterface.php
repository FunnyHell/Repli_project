<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function store(StoreProductRequest $request);
    public function update(UpdateProductRequest $request, Product $product);
}
