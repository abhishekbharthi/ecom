<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesItems;
use App\Models\Invoice;
use App\Models\User;
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

    /*Cart method to generate the sales order*/    
    public function cart(Request $request)
    {      
        $request->sales_status = "open";
        $total_price = 0;
        try{
            
            //get total cart amount
            foreach($request->products as $k => $v){

                $product = Product::find($v['product_id']);
                if(!$product){
                    return response()->json([
                        'message' => 'Product not found.'
                    ], 404);
                }

                $total_price = $total_price + ($product->price * $v['quantity']);
            }

            //Create sales order 
            $sales =  Sales::create([
                'user_id' => $request->user_id,
                'total_price' =>  $total_price ,
                'sales_status' => $request->sales_status,
            ]);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong while create sales order.'
            ], 404);
        }

        try{

            //Create Sales Items
            foreach($request->products as $k => $v){

                $product = Product::find($v['product_id']);
                if(!$product){
                    return response()->json([
                        'message' => 'Product not found.'
                    ], 404);
                }
                SalesItems::create([ 
                    'sales_id' => $sales->id,
                    'product_id' => $v['product_id'],
                    'product_name' => $product->name, // Product name taken from product table
                    'price_per_unit' => $product->price,
                    'total_price' => ($product->price * $v['quantity']),
                    'discount' => $product->discount,
                    'quantity' => $v['quantity'] // Qty goes in minus to running the sales in the system     
                ]);    
                
            }

            return response()->json([
                'message' => 'Item added into cart.',
                'sales_id' => $sales->id
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => $e
            ], 500);
        }
        
    }

    /* Invoice method to sales the products from the system */
    public function invoice(Request $request)
    {
        $invoice = Invoice::find($request->sales_id);
        if($invoice){
            return response()->json([
                'message' => 'Invoice already exists.',
                'invoice_id' => $invoice->id
            ], 404);
        }

        $sales = Sales::find($request->sales_id);
        if(!$sales){
            return response()->json([
                'message' => 'Sales order not found.'
            ], 404);
        }
        $request->discount = 0; // Dicount can be calculate at this stage.
        
        // We can add Payment related code here to complete the invoice.
        $request->payment_status = 'paid';
      
        try{
            //Create Invoice
            $invoice = Invoice::create([
                'sales_id'=>$sales->id,
                'user_id' => $sales->user_id,
                'total_price' => $sales->total_price,
                'discount' => $request->discount,
                'payment_status' => $request->payment_status,
            ]);

            return response()->json([
                'message' => 'Order placed successfully.',
                'invoice_id' => $invoice->id
            ], 200);

        }catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong in Order.'
            ], 500);
        }
        
    } 

    

}

