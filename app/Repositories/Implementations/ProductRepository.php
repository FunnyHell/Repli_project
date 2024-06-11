<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    public function index()
    {
        $user = auth()->user();
        if ($user) {
            $locationId = $user->location_id;
            $locationType = $user->location_type;
            $products = Product::where('deleted_at', null)
                ->with([
                    'category', 'feature', 'order', 'product_image',
                    'product_existence' => function ($query) use ($locationId, $locationType) {
                        $query->where('location_id', $locationId)->where('location_type', $locationType);
                    },
                    'supply_request' => function ($query) use ($locationId, $locationType) {
                        if ($locationType == 'warehouse') {
                            $query->where('warehouse_id', $locationId);
                        }
                    }
                ])->get();
            $categories = Category::all();
            return response()->json(compact('products', 'categories'));
        }
        return null;
    }


    public function create($data, $imagePath = null)
    {
        $data['slug'] = str_replace(' ', '-', $data['name']);
        $product = Product::create($data);
        if ($imagePath) {
            ProductImage::create(['product_id' => $product->id, 'source' => $imagePath]);
        }
        return $product;
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
        $product = Product::where('deleted_at', null)
            ->with([
                'category', 'feature', 'product_image',
                'order' => function ($query) {
                    $query->with(['employee', 'client']);
                },
                'product_existence' => function ($query) use ($locationId, $locationType) {
                    $query->where('location_id', $locationId)->where('location_type', $locationType);
                },
                'supply_request' => function ($query) use ($locationId, $locationType) {
                    if ($locationType == 'warehouse') {
                        $query->where('warehouse_id', $locationId);
                    }
                }
            ])->findOrFail($id);
        $categories = Category::all();
        return response()->json(compact('product', 'categories'));
    }

    public function edit($id)
    {
        // TODO: Implement edit() method.
    }

    public function update(array $data, $id, $imagePath = null)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        if ($imagePath) {
            ProductImage::create(['product_id' => $product->id, 'source' => $imagePath]);
        }
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
