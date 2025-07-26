<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInvoices = Invoice::count();
        $client = Client::count();
        $totalRevenue = Invoice::sum('total');
        $revenueThisMonth = Invoice::whereMonth('created_at', now()->month)->sum('total');

        $latestInvoices = Invoice::latest()->take(10)->get();
        $topClients = Invoice::selectRaw('client_id, COUNT(*) as invoice_count, SUM(total) as total_amount')
            ->groupBy('client_id')
            ->orderByDesc('total_amount')
            ->with('client')
            ->take(10)
            ->get();




        // Charts
        $months = collect(range(1, 12))->map(function ($m) {
            return Carbon::create()->month($m)->format('M');
        })->toArray();

        $paidData = [];
        $dueData = [];

        foreach (range(1, 12) as $month) {
            // Ei month e shob invoice
            $invoices = Invoice::whereMonth('created_at', $month)
                ->whereYear('created_at', now()->year)
                ->get();

            $totalAmount = $invoices->sum('total');

            $paidAmount  = Payment::whereIn('invoice_id', $invoices->pluck('id'))->sum('amount');

            $dueAmount = $totalAmount - $paidAmount;

            $paidData[] = $paidAmount;
            $dueData[] = $dueAmount;
        }

        return view('dashboard', [
            'totalInvoices' => $totalInvoices,
            'client' => $client,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'latestInvoices' => $latestInvoices,
            'topClients' => $topClients,
            'months' => $months,
            'paidData' => $paidData,
            'dueData' => $dueData,

        ]);
    }
}
