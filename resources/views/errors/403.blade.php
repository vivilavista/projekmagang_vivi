<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f0f4ff;
        }
    </style>
</head>

<body>
    <div class="text-center p-5">
        <div style="font-size:6rem;color:#dc2626;">403</div>
        <h2 class="fw-bold text-dark">Akses Ditolak</h2>
        <p class="text-muted">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url()->previous() }}" class="btn btn-danger">Kembali</a>
    </div>
</body>

</html>