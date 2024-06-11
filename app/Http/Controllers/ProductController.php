<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(protected ProductServiceInterface $productService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $products = $this->productService->all();
        return Inertia::render('Products', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->productService->getAllCategories();
        return Inertia::render('CreateProduct', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        } else {
            $imagePath = null;
        }

        if ($this->productService->create($validated, $imagePath)) {
            Session::flash('message', 'Success');
            return redirect()->back();
        } else {
            Session::flash('message', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $slug)
    {
        $product = $this->productService->find($id);
        return Inertia::render('Product', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(auth()->user()) {
            if($this->productService->delete($id)){
                Session::flash('message', 'Success');
                return redirect()->back();
            } else {
                Session::flash('message', 'Error');
                return redirect()->back();
            }
        } else {
            return redirect()->route('login');
        }
    }
}
