@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-4">📊 Dashboard Ringkasan</h3>

<div class="row">
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
</div>

{{-- 🔥 Tambahan Statistik --}}
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm text-center">
            <h5>📅 Penjualan Hari Ini</h5>
            <p class="mb-1">Jumlah Pesanan</p>
            <h3 class="fw-bold text-primary">{{ $jumlahPesananHariIni }}</h3>
            <p class="mb-1">Total Pendapatan</p>
            <h4 class="fw-bold text-success">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</h4>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 shadow-sm text-center">
            <h5>📆 Penjualan 7 Hari Terakhir</h5>
            <p class="mb-1">Jumlah Pesanan</p>
            <h3 class="fw-bold text-primary">{{ $jumlahPesananMingguIni }}</h3>
            <p class="mb-1">Total Pendapatan</p>
            <h4 class="fw-bold text-success">Rp {{ number_format($totalMingguIni, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>


{{-- 📈 Grafik --}}
<div class="card mt-4 p-3 shadow-sm">
    <h5 class="mb-3">📈 Grafik Penjualan 7 Hari Terakhir</h5>
    <canvas id="salesChart" height="100"></canvas>
</div>


{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartLabels = @json($chartData->pluck('date'));
    const chartValues = @json($chartData->pluck('total'));

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: chartValues,
                borderWidth: 3,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>

@endsection
