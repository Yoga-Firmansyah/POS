<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expenditure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenditureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenditures = Expenditure::orderBy('name')->get();
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Expenditures',
            'products'    => $expenditures
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
            'name'  => 'required',
            'total'  => 'required|numeric',
            'content'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $expenditure = Expenditure::create([
                    'name'   => $request->name,
                    'total'  => $request->total,
                    'content'  => $request->content,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => $expenditure,
                ], 200);
            } catch (QueryException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Save!',
                    'error' => $exception->errorInfo[2]
                ], 400);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expenditure $expenditure)
    {
        try {
            Expenditure::find($expenditure->id);
            return response()->json([
                'success' => true,
                'message' => 'Data Edit Product',
                'data'    => $expenditure
            ]);
        } catch (QueryException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Failed Retrieve Data!',
                'error' => $exception->errorInfo[2]
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expenditure $expenditure)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Edit Product',
            'data'    => $expenditure
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expenditure $expenditure)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'total'  => 'required|numeric',
            'content'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $expenditure->update([
                    'name'   => $request->name,
                    'total'  => $request->total,
                    'content'  => $request->content,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Data Updatet Successfully!',
                    'data' => $expenditure,
                ], 200);
            } catch (QueryException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Failed to Update!',
                    'error' => $exception->errorInfo[2]
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expenditure $expenditure)
    {
        try {
            $expenditure->delete();
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (QueryException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->errorInfo[2]
            ], 500);
        }
    }
}
