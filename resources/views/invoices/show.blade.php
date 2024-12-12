@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">تفاصيل الفاتورة رقم {{ $invoice->invoice_number }}</h2>
                    <div>
                        <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-dark">تحميل PDF</a>
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">تعديل الفاتورة</a>
                        <a href="{{ route('invoices.index') }}" class="btn btn-primary">عودة للقائمة</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">

                        <div class="col-sm-6">
                            <h6 class="mb-3">إلى:</h6>
                            <div>
                                <strong>{{ $invoice->client->name }}</strong>
                            </div>
                            <div> <b>كود العميل:</b> {{ $invoice->client->code }}</div>
                            <div> <b>العنوان:</b> {{ $invoice->client->address }}</div>
                            <div> <b>البريد الإلكتروني:</b> {{ $invoice->client->email }}</div>
                            <div> <b>الهاتف:</b> {{ $invoice->client->phone }}</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <div><strong>تاريخ الفاتورة:</strong> {{ $invoice->invoice_date ? ($invoice->invoice_date->format('Y/m/d')) : 'غير محدد' }}</div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الكود</th>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>المجموع</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @if($invoice->items && $invoice->items->isNotEmpty())
                                    @foreach($invoice->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product->code ?? 'غير محدد' }}</td>
                                        <td>{{ $item->product->name ?? 'غير محدد' }}</td>
                                        <td>{{ $item->quantity ?? 'غير محدد' }}</td>
                                        <td>{{ number_format($item->price ?? 0, 2) }} ج.م</td>
                                        <td>{{ number_format($item->total ?? 0, 2) }} ج.م</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">لا توجد عناصر في هذه الفاتورة.</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-end"><strong>المجموع الفرعي:</strong></td>
                                    <td>{{ number_format($invoice->subtotal ?? 0, 2) }} ج.م</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-end"><strong>خصم :</strong></td>
                                    <td>{{ number_format($invoice->discount_value ?? 0, 2) }} ج.م</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-end"><strong>الضريبة (14%):</strong></td>
                                    <td>{{ number_format($invoice->tax_amount ?? 0, 2) }} ج.م</td>
                                </tr>
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-end"><strong>الإجمالي:</strong></td>
                                    <td><strong>{{ number_format($invoice->total ?? 0, 2) }} ج.م</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->notes)
                    <div class="mb-4">
                        <h6>ملاحظات:</h6>
                        <p>{{ $invoice->notes }}</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
