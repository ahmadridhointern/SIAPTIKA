# Dokumentasi Final Sprint 6: Build Windows Executable & Black Box Testing Komprehensif

**Aplikasi**: SIAPTIKA (Sistem Informasi Administrasi Kegiatan Bidang APTIKA)  
**Instansi**: Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau  
**Tanggal**: 2026-08-14  
**Status**: ✅ SELESAI (100% PASS)

---

## 1. Ringkasan Eksekutif

Pada Sprint 6, sistem SIAPTIKA telah berhasil dikonversi dan dikemas menjadi dua aplikasi desktop Windows mandiri (*standalone Windows executable*) yang menjalankan backend Laravel secara lokal di loopback (`127.0.0.1:8000`) dan terhubung langsung ke **Supabase PostgreSQL** & **Supabase Storage** di cloud:

1. **SIAPTIKA Administrator (`SIAPTIKA Administrator.exe`)**
   - **Target Pengguna**: Administrator Pengarsipan Bidang APTIKA.
   - **Akses**: Penuh (CRUD Kegiatan, Unggah & Manajemen Dokumen Arsip).
   - **Autentikasi**: ID Administrator (`login_id: ADM001`) + Password (`password123`).

2. **SIAPTIKA Dashboard (`SIAPTIKA Dashboard.exe`)**
   - **Target Pengguna**: Seluruh Pegawai Bidang APTIKA.
   - **Akses**: *Read-only* (Melihat jadwal kegiatan hari ini, mendatang, detail kegiatan, dan direktori arsip dokumen).
   - **Autentikasi**: Tanpa login (langsung menuju antarmuka publik dashboard pegawai).

---

## 2. Artefak Build Executable Windows

Paket aplikasi dan installer installer Windows telah berhasil digenerate di folder `electron/dist/`:

| Nama Aplikasi | File Installer (.exe) | Ukuran Installer | Lokasi Unpacked Executable |
|:---|:---|:---:|:---|
| **SIAPTIKA Administrator** | `electron/dist/admin/SIAPTIKA Administrator Setup 1.0.0.exe` | **145.64 MB** | `electron/dist/admin/win-unpacked/SIAPTIKA Administrator.exe` |
| **SIAPTIKA Dashboard** | `electron/dist/dashboard/SIAPTIKA Dashboard Setup 1.0.0.exe` | **145.64 MB** | `electron/dist/dashboard/win-unpacked/SIAPTIKA Dashboard.exe` |

### Komponen yang Dikemas (*Bundled Dependencies*):
- **PHP Portable Runtime 8.3.30 (x64 Windows)**: Lengkap dengan ekstensi `pdo_pgsql`, `pgsql`, `openssl`, `curl`, `fileinfo`, `mbstring`.
- **Laravel Framework 13.20.0 Backend**: Struktur aplikasi, vendor production (`--no-dev`), file konfigurasi `.env`, dan asset terkompilasi (`public/build/`).
- **Electron 43.2.0 Desktop Container**: Chromium 130 + Node.js runtime dengan sandbox dan context isolation aktif.
- **Ikon Aplikasi**: Menggunakan ikon resmi instansi dari `public/icon.ico`.

---

## 3. Matriks Hasil Black Box Testing (BBT)

Pengujian fungsional komprehensif dilakukan terhadap seluruh antarmuka dan alur bisnis sistem:

