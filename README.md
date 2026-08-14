# SIAPTIKA (Sistem Informasi Administrasi Kegiatan Bidang APTIKA)

**Aplikasi Desktop Windows untuk Pengelolaan dan Pengarsipan Kegiatan Kedinasan**  
*Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau*

---

## 📌 Ringkasan Proyek

**SIAPTIKA** adalah aplikasi desktop Windows mandiri (*standalone Windows executable*) yang dikembangkan untuk mendigitalkan administrasi kegiatan dan pengarsipan dokumen kedinasan (Surat Undangan/Tugas, Notulen Rapat, dan Dokumentasi) pada Bidang Layanan Aplikasi Informatika dan Informasi Publik (APTIKA).

Aplikasi didistribusikan dalam **dua aplikasi desktop**:
1. **SIAPTIKA Administrator (`SIAPTIKA Administrator.exe`)**: Akses penuh CRUD kegiatan dan pengarsipan dokumen (memerlukan login ID Administrator `ADM001`).
2. **SIAPTIKA Dashboard (`SIAPTIKA Dashboard.exe`)**: Akses *read-only* bagi seluruh pegawai untuk melihat jadwal dan direktori arsip.

---

## 🏛️ Arsitektur Sistem

SIAPTIKA mengadopsi arsitektur **Hybrid Windows Desktop Application**:

```
Electron Container (Chromium 130 + Node.js 24)
        │
        ▼ (Silent background spawn)
Laravel Local Backend (127.0.0.1:8000)
  ├── Bundled Portable PHP 8.3.30 (x64)
  └── Stream Proxy & Business Rules Engine
        │
        ├── (HTTPS/SSL Port 6543) ──► Supabase PostgreSQL (Cloud Database)
        └── (S3 Protocol HTTPS)   ──► Supabase Storage (Cloud Archive Bucket)
```

---

## 🚀 Fitur Utama

- 🔑 **Autentikasi ID Administrator**: Login aman menggunakan ID Administrator (`ADM001`) dan Password terenkripsi bcrypt.
- 📅 **Manajemen Kegiatan Dinas**: Pencatatan data kegiatan, waktu, lokasi, status otomatis, dan pembaharuan agenda.
- 📁 **Pengarsipan Dokumen Multi-Format**: Unggah dokumen arsip (PDF, DOC, DOCX, JPG, PNG, MP4) ke Supabase Storage (maks. 10 MB).
- 👁️ **Streaming Pratinjau Dokumen**: Membaca dokumen arsip PDF/gambar secara langsung (*inline stream*) via proxy Laravel tanpa mengekspos public URL cloud.
- 📊 **Dashboard Pegawai (Read-Only)**: Papan informasi digital kegiatan hari ini dan kegiatan mendatang untuk seluruh pegawai tanpa perlu login.
- 🔍 **Pencarian & Penyaringan Lengkap**: Pencarian instan berdasarkan judul/lokasi, penyaringan status kegiatan, dan pengurutan tanggal.
- 🔒 **Keamanan & Isolasi Jaringan**: Server terikat hanya ke loopback `127.0.0.1` (tertutup dari akses LAN), renderer terisolasi dalam sandbox Electron.

---

## 📦 Paket Aplikasi Executable Windows

Paket installer dan aplikasi siap pakai tersedia di folder `electron/dist/`:
- **Installer Administrator**: `electron/dist/admin/SIAPTIKA Administrator Setup 1.0.0.exe`
- **Installer Dashboard**: `electron/dist/dashboard/SIAPTIKA Dashboard Setup 1.0.0.exe`
- **Unpacked Portable**:
  - `electron/dist/admin/win-unpacked/SIAPTIKA Administrator.exe`
  - `electron/dist/dashboard/win-unpacked/SIAPTIKA Dashboard.exe`

---

## 💻 Panduan Menjalankan dalam Mode Pengembangan (Development)

### Prasyarat
- PHP 8.2+ (dengan ekstensi `pdo_pgsql`, `pgsql`, `openssl`, `curl`, `fileinfo`, `mbstring`)
- Node.js 20+ & npm
- Composer

### Langkah-langkah
```bash
# 1. Clone repository & install backend dependencies
composer install
npm install

# 2. Build frontend assets
npm run build

# 3. Jalankan server Laravel lokal
php artisan serve

# 4. Di terminal terpisah, jalankan Electron
cd electron
npm install

# Jalankan Administrator App
npm run start:admin

# ATAU Jalankan Dashboard Pegawai App
npm run start:dashboard
```

---

## 🛠️ Perintah Build Aplikasi Desktop Windows (.exe)

```bash
cd electron

# Build Installer SIAPTIKA Administrator (.exe)
npm run build:admin

# Build Installer SIAPTIKA Dashboard (.exe)
npm run build:dashboard
```

Hasil build akan berada di `electron/dist/admin/` dan `electron/dist/dashboard/`.

---

## 📚 Dokumentasi Lengkap

- 📖 **Panduan Pengguna (*User Manual*)**: [docs/user_manual.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/docs/user_manual.md)
- ⚙️ **Dokumentasi Teknis (*Technical Documentation*)**: [docs/technical_documentation.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/docs/technical_documentation.md)
- 🎓 **Panduan & Bahan Laporan KP (BAB III & IV)**: [docs/panduan_laporan_kp.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/docs/panduan_laporan_kp.md)
- 📜 **Riwayat Migrasi Autentikasi**: [history/admin-authentication-id-password.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/history/admin-authentication-id-password.md)
- 🧪 **Dokumentasi Pengujian Sprint 6**: [sprint/dokumentasi_sprint6_final.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/sprint/dokumentasi_sprint6_final.md)

---

## ⚖️ Lisensi & Hak Cipta

© 2026 Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau. Dikembangkan sebagai bagian dari Laporan Kerja Praktik (KP).
