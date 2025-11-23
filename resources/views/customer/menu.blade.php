<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu - Warung Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            height: 100%;
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
            width: 100%;
        }
        .btn-keranjang {
            background-color: #c74a2e;
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
            border: none;
            transition: all 0.3s;
        }
        .btn-keranjang:hover {
            background-color: #a33d24;
            transform: scale(1.05);
        }
        .btn-add-cart {
            background-color: #198754;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-add-cart:hover {
            background-color: #146c43;
        }
        .cart-badge {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        footer {
            background: #198754;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 40px;
        }
        /* Tambahan untuk fitur pencarian */
        .search-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            padding: 15px;
            margin-bottom: 20px;
        }
        .search-input-group {
            position: relative;
        }
        .search-input-group .form-control {
            border-radius: 25px;
            padding-left: 45px;
            border: 1px solid #e0e0e0;
        }
        .search-input-group .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }
        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        .no-results i {
            font-size: 48px;
            margin-bottom: 15px;
            color: #dee2e6;
        }
    </style>
</head>
<body>

    <!-- Header Meja -->
    <div class="meja-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <label class="fw-semibold text-muted mb-1 d-block">Meja</label>
                    <div class="d-flex align-items-center">
                        @if($table)
                        <i class="bi bi-geo-alt-fill text-success me-2"></i>
                        <span class="fw-bold fs-5">Nomor Meja {{ $table->nomer_meja }}</span>
                        @else
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                        <span class="fw-semibold">Tidak Terdeteksi</span>
                        @endif
                    </div>
                </div>
                @if($table && $token)
                <a href="/order/history?t={{ $token }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-clock-history me-1"></i> History
                </a>
                @endif
            </div>

            @if($error)
            <div class="alert alert-danger mb-0">
                {{ $error }}
            </div>
            @else
            <!-- Info Meja -->
            @if($table)
            <div class="alert-meja">
                <i class="bi bi-check-circle-fill me-2"></i>
                Selamat datang di Meja {{ $table->nomer_meja }}! Silakan pilih menu favorit Anda.
            </div>
            @else
            <div class="alert-meja bg-warning text-dark">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Meja tidak terdeteksi. Silakan scan QR code meja Anda.
            </div>
            @endif
            @endif
        </div>
    </div>

    @if(!$error)
    <!-- Menu -->
    <div class="container my-5">
        <h4 class="fw-bold mb-3 text-success">Menu</h4>

        <!-- Fitur Pencarian Menu -->
        <div class="search-container">
            <div class="search-input-group">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="search-menu" class="form-control" placeholder="Cari menu makanan atau minuman...">
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills mb-4" id="menuTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="semua-tab" data-bs-toggle="pill" data-bs-target="#semua" type="button" role="tab" aria-controls="semua" aria-selected="true">Semua</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="makanan-tab" data-bs-toggle="pill" data-bs-target="#makanan" type="button" role="tab" aria-controls="makanan" aria-selected="false">Makanan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="minuman-tab" data-bs-toggle="pill" data-bs-target="#minuman" type="button" role="tab" aria-controls="minuman" aria-selected="false">Minuman</button>
            </li>
        </ul>

        <div class="tab-content" id="menuTabContent">
            <!-- Semua -->
            <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab" tabindex="0">
                <div class="row g-4" id="semua-menu">
                    @forelse($items as $item)
                        <div class="col-md-3 col-sm-6 menu-item" data-name="{{ strtolower($item->nama) }}" data-category="{{ strtolower($item->kategori) }}">
                            <div class="card menu-card">
                                @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                    <span class="text-white">No Image</span>
                                </div>
                                @endif
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold mb-2">{{ $item->nama }}</h5>
                                    <p class="text-muted mb-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    <span class="badge bg-success mb-3">{{ ucfirst($item->kategori) }}</span>
                                    <button onclick="addToCart({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga }})"
                                            class="btn btn-add-cart w-100">
                                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Tidak ada menu ditemukan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Makanan -->
            <div class="tab-pane fade" id="makanan" role="tabpanel" aria-labelledby="makanan-tab" tabindex="0">
                <div class="row g-4" id="makanan-menu">
                    @forelse($items->where('kategori', 'Makanan') as $item)
                        <div class="col-md-3 col-sm-6 menu-item" data-name="{{ strtolower($item->nama) }}" data-category="{{ strtolower($item->kategori) }}">
                            <div class="card menu-card">
                                @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                    <span class="text-white">No Image</span>
                                </div>
                                @endif
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold mb-2">{{ $item->nama }}</h5>
                                    <p class="text-muted mb-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    <span class="badge bg-success mb-3">{{ ucfirst($item->kategori) }}</span>
                                    <button onclick="addToCart({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga }})"
                                            class="btn btn-add-cart w-100">
                                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Tidak ada makanan ditemukan.</p>
                    @endforelse
                </div>
            </div>

            <!-- Minuman -->
            <div class="tab-pane fade" id="minuman" role="tabpanel" aria-labelledby="minuman-tab" tabindex="0">
                <div class="row g-4" id="minuman-menu">
                    @forelse($items->where('kategori', 'Minuman') as $item)
                        <div class="col-md-3 col-sm-6 menu-item" data-name="{{ strtolower($item->nama) }}" data-category="{{ strtolower($item->kategori) }}">
                            <div class="card menu-card">
                                @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 180px; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                    <span class="text-white">No Image</span>
                                </div>
                                @endif
                                <div class="card-body text-center">
                                    <h5 class="fw-semibold mb-2">{{ $item->nama }}</h5>
                                    <p class="text-muted mb-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    <span class="badge bg-success mb-3">{{ ucfirst($item->kategori) }}</span>
                                    <button onclick="addToCart({{ $item->id }}, '{{ $item->nama }}', {{ $item->harga }})"
                                            class="btn btn-add-cart w-100">
                                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Tidak ada minuman ditemukan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Keranjang -->
    <div class="cart-badge">
        <button onclick="viewCart()" class="btn btn-keranjang shadow-lg position-relative">
            <i class="bi bi-cart3 fs-5"></i> Buka Keranjang
            <span id="cart-count" class="cart-count">0</span>
        </button>
    </div>

    <footer>
        <p class="mb-0">© {{ date('Y') }} Warung Desa. Semua hak dilindungi.</p>
    </footer>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const TOKEN = '{{ $token ?? "" }}';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tabs
            const triggerTabList = document.querySelectorAll('#menuTabs button[data-bs-toggle="pill"]');
            triggerTabList.forEach(triggerEl => {
                const tabTrigger = new bootstrap.Tab(triggerEl);
                
                triggerEl.addEventListener('click', event => {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
            
            // Initialize cart count
            updateCartCount();

            // Initialize search functionality
            initializeSearch();
        });

        // Search functionality
        function initializeSearch() {
            const searchInput = document.getElementById('search-menu');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                filterMenuItems(searchTerm);
            });

            // Clear search when changing tabs
            document.querySelectorAll('#menuTabs button[data-bs-toggle="pill"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function() {
                    searchInput.value = '';
                    filterMenuItems('');
                });
            });
        }

        function filterMenuItems(searchTerm) {
            const activeTab = document.querySelector('.tab-pane.active');
            const menuItems = activeTab.querySelectorAll('.menu-item');
            let hasVisibleItems = false;

            menuItems.forEach(item => {
                const itemName = item.getAttribute('data-name');
                const itemCategory = item.getAttribute('data-category');
                
                if (searchTerm === '' || itemName.includes(searchTerm) || itemCategory.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasVisibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message
            let noResultsMsg = activeTab.querySelector('.no-results-message');
            
            if (!hasVisibleItems) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-results no-results-message';
                    noResultsMsg.innerHTML = `
                        <i class="bi bi-search"></i>
                        <h5 class="fw-semibold mb-2">Menu tidak ditemukan</h5>
                        <p class="text-muted">Coba kata kunci lain atau periksa ejaan</p>
                    `;
                    activeTab.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Initialize cart from localStorage
        function getCart() {
            const cart = localStorage.getItem('cart_' + TOKEN);
            return cart ? JSON.parse(cart) : [];
        }

        function saveCart(cart) {
            localStorage.setItem('cart_' + TOKEN, JSON.stringify(cart));
            updateCartCount();
        }

        function addToCart(id, nama, harga) {
            let cart = getCart();
            
            // Check if item already in cart
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: id,
                    name: nama,
                    price: harga,
                    quantity: 1
                });
            }
            
            saveCart(cart);
            
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: nama + ' ditambahkan ke keranjang',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        function updateCartCount() {
            const cart = getCart();
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cart-count').textContent = totalItems;
        }

        function viewCart() {
            const cart = getCart();
            
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Keranjang Kosong',
                    text: 'Belum ada item dalam keranjang'
                });
                return;
            }

            let cartHtml = '<div class="text-start" style="max-height: 400px; overflow-y: auto;">';
            let total = 0;
            
            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                
                cartHtml += `
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <p class="fw-bold mb-1">${item.name}</p>
                                <p class="text-muted small mb-0">Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</p>
                            </div>
                            <button onclick="removeFromCart(${index})" class="btn btn-sm btn-danger ms-2">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button onclick="decreaseQuantity(${index})" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="fw-bold px-3">${item.quantity}</span>
                            <button onclick="increaseQuantity(${index})" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-plus"></i>
                            </button>
                            <span class="ms-auto fw-bold text-success">Rp ${subtotal.toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                `;
            });
            
            cartHtml += `
                <div class="pt-3 text-end">
                    <p class="fs-5 fw-bold text-success mb-0">Total: Rp ${total.toLocaleString('id-ID')}</p>
                </div>
            </div>`;

            Swal.fire({
                title: '<i class="bi bi-cart3 text-success"></i> Keranjang Belanja',
                html: cartHtml,
                width: 600,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Checkout',
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    checkout();
                }
            });
        }

        function increaseQuantity(index) {
            let cart = getCart();
            cart[index].quantity += 1;
            saveCart(cart);
            viewCart();
        }

        function decreaseQuantity(index) {
            let cart = getCart();
            if (cart[index].quantity > 1) {
                cart[index].quantity -= 1;
                saveCart(cart);
                viewCart();
            }
        }

        function removeFromCart(index) {
            let cart = getCart();
            const itemName = cart[index].name;
            cart.splice(index, 1);
            saveCart(cart);
            
            Swal.fire({
                icon: 'success',
                title: 'Dihapus!',
                text: itemName + ' telah dihapus dari keranjang',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
            
            if (cart.length > 0) {
                viewCart();
            }
        }

        async function checkout() {
            const cart = getCart();
            
            if (cart.length === 0) {
                Swal.fire('Error', 'Keranjang kosong', 'error');
                return;
            }

            // Ask for customer info and payment method
            const { value: formValues } = await Swal.fire({
                title: 'Konfirmasi Pesanan',
                html: `
                    <div class="mb-3 text-start">
                        <label class="form-label">Nama (opsional)</label>
                        <input id="customer-name" class="form-control" placeholder="Masukkan nama Anda">
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input id="customer-phone" type="tel" class="form-control" placeholder="08xxxxxxxxxx" required>
                        <small class="text-muted">Invoice akan dikirim ke nomor ini setelah pembayaran dikonfirmasi</small>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                        <div class="payment-methods">
                            <div class="form-check mb-2 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment-cash" value="cash" checked>
                                <label class="form-check-label w-100" for="payment-cash">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-cash-coin fs-3 me-3 text-success"></i>
                                        <div>
                                            <strong>Cash</strong>
                                            <p class="mb-0 small text-muted">Bayar tunai di kasir</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check p-3 border rounded">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment-qris" value="qris">
                                <label class="form-check-label w-100" for="payment-qris">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-qr-code fs-3 me-3 text-primary"></i>
                                        <div>
                                            <strong>QRIS</strong>
                                            <p class="mb-0 small text-muted">Scan QRIS di kasir</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea id="notes" class="form-control" rows="2" placeholder="Catatan khusus untuk pesanan"></textarea>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Setelah memesan, silakan lanjutkan pembayaran di <strong>kasir</strong></small>
                    </div>
                `,
                width: 600,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-check-lg me-2"></i>Pesan Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754',
                preConfirm: () => {
                    const phone = document.getElementById('customer-phone').value;
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                    
                    // Validasi nomor WhatsApp
                    if (!phone || phone.trim() === '') {
                        Swal.showValidationMessage('Nomor WhatsApp wajib diisi!');
                        return false;
                    }
                    
                    // Validasi format nomor (harus angka dan minimal 10 digit)
                    const phoneRegex = /^[0-9]{10,15}$/;
                    const cleanPhone = phone.replace(/\D/g, ''); // Hapus karakter non-digit
                    
                    if (!phoneRegex.test(cleanPhone)) {
                        Swal.showValidationMessage('Format nomor WhatsApp tidak valid! (10-15 digit)');
                        return false;
                    }
                    
                    return {
                        customer_name: document.getElementById('customer-name').value,
                        customer_phone: cleanPhone,
                        payment_method: paymentMethod,
                        notes: document.getElementById('notes').value
                    }
                }
            });

            if (!formValues) return;

            // Show loading
            Swal.fire({
                title: 'Memproses Pesanan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send order to server
            try {
                const response = await fetch('/order/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        token: TOKEN,
                        items: cart,
                        customer_name: formValues.customer_name,
                        customer_phone: formValues.customer_phone,
                        payment_method: formValues.payment_method,
                        notes: formValues.notes
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Clear cart
                    localStorage.removeItem('cart_' + TOKEN);
                    updateCartCount();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Pesanan Berhasil!',
                        html: `
                            <p>Pesanan Anda telah dibuat.</p>
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Silakan lanjutkan pembayaran di kasir</strong>
                            </div>
                            <p class="text-muted small">Invoice akan dikirim setelah pembayaran dikonfirmasi</p>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#198754'
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal memproses pesanan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat memproses pesanan', 'error');
            }
        }
    </script>
</body>
</html>