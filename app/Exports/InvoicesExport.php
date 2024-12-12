<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Exports\Exportable;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoice::with(['client', 'items'])->get();
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Client',
            'Date',
            'Subtotal',
            'Discount',
            'Tax Percentage',
            'Tax Amount',
            'Total',
            'Notes',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->client->name,
            $invoice->created_at->format('Y-m-d'),
            $invoice->subtotal,
            $invoice->discount,
            $invoice->tax_percentage . '%',
            $invoice->tax_amount,
            $invoice->total,
            $invoice->notes,
        ];
    }
}
