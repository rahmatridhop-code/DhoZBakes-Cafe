@extends('layouts.app')
@section('title', 'Edit Meja - DhoZ-Bakes')
@section('page-title', 'Edit Meja')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil"></i> Form Edit Meja</div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.tables.update', $table) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Meja</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $table->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kapasitas (Orang)</label>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $table->capacity) }}" min="1" max="50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="maintenance" {{ old('status', $table->status) == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                        </select>
                    </div>
                    <div class="mb-3 form-check form-switch">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $table->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Perbarui</button>
                        <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
