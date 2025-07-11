<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Quote;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Mail\InvoiceMail;
use App\Models\QuoteItem;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuoteController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->setPermissions([
            'index'   => 'quote_access',
            'create'  => 'quote_add',
            'edit'    => 'quote_edit',
            'destroy' => 'quote_delete',
            'convertToInvoice' => 'quote_to_invoice',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotes = Quote::all();
        return view('quote.index', [
            'quotes' => $quotes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $categories = Category::all();
        $quote_number = rand(1000, 9999);

        $products = Product::all();
        $taxes = Tax::all();
        return view('quote.create', [
            'clients' => $clients,
            'categories' => $categories,
            'quote_number' => $quote_number,
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
            'quote_number' => 'required|unique:quotes,quote_number',
            'quote_date' => 'required',
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

        // Save main quote record to database
        $quote = Quote::create([
            'client_id' => $request->client_id,
            'quote_number' => $request->quote_number,
            'quote_date' => $request->quote_date,
            'discount_timing' => $discount_timing,
            'discount_type' => $discount_type,
            'discount' => $discount,
            'discount_amount' => $final_discount,
            'subtotal' => $subtotal,
            'tax' => $total_tax,
            'total' => $total,
        ]);

        // Loop through each product and save as a quote item
        foreach ($request->product_id as $key => $product_id) {
            // Get quantity and unit price for this product
            $qty = $request->qty[$key];
            $unit_price = $request->unit_price[$key];

            // Calculate total for this line
            $line_total = $qty * $unit_price;


            $taxData = Tax::findOrFail($request->tax_id[$key]);
            $tax = $taxData->value ?? 0;

            // Create quote item with calculated tax amount
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $product_id,
                'qty' => $qty,
                'unit_price' => $unit_price,
                'tax_id' => $request->tax_id[$key],
                'tax' => ($line_total * ($tax ?? 0)) / 100,
            ]);
        }

        // Log the action
        userLog('Quote Create', 'Created a New Quote - #' . $request->quote_number);

        // Redirect back with success message
        return redirect()->route('quote.index')->with('success', 'Quote created successfully!');
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
        $clients = Client::all();
        $categories = Category::all();
        $quote_number = 'QUT' . rand(1000, 9999);
        $products = Product::all();
        $taxes = Tax::all();
        $quoteData = Quote::findOrFail($id);

        $quoteProducts = QuoteItem::where('quote_id', $id)->get();
        return view('quote.edit', [
            'clients' => $clients,
            'categories' => $categories,
            'quote_number' => $quote_number,
            'products' => $products,
            'taxes' => $taxes,
            'quoteData' => $quoteData,
            'quoteProducts' => $quoteProducts
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'client_id' => 'required',
            'quote_number' => 'required|unique:quotes,quote_number,' . $id,
            'quote_date' => 'required',
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

        // Update main quote record to database
        $quote = Quote::findOrFail($id);
        $quote->update([
            'client_id' => $request->client_id,
            'quote_number' => $request->quote_number,
            'quote_date' => $request->quote_date,
            'discount_timing' => $discount_timing,
            'discount_type' => $discount_type,
            'discount' => $discount,
            'discount_amount' => $final_discount,
            'subtotal' => $subtotal,
            'tax' => $total_tax,
            'total' => $total,
        ]);


        // Delete all existing quote items
        QuoteItem::where('quote_id', $id)->delete();
        $taxes = Tax::whereIn('id', $request->tax_id)->get()->keyBy('id');

        // Loop through each product and save as a quote item
        foreach ($request->product_id as $key => $product_id) {
            // Get quantity and unit price for this product
            $qty = $request->qty[$key];
            $unit_price = $request->unit_price[$key];

            // Calculate total for this line
            $line_total = $qty * $unit_price;


            $taxData = Tax::findOrFail($request->tax_id[$key]);
            $tax = $taxes->get($request->tax_id[$key])->value ?? 0;

            // Create quote item with calculated tax amount
            QuoteItem::create([
                'quote_id' => $quote->id,
                'product_id' => $product_id,
                'qty' => $qty,
                'unit_price' => $unit_price,
                'tax_id' => $request->tax_id[$key],
                'tax' => ($line_total * ($tax ?? 0)) / 100,
            ]);
        }

        // Log the action
        userLog('Quote Updated', 'Updated a Quote - #' . $request->quote_number);

        // Redirect back with success message
        return redirect()->route('quote.index')->with('success', 'Quote updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quote = Quote::findOrFail($id);

        // Log the action
        userLog('Quote Delete', 'Deleted a Quote - #' . $quote->quote_number);

        try {
            // Delete Quote
            $quote->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Quote Deleted Successfully'], 200);
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

    public function convertToInvoice($id)
    {
        $quote = Quote::findOrFail($id);

        $latestInvoice = Invoice::orderBy('id', 'desc')->first();
        $invoice_number = $latestInvoice ? intval($latestInvoice->invoice_number) + 1 : 1001;

        $invoice = Invoice::create([
            'client_id' => $quote->client_id,
            'invoice_number' => $invoice_number,
            'invoice_date' => $quote->quote_date,
            'discount_timing' => $quote->discount_timing,
            'discount_type' => $quote->discount_type,
            'discount' => $quote->discount,
            'discount_amount' => $quote->discount_amount,
            'subtotal' => $quote->subtotal,
            'total' => $quote->total,
            'tax' => $quote->tax,
        ]);

        $quoteItems = QuoteItem::where('quote_id', $id)->get();

        foreach ($quoteItems as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'unit_price' => $item->unit_price,
                'tax_id' => $item->tax_id,
                'tax' => $item->tax,
            ]);
        }

        // Optionally, delete the quote after conversion
        $quote->delete();

        if ($invoice) {
            Mail::to($invoice->client->email)->queue(new InvoiceMail($invoice));
            return redirect()->route('invoice.show', $invoice->invoice_number)->with('success', 'Quote converted to Invoice successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to convert Quote to Invoice.');
        }
    }
}
