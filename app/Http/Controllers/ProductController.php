<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\ImageSaveTrait;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Permissions Method
    public function __construct()
    {
        $this->setPermissions([
            'create'  => 'product_add',
            'edit'    => 'product_edit',
            'destroy' => 'product_delete',
        ]);
    }
    use ImageSaveTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('product.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $code = 'PRD' . rand(1000, 9999);
        return view('product.create', [
            'categories' => $categories,
            'code' => $code
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required',
            'code' => 'required|unique:products,code',
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image_name = $this->saveImage('product', $request->file('image'), 400, 400);
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'code' => $request->code,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'image' => $image_name ?? null,
            'description' => $request->description
        ]);

        // Log the action
        userLog('Product Create', 'Created a New Product - ' . $request->name);

        return redirect()->route('product.index')->with('success', 'Product Created Successfully');
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
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('product.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required',
            'code' => 'required|unique:products,code,' . $id,
            'quantity' => 'required',
            'unit_price' => 'required',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $this->deleteImage('product', $product->image);
            $image_name = $this->saveImage('product', $request->file('image'), 400, 400);
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'image' => $image_name ?? $product->image,
            'code' => $request->code ?? $product->code,
            'description' => $request->description
        ]);

        // Log the action
        userLog('Product Update', 'Updated a Product - ' . $request->name);

        return redirect()->route('product.index')->with('success', 'Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Log the action
        userLog('Product Delete', 'Deleted a Product - ' . $product->name);

        try {
            // Delete product
            $product->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Category Deleted Successfully'], 200);
    }

    public function addCategoryAjax(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $request->category_name,
        ]);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }
}
