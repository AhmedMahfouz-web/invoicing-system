@extends('layouts.app')
@section('page-content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">تعديل الفاتورة رقم {{ $invoice->invoice_number }}</h2>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">عودة للفواتير</a>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_id" class="form-label">العميل</label>
                                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                    <option value="">حدد العميل</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">تاريخ الفاتورة</label>
                                <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold">نسبة الضريبة</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('tax-rate') is-invalid @enderror" id="tax-rate" name="tax-rate" value="14" min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('tax-rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold">الخصم</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="0" min="0" step="0.01">
                                    <span class="input-group-text">ج.م</span>
                                </div>
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="mb-0">عناصر الفاتورة</h4>
                        </div>
                        <div class="card-body">
                            <div id="invoice-items">
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label">المنتج</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">الكمية</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">سعر المنتج</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">المجموع</label>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">العمليات</label>
                                    </div>
                                </div>
                                @foreach($invoice->items as $index => $item)
                                    <div class="invoice-item row mb-2">
                                        <div class="col-md-4">
                                            <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                                <option value="">حدد المنتج</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control quantity" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control unit-price" step="0.01" value="{{ $item->price }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control item-total" step="0.01" value="{{ $item->total }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-item">حذف</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-success mt-2" id="add-item">إضافة عنصر</button>
                        </div>
                    </div>

                    <div class="row justify-content-end mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>المجموع الفرعي:</span>
                                        <span id="subtotal">{{ number_format($invoice->subtotal, 2) }} ج.م</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>خصم :</span>
                                        <span id="tax">{{ number_format($invoice->discount, 2) }} ج.م</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>ضريبة ({{ $invoice->tax_rate }}%):</span>
                                        <span id="tax">{{ number_format($invoice->tax_amount, 2) }} ج.م</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <strong>المجموع:</strong>
                                        <strong id="total">{{ number_format($invoice->total, 2) }} ج.م</strong>
                                    </div>
                                    <input type="hidden" name="subtotal" id="subtotal-input" value="{{ $invoice->subtotal }}">
                                    <input type="hidden" name="tax_amount" id="tax-input" value="{{ $invoice->tax_amount }}">
                                    <input type="hidden" name="total" id="total-input" value="{{ $invoice->total }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary me-md-2">إلغاء</a>
                        <button type="submit" class="btn btn-primary">تحديث الفاتورة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const taxRate = {{ config('invoice.tax_rate', 15) }} / 100;
    let itemCount = {{ count($invoice->items) }};

    // Add new item
    document.getElementById('add-item').addEventListener('click', function() {
        const template = document.querySelector('.invoice-item').cloneNode(true);
        template.querySelector('.product-select').name = `items[${itemCount}][product_id]`;
        template.querySelector('.quantity').name = `items[${itemCount}][quantity]`;
        template.querySelector('.quantity').value = 1;
        template.querySelector('.unit-price').value = '';
        template.querySelector('.item-total').value = '';
        document.getElementById('invoice-items').appendChild(template);
        itemCount++;
        attachEventListeners();
    });

    // Remove item
    function attachEventListeners() {
        document.querySelectorAll('.remove-item').forEach(button => {
            button.onclick = function() {
                if (document.querySelectorAll('.invoice-item').length > 1) {
                    this.closest('.invoice-item').remove();
                    calculateTotals();
                }
            };
        });

        document.querySelectorAll('.product-select').forEach(select => {
            select.onchange = function() {
                const row = this.closest('.invoice-item');
                const price = this.options[this.selectedIndex].dataset.price || 0;
                row.querySelector('.unit-price').value = price;
                calculateRowTotal(row);
            };
        });

        document.querySelectorAll('.quantity').forEach(input => {
            input.onchange = function() {
                calculateRowTotal(this.closest('.invoice-item'));
            };
        });
    }

    function calculateRowTotal(row) {
        const quantity = row.querySelector('.quantity').value;
        const unitPrice = row.querySelector('.unit-price').value;
        const total = quantity * unitPrice;
        row.querySelector('.item-total').value = total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(input => {
            subtotal += parseFloat(input.value || 0);
        });

        const taxAmount = subtotal * taxRate;
        const total = subtotal + taxAmount;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${taxAmount.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;

        document.getElementById('subtotal-input').value = subtotal.toFixed(2);
        document.getElementById('tax-input').value = taxAmount.toFixed(2);
        document.getElementById('total-input').value = total.toFixed(2);
    }

    attachEventListeners();
});
</script>
@endpush
@endsection
