# Sistem Buku Tamu Digital — Laravel

Sistem manajemen buku tamu berbasis web dengan role-based access control (Admin & Petugas).

## 📋 Fitur

- **Admin**: Kelola user, tamu, kunjungan, dashboard statistik
- **Petugas**: Input kunjungan, checkout tamu, lihat kunjungan
- Upload foto KTP dan foto wajah
- Audit Log otomatis pada setiap perubahan data
- Soft Delete pada tamu dan kunjungan
- Pagination & Search

## 🛠 Tech Stack

- PHP 8.3 / Laravel 12
- MySQL 8.0
- Blade + Bootstrap 5.3
- Session-based Authentication
- Service Layer + Repository Pattern

## 🚀 Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Langkah

```bash
# 1. Clone / masuk ke folder project
cd buku-tamu

# 2. Install dependencies
composer install

# 3. Copy .env
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Edit .env — sesuaikan DB_*
#    DB_DATABASE=buku_tamu
#    DB_USERNAME=root
#    DB_PASSWORD=yourpassword

# 6. Buat database
mysql -u root -p -e "CREATE DATABASE buku_tamu;"

# 7. Migrate & seed
php artisan migrate --seed

# 8. Storage link
php artisan storage:link

# 9. Jalankan server
php artisan serve
```

Akses di: http://127.0.0.1:8000

## 🔑 Akun Default

| Role    | Username  | Password   |
| ------- | --------- | ---------- |
| Admin   | admin     | admin123   |
| Petugas | petugas01 | petugas123 |

## 🐳 Menjalankan dengan Docker

```bash
cp .env.example .env
# Edit .env jika perlu

docker-compose up -d

# Pertama kali — jalankan migrasi dalam container
docker exec buku_tamu_app php artisan migrate --seed
docker exec buku_tamu_app php artisan storage:link
```

- App: http://localhost:8080
- phpMyAdmin: http://localhost:8081

## 🧪 Menjalankan Test

```bash
php artisan test
```

## 📁 Struktur Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── TamuController.php
│   │   │   └── KunjunganController.php
│   │   └── Petugas/
│   │       ├── DashboardController.php
│   │       └── KunjunganController.php
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   └── Requests/
│       ├── Auth/LoginRequest.php
│       └── Admin/Store|UpdateUserRequest.php ...
├── Models/
│   ├── User.php
│   ├── Tamu.php
│   ├── Kunjungan.php
│   └── AuditLog.php
├── Repositories/
│   ├── Contracts/ (Interfaces)
│   ├── UserRepository.php
│   ├── TamuRepository.php
│   └── KunjunganRepository.php
├── Services/
│   ├── AuthService.php
│   ├── UserService.php
│   ├── TamuService.php
│   ├── KunjunganService.php
│   └── AuditLogService.php
├── Observers/
│   ├── UserObserver.php
│   ├── TamuObserver.php
│   └── KunjunganObserver.php
└── Policies/
    ├── UserPolicy.php
    ├── TamuPolicy.php
    └── KunjunganPolicy.php
```

## 📊 ERD (Entity Relationship Diagram)

```
USERS
  id            PK
  nama          varchar(255)
  username      varchar(255) UNIQUE
  password      varchar(255)  [bcrypt]
  role          enum('admin','petugas')
  nrp           varchar(50) NULL
  pangkat       varchar(100) NULL
  created_at
  updated_at

TAMU
  id            PK
  nama          varchar(255)
  alamat        text
  no_hp         varchar(20)
  foto_ktp      varchar(255) NULL
  deleted_at    NULL [soft delete]
  created_at
  updated_at

KUNJUNGAN
  id            PK
  tamu_id       FK -> TAMU.id
  tujuan        varchar(500)
  jam_masuk     datetime
  jam_keluar    datetime NULL
  keterangan    text NULL
  foto_wajah    varchar(255) NULL
  instansi      varchar(255) NULL
  operator_id   FK -> USERS.id
  status        enum('Aktif','Selesai')
  deleted_at    NULL [soft delete]
  created_at
  updated_at

AUDIT_LOGS
  id            PK
  user_id       FK -> USERS.id (nullable)
  aktivitas     varchar(255)
  tabel         varchar(100)
  data_id       bigint NULL
  created_at
```

**Relasi:**

- TAMU `1` ←→ `N` KUNJUNGAN
- USERS `1` ←→ `N` KUNJUNGAN (sebagai operator)
- USERS `1` ←→ `N` AUDIT_LOGS

## 📝 Upload File

File tersimpan di: `storage/app/public/uploads/`

Validasi:

- Format: image (jpg, png, gif, bmp, webp)
- Max: 2MB

Akses public via: `http://domain/storage/uploads/filename.jpg`
