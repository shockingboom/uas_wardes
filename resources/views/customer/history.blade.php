<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pesanan - Warung Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Poppins', sans-serif;
        }
        .header {
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .order-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-processing {
            background-color: #cfe2ff;
            color: #084298;
        }
        .status-completed {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #842029;
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
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="/?t={{ $token }}" class="btn btn-sm btn-outline-primary mb-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu
                    </a>
                    <h1 class="h3 fw-bold text-success mb-0">
                        <i class="bi bi-clock-history me-2"></i>History Pesanan
                    </h1>
                </div>
                @if($table)
                <div class="text-end">
                    <p class="text-muted mb-1 small">Meja</p>
                    <p class="h4 fw-bold text-success mb-0">{{ $table->nomer_meja }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container my-5">
        @if($error)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}
        </div>
        @else
            @forelse($orders as $order)
            <div class="card order-card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Order #{{ $order->id }}</h5>
                            <p class="text-muted mb-1 small">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                            @if($order->customer_name)
                            <p class="text-muted mb-0 small">
                                <i class="bi bi-person-fill me-1"></i>
                                {{ $order->customer_name }}
                            </p>
                            @endif
                        </div>
                        <span class="status-badge 
                            @if($order->status === 'pending') status-pending
                            @elseif($order->status === 'waiting_payment') status-processing
                            @elseif($order->status === 'processing') status-processing
                            @elseif($order->status === 'completed') status-completed
                            @elseif($order->status === 'cancelled') status-cancelled
                            @endif">
                            @if($order->status === 'pending')
                                <i class="bi bi-clock-fill me-1"></i>Pending
                            @elseif($order->status === 'waiting_payment')
                                <i class="bi bi-credit-card me-1"></i>Menunggu Pembayaran
                            @elseif($order->status === 'processing')
                                <i class="bi bi-hourglass-split me-1"></i>Diproses
                            @elseif($order->status === 'waiting_payment')
                                <i class="bi bi-check-circle-fill me-1"></i>Menunggu Pembayaran
                            @elseif($order->status === 'completed')
                                <i class="bi bi-check-circle-fill me-1"></i>Selesai
                            @elseif($order->status === 'cancelled')
                                <i class="bi bi-x-circle-fill me-1"></i>Dibatalkan
                            @endif
                        </span>
                    </div>

                    <div class="border-top pt-3">
                        <h6 class="fw-semibold mb-3">Items:</h6>
                        @foreach($order->items as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <p class="fw-medium mb-1">{{ $item['name'] }}</p>
                                <p class="text-muted mb-0 small">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}
                                </p>
                            </div>
                            <p class="fw-bold text-success mb-0">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    @if($order->notes)
                    <div class="mt-3 p-3 bg-light rounded">
                        <p class="text-muted mb-1 small fw-semibold">
                            <i class="bi bi-sticky-fill me-1"></i>Catatan:
                        </p>
                        <p class="mb-0 small">{{ $order->notes }}</p>
                    </div>
                    @endif

                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total:</span>
                            <span class="h4 fw-bold text-success mb-0">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card order-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-basket text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3 mb-3">Belum ada pesanan</h5>
                    <a href="/?t={{ $token }}" class="btn btn-success px-4">
                        <i class="bi bi-cart-plus me-2"></i>Mulai Pesan
                    </a>
                </div>
            </div>
            @endforelse
        @endif
    </div>

    <footer>
        <p class="mb-0">© {{ date('Y') }} Warung Desa. Semua hak dilindungi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>