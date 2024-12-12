@extends('layouts.app')

@section('content')

    <div class="py-12 mt-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <!-- Summary Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card border-0 bg-light shadow-sm" style="height: 200px;">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave"></i> إجمالي الإيرادات</h5>
                                        <h5 class="mt-2"><b>{{ number_format($stats['total_revenue'], 2) }} ج.م</b></h5>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $stats['revenue_percentage'] }}%;" aria-valuenow="{{ $stats['revenue_percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-light shadow-sm" style="height: 200px;">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-0"><i class="fas fa-file-invoice"></i> عدد الفواتير</h5>
                                        <h5 class="mt-2"><b>{{ $stats['total_invoices'] }}</b></h5>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stats['invoice_percentage'] }}%;" aria-valuenow="{{ $stats['invoice_percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-light shadow-sm" style="height: 200px;">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-0"><i class="fas fa-users"></i> عدد العملاء</h5>
                                        <h5 class="mt-2"><b>{{ $stats['total_clients'] }}</b></h5>
                                        <div class="progress mt-2">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['client_percentage'] }}%;" aria-valuenow="{{ $stats['client_percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">الإيرادات الشهرية</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="revenueChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- Recent Invoices -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">أحدث الفواتير</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>رقم الفاتورة</th>
                                                        <th>العميل</th>
                                                        <th>التاريخ</th>
                                                        <th>المبلغ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentInvoices as $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->invoice_number }}</td>
                                                        <td>{{ $invoice->client->name }}</td>
                                                        <td>{{ $invoice->invoice_date->format('Y/m/d') }}</td>
                                                        <td>{{ number_format($invoice->total, 2) }} ج.م</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Top Clients -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">أفضل العملاء</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>العميل</th>
                                                        <th>عدد الفواتير</th>
                                                        <th>إجمالي المبيعات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topClients as $client)
                                                    <tr>
                                                        <td>{{ $client->name }}</td>
                                                        <td>{{ $client->invoices_count }}</td>
                                                        <td>{{ number_format($client->total_revenue, 2) }} ج.م</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">المنتجات الأكثر مبيعاً</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>المنتج</th>
                                                        <th>الكمية المباعة</th>
                                                        <th>إجمالي المبيعات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topProducts as $product)
                                                    <tr>
                                                        <td>{{ $product->name }}</td>
                                                        <td>{{ $product->total_quantity }}</td>
                                                        <td>{{ number_format($product->total_revenue, 2) }} ج.م</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script  src="{{ asset('assets/js/chart.js') }}"></script>
<script >
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'إبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
                datasets: [{
                    label: 'الإيرادات الشهرية',
                    data: @json(array_values($chartData)),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' ج.م';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString() + ' ج.م';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
