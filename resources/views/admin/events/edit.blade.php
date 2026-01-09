@extends('admin.layouts.app')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-medium">Nama Event *</label>
                            <input type="text" name="nama" value="{{ old('nama', $event->nama) }}" required
                                class="form-control @error('nama') is-invalid @enderror">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Deskripsi</label>
                            <textarea name="deskripsi" rows="4"
                                class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $event->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Tanggal Event *</label>
                                <input type="datetime-local" name="tanggal_event"
                                    value="{{ old('tanggal_event', $event->tanggal_event->format('Y-m-d\TH:i')) }}" required
                                    class="form-control @error('tanggal_event') is-invalid @enderror">
                                @error('tanggal_event')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Lokasi</label>
                                <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}"
                                    class="form-control @error('lokasi') is-invalid @enderror">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Harga (Rp) *</label>
                                <input type="number" name="harga" value="{{ old('harga', $event->harga) }}" min="0" required
                                    class="form-control @error('harga') is-invalid @enderror">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Kuota Peserta *</label>
                                <input type="number" name="kuota" value="{{ old('kuota', $event->kuota) }}" min="1" required
                                    class="form-control @error('kuota') is-invalid @enderror">
                                @error('kuota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bank Info -->
                        <h6 class="text-muted mb-3 mt-4"><i class="bi bi-bank me-1"></i> Informasi Rekening</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $event->bank_name) }}"
                                    class="form-control @error('bank_name') is-invalid @enderror"
                                    placeholder="BCA, BRI, Mandiri...">
                                @error('bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">No. Rekening</label>
                                <input type="text" name="bank_account"
                                    value="{{ old('bank_account', $event->bank_account) }}"
                                    class="form-control @error('bank_account') is-invalid @enderror"
                                    placeholder="1234567890">
                                @error('bank_account')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Atas Nama</label>
                                <input type="text" name="bank_holder" value="{{ old('bank_holder', $event->bank_holder) }}"
                                    class="form-control @error('bank_holder') is-invalid @enderror"
                                    placeholder="Nama pemilik rekening">
                                @error('bank_holder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Gambar Banner</label>
                            @if($event->gambar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $event->gambar) }}" alt="Current" class="img-thumbnail"
                                        style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="gambar" accept="image/*"
                                class="form-control @error('gambar') is-invalid @enderror">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah</small>
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ $event->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Aktifkan Event</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection