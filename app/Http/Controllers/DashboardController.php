<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
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

        return view('dashboard', [
            'totalInvoices' => $totalInvoices,
            'client' => $client,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'latestInvoices' => $latestInvoices,
            'topClients' => $topClients

        ]);
    }
}
