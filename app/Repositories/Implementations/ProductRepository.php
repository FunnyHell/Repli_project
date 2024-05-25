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
            return Product::where('deleted_at', null)->with(['category', 'feature', 'order', 'product_image',
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

    public function create($data)
    {
        $data['slug'] = str_replace(' ', '-', $data['name']);
        return Product::create($data);
    }

    public function store(StoreProductRequest $request)
    {
        // TODO: Implement store() method.
    }

    public function show($id)
    {
        $user = auth()->user();
        $locationId = $user->location_id;
        $locationType = $user->location_type;
        return Product::where('deleted_at', null)->with(['category', 'feature', 'product_image',
            'order' => function ($query) {
                $query->with(['employee', 'client']);
            },
            'product_existence' => function ($query) use ($locationId, $locationType) {
                $query->where('location_id', $locationId)->where('location_type', $locationType);
            }, 'supply_request'=>function ($query) use ($locationId, $locationType) {
                if ($locationType == 'warehouse') {
                    $query->where('warehouse_id', $locationId);
                }
            }])->findOrFail($id);
    }

    public function edit($id)
    {
        // TODO: Implement edit() method.
    }

    public function update(array $data, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        if (Product::find($id)) {
            if(Product::where('id', $id)->update(['deleted_at' => now()])) {
                return true;
            }
            return false;
        }
    }

}
