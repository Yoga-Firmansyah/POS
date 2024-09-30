<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return response()->json([
            'success'       => true,
            'message'       => 'List Data Categories',
            'categories'    => $categories
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
            'name'  => 'required|unique:categories',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                $image = $request->file('image');
                $image->storeAs('public/categories', $image->hashName());
                $category = Category::create([
                    'name'   => $request->name,
                    'image'  => $image->hashName(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data Saved Successfully!',
                    'data' => $category,
                ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Edit Product',
            'data'    => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            try {
                if ($request->file('image') == '') {
                    $category->update([
                        'name'   => $request->name,
                    ]);
                } else {
                    Storage::disk('local')->delete('public/categories/' . basename($category->image));
                    $image = $request->file('image');
                    $image->storeAs('public/categories', $image->hashName());
                    $category->update([
                        'name'   => $request->name,
                        'image'  => $image->hashName(),
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => 'Data Updated Successfully!',
                    'data' => $category,
                ]);
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
    public function destroy(Category $category)
    {
        $image = $category->image;
        try {
            $category->delete();
            Storage::disk('local')->delete('public/categories/' . basename($image));

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
