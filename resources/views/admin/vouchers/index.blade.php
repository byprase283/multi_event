@extends('admin.layouts.app')

@section('title', 'Vouchers')
@section('page-title', 'Kelola Vouchers')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Daftar Vouchers</h6>
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Voucher
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nominal</th>
                            <th>Kuota</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code class="fs-6">{{ $voucher->kode }}</code></td>
                                <td>Rp {{ number_format($voucher->nominal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $voucher->terpakai }}/{{ $voucher->kuota }}</span>
                                </td>
                                <td>
                                    @if($voucher->start_date && $voucher->end_date)
                                        <small>{{ $voucher->start_date->format('d M') }} -
                                            {{ $voucher->end_date->format('d M Y') }}</small>
                                    @else
                                        <small class="text-muted">Tidak dibatasi</small>
                                    @endif
                                </td>
                                <td>
                                    @if($voucher->is_active && $voucher->isValid())
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($voucher->is_active)
                                        <span class="badge bg-warning">Habis</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada voucher</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($vouchers->hasPages())
            <div class="card-footer">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>
@endsection