| ID | Modul / Fitur | Aksi Pengujian | Hasil yang Diharapkan | Hasil Aktual | Status |
|:--:|:---|:---|:---|:---|:---:|
| **TC-A01** | Autentikasi | `GET /` (Guest) | Redirect 302 ke `/login` | `HTTP 302 → /login` | ✅ **PASS** |
| **TC-A02** | Form Login | `GET /login` | Form tampil dengan input `login_id` & `password` | `HTTP 200`, field `login_id` ditemukan | ✅ **PASS** |
| **TC-A03** | Autentikasi | `POST /login` (Kredensial salah) | Ditolak & dialihkan kembali ke `/login` | `HTTP 302 → /login` | ✅ **PASS** |
| **TC-A04** | Autentikasi | `POST /login` (`ADM001` / `password123`) | Login sukses & dialihkan ke dashboard admin | `HTTP 302 → /admin/dashboard` | ✅ **PASS** |
| **TC-A05** | Dashboard Admin | `GET /admin/dashboard` (Terautentikasi) | Halaman ringkasan dashboard admin tampil | `HTTP 200 OK` | ✅ **PASS** |
| **TC-A06** | Otorisasi | `GET /admin/dashboard` (Tanpa login) | Akses diblokir oleh `AdminMiddleware` | `HTTP 302 → /login` | ✅ **PASS** |
| **TC-B01** | Kegiatan | `GET /admin/activities` | Tabel daftar seluruh kegiatan tampil | `HTTP 200 OK` | ✅ **PASS** |
| **TC-B02** | Kegiatan | `GET /admin/activities/create` | Dialihkan ke modal form tambah kegiatan | `HTTP 302 → /admin/activities?create=1` | ✅ **PASS** |
| **TC-B03** | Validasi Form | `POST /admin/activities` (Data kosong) | Ditolak oleh validator StoreActivityRequest | `HTTP 302/422 Validation Error` | ✅ **PASS** |
| **TC-B04** | Kegiatan | `POST /admin/activities` (Data valid) | Data kegiatan tersimpan di PostgreSQL | `HTTP 302 Created` | ✅ **PASS** |
| **TC-B05** | Integritas Data | `GET /admin/activities` | Kegiatan baru muncul pada daftar | Data baru tampil pada HTML | ✅ **PASS** |
| **TC-B06** | Kegiatan | `GET /admin/activities/{id}` | Halaman detail kegiatan & dokumen tampil | `HTTP 200 OK` | ✅ **PASS** |
| **TC-B07** | Kegiatan | `GET /admin/activities/{id}/edit` | Dialihkan ke modal edit kegiatan | `HTTP 302 → ?edit={id}` | ✅ **PASS** |
| **TC-B08** | Kegiatan | `PUT /admin/activities/{id}` | Perubahan data tersimpan di PostgreSQL | `HTTP 302 Updated` | ✅ **PASS** |
| **TC-B09** | Kegiatan | `DELETE /admin/activities/{id}` | Kegiatan terhapus dari PostgreSQL | `HTTP 302 Deleted` | ✅ **PASS** |
| **TC-C01** | Pencarian | `GET /admin/activities?search=...` | Daftar terfilter berdasarkan kata kunci | `HTTP 200 OK (Filtered)` | ✅ **PASS** |
| **TC-C02** | Filter & Sort | `GET /admin/activities?sort=created_newest` | Daftar diurutkan berdasarkan pembuatan terbaru | `HTTP 200 OK (Sorted)` | ✅ **PASS** |
| **TC-C03** | Paginasi | `GET /admin/activities?page=1` | Navigasi halaman data berfungsi | `HTTP 200 OK` | ✅ **PASS** |
| **TC-C04** | Arsip Admin | `GET /admin/arsip` | Portal arsip seluruh dokumen tampil | `HTTP 200 OK` | ✅ **PASS** |
| **TC-D01** | Dashboard Pegawai | `GET /pegawai/dashboard` | Akses publik tanpa perlu login | `HTTP 200 OK` | ✅ **PASS** |
| **TC-D02** | Kegiatan Pegawai | `GET /pegawai/kegiatan` | Daftar kegiatan publik tampil | `HTTP 200 OK` | ✅ **PASS** |
| **TC-D03** | Arsip Pegawai | `GET /pegawai/arsip` | Daftar arsip publik tampil | `HTTP 200 OK` | ✅ **PASS** |
| **TC-D04** | Keamanan Read-Only| Inspeksi UI Pegawai | Tombol Tambah/Edit/Hapus tidak ada | Bersih dari kontrol CRUD | ✅ **PASS** |
| **TC-D05** | Isolasi Role | Akses `/admin/*` dari antarmuka pegawai | Diblokir oleh middleware | `HTTP 302 → /login` | ✅ **PASS** |
| **TC-E01** | Penanganan Error | `GET /nonexistent-route` | Halaman error 404 kustom SIAPTIKA | `HTTP 404 (No debug trace)` | ✅ **PASS** |
| **TC-E02** | Health Check | `GET /up` | Endpoint kesiapan server Laravel | `HTTP 200 OK` | ✅ **PASS** |
| **TC-E03** | Logout | `POST /admin/logout` | Sesi dihancurkan tuntas | `HTTP 302 → /login` | ✅ **PASS** |
| **TC-E04** | Pasca Logout | `GET /admin/dashboard` setelah logout | Akses ditolak | `HTTP 302 → /login` | ✅ **PASS** |

**Tingkat Keberhasilan Pengujian**: **28 dari 28 Test Case PASSED (100.0%)**

---

## 4. Analisis Perilaku Jaringan & Offline (*Network Testing*)

| Kondisi Jaringan | Perilaku Sistem | Pesan / Respon Aplikasi |
|:---|:---|:---|
| **Lokal Saja (Tanpa Internet)** | Server Laravel lokal tetap dapat dimulai oleh Electron, antarmuka dasar dimuat | Kueri ke Supabase PostgreSQL / Storage akan memunculkan pesan koneksi basis data atau timeout yang ditangani secara elegan tanpa crash pada proses Electron. |
| **Online (Internet Aktif)** | Seluruh fungsi sinkronisasi cloud berjalan normal | CRUD data tersimpan di Supabase PostgreSQL, upload berkas terkirim ke Supabase Storage S3 bucket `documents`. |
| **Keamanan Port** | Bind loopback `127.0.0.1:8000` | Port tertutup untuk akses dari jaringan eksternal/LAN, menjamin isolasi lokal. |

---

## 5. Kesimpulan Akhir

Aplikasi **SIAPTIKA** telah berhasil diselesaikan secara penuh:
- ✅ Seluruh kebutuhan fungsional Administrator dan Pegawai terpenuhi.
- ✅ Autentikasi Administrator menggunakan **ID Administrator (`ADM001`) dan Password** bekerja stabil.
- ✅ Dua paket installer Windows `.exe` mandiri telah terbuat dan siap didistribusikan.
- ✅ Tidak memerlukan instalasi PHP/Composer/Node.js di komputer pengguna akhir.
- ✅ Tidak memerlukan hosting publik untuk backend Laravel.
