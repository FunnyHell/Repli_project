<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Category;
use App\Models\Order;
use App\Services\Implementations\OrderService;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected OrderService $service)
    {
    }

    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id): \Inertia\Response
    {
        $order = $this->service->find($id);
        $categories = Category::all();
        return Inertia::render('Order', ['order' => $order, 'categories' => $categories]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $id)
    {
        if (auth()->user()) {
            $validated = $request->validated();
            if ($this->service->update($validated, $id)) {
                Session::flash('message', 'Success');
                return redirect()->route('orders.show', $id);
            } else {
                Session::flash('message', 'Error');
                return redirect()->back();
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
