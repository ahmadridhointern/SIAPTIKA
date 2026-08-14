# Dokumentasi Teknis Sistem SIAPTIKA (Technical Documentation)

**Aplikasi**: SIAPTIKA (Sistem Informasi Administrasi Kegiatan Bidang APTIKA)  
**Platform**: Windows Desktop Application (64-bit)  
**Versi**: 1.0.0 Production Release  
**Instansi**: Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau

---

## 1. Arsitektur Sistem Final

SIAPTIKA mengadopsi arsitektur **Hybrid Windows Desktop Application** yang memadukan keamanan komputasi lokal dengan kemudahan sinkronisasi cloud terpusat:

```
┌─────────────────────────────────────────────────────────────────┐
│                    WINDOWS CLIENT COMPUTER                      │
│                                                                 │
│  ┌─────────────────────────┐       ┌─────────────────────────┐  │
│  │ SIAPTIKA Admin.exe      │       │ SIAPTIKA Dashboard.exe  │  │
│  │ (Full CRUD + Auth)      │       │ (Read-Only Public)      │  │
│  └───────────┬─────────────┘       └───────────┬─────────────┘  │
│              │                                 │                │
│              ▼                                 ▼                │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │                ELECTRON DESKTOP CONTAINER                 │  │
│  │  - Chromium 130 + Node.js 24                              │  │
│  │  - Context Isolation & Sandbox Enabled                    │  │
│  │  - Lifecycle & Process Bridge Manager                     │  │
│  └───────────────────────────┬───────────────────────────────┘  │
│                              │                                  │
│                              ▼                                  │
│  ┌───────────────────────────────────────────────────────────┐  │
│  │              LOCAL LARAVEL BACKEND (PORT 8000)            │  │
│  │  - Binding: 127.0.0.1 (Loopback Only)                     │  │
│  │  - Bundled Portable PHP 8.3.30 x64                        │  │
│  │  - Mode: Production (APP_DEBUG=false, Caches Active)      │  │
│  │  - Stream Proxy & Business Rules Validation Layer         │  │
│  └───────────────────────────┬───────────────────────────────┘  │
└──────────────────────────────┼──────────────────────────────────┘
                               │ HTTPS / SSL (Encrypted TLS)
                               ▼
┌─────────────────────────────────────────────────────────────────┐
│                      SUPABASE CLOUD PLATFORM                    │
│                                                                 │
│  ┌─────────────────────────┐       ┌─────────────────────────┐  │
│  │ Supabase PostgreSQL DB  │       │ Supabase Storage (S3)   │  │
│  │ (Centralized Relational)│       │ (Bucket: 'documents')   │  │
│  └─────────────────────────┘       └─────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Peran & Spesifikasi Komponen

### A. Electron Desktop Container
- **Tanggung Jawab**:
  - Mengemas antarmuka grafis menjadi aplikasi desktop Windows `.exe`.
  - Mengelola *lifecycle* backend Laravel di latar belakang (*silent background execution*).
  - Melakukan *polling* kesiapan server lokal ke endpoint `http://127.0.0.1:8000/up` sebelum menampilkan jendela utama (*BrowserWindow*).
  - Menghentikan proses *child process* PHP saat jendela aplikasi ditutup oleh pengguna.
- **Konfigurasi Keamanan**:
  - `nodeIntegration: false` (Mencegah eksekusi script Node.js dari renderer).
  - `contextIsolation: true` (Memisahkan konteks eksekusi Chromium dengan Node main process).
  - `sandbox: true` (Renderer process berjalan dalam mode sandbox terisolasi).

### B. Backend Lokal Laravel
- **Tanggung Jawab**:
  - Menangani seluruh *routing*, logika bisnis, otorisasi, dan validasi formulir.
  - Berfungsi sebagai proksi aman ke Supabase Storage (mengunduh dan men-stream berkas secara *inline* tanpa mengekspos public bucket URL).
  - Mengisolasi seluruh kredensial database dan API keys di dalam berkas `.env` lokal.
- **Konfigurasi Binding Jaringan**:
  - Bind secara ketat ke `127.0.0.1:8000` (Loopback). Server **TIDAK** membuka port ke `0.0.0.0`, sehingga aman dari pemindaian jaringan LAN/Wi-Fi.

### C. Supabase PostgreSQL (Cloud Database)
- **Tanggung Jawab**:
  - Menyimpan data terpusat meliputi akun Administrator, data kegiatan kedinasan, dan metadata dokumen arsip.
