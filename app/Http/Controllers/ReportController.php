<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;

class ReportController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('reports.index', compact('clients', 'products'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:sales,revenue,client,product',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'client_id' => 'nullable|exists:clients,id',
            'product_id' => 'nullable|exists:products,id',
            'status' => 'nullable|in:draft,sent,paid',
            'format' => 'required|in:view,excel,pdf'
        ]);

        $dateFrom = Carbon::parse($request->date_from);
        $dateTo = Carbon::parse($request->date_to);

        switch ($request->report_type) {
            case 'sales':
                $data = $this->generateSalesReport($dateFrom, $dateTo, $request->status);
                break;
            case 'revenue':
                $data = $this->generateRevenueReport($dateFrom, $dateTo);
                break;
            case 'client':
                $data = $this->generateClientReport($dateFrom, $dateTo, $request->client_id);
                break;
            case 'product':
                $data = $this->generateProductReport($dateFrom, $dateTo, $request->product_id);
                break;
        }

        if ($request->format === 'excel') {
            return Excel::download(
                new InvoicesExport($data, $request->report_type),
                $request->report_type . '_report.xlsx'
            );
        }

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('reports.pdf', [
                'data' => $data,
                'type' => $request->report_type,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);

            return $pdf->download($request->report_type . '_report.pdf');
        }

        return view('reports.show', [
            'data' => $data,
            'type' => $request->report_type,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]);
    }

    private function generateSalesReport($dateFrom, $dateTo, $status = null)
    {
        $query = Invoice::with(['client', 'items.product'])
            ->whereBetween('invoice_date', [$dateFrom, $dateTo]);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get()->map(function ($invoice) {
            return [
                'invoice_number' => $invoice->invoice_number,
                'client' => $invoice->client->name,
                'date' => $invoice->invoice_date->format('Y-m-d'),
                'items' => $invoice->items->count(),
                'subtotal' => $invoice->subtotal,
                'tax' => $invoice->tax_amount,
                'total' => $invoice->total,
                'status' => $invoice->status
            ];
        });
    }

    private function generateRevenueReport($dateFrom, $dateTo)
    {
        return Invoice::where('status', 'paid')
            ->whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(invoice_date) as date'),
                DB::raw('COUNT(*) as invoices_count'),
                DB::raw('SUM(subtotal) as subtotal'),
                DB::raw('SUM(tax_amount) as tax'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function generateClientReport($dateFrom, $dateTo, $clientId = null)
    {
        $query = Client::with(['invoices' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('invoice_date', [$dateFrom, $dateTo]);
        }]);

        if ($clientId) {
            $query->where('id', $clientId);
        }

        return $query->get()->map(function ($client) {
            return [
                'client' => $client->name,
                'invoices_count' => $client->invoices->count(),
                'total_amount' => $client->invoices->sum('total'),
                'paid_amount' => $client->invoices->where('status', 'paid')->sum('total'),
                'pending_amount' => $client->invoices->whereIn('status', ['draft', 'sent'])->sum('total')
            ];
        });
    }

    private function generateProductReport($dateFrom, $dateTo, $productId = null)
    {
        $query = Product::with(['invoiceItems' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereHas('invoice', function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('invoice_date', [$dateFrom, $dateTo]);
            });
        }]);

        if ($productId) {
            $query->where('id', $productId);
        }

        return $query->get()->map(function ($product) {
            $invoiceItems = $product->invoiceItems;
            return [
                'product' => $product->name,
                'units_sold' => $invoiceItems->sum('quantity'),
                'revenue' => $invoiceItems->sum('total'),
                'average_price' => $invoiceItems->avg('unit_price')
            ];
        });
    }
}
