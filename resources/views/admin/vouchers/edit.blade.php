@extends('admin.layouts.app')

@section('title', 'Edit Voucher')
@section('page-title', 'Edit Voucher')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-medium">Kode Voucher *</label>
                            <input type="text" name="kode" value="{{ old('kode', $voucher->kode) }}" required
                                class="form-control @error('kode') is-invalid @enderror text-uppercase">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Nominal Diskon (Rp) *</label>
                                <input type="number" name="nominal" value="{{ old('nominal', $voucher->nominal) }}" min="0"
                                    required class="form-control @error('nominal') is-invalid @enderror">
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Kuota Penggunaan *</label>
                                <input type="number" name="kuota" value="{{ old('kuota', $voucher->kuota) }}" min="1"
                                    required class="form-control @error('kuota') is-invalid @enderror">
                                <small class="text-muted">Terpakai: {{ $voucher->terpakai }}</small>
                                @error('kuota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Tanggal Mulai</label>
                                <input type="datetime-local" name="start_date"
                                    value="{{ old('start_date', $voucher->start_date?->format('Y-m-d\TH:i')) }}"
                                    class="form-control @error('start_date') is-invalid @enderror">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Tanggal Berakhir</label>
                                <input type="datetime-local" name="end_date"
                                    value="{{ old('end_date', $voucher->end_date?->format('Y-m-d\TH:i')) }}"
                                    class="form-control @error('end_date') is-invalid @enderror">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ $voucher->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">Aktifkan Voucher</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection