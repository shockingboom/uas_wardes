@extends('layouts.admin')

@section('content')
<h3 class="fw-bold mb-3">🪑 Edit Meja</h3>

<div class="card p-4" style="max-width: 600px;">
  <form action="{{ route('admin.table.update', $table->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Nomor Meja --}}
    <div class="mb-3">
      <label for="nomer_meja" class="form-label">Nomor Meja <span class="text-danger">*</span></label>
      <input 
        type="text" 
        name="nomer_meja" 
        id="nomer_meja" 
        class="form-control @error('nomer_meja') is-invalid @enderror" 
        value="{{ old('nomer_meja', $table->nomer_meja) }}"
        placeholder="Contoh: Meja 1, A1, VIP-01"
        required
      >
      @error('nomer_meja')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>


    {{-- Tombol submit --}}
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Update Meja</button>
      <a href="{{ route('admin.table.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </form>
</div>
@endsection
