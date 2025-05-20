<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxes = Tax::all();
        return view('tax.index', [
            'taxes' => $taxes,
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
            'name' => 'required|max:255|unique:taxes,name',
            'value' => 'required',
        ]);

        Tax::create([
            'name' => $request->name,
            'value' => $request->value,
        ]);

        return Redirect::route('tax.index')->with('success', 'Tax Created Successfully');
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
            'name' => 'required|max:255',
            'value' => 'required',
        ]);

        // Check if tax already exists
        $exists = Tax::where('name', $request->name)->where('id', '!=', $id)->exists();
        if ($exists) {
            return Redirect::route('tax.index')->with('error', 'Tax Already Exists');
        }

        // Update tax
        $tax = Tax::findOrFail($id);
        $tax->name = $request->name;
        $tax->value = $request->value;
        $tax->save();

        return Redirect::route('tax.index')->with('success', 'Tax Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tax = Tax::findOrFail($id);

        try {
            // Delete Tax
            $tax->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Tax Deleted Successfully'], 200);
    }

    public function status_update($id)
    {
        $tax = Tax::findOrFail($id);

        try {
            // If the current tax is being activated, deactivate all others
            if ($tax->status == 0) {
                Tax::where('id', '!=', $tax->id)->update(['status' => 0]);
                $tax->update(['status' => 1]);
            } else {
                // Otherwise, just deactivate the current one
                $tax->update(['status' => 0]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tax status updated successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating tax status.'
            ], 500);
        }
    }
}
