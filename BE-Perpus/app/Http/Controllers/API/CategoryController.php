<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'isOwner'])->only('store', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();

        return response()->json([
            "message" => "View all categories",
            "data" => $category
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->all());

        return response()->json([
            "message" => "Category successfully added"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('list_books')->find($id);

        if(!$category){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data with ID : $id",
            "data" => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($validatedData);

        $category->name = $request['name'];

        $category->save();

        return response()->json([
            "message" => "Successfully updated Category with ID : $id"
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return response()->json([
                "message" => "ID is not found"
            ], 404);
        }

        $category->delete();
        return response()->json([
            "message" => "Category with ID $id successfully deleted"
        ]);
    }
}
