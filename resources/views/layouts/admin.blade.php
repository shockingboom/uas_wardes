<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Warung Desa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    .sidebar {
        width: 240px;
        background: ForestGreen;
        color: white;
        position: fixed;
        height: 100%;
        padding-top: 20px;
    }
    .sidebar a {
        display: block;
        color: white;
        padding: 12px 20px;
        text-decoration: none;
    }
    .sidebar a:hover {
        background-color: limegreen;
    }
    .sidebar .logout-btn {
        position: absolute;
        bottom: 20px;
        width: calc(100% - 40px);
        margin: 0 20px;
    }
    .sidebar .logout-btn form button {
        width: 100%;
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 12px 20px;
        text-align: left;
        cursor: pointer;
        border-radius: 5px;
    }
    .sidebar .logout-btn form button:hover {
        background-color: #bb2d3b;
    }
    .content {
        margin-left: 250px;
        padding: 20px;
    }
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-center fw-bold mb-4">Warung Desa</h4>
    <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
    <a href="{{ route('admin.item.index') }}">🍱 Manajemen Menu</a>
    <a href="{{ route('admin.table.index') }}">🪑 Manajemen Meja</a>
    <a href="{{ route('admin.pesanan.index') }}">📋 Status Pesanan</a>
    {{-- <a href="{{ route('admin.pesanan') }}">🧾 Pesanan Masuk</a> --}}
    @auth
        <form method="POST" action="{{ route('logout') }}" class="mt-3 px-3">
            @csrf
            <button type="submit" class="btn w-100 text-start" style="background-color: darkgreen; color: white;">
                🚪 Logout
            </button>
        </form>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="mt-3 d-block px-3 text-white text-decoration-none">🔑 Login</a>
    @endguest
</div>

<div class="content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>