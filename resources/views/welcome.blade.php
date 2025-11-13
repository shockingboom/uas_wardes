
@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Poppins', sans-serif;
        }
        .meja-header {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .alert-meja {
            background-color: #fff8e1;
            color: #856404;
            border: 1px solid #ffeeba;
            border-radius: 8px;
            margin: 15px 0;
            padding: 12px 15px;
            font-size: 14px;
        }
        .search-bar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 10px 15px;
            margin-bottom: 15px;
        }
        .search-bar input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 15px;
        }
        .menu-card {
            border: none;
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .menu-card img {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            height: 180px;
            object-fit: cover;
        }
        .btn-keranjang {
            background-color: #c74a2e;
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
        }
        footer {
            background: #198754;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <!-- Header Meja -->
    <div class="meja-header">
        <div class="container">
            <label class="fw-semibold text-muted mb-1">Meja</label>
            <div class="d-flex align-items-center">
                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                <span class="fw-semibold">Nomor</span>
            </div>

            <!-- 🔍 Form Pencarian -->
            <form action="{{ route('home') }}" method="GET" class="search-bar mt-3">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari hidangan favorit kamu..." 
                    value="{{ request('search') }}"
                >
            </form>

            <!-- Pesan Meja -->
            <div class="alert-meja">
                Meja tidak terdeteksi. Silakan scan QR code meja Anda.
            </div>
        </div>
    </div>

    <!-- Menu -->
    <div class="container my-5">
        <h4 class="fw-bold mb-3 text-success">Menu</h4>

        @if(request('search'))
            <p class="text-muted">Menampilkan hasil pencarian untuk: 
                <strong>{{ request('search') }}</strong>
            </p>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-pills mb-3" id="menuTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#semua">Semua</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#makanan">Makanan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#minuman">Minuman</button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Semua -->
            <div class="tab-pane fade show active" id="semua">
                <div class="row g-4">
                    @forelse ($items as $item)
                        <div class="col-md-3">
                            <div class="card menu-card">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold">{{ $item->nama }}</h5>
                                    <p class="text-muted mb-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    <span class="badge bg-success">{{ ucfirst($item->kategori) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Tidak ada menu ditemukan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Makanan -->
            <div class="tab-pane fade" id="makanan">
                <div class="row g-4">
                    @foreach ($items->where('kategori', 'makanan') as $item)
                        <div class="col-md-3">
                            <div class="card menu-card">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold">{{ $item->nama }}</h5>
                                    <p class="text-muted mb-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Minuman -->
            <div class="tab-pane fade" id="minuman">
                <div class="row g-4">
                    @foreach ($items->where('kategori', 'minuman') as $item)
                        <div class="col-md-3">
                            <div class="card menu-card">
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold">{{ $item->nama }}</h5>
                                    <p class="text-muted mb-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Keranjang -->
    <div class="text-center mb-5">
        <button class="btn btn-keranjang shadow-sm">
            🛒 Buka Keranjang
        </button>
    </div>

    <footer>
        <p class="mb-0">© {{ date('Y') }} Warung Desa. Semua hak dilindungi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
@endsection
