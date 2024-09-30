<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Products',
            'products'    => $products
        ]);
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'category_id' => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required',
            'discount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $image = $request->file('image');
                $image->storeAs('public/products', $image->hashName());
                $product = Product::create([
                    'code'   => $request->code,
                    'name'   => $request->name,
                    'image'  => $image->hashName(),
                    'category_id'   => $request->category_id,
                    'purchase_price' => $request->purchase_price,
                    'sale_price' => $request->sale_price,
                    'discount' => $request->discount,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => $product,
                ], 200);
            } catch (QueryException $exception) {
                Storage::disk('local')->delete('public/products/' . basename($image->hashName()));
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Save!',
                    'error' => $exception->errorInfo[2]
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Edit Product',
            'data'    => $product
        ]);
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
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products,code,' . $product->id,
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'category_id' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required',
            'discount' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                if ($request->file('image') == '') {
                    $product->update([
                        'code'   => $request->code,
                        'name'   => $request->name,
                        'category_id'   => $request->category_id,
                        'purchase_price' => $request->purchase_price,
                        'sale_price' => $request->sale_price,
                        'discount' => $request->discount,
                    ]);
                } else {
                    Storage::disk('local')->delete('public/products/' . basename($product->image));
                    $image = $request->file('image');
                    $image->storeAs('public/products', $image->hashName());
                    $product->update([
                        'code'   => $request->code,
                        'name'   => $request->name,
                        'image'  => $image->hashName(),
                        'category_id'   => $request->category_id,
                        'purchase_price' => $request->purchase_price,
                        'sale_price' => $request->sale_price,
                        'discount' => $request->discount,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Updated Successfully!',
                    'data' => $product,
                ], 200);
            } catch (QueryException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Update!',
                    'error' => $exception->errorInfo[2]
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $image = $product->image;
        try {
            $product->delete();
            Storage::disk('local')->delete('public/products/' . basename($image));

            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (QueryException $exception) {
            return response()->json([
                'status' => 'error',
                'error' => $exception->errorInfo[2]
            ], 500);
        }
    }
}
