<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function store(StoreOrderRequest $request);
    public function update(UpdateOrderRequest $request, Order $order);
}
