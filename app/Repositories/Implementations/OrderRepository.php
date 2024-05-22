<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function show($id)
    {
        return Order::with([
            'client', 'employee', 'product', 'product.category', 'product.feature', 'product.product_image', 'delivery'
        ])->findOrFail($id);
    }

    public function edit($id)
    {
        // TODO: Implement edit() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function store(StoreOrderRequest $request)
    {
        // TODO: Implement store() method.
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        // TODO: Implement update() method.
    }
}
