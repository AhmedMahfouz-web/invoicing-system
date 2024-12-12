@extends('layouts.app')

@section('page-content')
<div class="container mt-4">
    <h2 class="mb-4">تحميل مجموعة فواتير</h2>
    <form action="{{ route('invoices.bulk-download') }}" method="POST" class="border p-4 rounded shadow">
        @csrf
        <div class="form-group">
            <label for="start_date">حتي تاريخ:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_date">من تاريخ:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">تحميل الفواتير</button>
    </form>
</div>
@endsection
