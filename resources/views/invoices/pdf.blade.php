<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>فاتورة #{{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'Rubik';
            src: url('{{ asset('assets/fonts/Rubik-Regular.ttf') }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Rubik';
            src: url('{{ asset('assets/fonts/Rubik-Bold.ttf') }}') format('truetype');
            font-weight: bold;
        }
        body {
            font-family: 'Rubik', sans-serif;
            direction: rtl;
            max-width: 595px; /* A4 width in pixels */
            margin: 0 auto; /* Center the body */
            padding: 20px; /* Optional padding */
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .client-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f8f9fa;
        }
        .totals {
            float: left;
            width: 300px;
        }
        .total-row {
            font-weight: bold;
        }
        .d-flex{
            display: flex;
            flex-direction: row;
        }
        .justify-between{
            justify-content: space-between;
        }
        .col-6{
            width: 50%;
        }
        .ml-4{
            margin-left: 1rem;
        }
        .mr-4{
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <div class="client-details">
        <p class="row">
            <p><strong>رقم الفاتورة:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>التاريخ:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y/m/d') }}</p>
            <p><strong>كود العميل:</strong> {{ $invoice->client->code }}</p>
            <table style="width: 100%; border: none !important; margin:0 !important">
                <tr style="border: none !important; margin: 0 !important;">
                    <td style="width: 50%; border: none !important;"><strong>الاسم:</strong> {{ $invoice->client->name }}</td>
                    <td style="width: 50%; border: none !important;"><strong>العنوان:</strong> {{ $invoice->client->address }}</td>
                </tr>
            </table>
            <p><strong>البريد الإلكتروني:</strong> {{ $invoice->client->email }}</p>
            <p><strong>الهاتف:</strong> {{ $invoice->client->phone }}</p>
        </p>

    </div>

    <table>
        <thead>
            <tr>
                <th>كود المنتج</th>
                <th>اسم المنتج</th>
                <th>الكمية</th>
                <th>السعر الوحدة</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $item->product->code }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }} ج.م</td>
                <td>{{ number_format($item->total, 2) }} ج.م</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>المبلغ الفرعي:</td>
                <td>{{ number_format($invoice->subtotal, 2) }} ج.م</td>
            </tr>
            <tr>
                <td>خصم:</td>
                <td>{{ number_format($invoice->discount, 2) }} ج.م</td>
            </tr>
            <tr>
                <td>الضريبة ({{$invoice->tax_percentage}}%):</td>
                <td>{{ number_format($invoice->tax_amount, 2) }} ج.م</td>
            </tr>
            <tr class="total-row">
                <td>الإجمالي:</td>
                <td>{{ number_format($invoice->total, 2) }} ج.م</td>
            </tr>
        </table>
    </div>

    @if($invoice->notes)
    <div style="clear: both; padding-top: 20px;">
        <h3>ملاحظات</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif
</body>
</html>
