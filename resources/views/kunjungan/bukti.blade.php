<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bukti Kunjungan - {{ $kunjungan->tamu->nama }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding: 20px;
            color: #333;
        }

        .ticket {
            max-width: 600px;
            margin: 0 auto;
            border: 2px dashed #ccc;
            padding: 30px;
            border-radius: 12px;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #052355;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        .row {
            display: flex;
            margin-bottom: 12px;
        }

        .label {
            width: 140px;
            font-weight: bold;
            color: #555;
        }

        .value {
            flex: 1;
            color: #000;
        }

        .qr-container {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .qr-container img {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
        }

        .qr-code-text {
            margin-top: 10px;
            font-family: monospace;
            font-size: 16px;
            letter-spacing: 2px;
        }

        @media print {
            body {
                padding: 0;
                background: #fff;
            }

            .ticket {
                border: 2px solid #000;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="ticket">
        <div class="header">
            <h2>BUKTI KUNJUNGAN</h2>
            <p>Sistem Digital Buku Tamu</p>
        </div>

        <div class="row">
            <div class="label">Nama Tamu</div>
            <div class="value">: {{ $kunjungan->tamu->nama }}</div>
        </div>
        <div class="row">
            <div class="label">NIK</div>
            <div class="value">: {{ $kunjungan->tamu->nik ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="label">No. HP</div>
            <div class="value">: {{ $kunjungan->tamu->no_hp }}</div>
        </div>
        <div class="row">
            <div class="label">Instansi</div>
            <div class="value">: {{ $kunjungan->instansi ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="label">Tujuan Kunjungan</div>
            <div class="value">: {{ $kunjungan->tujuan }}</div>
        </div>
        <div class="row">
            <div class="label">Status</div>
            <div class="value">: <strong>{{ strtoupper($kunjungan->status) }}</strong></div>
        </div>
        <div class="row">
            <div class="label">Waktu Kedatangan</div>
            <div class="value">: {{ $kunjungan->jam_masuk ? $kunjungan->jam_masuk->format('d M Y - H:i:s') : '-' }}
            </div>
        </div>

        @if($kunjungan->kode_qr)
            <div class="qr-container">
                <p style="margin-top:0; margin-bottom: 15px; font-weight:bold;">PINDAI QR CODE INI SAAT DATANG</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($kunjungan->kode_qr) }}"
                    alt="QR Code" width="180" height="180">
                <div class="qr-code-text">{{ $kunjungan->kode_qr }}</div>
            </div>
        @endif
    </div>

    <div style="text-align: center; margin-top: 30px;" class="no-print">
        <button onclick="window.print()"
            style="padding: 10px 20px; background: #052355; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Cetak Bukti Kunjungan
        </button>
        <button onclick="window.close()"
            style="padding: 10px 20px; background: #ccc; color: #333; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>

</html>