<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Table;
use App\Models\Pesanan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // BAGIAN LAMA
        $totalProduk = Item::count();
        $totalMeja = Table::count();

        // STATISTIK PENJUALAN
        $today = Carbon::today();
        $weekAgo = Carbon::now()->subDays(7);

        // Hari Ini
        $totalHariIni = Pesanan::whereDate('created_at', $today)->sum('total');
        $jumlahPesananHariIni = Pesanan::whereDate('created_at', $today)->count();

        // 7 Hari Terakhir
        $totalMingguIni = Pesanan::whereBetween('created_at', [$weekAgo, Carbon::now()])->sum('total');
        $jumlahPesananMingguIni = Pesanan::whereBetween('created_at', [$weekAgo, Carbon::now()])->count();

        // Grafik 7 hari terakhir
        $chartData = Pesanan::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', $weekAgo)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Kirim ke View
        return view('admin.dashboard', compact(
            'totalProduk',
            'totalMeja',
            'totalHariIni',
            'jumlahPesananHariIni',
            'totalMingguIni',
            'jumlahPesananMingguIni',
            'chartData'
        ));
    }
}
