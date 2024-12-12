<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get summary statistics
        $stats = [
            'total_revenue' => Invoice::sum('total'),
            'total_invoices' => Invoice::count(),
            'total_clients' => Client::count(),
        ];

        // Calculate percentages
        $maxRevenue = 100000; // Example maximum revenue
        $maxInvoices = 500; // Example maximum invoices
        $maxClients = 300; // Example maximum clients

        $stats['revenue_percentage'] = ($stats['total_revenue'] / $maxRevenue) * 100;
        $stats['invoice_percentage'] = ($stats['total_invoices'] / $maxInvoices) * 100;
        $stats['client_percentage'] = ($stats['total_clients'] / $maxClients) * 100;

        // Get monthly revenue for the current year
        $monthlyRevenue = Invoice::whereYear('invoice_date', Carbon::now()->year)
            ->select(
                DB::raw('MONTH(invoice_date) as month'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Fill in missing months with zero
        $chartData = array_replace(
            array_fill(1, 12, 0),
            $monthlyRevenue
        );

        // Get recent invoices
        $recentInvoices = Invoice::with('client')
            ->latest()
            ->take(5)
            ->get();

        // Get top clients by revenue
        $topClients = Client::select([
                'clients.id',
                'clients.name',
                'clients.email',
                DB::raw('COUNT(invoices.id) as invoices_count'),
                DB::raw('SUM(invoices.total) as total_revenue')
            ])
            ->join('invoices', 'clients.id', '=', 'invoices.client_id')
            ->groupBy([
                'clients.id',
                'clients.name',
                'clients.email'
            ])
            ->orderByRaw('SUM(invoices.total) DESC')
            ->take(5)
            ->get();

        // Get top products by quantity sold
        $topProducts = Product::select([
                'products.id',
                'products.name',
                'products.price',
                DB::raw('SUM(invoice_items.quantity) as total_quantity'),
                DB::raw('SUM(invoice_items.total) as total_revenue')
            ])
            ->join('invoice_items', 'products.id', '=', 'invoice_items.product_id')
            ->groupBy([
                'products.id',
                'products.name',
                'products.price'
            ])
            ->orderByRaw('SUM(invoice_items.quantity) DESC')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'chartData',
            'recentInvoices',
            'topClients',
            'topProducts'
        ));
    }
}
