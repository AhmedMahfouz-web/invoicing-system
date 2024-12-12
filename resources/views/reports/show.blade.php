@extends('layouts.app')
@section('page-content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    تقرير {{ ucfirst($type) }}
                    <small class="text-muted">
                        ({{ $dateFrom->format('F d, Y') }} - {{ $dateTo->format('F d, Y') }})
                    </small>
                </h2>
                <div>
                    <a href="{{ request()->fullUrlWithQuery(['format' => 'excel']) }}" class="btn btn-success">تصدير Excel</a>
                    <a href="{{ request()->fullUrlWithQuery(['format' => 'pdf']) }}" class="btn btn-danger">تصدير PDF</a>
                    <a href="{{ route('reports.index') }}" class="btn btn-primary">تقرير جديد</a>
                </div>
            </div>

            <div class="card-body">
                @if($type === 'sales')
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>العميل</th>
                                    <th>التاريخ</th>
                                    <th>المنتجات</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>الضريبة</th>
                                    <th>المبلغ الكلي</th>
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
                                        <td>{{ number_format($row['subtotal'], 2) }} ج.م</td>
                                        <td>{{ number_format($row['tax'], 2) }} ج.م</td>
                                        <td>{{ number_format($row['total'], 2) }} ج.م</td>
                                        <td>
                                            <span class="badge bg-{{ $row['status'] === 'paid' ? 'success' : ($row['status'] === 'sent' ? 'warning' : 'secondary') }}">
                                                {{ $row['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"><strong>المجموع</strong></td>
                                    <td><strong>{{ number_format($data->sum('subtotal'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->sum('tax'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->sum('total'), 2) }} ج.م</strong></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                @elseif($type === 'revenue')
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>عدد الفواتير</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>الضريبة</th>
                                    <th>المبلغ الكلي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{ $row->date }}</td>
                                        <td>{{ $row->invoices_count }}</td>
                                        <td>{{ number_format($row->subtotal, 2) }} ج.م</td>
                                        <td>{{ number_format($row->tax, 2) }} ج.م</td>
                                        <td>{{ number_format($row->total, 2) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>المجموع</strong></td>
                                    <td><strong>{{ $data->sum('invoices_count') }}</strong></td>
                                    <td><strong>{{ number_format($data->sum('subtotal'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->sum('tax'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->sum('total'), 2) }} ج.م</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                @elseif($type === 'client')
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>العميل</th>
                                    <th>عدد الفواتير</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>المبلغ المدفوع</th>
                                    <th>المبلغ المتبقي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{ $row['client'] }}</td>
                                        <td>{{ $row['invoices_count'] }}</td>
                                        <td>{{ number_format($row['total_amount'], 2) }} ج.م</td>
                                        <td>{{ number_format($row['paid_amount'], 2) }} ج.م</td>
                                        <td>{{ number_format($row['pending_amount'], 2) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>المجموع</strong></td>
                                    <td><strong>{{ $data->sum('invoices_count') }}</strong></td>
                                    <td><strong>{{ number_format($data->sum('total_amount'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->sum('paid_amount'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->sum('pending_amount'), 2) }} ج.م</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                @elseif($type === 'product')
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الوحدات المباعة</th>
                                    <th>الإيرادات</th>
                                    <th>السعر المتوسط</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{ $row['product'] }}</td>
                                        <td>{{ $row['units_sold'] }}</td>
                                        <td>{{ number_format($row['revenue'], 2) }} ج.م</td>
                                        <td>{{ number_format($row['average_price'], 2) }} ج.م</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><strong>المجموع</strong></td>
                                    <td><strong>{{ $data->sum('units_sold') }}</strong></td>
                                    <td><strong>{{ number_format($data->sum('revenue'), 2) }} ج.م</strong></td>
                                    <td><strong>{{ number_format($data->avg('average_price'), 2) }} ج.م</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($type === 'revenue')
    @push('scripts')
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($data->pluck('date')),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($data->pluck('total')),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' ج.م';
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
    @endpush
@endif
@endsection
