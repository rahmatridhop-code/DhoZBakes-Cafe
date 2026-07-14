@extends('layouts.app')
@section('title', 'Meja - DhoZ-Bakes')
@section('page-title', 'Manajemen Meja')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-grid-3x3-gap-fill"></i> Daftar Meja</span>
        <a href="{{ route('admin.tables.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Meja
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Aktif</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tables as $table)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $table->name }}</td>
                        <td>{{ $table->capacity }} orang</td>
                        <td>
                            @if($table->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($table->status === 'occupied')
                                <span class="badge bg-danger">Terisi</span>
                            @else
                                <span class="badge bg-warning text-dark">Perawatan</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $table->is_active ? 'success' : 'secondary' }}">
                                {{ $table->is_active ? 'Ya' : 'Tidak' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.tables.edit', $table) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus meja ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada meja</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
