@extends('layouts.app')

@section('page-content')
<div class="container py-4">
    <h2 class="mb-4 text-center text-dark fw-bold">التقارير</h2>
    <div class="card border-0 shadow-sm bg-white rounded-3">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0 text-dark fw-bold">التقارير</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('reports.generate') }}" method="GET" id="reportForm">
                <div class="mb-3">
                    <label for="report_type" class="form-label text-dark fw-bold">نوع التقرير</label>
                    <select class="form-select @error('report_type') is-invalid @enderror" id="report_type" name="report_type" required>
                        <option value="">اختر نوع التقرير</option>
                        <option value="sales" {{ old('report_type') == 'sales' ? 'selected' : '' }}>مبيعات</option>
                        <option value="revenue" {{ old('report_type') == 'revenue' ? 'selected' : '' }}>إيرادات</option>
                        <option value="client" {{ old('report_type') == 'client' ? 'selected' : '' }}>عميل</option>
                        <option value="product" {{ old('report_type') == 'product' ? 'selected' : '' }}>منتج</option>
                    </select>
                    @error('report_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="date_from" class="form-label text-dark fw-bold">التاريخ من</label>
                    <input type="date" class="form-control @error('date_from') is-invalid @enderror" id="date_from" name="date_from" value="{{ old('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required>
                    @error('date_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="date_to" class="form-label text-dark fw-bold">التاريخ إلى</label>
                    <input type="date" class="form-control @error('date_to') is-invalid @enderror" id="date_to" name="date_to" value="{{ old('date_to', now()->format('Y-m-d')) }}" required>
                    @error('date_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="client_id" class="form-label text-dark fw-bold">العميل</label>
                        <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                            <option value="">جميع العملاء</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="product_id" class="form-label text-dark fw-bold">المنتج</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id">
                            <option value="">جميع المنتجات</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label text-dark fw-bold">الحالة</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>مرسل</option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="format" class="form-label text-dark fw-bold">التنسيق</label>
                        <select class="form-select @error('format') is-invalid @enderror" id="format" name="format" required>
                            <option value="view" {{ old('format') == 'view' ? 'selected' : '' }}>عرض اون لاين</option>
                            <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>إكسل</option>
                            <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>بي دي إف</option>
                        </select>
                        @error('format')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-3">إنشاء تقرير</button>
                </div>
            </form>
            <div class="d-flex justify-content-between align-items-center">
                {{ $reports->links('vendor.pagination.bootstrap-4') }}
            </div>
            <div class="d-flex justify-content-between align-items-center">
                {{ $reports->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportType = document.getElementById('report_type');
    const clientFilter = document.getElementById('client_id').parentElement;
    const productFilter = document.getElementById('product_id').parentElement;
    const statusFilter = document.getElementById('status').parentElement;

    reportType.addEventListener('change', function() {
        // Hide all filters first
        clientFilter.style.display = 'none';
        productFilter.style.display = 'none';
        statusFilter.style.display = 'none';

        // Show relevant filters based on report type
        switch(this.value) {
            case 'sales':
                statusFilter.style.display = 'block';
                break;
            case 'client':
                clientFilter.style.display = 'block';
                break;
            case 'product':
                productFilter.style.display = 'block';
                break;
        }
    });

    // Trigger change event to set initial state
    reportType.dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection
