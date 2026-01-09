@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card blue">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75">Total Events</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_events'] }}</h3>
                    </div>
                    <i class="bi bi-calendar-event fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card green">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75">Total Peserta</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_participants'] }}</h3>
                    </div>
                    <i class="bi bi-people fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card orange">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75">Pending</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['pending_participants'] }}</h3>
                    </div>
                    <i class="bi bi-hourglass-split fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card purple">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="mb-1 opacity-75">Tervalidasi</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['valid_participants'] }}</h3>
                    </div>
                    <i class="bi bi-check-circle fs-2 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Participants -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Pendaftar Terbaru</h6>
                    <a href="{{ route('admin.participants.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Event</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentParticipants as $participant)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $participant->nama }}</div>
                                            <small class="text-muted">{{ $participant->whatsapp }}</small>
                                        </td>
                                        <td>{{ $participant->event->nama ?? '-' }}</td>
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
                                            <small class="text-muted">{{ $participant->created_at->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada pendaftar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Event Mendatang</h6>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @forelse($upcomingEvents as $event)
                        <div class="d-flex gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded p-2 text-center" style="width: 50px;">
                                    <div class="text-primary fw-bold">{{ $event->tanggal_event->format('d') }}</div>
                                    <small class="text-primary">{{ $event->tanggal_event->format('M') }}</small>
                                </div>
                            </div>
                            <div>
                                <div class="fw-medium">{{ $event->nama }}</div>
                                <small class="text-muted">{{ $event->terisi }}/{{ $event->kuota }} peserta</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Tidak ada event mendatang</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection