<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    public function index()
    {
        $user = auth()->user();
        if ($user) {
            $locationId = $user->location_id;
            $locationType = $user->location_type;
            return Product::with(['category', 'feature', 'order',
                'product_existence' => function ($query) use ($locationId, $locationType) {
                $query->where('location_id', $locationId)->where('location_type', $locationType);
            }, 'supply_request'=>function ($query) use ($locationId, $locationType) {
                if ($locationType == 'warehouse') {
                    $query->where('warehouse_id', $locationId);
                }
            }])->get();
        }
        return null;
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
