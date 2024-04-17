<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    public function index(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Product::with(['category', 'feature', 'order', 'product_existence', 'supply_request'])->get();
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function store(StoreProductRequest $request)
    {
        // TODO: Implement store() method.
    }

    public function show(Product $product)
    {
        // TODO: Implement show() method.
    }

    public function edit(Product $product)
    {
        // TODO: Implement edit() method.
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        // TODO: Implement update() method.
    }

    public function destroy(Product $product)
    {
        // TODO: Implement destroy() method.
    }
}
