@extends('admin.layouts.app')

@section('title', 'Events')
@section('page-title', 'Kelola Events')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Daftar Events</h6>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Tambah Event
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Event</th>
                            <th>Tanggal</th>
                            <th>Harga</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-medium">{{ $event->nama }}</div>
                                    <small class="text-muted">{{ Str::limit($event->lokasi, 30) }}</small>
                                </td>
                                <td>{{ $event->tanggal_event->format('d M Y, H:i') }}</td>
                                <td>Rp {{ number_format($event->harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $event->terisi }}/{{ $event->kuota }}</span>
                                </td>
                                <td>
                                    @if($event->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus event ini?')">
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
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada event</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($events->hasPages())
            <div class="card-footer">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection