<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use ArrayIterator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MultipleIterator;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::orderBy('id')->with('saleDetails')->get();
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Sales',
            'sales'    => $sales
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
            'receive'       => 'required|numeric',
            'return'        => 'required|numeric',
            'product_id.*'  => 'required|numeric',
            'qty.*'         => 'required|numeric',
            'sale_price.*'  => 'required|numeric',
            'product_discount.*'    => 'required|numeric',
            'subtotal.*'    => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {

                $sale = Sale::create([
                    'total_item'   => $request->total_item,
                    'total_price'   => $request->total_price,
                    'discount'   => $request->discount,
                    'grand_total' => $request->grand_total,
                    'receive' => $request->receive,
                    'return' => $request->return,
                ]);

                $mi = new MultipleIterator();
                $mi->attachIterator(new ArrayIterator($request->product_id));
                $mi->attachIterator(new ArrayIterator($request->sale_price));
                $mi->attachIterator(new ArrayIterator($request->product_discount));
                $mi->attachIterator(new ArrayIterator($request->qty));
                $mi->attachIterator(new ArrayIterator($request->subtotal));
                foreach ($mi as list($product_id, $sale_price, $product_discount, $qty, $subtotal)) {
                    $product = Product::find($product_id);
                    SaleDetail::create([
                        'sale_id'   => $sale->id,
                        'product_id'    => $product_id,
                        'product_name'    => $product->name,
                        'sale_price'           => $sale_price,
                        'qty'           => $qty,
                        'discount'           => $product_discount,
                        'subtotal'     => $subtotal
                    ]);

                    $product->update([
                        'qty'       => $product->qty - $qty,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => Sale::where('id', $sale->id)->with('saleDetails')->get(),
                ], 200);
            } catch (QueryException $exception) {
                $sale->delete();
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
    public function show(Sale $sale)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Sale',
            'data' => Sale::where('id', $sale->id)->with('saleDetails')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Edit Sale',
            'data' => Sale::where('id', $sale->id)->with('saleDetails')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'total_item'    => 'required|numeric',
            'total_price'   => 'required|numeric',
            'discount'      => 'nullable|numeric',
            'grand_total'   => 'required|numeric',
            'receive'       => 'required|numeric',
            'return'        => 'required|numeric',
            'product_id.*'  => 'required|numeric',
            'qty.*'         => 'required|numeric',
            'sale_price.*'  => 'required|numeric',
            'product_discount.*'    => 'required|numeric',
            'subtotal.*'    => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $sale = Sale::findOrFail($sale->id);
                $ti = $sale->total_item;
                $tp = $sale->total_price;
                $d = $sale->discount;
                $gt = $sale->grand_total;
                $rc = $sale->receive;
                $rt = $sale->return;

                $sale->update([
                    'total_item'   => $request->total_item,
                    'total_price'   => $request->total_price,
                    'discount'   => $request->discount,
                    'grand_total' => $request->grand_total,
                    'receive' => $request->receive,
                    'return' => $request->return,
                ]);

                $saleDetails = SaleDetail::whereRaw('sale_id = ' . $sale->id)->get();

                foreach ($saleDetails as $sd) {
                    $product = Product::find($sd->product_id);
                    $product->update([
                        'qty' => $product->qty + $sd->qty,
                    ]);

                    $sd->delete();
                };

                $mi = new MultipleIterator();
                $mi->attachIterator(new ArrayIterator($request->product_id));
                $mi->attachIterator(new ArrayIterator($request->sale_price));
                $mi->attachIterator(new ArrayIterator($request->product_discount));
                $mi->attachIterator(new ArrayIterator($request->qty));
                $mi->attachIterator(new ArrayIterator($request->subtotal));
                foreach ($mi as list($product_id, $sale_price, $product_discount, $qty, $subtotal)) {
                    $product = Product::find($product_id);
                    SaleDetail::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $product_id,
                        'product_name'  => $product->name,
                        'sale_price'    => $sale_price,
                        'qty'           => $qty,
                        'discount'      => $product_discount,
                        'subtotal'      => $subtotal
                    ]);

                    $product->update([
                        'qty'       => $product->qty - $qty,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => Sale::where('id', $sale->id)->with('saleDetails')->get(),
                ], 200);
            } catch (QueryException $exception) {
                $sale->update([
                    'total_item'    => $ti,
                    'total_price'   => $tp,
                    'discount'      => $d,
                    'grand_total'   => $gt,
                    'receive'       => $rc,
                    'return'        => $rt,
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
    public function destroy(Sale $sale)
    {
        try {
            $saleDetails = SaleDetail::whereRaw('sale_id = ' . $sale->id)->get();

            foreach ($saleDetails as $sd) {
                $product = Product::find($sd->product_id);
                $product->update([
                    'qty' => $product->qty + $sd->qty,
                ]);

                $sd->delete();
            };

            $sale->delete();

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
