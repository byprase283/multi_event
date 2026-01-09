@extends('admin.layouts.app')

@section('title', 'Scan Tiket')
@section('page-title', 'Scan QR Code Tiket')

@section('content')
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div id="reader" width="100%"></div>
                    <div class="text-center mt-3">
                        <p class="text-muted small">Arahkan kamera ke QR Code tiket</p>
                        <div id="scanResult" class="d-none alert mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0 fw-bold">Hasil Scan</h6>
                </div>
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center"
                    id="resultCard">
                    <i class="bi bi-qr-code-scan fs-1 text-muted mb-3"></i>
                    <p class="text-muted">Hasil scan akan muncul di sini</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const scanResultConfig = { fps: 10, qrbox: { width: 250, height: 250 } };
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            // Play beep sound
            const audio = new Audio('https://www.soundjay.com/button/beep-07.mp3');
            audio.play().catch(e => console.log('Audio play failed', e));

            // Show processing
            document.getElementById('resultCard').innerHTML = `
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p>Memproses data...</p>
            `;

            // Send to backend
            fetch('{{ route("admin.scanner.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ token_hash: decodedText })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success
                        document.getElementById('resultCard').innerHTML = `
                        <div class="text-success mb-3">
                            <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-2">BERHASIL!</h4>
                        <p class="mb-4 text-muted">Tiket valid dan berhasil diredeem</p>

                        <div class="w-100 text-start bg-light p-3 rounded">
                            <div class="mb-2"><strong>Nama:</strong> ${data.participant.nama}</div>
                            <div class="mb-2"><strong>Event:</strong> ${data.event.nama}</div>
                            <div class="mb-0"><strong>Kategori:</strong> ${data.participant.ukuran_jersey || '-'}</div>
                        </div>

                        <button class="btn btn-primary mt-4 w-100" onclick="resetScanner()">Scan Lagi</button>
                    `;
                        html5QrCode.pause();
                    } else {
                        // Error (Invalid/Used)
                        let color = 'text-danger';
                        let icon = 'bi-x-circle-fill';

                        if (data.message.includes('sudah digunakan')) {
                            color = 'text-warning';
                            icon = 'bi-exclamation-circle-fill';
                        }

                        document.getElementById('resultCard').innerHTML = `
                        <div class="${color} mb-3">
                            <i class="bi ${icon}" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold ${color} mb-2">GAGAL!</h4>
                        <p class="mb-4">${data.message}</p>

                        ${data.participant ? `
                        <div class="w-100 text-start bg-light p-3 rounded">
                            <div class="mb-2"><strong>Nama:</strong> ${data.participant.nama}</div>
                            <div class="mb-2"><strong>Event:</strong> ${data.event.nama}</div>
                        </div>
                        ` : ''}

                        <button class="btn btn-outline-secondary mt-4 w-100" onclick="resetScanner()">Scan Lagi</button>
                    `;
                        html5QrCode.pause();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('resultCard').innerHTML = `
                    <div class="text-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-2">ERROR</h4>
                    <p class="mb-4">Terjadi kesalahan sistem</p>
                    <button class="btn btn-outline-secondary mt-4 w-100" onclick="resetScanner()">Coba Lagi</button>
                `;
                    isProcessing = false; // Allow retry immediately for system errors
                });
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // console.warn(`Code scan error = ${error}`);
        }

        function resetScanner() {
            isProcessing = false;
            document.getElementById('resultCard').innerHTML = `
                <i class="bi bi-qr-code-scan fs-1 text-muted mb-3"></i>
                <p class="text-muted">Hasil scan akan muncul di sini</p>
            `;
            html5QrCode.resume();
        }

        // Start scanning
        html5QrCode.start(
            { facingMode: "environment" },
            scanResultConfig,
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Error starting scanner", err);
            document.getElementById('reader').innerHTML = `
                <div class="alert alert-danger">
                    Gagal mengakses kamera. Pastikan izin kamera diberikan. <br>
                    Error: ${err}
                </div>
            `;
        });
    </script>
@endpush