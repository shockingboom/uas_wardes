<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    /**
     * menampilkan seluruh meja yang tersedia
     */
    public function index()
    {
        $tables = Table::latest()->get();
        return view('admin.table.index', compact('tables'));
    }

    /**
     * menampilkan form buat tabel
     */
    public function create()
    {
        return view('admin.table.create');
    }

    /**
     * membuat data meja baru di database
     */
    public function store(Request $request)
    {
        //validasi nomer meja
        $validated = $request->validate([
            'nomer_meja' => 'required|string|max:255|unique:tables,nomer_meja',
        ]);

        //buat nomer meja dan token meja
        Table::create([
            'nomer_meja' => $validated['nomer_meja'],
            'token' => Table::generateToken(),
        ]);

        return redirect()->route('admin.table.index')->with('success', 'Meja berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit meja
     */
    public function edit(Table $table)
    {
        return view('admin.table.edit', compact('table'));
    }

    /**
     * update meja
     */
    public function update(Request $request, Table $table)
    {
        //ini bakal ngevalidasi meja
        $validated = $request->validate([
            'nomer_meja' => 'required|string|max:255|unique:tables,nomer_meja,' . $table->id,
        ]);

        //kalo validasinya bener dia bakal di update nomer mejanya
        $table->update([
            'nomer_meja' => $validated['nomer_meja'],
        ]);

        return redirect()->route('admin.table.index')->with('success', 'Meja berhasil diperbarui!');
    }

    /**
     * hapus meja dari database
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('admin.table.index')->with('success', 'Meja berhasil dihapus!');
    }

    /**
     * generete ulang token buat meja
     */
    public function regenerateToken(Table $table)
    {
        $table->update([
            'token' => Table::generateToken(),
        ]);

        return redirect()->route('admin.table.index')->with('success', 'Token meja berhasil di-generate ulang!');
    }

    /**
     * Membuat kode QR untuk meja tertentu.
     *
     * Fungsi ini akan membuat kode qr baut meja
     * Kode QR dihasilkan dalam bentuk gambar SVG dengan ukuran dan margin tertentu.
     */
    public function generateQrCode(Table $table)
    {
        // Membuat URL yang akan disematkan di dalam QR Code 
        $url = url('/?t=' . $table->token);

        // Menghasilkan QR Code dengan ukuran 300px dan margin 2
        $qrCode = QrCode::size(300)
            ->margin(2)
            ->generate($url);
        // Mengembalikan hasil QR Code dalam format SVG ke browser
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Download kode qr sebagai svg
     */
    public function downloadQrCode(Table $table)
    {
        // Membuat URL unik berdasarkan token meja
        $url = url('/?t=' . $table->token);

        // Menghasilkan QR Code dalam format SVG dengan ukuran 500px dan margin 2
        $qrCode = QrCode::size(500)
            ->margin(2)
            ->generate($url);

        // Menentukan nama file hasil unduhan
        $filename = 'qr-meja-' . $table->nomer_meja . '.svg';

        // Mengembalikan respons berupa file SVG untuk diunduh browser
        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Menampilkan qr page
     */
    public function showQrCode(Table $table)
    {
        // Membuat URL berdasarkan token meja
        $url = url('/?t=' . $table->token);

        // Menghasilkan QR Code dengan ukuran 300px dan margin 2
        $qrCode = QrCode::size(300)
            ->margin(2)
            ->generate($url);

        // Mengirimkan data ke view untuk ditampilkan
        return view('admin.table.qrcode', compact('table', 'url', 'qrCode'));
    }
}
