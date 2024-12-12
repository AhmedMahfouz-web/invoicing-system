@extends('layouts.app')

@section('page-content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">قائمة الفواتير</h2>
                <div>
                    <a href="{{ route('invoices.export') }}" class="btn btn-success me-2">تصدير Excel</a>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">إنشاء فاتورة جديدة</a>
                    <a href="{{ route('invoices.bulk.download') }}" class="btn btn-info">تحميل مجموعة فواتير</a>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search and Filter -->
                <form action="{{ route('invoices.index') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="بحث برقم الفاتورة" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="client" class="form-select">
                                <option value="">جميع العملاء</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" placeholder="من تاريخ" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" placeholder="إلى تاريخ" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">تصفية</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>رقم الفاتورة</th>
                                <th>العميل</th>
                                <th>تاريخ الفاتورة</th>
                                <th>المبلغ الفرعي</th>
                                <th>الضريبة</th>
                                <th>المبلغ الإجمالي</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->client->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}</td>
                                <td>{{ number_format($invoice->subtotal, 2) }} ج.م</td>
                                <td>{{ number_format($invoice->tax_amount, 2) }} ج.م</td>
                                <td>{{ number_format($invoice->total, 2) }} ج.م</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info btn-sm">عرض</a>
                                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary btn-sm">تعديل</a>
                                        <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-secondary btn-sm">PDF</a>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    {{ $invoices->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
