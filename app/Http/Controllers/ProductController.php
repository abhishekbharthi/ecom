<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //All Products
        $products = Product::all();

        //Return Json
        return response()->json([
            'product' => $products
        ],200);

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
    public function store(ProductStoreRequest $request)
    {
        try{
            //Create Product
            Product::create([
                'name' => $request->name,
                'detail' => $request->detail,
                'price' => $request->price,
                'stock' => $request->stock,
                'discount' => $request->discount
            ]);

            return response()->json([
                'message' => 'Product Created Successfully'
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Product detail
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'product' => $product
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductStoreRequest $request, int $id)
    {
        //dd($request);
        try{
            $product = Product::find($id);
            
            if(!$product){
                return response()->json([
                    'message' => 'Product not found.'
                ], 404);
            }   


            $product->name = $request->name;
            $product->detail = $request->detail;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->discount = $request->discount;

            $product->save();

            return response()->json([
                'message' => 'Product Updated Successfully.'
            ], 200);
        

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
        // Delete product
        $product->delete();

        return response()->json([
            'message'=> 'Product deleted.'
        ], 200);
    }
}
