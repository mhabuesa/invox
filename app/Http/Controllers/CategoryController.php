<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    // Permissions Method
    public function __construct()
    {
        $this->setPermissions([
            'index'   => 'category_access',
            'create'  => 'category_add',
            'edit'    => 'category_edit',
            'destroy' => 'category_delete',
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('category.index', [
            'categories' => $categories,
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
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);
        // Log the action
        userLog('Category Create', 'Created a New Category - ' . $request->name);

        // Redirect with success message
        return Redirect::route('category.index')->with('success', 'Category Created Successfully');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_name' => 'required|max:255'
        ]);

        // Check if category already exists
        $exists = Category::where('name', $request->category_name)->where('id', '!=', $id)->exists();
        if ($exists) {
            return Redirect::route('category.index')->with('error', 'Category Already Exists');
        }

        // Update category
        $category = Category::findOrFail($id);
        $category->name = $request->category_name;
        $category->save();

        // Log the action
        userLog('Category Update', 'Updated a Category - ' . $request->category_name);
        // Redirect with success message
        return Redirect::route('category.index')->with('success', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $uncategorized = Category::where('name', 'Uncategorized')->first();
        if (!$uncategorized) {
            return response()->json(['success' => false, 'message' => 'Uncategorized category not found.'], 404);
        }

        Product::where('category_id', $id)->update(['category_id' => $uncategorized->id]);
        $category = Category::findOrFail($id);
        // Log the action
        userLog('Category Destroy', 'Destroyed a Category - ' . $category->name);

        try {
            $category->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Category Deleted Successfully'], 200);
    }
}
