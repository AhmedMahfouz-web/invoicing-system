<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($type) }} تقرير</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            direction: rtl;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-title {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .date-range {
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-draft { background-color: #f8f9fa; color: #666; }
        .status-sent { background-color: #fff3cd; color: #856404; }
        .status-paid { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="report-title">{{ ucfirst($type) }} تقرير</h1>
            <p>التاريخ: {{ $dateFrom->format('F d, Y') }} - {{ $dateTo->format('F d, Y') }}</p>
            <div>{{ config('app.name') }}</div>
        </div>

        @if($type === 'sales')
            <table>
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>التاريخ</th>
                        <th>المنتجات</th>
                        <th class="text-right">المجموع</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>{{ $row['invoice_number'] }}</td>
                            <td>{{ $row['client'] }}</td>
                            <td>{{ $row['date'] }}</td>
                            <td>{{ $row['items'] }}</td>
                            <td class="text-right">{{ number_format($row['total'], 2) }} ريال</td>
                            <td>
                                <span class="status status-{{ $row['status'] }}">
                                    {{ $row['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>المجموع</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('total'), 2) }} ريال</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        @elseif($type === 'revenue')
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>عدد الفواتير</th>
                        <th class="text-right">المبلغ قبل الضريبة</th>
                        <th class="text-right">الضريبة</th>
                        <th class="text-right">المبلغ الكلي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->invoices_count }}</td>
                            <td class="text-right">{{ number_format($row->subtotal, 2) }} ريال</td>
                            <td class="text-right">{{ number_format($row->tax, 2) }} ريال</td>
                            <td class="text-right">{{ number_format($row->total, 2) }} ريال</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>المجموع</strong></td>
                        <td><strong>{{ $data->sum('invoices_count') }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('subtotal'), 2) }} ريال</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('tax'), 2) }} ريال</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('total'), 2) }} ريال</strong></td>
                    </tr>
                </tfoot>
            </table>

        @elseif($type === 'client')
            <table>
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th>عدد الفواتير</th>
                        <th class="text-right">المبلغ الكلي</th>
                        <th class="text-right">المبلغ المدفوع</th>
                        <th class="text-right">المبلغ المتبقي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>{{ $row['client'] }}</td>
                            <td>{{ $row['invoices_count'] }}</td>
                            <td class="text-right">{{ number_format($row['total_amount'], 2) }} ريال</td>
                            <td class="text-right">{{ number_format($row['paid_amount'], 2) }} ريال</td>
                            <td class="text-right">{{ number_format($row['pending_amount'], 2) }} ريال</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>المجموع</strong></td>
                        <td><strong>{{ $data->sum('invoices_count') }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('total_amount'), 2) }} ريال</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('paid_amount'), 2) }} ريال</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('pending_amount'), 2) }} ريال</strong></td>
                    </tr>
                </tfoot>
            </table>

        @elseif($type === 'product')
            <table>
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الوحدات المباعة</th>
                        <th class="text-right">الإيرادات</th>
                        <th class="text-right">السعر المتوسط</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            <td>{{ $row['product'] }}</td>
                            <td>{{ $row['units_sold'] }}</td>
                            <td class="text-right">{{ number_format($row['revenue'], 2) }} ريال</td>
                            <td class="text-right">{{ number_format($row['average_price'], 2) }} ريال</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>المجموع</strong></td>
                        <td><strong>{{ $data->sum('units_sold') }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->sum('revenue'), 2) }} ريال</strong></td>
                        <td class="text-right"><strong>{{ number_format($data->avg('average_price'), 2) }} ريال</strong></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        <div class="footer">
            <p>تم إنشاء التقرير في {{ now()->format('F d, Y H:i:s') }}</p>
            <p>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
