@extends('admin.layouts.app')

@section('title', 'Peserta')
@section('page-title', 'Kelola Peserta')

@section('content')
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.participants.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Valid" {{ request('status') == 'Valid' ? 'selected' : '' }}>Valid</option>
                        <option value="Invalid" {{ request('status') == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                        <option value="Redeem" {{ request('status') == 'Redeem' ? 'selected' : '' }}>Redeem</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Event</label>
                    <select name="event_id" class="form-select">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Nama, kode, atau WhatsApp...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-bold">Daftar Peserta</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Peserta</th>
                            <th>Event</th>
                            <th>Kode Registrasi</th>
                            <th>Jersey</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $participant)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $participant->nama }}</div>
                                    <small class="text-muted">{{ $participant->whatsapp }}</small>
                                </td>
                                <td>{{ $participant->event->nama ?? '-' }}</td>
                                <td>
                                    @if($participant->kode_registrasi)
                                        <code>{{ $participant->kode_registrasi }}</code>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $participant->ukuran_jersey ?? '-' }}</td>
                                <td>
                                    @if($participant->status_verifikasi === 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($participant->status_verifikasi === 'Valid')
                                        <span class="badge bg-success">Valid</span>
                                    @elseif($participant->status_verifikasi === 'Redeem')
                                        <span class="badge bg-info">Redeem</span>
                                    @else
                                        <span class="badge bg-danger">Invalid</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if($participant->status_verifikasi === 'Pending')
                                            <form action="{{ route('admin.participants.validate', $participant) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Validasi">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.participants.invalidate', $participant) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($participant->status_verifikasi === 'Valid')
                                            <a href="{{ route('admin.participants.send-ticket', $participant) }}"
                                                class="btn btn-sm btn-success" title="Kirim via WA" target="_blank">
                                                <i class="bi bi-whatsapp"></i>
                                            </a>
                                            <form action="{{ route('admin.participants.redeem', $participant) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info" title="Redeem">
                                                    <i class="bi bi-qr-code-scan"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.participants.show', $participant) }}"
                                            class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <form action="{{ route('admin.participants.destroy', $participant) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus peserta ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada peserta</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($participants->hasPages())
            <div class="card-footer">
                {{ $participants->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection