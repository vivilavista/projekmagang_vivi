@extends('layouts.app')
@section('title', 'Scan QR Kunjungan')
@section('page-title', 'Scanner QR Kunjungan')

@section('styles')
    <style>
        #reader {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 2px dashed #9ca3af;
        }

        #reader video {
            border-radius: 12px;
            object-fit: cover;
        }

        .scan-result-card {
            display: none;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header py-3 bg-white d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-qr-code-scan text-primary me-2"></i>Arahkan QR Code ke Kamera</span>
                    <button id="toggleCamera" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-camera-video me-1"></i>Mulai Kamera</button>
                </div>
                <div class="card-body text-center p-4">
                    <div id="reader"></div>
                    <p class="text-muted small mt-3 mb-0" id="scanStatusText">Klik 'Mulai Kamera' untuk memulai pemindaian.
                    </p>
                </div>
            </div>

            <!-- Kartu Hasil Scan -->
            <div class="card scan-result-card border-0 shadow-sm" id="resultCard">
                <div class="card-body p-4 text-center">
                    <div id="resultIcon" class="mb-3" style="font-size:2.5rem;"></div>
                    <h5 id="resultTitle" class="fw-bold mb-2"></h5>
                    <p id="resultMessage" class="text-muted mb-4"></p>
                    <button class="btn btn-primary px-4 rounded-pill" onclick="resetScanner()">Scan Lagi</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let html5QrcodeScanner = null;
            let isScanning = false;
            const toggleBtn = document.getElementById('toggleCamera');
            const statusText = document.getElementById('scanStatusText');
            const resultCard = document.getElementById('resultCard');
            const readerDiv = document.getElementById('reader');

            // Get proper route based on user prefix
            const currentPath = window.location.pathname;
            let processUrl = '';
            if (currentPath.includes('admin')) processUrl = '{{ route("admin.scan.process") }}';
            else if (currentPath.includes('petugas')) processUrl = '{{ route("petugas.scan.process") }}';
            else if (currentPath.includes('operator')) processUrl = '{{ route("operator.scan.process") }}';

            function onScanSuccess(decodedText, decodedResult) {
                // Pause scanning to prevent multiple triggers
                html5QrcodeScanner.pause(true);
                statusText.innerText = "Memproses QR Code...";

                // Kirim hasil scan ke backend
                fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ kode_qr: decodedText })
                })
                    .then(response => response.json())
                    .then(data => {
                        html5QrcodeScanner.clear(); // Hentikan kamera
                        readerDiv.style.display = 'none';
                        toggleBtn.style.display = 'none';
                        showResult(data.success, data.message);
                    })
                    .catch(error => {
                        console.error("Error processing QR:", error);
                        alert("Terjadi kesalahan jaringan.");
                        html5QrcodeScanner.resume();
                    });
            }

            function onScanFailure(error) {
                // handle scan failure, usually better to ignore and keep scanning
            }

            function showResult(isSuccess, message) {
                resultCard.style.display = 'block';
                const icon = document.getElementById('resultIcon');
                const title = document.getElementById('resultTitle');
                const msg = document.getElementById('resultMessage');

                if (isSuccess) {
                    icon.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
                    title.innerText = "Check-In Berhasil!";
                    title.className = "fw-bold mb-2 text-success";
                } else {
                    icon.innerHTML = '<i class="bi bi-x-circle-fill text-danger"></i>';
                    title.innerText = "Gagal Mengakses";
                    title.className = "fw-bold mb-2 text-danger";
                }
                msg.innerText = message;
            }

            window.resetScanner = function () {
                resultCard.style.display = 'none';
                readerDiv.style.display = 'block';
                toggleBtn.style.display = 'block';
                startScanner();
            }

            function startScanner() {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader",
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                        /* verbose= */ false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                isScanning = true;
                toggleBtn.innerHTML = '<i class="bi bi-camera-video-off me-1"></i>Hentikan Kamera';
                toggleBtn.classList.replace('btn-outline-secondary', 'btn-outline-danger');
                statusText.innerText = "Kamera aktif. Arahkan QR Code ke dalam kotak.";
            }

            function stopScanner() {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().then(() => {
                        isScanning = false;
                        toggleBtn.innerHTML = '<i class="bi bi-camera-video me-1"></i>Mulai Kamera';
                        toggleBtn.classList.replace('btn-outline-danger', 'btn-outline-secondary');
                        statusText.innerText = "Mulai kamera untuk memindai.";
                    });
                }
            }

            toggleBtn.addEventListener('click', () => {
                if (isScanning) {
                    stopScanner();
                } else {
                    startScanner();
                }
            });
        });
    </script>
@endsection