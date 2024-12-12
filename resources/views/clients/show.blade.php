@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">تفاصيل العميل</h2>
                    <div>
                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">تعديل العميل</a>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">كود العميل:</div>
                        <div class="col-md-9">{{ $client->code }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">الاسم:</div>
                        <div class="col-md-9">{{ $client->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">البريد الإلكتروني:</div>
                        <div class="col-md-9">{{ $client->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">الهاتف:</div>
                        <div class="col-md-9">{{ $client->phone }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">العنوان:</div>
                        <div class="col-md-9">{{ $client->address }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">تاريخ الإنشاء:</div>
                        <div class="col-md-9">{{ $client->created_at->format('F d, Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">تاريخ آخر تحديث:</div>
                        <div class="col-md-9">{{ $client->updated_at->format('F d, Y') }}</div>
                    </div>

                    <h3 class="mt-4 mb-3">الفواتير الأخيرة</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>المجموع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->invoices()->latest()->take(5)->get() as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>
                                        <td>
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info btn-sm">عرض</a>
                                        </td>
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
@endsection
