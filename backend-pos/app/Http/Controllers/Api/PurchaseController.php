<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use ArrayIterator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MultipleIterator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::orderBy('id')->with('purchaseDetails')->get();
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Purchases',
            'purchases'    => $purchases
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
            'total_item'    => 'required|numeric',
            'total_price'   => 'required|numeric',
            'discount'      => 'nullable|numeric',
            'grand_total'   => 'required|numeric',
            'product_id.*'  => 'required|numeric',
            'qty.*'         => 'required|numeric',
            'subtotal.*'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {

                $purchase = Purchase::create([
                    'total_item'   => $request->total_item,
                    'total_price'   => $request->total_price,
                    'discount'   => $request->discount,
                    'grand_total' => $request->grand_total,
                ]);

                $mi = new MultipleIterator();
                $mi->attachIterator(new ArrayIterator($request->product_id));
                $mi->attachIterator(new ArrayIterator($request->qty));
                $mi->attachIterator(new ArrayIterator($request->subtotal));
                foreach ($mi as list($product_id, $qty, $subtotal)) {
                    PurchaseDetail::create([
                        'purchase_id'   => $purchase->id,
                        'product_id'    => $product_id,
                        'qty'           => $qty,
                        'subtotal'     => $subtotal
                    ]);

                    $product = Product::find($product_id);
                    $product->update([
                        'qty'       => $product->qty + $qty,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => Purchase::where('id', $purchase->id)->with('purchaseDetails')->get(),
                ], 200);
            } catch (QueryException $exception) {
                $purchase->delete();
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
    public function show(Purchase $purchase)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Purchase',
            'data' => Purchase::where('id', $purchase->id)->with('purchaseDetails')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Edit Purchase',
            'data' => Purchase::where('id', $purchase->id)->with('purchaseDetails')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validator = Validator::make($request->all(), [
            'total_item'    => 'required|numeric',
            'total_price'   => 'required|numeric',
            'discount'      => 'nullable|numeric',
            'grand_total'   => 'required|numeric',
            'product_id.*'  => 'required|numeric',
            'qty.*'         => 'required|numeric',
            'subtotal.*'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $ti = $purchase->total_item;
                $tp = $purchase->total_price;
                $d = $purchase->discount;
                $gt = $purchase->grand_total;
                $purchase->update([
                    'total_item'   => $request->total_item,
                    'total_price'   => $request->total_price,
                    'discount'   => $request->discount,
                    'grand_total' => $request->grand_total,
                ]);

                $purchaseDetails = PurchaseDetail::whereRaw('purchase_id = ' . $purchase->id)->get();

                foreach ($purchaseDetails as $pd) {
                    $product = Product::find($pd->product_id);
                    $product->update([
                        'qty' => $product->qty - $pd->qty,
                    ]);

                    $pd->delete();
                };

                $mi = new MultipleIterator();
                $mi->attachIterator(new ArrayIterator($request->product_id));
                $mi->attachIterator(new ArrayIterator($request->qty));
                $mi->attachIterator(new ArrayIterator($request->subtotal));
                foreach ($mi as list($product_id, $qty, $subtotal)) {
                    PurchaseDetail::create([
                        'purchase_id'   => $purchase->id,
                        'product_id'    => $product_id,
                        'qty'           => $qty,
                        'subtotal'     => $subtotal
                    ]);

                    $product = Product::find($product_id);
                    $product->update([
                        'qty'       => $product->qty + $qty,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => Purchase::where('id', $purchase->id)->with('purchaseDetails')->get(),
                ], 200);
            } catch (QueryException $exception) {
                $purchase->update([
                    'total_item'   => $ti,
                    'total_price'   => $tp,
                    'discount'   => $d,
                    'grand_total' => $gt,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Save!',
                    'error' => $exception->errorInfo[2]
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        try {
            $purchaseDetail = PurchaseDetail::whereRaw('purchase_id = ' . $purchase->id)->get();

            foreach ($purchaseDetail as $pd) {
                $product = Product::find($pd->product_id);
                $product->update([
                    'qty' => $product->qty - $pd->qty,
                ]);

                $pd->delete();
            };

            $purchase->delete();

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
