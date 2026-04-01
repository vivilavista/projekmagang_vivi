<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Buku Tamu Digital</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 480px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(30, 58, 138, 0.3);
            padding: 2.5rem;
            margin: 2rem 0;
        }

        .login-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .login-logo i {
            font-size: 2rem;
            color: #fff;
        }

        .login-card h3 {
            text-align: center;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 0.25rem;
        }

        .login-card p.subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.75rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.02em;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            background: #f9fafb;
            border-color: #e5e7eb;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.35rem;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="login-logo">
            <i class="bi bi-person-plus-fill"></i>
        </div>
        <h3>Registrasi Akun</h3>
        <p class="subtitle">Buat akun untuk masuk ke Buku Tamu Digital</p>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Ada kesalahan pada input Anda.
                <ul class="mb-0 mt-1" style="font-size:0.85rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <h5 class="mb-3 text-secondary border-bottom pb-2 fs-6">Data Diri</h5>
            <div class="mb-3">
                <label for="nik">NIK KTP <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-text text-secondary"></i></span>
                    <input type="text" id="nik" name="nik"
                        class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}"
                        placeholder="Contoh: 35150xxxxxx" autofocus required>
                </div>
            </div>
            <div class="mb-3">
                <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge text-secondary"></i></span>
                    <input type="text" id="nama" name="nama"
                        class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}"
                        placeholder="Masukkan nama sesuai KTP" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="email">Email Asli <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope text-secondary"></i></span>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="Contoh: user@email.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="no_hp">Nomor HP/WhatsApp <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-telephone text-secondary"></i></span>
                    <input type="text" id="no_hp" name="no_hp"
                        class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}"
                        placeholder="Contoh: 08123456789" required>
                </div>
            </div>

            <h5 class="mb-3 mt-4 text-secondary border-bottom pb-2 fs-6">Data Akun Login</h5>
            <div class="mb-3">
                <label for="username">Username <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person text-secondary"></i></span>
                    <input type="text" id="username" name="username"
                        class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                        placeholder="Masukkan username login" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="password">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock text-secondary"></i></span>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill text-secondary"></i></span>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control" placeholder="Ulangi password di atas" required>
                </div>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-login text-white">
                    <i class="bi bi-check-circle me-1"></i> Daftar Sekarang
                </button>
            </div>
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted" style="font-size: 0.9rem;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
