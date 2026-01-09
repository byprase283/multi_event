@extends('admin.layouts.app')

@section('title', 'Detail Peserta')
@section('page-title', 'Detail Peserta')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Informasi Peserta</h6>
                    @if($participant->status_verifikasi === 'Pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($participant->status_verifikasi === 'Valid')
                        <span class="badge bg-success">Valid</span>
                    @elseif($participant->status_verifikasi === 'Redeem')
                        <span class="badge bg-info">Redeem</span>
                    @else
                        <span class="badge bg-danger">Invalid</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Nama</h6>
                            <p class="fw-medium mb-3">{{ $participant->nama }}</p>

                            <h6 class="text-muted mb-1">Jenis Kelamin</h6>
                            <p class="fw-medium mb-3">{{ $participant->jenis_kelamin }}</p>

                            <h6 class="text-muted mb-1">Usia</h6>
                            <p class="fw-medium mb-3">{{ $participant->usia }} tahun</p>

                            <h6 class="text-muted mb-1">WhatsApp</h6>
                            <p class="fw-medium mb-3">{{ $participant->whatsapp }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Alamat</h6>
                            <p class="fw-medium mb-3">{{ $participant->alamat }}</p>

                            <h6 class="text-muted mb-1">Ukuran Jersey</h6>
                            <p class="fw-medium mb-3">{{ $participant->ukuran_jersey ?? '-' }}</p>

                            <h6 class="text-muted mb-1">Kode Voucher</h6>
                            <p class="fw-medium mb-3">{{ $participant->kode_voucher ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Event</h6>
                            <p class="fw-medium mb-3">{{ $participant->event->nama ?? '-' }}</p>

                            <h6 class="text-muted mb-1">Kode Registrasi</h6>
                            <p class="fw-medium mb-3">
                                @if($participant->kode_registrasi)
                                    <code class="fs-5">{{ $participant->kode_registrasi }}</code>
                                @else
                                    <span class="text-muted">Belum digenerate</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Waktu Daftar</h6>
                            <p class="fw-medium mb-3">{{ $participant->waktu_daftar?->format('d M Y H:i') ?? '-' }}</p>

                            <h6 class="text-muted mb-1">Waktu Validasi</h6>
                            <p class="fw-medium mb-3">{{ $participant->tgl_validasi?->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                    </div>

                    @if($participant->bukti_bayar)
                        <hr>
                        <h6 class="text-muted mb-2">Bukti Pembayaran</h6>
                        <img src="{{ asset('storage/' . $participant->bukti_bayar) }}" alt="Bukti Bayar"
                            class="img-fluid rounded" style="max-height: 300px;">
                    @endif

                    <hr>

                    <!-- Actions -->
                    <div class="d-flex gap-2 flex-wrap">
                        @if($participant->status_verifikasi === 'Pending')
                            <form action="{{ route('admin.participants.validate', $participant) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1"></i> Validasi
                                </button>
                            </form>
                            <form action="{{ route('admin.participants.invalidate', $participant) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-lg me-1"></i> Tolak
                                </button>
                            </form>
                        @endif

                        @if($participant->status_verifikasi === 'Valid')
                            <a href="{{ route('admin.participants.send-ticket', $participant) }}" class="btn btn-success"
                                target="_blank">
                                <i class="bi bi-whatsapp me-1"></i> Kirim Tiket via WA
                            </a>
                            <form action="{{ route('admin.participants.redeem', $participant) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-info text-white">
                                    <i class="bi bi-qr-code-scan me-1"></i> Tandai Redeem
                                </button>
                            </form>
                        @endif

                        @if($participant->token_hash)
                            <a href="{{ route('ticket.verify', $participant->token_hash) }}" class="btn btn-outline-primary"
                                target="_blank">
                                <i class="bi bi-ticket me-1"></i> Lihat Tiket
                            </a>
                        @endif

                        <a href="{{ route('admin.participants.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection