<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoice.index', [
            'invoices' => $invoices
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $invoice_number = 'INV-' . rand(1000000, 9999999);
        $products = Product::all();
        $taxes = Tax::all();
        return view('invoice.create', [
            'clients' => $clients,
            'invoice_number' => $invoice_number,
            'products' => $products,
            'taxes' => $taxes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'invoice_date' => 'required',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'unit_price' => 'required|array',
        ]);

        $subtotal = 0;
        $total_tax = 0;

        // Calculate subtotal and tax for each product
        foreach ($request->product_id as $key => $product_id) {
            $qty = $request->qty[$key];
            $unit_price = $request->unit_price[$key];
            $taxData = Tax::findOrFail($request->tax_id[$key]);
            $tax = $taxData->value ?? 0;

            $line_total = $qty * $unit_price; // total for this line item
            $subtotal += $line_total; // add to subtotal

            // If tax is present, calculate tax for this line item
            $withTax = ($tax > 0) ? ($line_total * $tax / 100) : 0;
            $total_tax += $withTax; // add to total tax
        }

        // Set default values if some inputs are not present
        $discount = $request->discount ?? 0;
        $discount_type = $request->discount_type ?? 'fixed';
        $discount_timing = $request->discount_timing ?? 'after_tax';

        $final_discount = 0;
        $total = 0;

        // Calculate final discount and total amount based on type and timing
        if ($discount_type === 'percentage') {
            if ($discount_timing === 'before_tax') {
                $final_discount = ($subtotal * $discount) / 100;
                $total = $subtotal - $final_discount + $total_tax;
            } else {
                $final_discount = (($subtotal + $total_tax) * $discount) / 100;
                $total = ($subtotal + $total_tax) - $final_discount;
            }
        } else {
            $final_discount = $discount;
            $total = ($subtotal + $total_tax) - $final_discount;
        }

        // Save main invoice record to database
        $invoice = Invoice::create([
            'client_id' => $request->client_id,
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'discount_timing' => $discount_timing,
            'discount_type' => $discount_type,
            'discount' => $discount,
            'discount_amount' => $final_discount,
            'subtotal' => $subtotal,
            'tax' => $total_tax,
            'total' => $total,
        ]);

        // Loop through each product and save as a invoice item
        foreach ($request->product_id as $key => $product_id) {
            // Get quantity and unit price for this product
            $qty = $request->qty[$key];
            $unit_price = $request->unit_price[$key];

            // Calculate total for this line
            $line_total = $qty * $unit_price;


            $taxData = Tax::findOrFail($request->tax_id[$key]);
            $tax = $taxData->value ?? 0;

            // Create invoice item with calculated tax amount
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product_id,
                'qty' => $qty,
                'unit_price' => $unit_price,
                'tax_id' => $request->tax_id[$key],
                'tax' => ($line_total * ($tax ?? 0)) / 100,
            ]);
        }

        // Redirect back with success message
        return redirect()->route('invoice.index')->with('success', 'Invoice created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceProducts = InvoiceItem::where('invoice_id', $id)->get();
        return view('invoice.invoice_template.third');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $clients = Client::all();
        $invoice_number = 'QUT' . rand(1000, 9999);
        $products = Product::all();
        $taxes = Tax::all();
        $invoiceData = Invoice::findOrFail($id);

        $invoiceProducts = InvoiceItem::where('invoice_id', $id)->get();
        return view('invoice.edit', [
            'clients' => $clients,
            'invoice_number' => $invoice_number,
            'products' => $products,
            'taxes' => $taxes,
            'invoiceData' => $invoiceData,
            'invoiceProducts' => $invoiceProducts
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'required',
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $id,
            'invoice_date' => 'required',
            'product_id' => 'required|array',
            'qty' => 'required|array',
            'unit_price' => 'required|array',
        ]);

        $subtotal = 0;
        $total_tax = 0;

        // Calculate subtotal and tax for each product
        foreach ($request->product_id as $key => $product_id) {
            $qty = $request->qty[$key];
            $unit_price = $request->unit_price[$key];
            $taxData = Tax::findOrFail($request->tax_id[$key]);
            $tax = $taxData->value ?? 0;

            $line_total = $qty * $unit_price; // total for this line item
            $subtotal += $line_total; // add to subtotal

            // If tax is present, calculate tax for this line item
            $withTax = ($tax > 0) ? ($line_total * $tax / 100) : 0;
            $total_tax += $withTax; // add to total tax
        }

        // Set default values if some inputs are not present
        $discount = $request->discount ?? 0;
        $discount_type = $request->discount_type ?? 'fixed';
        $discount_timing = $request->discount_timing ?? 'after_tax';

        $final_discount = 0;
        $total = 0;

        // Calculate final discount and total amount based on type and timing
        if ($discount_type === 'percentage') {
            if ($discount_timing === 'before_tax') {
                $final_discount = ($subtotal * $discount) / 100;
                $total = $subtotal - $final_discount + $total_tax;
            } else {
                $final_discount = (($subtotal + $total_tax) * $discount) / 100;
                $total = ($subtotal + $total_tax) - $final_discount;
            }
        } else {
            $final_discount = $discount;
            $total = ($subtotal + $total_tax) - $final_discount;
        }

        // Update main Invoice record to database
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'client_id' => $request->client_id,
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'discount_timing' => $discount_timing,
            'discount_type' => $discount_type,
            'discount' => $discount,
            'discount_amount' => $final_discount,
            'subtotal' => $subtotal,
            'tax' => $total_tax,
            'total' => $total,
        ]);


        // Delete all existing invoice items
        InvoiceItem::where('invoice_id', $id)->delete();
        $taxes = Tax::whereIn('id', $request->tax_id)->get()->keyBy('id');

        // Loop through each product and save as a invoice item
        foreach ($request->product_id as $key => $product_id) {
            // Get quantity and unit price for this product
            $qty = $request->qty[$key];
            $unit_price = $request->unit_price[$key];

            // Calculate total for this line
            $line_total = $qty * $unit_price;


            $taxData = Tax::findOrFail($request->tax_id[$key]);
            $tax = $taxes->get($request->tax_id[$key])->value ?? 0;

            // Create invoice item with calculated tax amount
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product_id,
                'qty' => $qty,
                'unit_price' => $unit_price,
                'tax_id' => $request->tax_id[$key],
                'tax' => ($line_total * ($tax ?? 0)) / 100,
            ]);
        }

        // Redirect back with success message
        return redirect()->route('invoice.index')->with('success', 'Invoice updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);

        try {
            // Delete invoice
            $invoice->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Invoice Deleted Successfully'], 200);
    }

    public function addClientAjax(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255|unique:clients,name',
            'email' => 'required|email|unique:clients,email',
        ]);

        $client = Client::create([
            'name' => $request->client_name,
            'email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'client' => $client,
        ]);
    }
}
