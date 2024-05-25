<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductTransferRequest;
use App\Http\Requests\UpdateProductTransferRequest;
use App\Models\ProductTransfer;
use Illuminate\Support\Facades\Session;

class ProductTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreProductTransferRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductTransfer $ProductTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransfer $ProductTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductTransferRequest $request, ProductTransfer $ProductTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransfer $ProductTransfer)
    {
        //
    }

    public function updateStatus(UpdateProductTransferRequest $request, $id)
    {
        if (auth()->user()) {
            $validated = $request->validated();

            $transfer = ProductTransfer::findOrFail($id);
            $transfer->status = $request->status;
            if ($transfer->save()) {
                Session::flash('message', 'Status updated successfully');
            } else {
                Session::flash('message', 'Failed to update status');
            }
            return redirect()->back();
        } else {
            return redirect()->route('login');
        }
    }
}
