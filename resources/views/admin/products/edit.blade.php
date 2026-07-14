@extends('layouts.app')
@section('title', 'Edit Produk - DhoZ-Bakes')
@section('page-title', 'Edit Produk')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil"></i> Form Edit Produk</div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.products.update', $product) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category_id" class="form-select" onchange="document.querySelector('[name=category]').value = this.options[this.selectedIndex].text">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="category" value="{{ old('category', $product->category) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Emoji</label>
                        <input type="text" name="emoji" class="form-control" value="{{ old('emoji', $product->emoji) }}" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Badge</label>
                        <input type="text" name="badge" class="form-control" value="{{ old('badge', $product->badge) }}">
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Perbarui</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
