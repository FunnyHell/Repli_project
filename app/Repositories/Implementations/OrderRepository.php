<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\Refund;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{

    public function index()
    {
        // TODO: Implement index() method.
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

    public function update($data, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'client_id' => $data['client']['id'],
            'employee_id' => $data['employee']['id'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'total' => $data['total'],
            'payment_method' => $data['payment_method'],
            'is_credit' => $data['is_credit'],
            'is_paid' => $data['is_paid'],
        ]);
        if (isset($data['products'])) {
            $productsToSync = collect($data['products'])->map(function ($product) {
                if (isset($product['id']) && Product::find($product['id'])) {
                    return [
                        'id' => $product['id'],
                        'quantity' => $product['pivot']['quantity'],
                    ];
                } else {  // Создание нового продукта, если он не существует
                    $newProduct = Product::create([
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'category_id' => $product['category_id'],
                        'description' => '',
                        'slug' => Str::slug($product['name']),
                    ]);
                }
                return [
                    'id' => $newProduct->id,
                    'quantity' => $product['pivot']['quantity']
                ];
            });
            $syncData = [];
            foreach ($productsToSync as $product) {
                $syncData[$product['id']] = ['quantity' => $product['quantity']];
            }

            $order->product()->sync($syncData);
        }

        return $order;
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function getSalesData($locationId, $locationType, $startDate, $endDate)
    {
        return Order::whereHas('employee', function($query) use ($locationId) {
            $query->where('market_id', $locationId);
        })
            ->where('is_refunded', false)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('DATE(order_date) as date, SUM(total) as total_sales, COUNT(*) as total_orders')
            ->groupBy('date')
            ->get();
    }

    public function getRefundsData($locationId, $locationType, $startDate, $endDate)
    {
        return Refund::whereHas('order.employee', function($query) use ($locationId) {
            $query->where('market_id', $locationId);
        })
            ->join('orders', 'refunds.order_id', '=', 'orders.id')
            ->whereBetween('refund_date', [$startDate, $endDate])
            ->selectRaw('DATE(refund_date) as date, SUM(orders.total) as total_refunds, COUNT(refunds.id) as total_refunded_orders')
            ->groupBy('date')
            ->get();
    }

}
