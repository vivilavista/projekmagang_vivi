<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
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
        <div style="font-size:6rem;color:#1e3a8a;">404</div>
        <h2 class="fw-bold text-dark">Halaman Tidak Ditemukan</h2>
        <p class="text-muted">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
    </div>
</body>

</html>