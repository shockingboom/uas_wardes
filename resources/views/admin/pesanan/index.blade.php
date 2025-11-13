@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning border-start border-4 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history text-warning fs-1 me-3"></i>
                                    <div>
                                        <p class="text-muted mb-1 small">Menunggu Pembayaran</p>
                                        <h3 class="text-warning mb-0">{{ $pesanan->where('status', 'waiting_payment')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary border-start border-4 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-arrow-repeat text-primary fs-1 me-3"></i>
                                    <div>
                                        <p class="text-muted mb-1 small">Sedang Diproses</p>
                                        <h3 class="text-primary mb-0">{{ $pesanan->where('status', 'processing')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-success border-start border-4 bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle text-success fs-1 me-3"></i>
                                    <div>
                                        <p class="text-muted mb-1 small">Selesai</p>
                                        <h3 class="text-success mb-0">{{ $pesanan->where('status', 'completed')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="mb-4 d-flex flex-wrap gap-2">
                    <button onclick="filterOrders('all')" class="btn btn-secondary">Semua</button>
                    <button onclick="filterOrders('waiting_payment')" class="btn btn-warning">Menunggu Pembayaran</button>
                    <button onclick="filterOrders('processing')" class="btn btn-primary">Diproses</button>
                    <button onclick="filterOrders('completed')" class="btn btn-success">Selesai</button>
                </div>

                <!-- Table Pesanan -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Meja</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan as $order)
                            <tr class="order-row" data-status="{{ $order->status }}">
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    {{ $order->table->nomer_meja }}
                                    @if($order->customer_name)
                                    <br><small class="text-muted">{{ $order->customer_name }}</small>
                                    @endif
                                    @if($order->customer_phone)
                                    <br><small class="text-muted">📱 {{ $order->customer_phone }}</small>
                                    @endif
                                    <br><small class="fw-bold {{ $order->payment_method === 'qris' ? 'text-primary' : 'text-success' }}">
                                        {{ $order->payment_method === 'qris' ? '💳 QRIS' : '💵 Cash' }}
                                    </small>
                                </td>
                                <td>
                                    <details>
                                        <summary class="text-primary" style="cursor: pointer;">{{ count($order->items) }} item(s)</summary>
                                        <ul class="mt-2 small">
                                            @foreach($order->items as $item)
                                            <li>{{ $item['name'] }} x{{ $item['quantity'] }} - Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                        @if($order->notes)
                                        <p class="mt-2 small text-muted fst-italic">Catatan: {{ $order->notes }}</p>
                                        @endif
                                    </details>
                                </td>
                                <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($order->status === 'waiting_payment')
                                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge bg-primary">Diproses</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td><small>{{ $order->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    <form action="{{ route('admin.pesanan.status', $order) }}" method="POST" id="form-{{ $order->id }}" onsubmit="return confirmStatusChange(event, '{{ $order->status }}')">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="handleStatusChange(this, '{{ $order->id }}', '{{ $order->status }}')">
                                            <option value="waiting_payment" {{ $order->status === 'waiting_payment' ? 'selected' : '' }}>⏳ Menunggu Pembayaran</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>🔄 Diproses</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Selesai</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Belum ada pesanan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterOrders(status) {
            const rows = document.querySelectorAll('.order-row');
            rows.forEach(row => {
                row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
            });
        }

        function handleStatusChange(selectElement, orderId, currentStatus) {
            const newStatus = selectElement.value;
            
            // Jika status tidak berubah, jangan submit
            if (currentStatus === newStatus) {
                return;
            }
            
            // Submit form dengan confirmation
            const form = document.getElementById('form-' + orderId);
            if (form) {
                form.submit();
            }
        }

        function confirmStatusChange(event, currentStatus) {
            const form = event.target;
            const newStatus = form.querySelector('select[name="status"]').value;
            
            // Jika status tidak berubah, cancel submit
            if (currentStatus === newStatus) {
                event.preventDefault();
                return false;
            }
            
            let message = 'Yakin ingin mengubah status pesanan?';
            
            if (currentStatus === 'waiting_payment' && newStatus === 'processing') {
                message = '⚠️ KONFIRMASI PEMBAYARAN\n\n✅ Pembayaran sudah diterima dari customer?\n📱 Invoice akan otomatis dikirim ke WhatsApp customer\n\nLanjutkan?';
            } else if (newStatus === 'completed') {
                message = '✅ Pesanan sudah selesai dan dihidangkan?\n\nLanjutkan?';
            } else if (newStatus === 'waiting_payment') {
                message = '⚠️ Kembalikan status ke Menunggu Pembayaran?\n\nLanjutkan?';
            }
            
            if (!confirm(message)) {
                event.preventDefault();
                // Reset dropdown ke nilai semula
                form.querySelector('select[name="status"]').value = currentStatus;
                return false;
            }
            
            return true;
        }
    </script>
@endsection
