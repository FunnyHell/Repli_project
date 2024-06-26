<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductTransfer;
use App\Models\Refund;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{

    public function index()
    {
        return Order::with([
            'client', 'employee', 'product', 'product.category', 'product.feature', 'product.product_image', 'delivery'
        ])->where('is_refunded', '=', '0')->get();
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
        if (Order::find($id)) {
            if(Order::where('id', $id)->update(['is_refunded' => 1])) {
                return true;
            }
            return false;
        }
    }

    public function store(StoreOrderRequest $request)
    {
        // TODO: Implement store() method.
    }

    public function update($data, $id, $imagePath = null)
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

    public function create($data, $imagePath = null)
    {
        // Поиск или создание клиента
        $client = Client::firstOrCreate(
            ['surname' => $data['client']['surname'], 'name' => $data['client']['name']],
            [
                'sex' => $data['client']['sex'],
                'age' => $data['client']['age'],
                'phone' => $data['client']['phone'],
                'email' => $data['client']['email'],
                'address' => $data['client']['address']
            ]
        );

        // Поиск или создание сотрудника с заданной позицией и market_id
        $user = Auth::user(); // Получение текущего аутентифицированного пользователя
        $locationId = $user->location_id; // Получение location_id пользователя
        $email = $user->email;
        $employee = Employee::firstOrCreate(
            ['surname' => $data['employee']['surname'], 'name' => $data['employee']['name']],
            [
                'position' => 'продавец',
                'market_id' => $locationId,
                'email' => $email,
                'phone' => +1,
                'age' => 18
            ]
        );

        // Создание заказа
        $order = Order::create([
            'client_id' => $client->id,
            'employee_id' => $employee->id,
            'total' => $data['total'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'payment_method' => $data['payment_method'],
            'is_credit' => $data['is_credit'],
            'is_paid' => $data['is_paid'],
        ]);

        // Добавление продуктов к заказу
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
                    return [
                        'id' => $newProduct->id,
                        'quantity' => $product['pivot']['quantity']
                    ];
                }
            });

            $syncData = [];
            foreach ($productsToSync as $product) {
                $syncData[$product['id']] = ['quantity' => $product['quantity']];
            }

            $order->product()->sync($syncData);
        }

        return $order;
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
