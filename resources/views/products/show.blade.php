@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">تفاصيل المنتج</h2>
                    <div>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">تعديل المنتج</a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">العودة إلى القائمة</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">رمز المنتج:</div>
                        <div class="col-md-9">{{ $product->code }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">الاسم:</div>
                        <div class="col-md-9">{{ $product->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">الوصف:</div>
                        <div class="col-md-9">{{ $product->description }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">السعر:</div>
                        <div class="col-md-9">{{ number_format($product->unit_price, 2) }} ج.م</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">تاريخ الإنشاء:</div>
                        <div class="col-md-9">{{ $product->created_at->format('F d, Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">تاريخ آخر تحديث:</div>
                        <div class="col-md-9">{{ $product->updated_at->format('F d, Y') }}</div>
                    </div>

                    <h3 class="mt-4 mb-3">أحدث الاستخدامات في الفواتير</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>التاريخ</th>
                                    <th>الكمية</th>
                                    <th>المجموع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->invoiceItems()->with('invoice')->latest()->take(5)->get() as $item)
                                    <tr>
                                        <td>{{ $item->invoice->invoice_number }}</td>
                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->total, 2) }} ج.م</td>
                                        <td>
                                            <a href="{{ route('invoices.show', $item->invoice) }}" class="btn btn-info btn-sm">عرض الفاتورة</a>
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
