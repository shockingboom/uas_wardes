@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3">🪑 Manajemen Meja</h3>

{{-- Notifikasi sukses --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Tombol tambah meja --}}
<div class="d-flex justify-content-end mb-3">
  <a href="{{ route('admin.table.create') }}" class="btn btn-success">+ Tambah Meja</a>
</div>

{{-- Tabel meja --}}
<div class="card p-3">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>#</th>
        <th>Nomor Meja</th>
        <th>Token</th>
        <th>Dibuat</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($tables as $table)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td><strong>{{ $table->nomer_meja }}</strong></td>
        <td>
          <code class="bg-light p-2 rounded">{{ $table->token }}</code>
        </td>
        <td>{{ $table->created_at->format('d/m/Y H:i') }}</td>
        <td>
          <a href="{{ route('admin.table.edit', $table->id) }}" class="btn btn-sm btn-warning">Edit</a>
          
          {{-- Tombol QR Code --}}
          <a href="{{ route('admin.table.qrcode', $table->id) }}" class="btn btn-sm btn-primary" title="Lihat QR Code">
            📱 QR Code
          </a>
          
          {{-- Tombol regenerate token --}}
          <form action="{{ route('admin.table.regenerate', $table->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button class="btn btn-sm btn-info" onclick="return confirm('Generate ulang token untuk meja ini?')">
              🔄 Token
            </button>
          </form>

          {{-- Tombol hapus --}}
          <form action="{{ route('admin.table.destroy', $table->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus meja ini?')">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center text-muted py-4">
          Belum ada meja. Silakan tambahkan meja baru.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

@if ($tables->count() > 0)
<div class="mt-3">
  <small class="text-muted">
    <strong>Total Meja:</strong> {{ $tables->count() }}
  </small>
</div>
@endif

@endsection
