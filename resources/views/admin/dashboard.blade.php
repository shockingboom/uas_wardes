@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4">📊 Dashboard Ringkasan</h3>
<div class="row">
  {{-- <div class="col-md-3">
    <div class="card p-4 text-center">
      <h5>Total Pesanan</h5>
      <h3 class="fw-bold text-primary">{{ $totalPesanan }}</h3>
    </div>
  </div> --}}
  <div class="col-md-6">
    <div class="card p-4 text-center">
      <h5>Total Produk</h5>
      <h3 class="fw-bold text-success">{{ $totalProduk }}</h3>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-4 text-center">
      <h5>Total Meja</h5>
      <h3 class="fw-bold text-info">{{ $totalMeja }}</h3>
    </div>
  </div>
  {{-- <div class="col-md-3">
    <div class="card p-4 text-center">
      <h5>Total Pendapatan</h5>
      <h3 class="fw-bold text-warning">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
    </div>
  </div> --}}
</div>

<div class="mt-4">
  <div class="alert alert-info">
    <strong>ℹ️ Info:</strong> Fitur pesanan akan dibuat nanti. Saat ini hanya menampilkan data produk dan meja.
  </div>
</div>
@endsection
