<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use App\Exports\InvoicesExport;
// use PDF;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Barryvdh\DomPDF\PDF as Dompdf;
use Barryvdh\DomPDF\Options;
// use Dompdf\Options as DompdfOptions;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use PhpOffice\PhpSpreadsheet\Writer\Pdf as WriterPdf;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Dompdf;
use Dompdf\Options as DompdfOptions;
use Mpdf\Mpdf;
use ZipArchive;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('client');

        // Search by invoice number
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%');
        }

        // Filter by client
        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->latest()->paginate(25);
        $clients = Client::orderBy('name')->get(['id', 'name']);

        return view('invoices.index', compact('invoices', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $products = Product::all();
        return view('invoices.create', compact('clients', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log incoming request data

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'tax-rate' => 'required|numeric|min:0|max:100',
            'discount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);


        try {
            $invoice = DB::transaction(function () use ($validated, $request) {
                $subtotal = collect($request->items)->sum(function ($item) {
                    $product = Product::findOrFail($item['product_id']);
                    return $item['quantity'] * $product->price;
                });

                $tax_amount = ($subtotal - $validated['discount']) * ($validated['tax-rate'] / 100);
                $total = $subtotal - $validated['discount'] + $tax_amount;

                $invoice = Invoice::create([
                    'client_id' => $validated['client_id'],
                    'invoice_date' => $validated['invoice_date'],
                    'invoice_number' => Invoice::max('id') + 1,
                    'subtotal' => $subtotal,
                    'discount' => $validated['discount'],
                    'tax_percentage' => $validated['tax-rate'],
                    'tax_amount' => $tax_amount,
                    'total' => $total,
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $invoice->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'total' => $item['quantity'] * $product->price,
                    ]);
                }

                return $invoice;
            });

            return redirect()->route('invoices.index')
                ->with('success', 'تم إنشاء الفاتورة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error creating invoice: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفاتورة. يرجى المحاولة مرة أخرى.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['client', 'items.product']);
        $clients = Client::all();
        $products = Product::all();
        return view('invoices.edit', compact('invoice', 'clients', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'tax_percentage' => 'required|numeric|min:0|max:100',
            'discount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request, $invoice) {
            $subtotal = collect($request->items)->sum(function ($item) {
                $product = Product::findOrFail($item['product_id']);
                return $item['quantity'] * $product->unit_price;
            });

            $tax_amount = ($subtotal - $validated['discount']) * ($validated['tax_percentage'] / 100);
            $total = $subtotal - $validated['discount'] + $tax_amount;

            $invoice->update([
                'client_id' => $validated['client_id'],
                'subtotal' => $subtotal,
                'discount' => $validated['discount'],
                'tax_percentage' => $validated['tax_percentage'],
                'tax_amount' => $tax_amount,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete existing items
            $invoice->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->unit_price,
                    'total' => $item['quantity'] * $product->unit_price,
                ]);
            }
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Download the invoice as a PDF.
     */
    public function downloadPdf(Invoice $invoice)
    {
        // $invoice->load(['client', 'items.product']);

        // $options = new DompdfOptions();
        // $options->set('defaultFont', 'Amiri'); // Set the default font to an Arabic font
        // $options->set('isHtml5ParserEnabled', true); // Enable HTML5 support

        // $dompdf = new Dompdf($options);
        // $fontDir = public_path('assets/fonts'); // Adjust the path to the public assets directory
        // $fontMetrics = $dompdf->getFontMetrics();
        // $fontMetrics->registerFont('Amiri', $fontDir . '/Amiri-Regular.ttf'); // Register the Amiri font

        // // Load the HTML and specify the font
        // $html = view('invoices.pdf', compact('invoice'))->render();
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->render();
        // $dompdf->stream('invoice.pdf');
        return view('invoices.pdf', compact('invoice'));
    }

    /**
     * Download the invoice as a PDF.
     */

public function download(Invoice $invoice)
{
    $invoice->load(['client', 'items.product']);
    // return view('invoices.pdf', compact('invoice'));

    $mpdf = new Mpdf();
    $html = view('invoices.pdf', compact('invoice'))->render();
    $mpdf->WriteHTML($html);
    $mpdf->Output("invoice-{$invoice->invoice_number}.pdf", 'D'); // Download the PDF
}

    /**
     * Bulk download invoices as PDFs.
     */
    public function bulkDownload(Request $request)
    {
        // Validate the request
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Retrieve invoices based on the date range
        $invoices = Invoice::whereBetween('invoice_date', [$request->start_date, $request->end_date])->get();

        // Create a new ZipArchive instance
        $zip = new ZipArchive;
        $zipFileName = 'invoices.zip';
        $zip->open($zipFileName, ZipArchive::CREATE);

        // Generate PDFs for each invoice using mPDF
        foreach ($invoices as $invoice) {
            // Create a new mPDF instance
            $mpdf = new \Mpdf\Mpdf();

            // Load the view and render it as HTML
            $html = view('invoices.pdf', compact('invoice'))->render();

            // Write the HTML to the PDF
            $mpdf->WriteHTML($html);

            // Output the PDF to a variable
            $pdfOutput = $mpdf->Output('', 'S'); // 'S' means return the PDF as a string

            // Add the PDF to the zip file with the invoice ID in the filename
            $zip->addFromString('invoice-' . $invoice->id . '.pdf', $pdfOutput);
        }

        // Close the zip file
        $zip->close();

        // Return the zip file as a download
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
    /**
     * Export all invoices to an Excel file.
     */
    public function exportExcel()
    {
        return FacadesExcel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
