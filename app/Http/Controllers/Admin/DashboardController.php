<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index()
{
    // $totalPesanan = \App\Models\Pesanan::count();
    $totalProduk = \App\Models\Item::count();
    // $totalPendapatan = \App\Models\Pesanan::sum('total_harga');
    $totalMeja = \App\Models\Table::count();

    return view('admin.dashboard', compact('totalProduk', 'totalMeja'));
}


}
