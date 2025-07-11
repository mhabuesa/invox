<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     * Set permissions for each category-related action using custom permission keys.
     */
    public function __construct()
    {
        // Define required permissions for each action in the Category module
        $this->setPermissions([
            'index'   => 'category_access',  // Permission to view category list
            'create'  => 'category_add',     // Permission to add a new category
            'edit'    => 'category_edit',    // Permission to edit existing category
            'destroy' => 'category_delete',  // Permission to delete a category
        ]);
    }

    /** Display a listing of all categories. **/
    public function index()
    {
        // Retrieve all categories from the database
        $categories = Category::all();

        // Return view with the category list
        return view('category.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     **/
    public function create()
    {
        // You can implement category create form here
    }

    /**
     * Store a newly created category in the database.
     */
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
        ]);

        // Create new category
        Category::create([
            'name' => $request->name,
        ]);

        // Log the creation action
        userLog('Category Create', 'Created a New Category - ' . $request->name);

        // Redirect with success message
        return Redirect::route('category.index')->with('success', 'Category Created Successfully');
    }

    /**
     * Display the specified category.
     */
    public function show(string $id)
    {
        // You can implement view single category here
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(string $id)
    {
        // You can implement category edit form here
    }

    /**
     * Update the specified category in the database.
     */
    public function update(Request $request, string $id)
    {
        // Validate input data
        $request->validate([
            'category_name' => 'required|max:255'
        ]);

        // Check for duplicate category name excluding current category
        $exists = Category::where('name', $request->category_name)->where('id', '!=', $id)->exists();
        if ($exists) {
            return Redirect::route('category.index')->with('error', 'Category Already Exists');
        }

        // Update category data
        $category = Category::findOrFail($id);
        $category->name = $request->category_name;
        $category->save();

        // Log the update action
        userLog('Category Update', 'Updated a Category - ' . $request->category_name);

        // Redirect with success message
        return Redirect::route('category.index')->with('success', 'Category Updated Successfully');
    }

    /**
     * Remove the specified category from the database.
     * Reassign its products to the 'Uncategorized' category before deletion.
     */
    public function destroy(string $id)
    {

        // Find the category to delete
        $category = Category::findOrFail($id);

        // Log the deletion action
        userLog('Category Destroy', 'Destroyed a Category - ' . $category->name);

        try {
            // Delete the category
            $category->delete();
        } catch (\Exception $e) {
            // Log any error and return response
            Log::error($e);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        // Return successful response
        return response()->json(['success' => true, 'message' => 'Category Deleted Successfully'], 200);
    }
}
