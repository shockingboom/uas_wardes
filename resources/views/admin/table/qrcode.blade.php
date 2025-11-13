@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <a href="{{ route('admin.table.index') }}" class="btn btn-secondary">
                ← Kembali ke Daftar Meja
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📱 QR Code - Meja {{ $table->nomer_meja }}</h4>
                </div>
                <div class="card-body text-center">
                    {{-- QR Code Display --}}
                    <div class="mb-4 p-4 bg-light rounded">
                        <div class="d-inline-block">
                            {!! $qrCode !!}
                        </div>
                    </div>

                    {{-- Table Info --}}
                    <div class="alert alert-info">
                        <h5 class="mb-3">Informasi Meja</h5>
                        <p class="mb-2">
                            <strong>Nomor Meja:</strong> 
                            <span class="badge bg-primary fs-5">{{ $table->nomer_meja }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Token:</strong> 
                            <code class="bg-white p-2 rounded">{{ $table->token }}</code>
                        </p>
                        <p class="mb-0">
                            <strong>URL:</strong><br>
                            <small class="text-break">
                                <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                            </small>
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.table.qrcode.download', $table->id) }}" 
                           class="btn btn-success btn-lg">
                            📥 Download QR Code (PNG)
                        </a>
                        
                        <button onclick="printQRCode()" class="btn btn-primary btn-lg">
                            🖨️ Print QR Code
                        </button>
                        
                        <a href="{{ $url }}" target="_blank" class="btn btn-info">
                            🔗 Test Link Menu
                        </a>
                    </div>

                    {{-- Instructions --}}
                    <div class="alert alert-warning mt-4 text-start">
                        <h6 class="alert-heading">📋 Cara Penggunaan:</h6>
                        <ol class="mb-0">
                            <li>Download atau print QR Code di atas</li>
                            <li>Cetak dan tempelkan di meja {{ $table->nomer_meja }}</li>
                            <li>Customer scan QR Code untuk akses menu</li>
                            <li>Customer bisa langsung pesan tanpa login</li>
                        </ol>
                    </div>

                    {{-- Regenerate Token --}}
                    <div class="mt-3">
                        <form action="{{ route('admin.table.regenerate', $table->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-warning"
                                    onclick="return confirm('Generate ulang token akan mengubah QR Code. Yakin?')">
                                🔄 Generate Ulang Token & QR Code
                            </button>
                        </form>
                        <small class="text-muted">
                            * QR Code lama tidak akan berfungsi setelah token di-generate ulang
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printQRCode() {
    // Create a new window for printing
    var printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>QR Code - Meja {{ $table->nomer_meja }}</title>
            <style>
                @media print {
                    body {
                        margin: 0;
                        padding: 20px;
                        text-align: center;
                        font-family: Arial, sans-serif;
                    }
                    .no-print {
                        display: none;
                    }
                }
                body {
                    margin: 0;
                    padding: 20px;
                    text-align: center;
                    font-family: Arial, sans-serif;
                }
                h1 {
                    margin: 20px 0;
                    color: #333;
                }
                .qr-container {
                    border: 5px solid #333;
                    padding: 30px;
                    display: inline-block;
                    margin: 20px auto;
                    background: white;
                }
                img {
                    display: block;
                    margin: 0 auto;
                }
                .instructions {
                    margin-top: 30px;
                    font-size: 18px;
                    color: #666;
                }
                .button-container {
                    margin: 20px 0;
                }
                button {
                    padding: 10px 30px;
                    font-size: 16px;
                    cursor: pointer;
                    background: #007bff;
                    color: white;
                    border: none;
                    border-radius: 5px;
                }
                button:hover {
                    background: #0056b3;
                }
            </style>
        </head>
        <body>
            <h1>🍽️ Wardes Restaurant</h1>
            <h2>Meja {{ $table->nomer_meja }}</h2>
            
            <div class="qr-container">
                <img src="{{ route('admin.table.qrcode.generate', $table->id) }}" 
                     alt="QR Code" 
                     width="400">
            </div>
            
            <div class="instructions">
                <p><strong>Scan QR Code untuk melihat menu</strong></p>
                <p>dan pesan langsung dari meja Anda</p>
            </div>
            
            <div class="button-container no-print">
                <button onclick="window.print()">🖨️ Print</button>
                <button onclick="window.close()" style="background: #6c757d;">✖ Close</button>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    
    // Auto print after image loads
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.focus();
        }, 250);
    };
}
</script>

@endsection