- **Konektivitas**:
  - Port `6543` via AWS Transaction Pooler (`aws-0-ap-southeast-1.pooler.supabase.com`).
  - Mode SSL: `DB_SSLMODE=require`.

### D. Supabase Storage S3 (Cloud Object Storage)
- **Tanggung Jawab**:
  - Menyimpan berkas fisik dokumen kedinasan (Surat, Notulen, Dokumentasi Foto/Video) di bucket privat `documents`.
- **Integrasi**:
  - Driver `league/flysystem-aws-s3-v3` menggunakan protokol S3 AWS terenkripsi.

---

## 3. Struktur Executable & Bundled PHP Runtime

Aplikasi didistribusikan sebagai installer NSIS (`Setup.exe`) mandiri yang menyertakan runtime PHP portable di dalamnya:

```
win-unpacked/
├── SIAPTIKA Administrator.exe    ← Executable Electron Utama
├── resources/
│   └── laravel/                  ← Aplikasi Laravel Lengkap
│       ├── app/
│       ├── bootstrap/cache/
│       ├── config/
│       ├── public/build/         ← Aset CSS & JS Terkompilasi
│       ├── routes/
│       ├── vendor/               ← Production Dependencies (--no-dev)
│       └── artisan
└── runtime/
    └── php/                      ← Bundled Portable PHP 8.3.30 (x64)
        ├── php.exe
        ├── php.ini
        ├── ext/
        │   ├── php_pdo_pgsql.dll
        │   ├── php_pgsql.dll
        │   ├── php_openssl.dll
        │   ├── php_curl.dll
        │   ├── php_fileinfo.dll
        │   └── php_mbstring.dll
        └── libpq.dll
```

---

## 4. Arsitektur Autentikasi & Otorisasi

### A. Autentikasi Administrator (ID + Password)
- **ID Login**: Menggunakan atribut unik `login_id` (`VARCHAR(50)`). Akun resmi Administrator adalah `ADM001`.
- **Penghapusan Kolom Email**: Kolom `email` telah dihapus sepenuhnya dari tabel `users` dan digantikan oleh `login_id`.
- **Hashing Password**: Menggunakan algoritma **Bcrypt** (`Hash::make`).
- **Pencegahan Brute-Force**: Dilindungi oleh rate limiter bawaan Laravel pada endpoint `/login`.

### B. Pemisahan Hak Akses (Role & Route Isolation)
| Grup Route | Prefix | Middleware | Akses | Peruntukan |
|:---|:---|:---|:---:|:---|
| **Auth** | `/` & `/login` | `guest` | Publik | Form Login Administrator |
| **Administrator** | `/admin/*` | `admin.auth` | Terproteksi Sesi | Manajemen CRUD Kegiatan & Dokumen |
| **Pegawai** | `/pegawai/*` | `web` | Publik (Read-Only) | Melihat Jadwal & Direktori Arsip |

---

## 5. Skema Basis Data Relasional

```sql
-- Tabel Pengguna (Hanya 1 Administrator)
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    login_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel Kegiatan Kedinasan
CREATE TABLE activities (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    activity_date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Tabel Dokumen Arsip
CREATE TABLE documents (
    id BIGSERIAL PRIMARY KEY,
    activity_id BIGINT NOT NULL REFERENCES activities(id) ON DELETE CASCADE,
    document_type VARCHAR(50) NOT NULL, -- surat, notulen, dokumentasi
    file_name VARCHAR(255) NOT NULL,
    file_url TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## 6. Rekapitulasi Hasil Pengujian Sistem

- **Total Pengujian Fungsional (Black Box Testing)**: **37 Test Cases**.
- **Tingkat Keberhasilan**: **100% PASS (37/37)**.
- **Pengujian Meliputi**:
  - Kesiapan dan peluncuran binary `.exe`.
  - Konektivitas cloud Supabase PostgreSQL dan Storage S3.
  - Alur autentikasi ID Administrator (`ADM001`) dan penolakan kredensial salah.
  - Operasi CRUD kegiatan kedinasan beserta validasi form.
  - Unggah, metadata, dan *inline proxy streaming* dokumen PDF/gambar.
  - Isolasi *read-only* antarmuka Pegawai (pencegahan aksi CRUD).
  - Penanganan error 404 tanpa kebocoran *debug trace*.
  - Ketahanan saat terjadi kegagalan jaringan internet.
