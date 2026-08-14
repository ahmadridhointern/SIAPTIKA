# Panduan & Bahan Penyusunan Laporan Kerja Praktik (BAB III & BAB IV)

**Judul Laporan**: Pengembangan Sistem Informasi Administrasi Kegiatan Bidang APTIKA (SIAPTIKA) Berbasis Desktop Menggunakan Electron, Laravel, dan Supabase pada Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau  
**Instansi Kerja Praktik**: Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik) Provinsi Riau  
**Bidang Penempatan**: Bidang Layanan Aplikasi Informatika dan Informasi Publik (APTIKA)

---

## 1. Bahan Penyusunan BAB III — Pelaksanaan Kerja Praktik

### A. Metodologi Pengembangan Sistem
- **Metode**: *Scrum Agile Framework* yang terbagi ke dalam 6 Sprint:
  - **Sprint 1**: Analisis kebutuhan, perancangan arsitektur, dan inisialisasi project Laravel.
  - **Sprint 2**: Implementasi modul autentikasi dan otorisasi Administrator.
  - **Sprint 3**: Implementasi CRUD kegiatan kedinasan, filtering, dan pagination.
  - **Sprint 4**: Integrasi Supabase Storage untuk pengarsipan dokumen (Surat, Notulen, Dokumentasi).
  - **Sprint 5**: Pengembangan Dashboard Pegawai (*read-only*), integrasi fitur pencarian, dan migrasi autentikasi ke ID Administrator (`ADM001`).
  - **Sprint 6**: Pengemasan desktop dengan Electron, bundling PHP runtime portable, pengujian *End-to-End Black Box*, dan finalisasi dokumentasi.

### B. Lingkungan Pengembangan & Alat Bantu (*Tools*)
- **Perangkat Keras (*Hardware*)**: Laptop Asus (Intel Core i5 / RAM 16 GB / SSD 512 GB).
- **Sistem Operasi**: Microsoft Windows 11 (64-bit).
- **Bahasa & Framework**:
  - PHP 8.3.30 & Laravel Framework 13.20.0 (Backend Lokal).
  - JavaScript, TailwindCSS, & Alpine.js (Frontend Blade Templates).
  - Node.js 24 & Electron 43.2.0 (Desktop Application Wrapper).
- **Basis Data & Cloud Storage**:
  - Supabase PostgreSQL (Cloud Relational Database dengan SSL).
  - Supabase Storage S3 Protocol (Bucket `documents`).
- **Alat Pendukung**: Visual Studio Code, Git/GitHub, Postman, TablePlus/DBeaver.

### C. Arsitektur Perangkat Lunak
- **Model Arsitektur**: *Hybrid Windows Desktop Application*.
- **Mekanisme Kerja**:
  1. Pengguna membuka file `.exe` (Administrator atau Dashboard).
  2. Electron secara otomatis menjalankan backend Laravel lokal di background melalui *child process* menggunakan runtime PHP portable yang ter-bundle.
  3. Electron memantau kesiapan server melalui endpoint `http://127.0.0.1:8000/up`.
  4. Jendela aplikasi dimuat dan berkomunikasi secara lokal dengan backend Laravel.
  5. Backend Laravel berkomunikasi secara aman melalui koneksi terenkripsi (HTTPS/SSL) ke Supabase PostgreSQL dan Supabase Storage.
  6. Saat jendela aplikasi ditutup, Electron mematikan proses PHP secara otomatis.

---

## 2. Bahan Penyusunan BAB IV — Hasil dan Pembahasan

### A. Hasil Implementasi Sistem
Sistem menghasilkan dua aplikasi desktop Windows mandiri:
1. **SIAPTIKA Administrator (`SIAPTIKA Administrator.exe`)**:
   - Berfungsi untuk mencatat, memperbarui, dan menghapus data agenda kegiatan dinas.
   - Mengunggah berkas arsip kedinasan (Surat Undangan, Notulen Rapat, Foto/Video Kegiatan).
   - Memerlukan login resmi dengan **ID Administrator (`ADM001`)** dan kata sandi terenkripsi.
2. **SIAPTIKA Dashboard (`SIAPTIKA Dashboard.exe`)**:
   - Berfungsi sebagai papan informasi digital (*digital dashboard*) untuk seluruh pegawai APTIKA.
   - Menampilkan kegiatan hari ini, kegiatan yang akan datang, dan direktori arsip dokumen.
   - Berjalan dalam mode *read-only* tanpa tombol tambah, ubah, atau hapus data.

### B. Pembahasan Hasil Pengujian Fungsional (*Black Box Testing*)
Pengujian dilakukan terhadap 37 skenario pengujian fungsional dengan hasil **100% Lolos (37/37 Test Case PASS)**:
- **Pengujian Autentikasi**: Sistem berhasil memverifikasi ID Administrator `ADM001` dan menolak kredensial yang tidak sah.
- **Pengujian Otorisasi**: Route Administrator terlindungi ketat dari akses tanpa izin.
- **Pengujian CRUD Kegiatan**: Siklus hidup pencatatan kegiatan berjalan akurat sesuai aturan bisnis.
- **Pengujian Pengarsipan Berkas**: Pengunggahan berkas ke Supabase Storage dan streaming pratinjau inline PDF/gambar berhasil tanpa kebocoran URL privat.
- **Pengujian Isolasi Pegawai**: Antarmuka Pegawai terbukti bersih dari fungsi modifikasi data.

---

## 3. Checklist Tangkapan Layar & Daftar Gambar (*Screenshot & Figure Checklist*)

Berikut adalah panduan tangkapan layar yang wajib dilampirkan pada naskah laporan:

| No | Nama Berkas Gambar | Judul / Caption Gambar di Laporan | Penempatan Bab | Keterangan / Yang Ditunjukkan |
|:--:|:---|:---|:---:|:---|
| 1 | `gambar_arsitektur_sistem.png` | **Gambar 3.1** Arsitektur Sistem Hybrid Desktop SIAPTIKA | **BAB III** | Diagram alur integrasi Electron ➔ Laravel Local ➔ Supabase Cloud |
| 2 | `gambar_erd_database.png` | **Gambar 3.2** *Entity Relationship Diagram* (ERD) Basis Data | **BAB III** | Struktur relasi tabel `users`, `activities`, dan `documents` |
| 3 | `gambar_use_case_diagram.png` | **Gambar 3.3** *Use Case Diagram* Sistem SIAPTIKA | **BAB III** | Interaksi aktor Administrator dan Pegawai dengan sistem |
| 4 | `gambar_activity_diagram_login.png`| **Gambar 3.4** *Activity Diagram* Alur Login Administrator | **BAB III** | Alur verifikasi ID Administrator (`login_id`) dan Password |
| 5 | `gambar_installer_windows.png` | **Gambar 4.1** File Installer Aplikasi Desktop Windows | **BAB IV** | Tampilan file installer setup `.exe` dan ikon aplikasi |
| 6 | `gambar_splash_screen.png` | **Gambar 4.2** Tampilan *Splash Screen* Pemuatan Server Lokal | **BAB IV** | Tampilan loading screen animasi saat server PHP dimulai |
| 7 | `gambar_halaman_login_admin.png` | **Gambar 4.3** Tampilan Antarmuka Halaman Login Administrator | **BAB IV** | Form login dengan input ID Administrator (`ADM001`) |
| 8 | `gambar_validasi_login_gagal.png` | **Gambar 4.4** Tampilan Pesan Peringatan Login Gagal | **BAB IV** | Pesan error saat ID atau password salah dimasukkan |
| 9 | `gambar_dashboard_admin.png` | **Gambar 4.5** Tampilan Dashboard Utama Administrator | **BAB IV** | Ringkasan statistik kegiatan dan bilah navigasi admin |
| 10 | `gambar_tabel_daftar_kegiatan.png`| **Gambar 4.6** Tampilan Tabel Daftar Seluruh Kegiatan Dinas | **BAB IV** | Tabel data kegiatan dengan filter status dan aksi |
| 11 | `gambar_modal_tambah_kegiatan.png`| **Gambar 4.7** Tampilan Modal Formulir Tambah Kegiatan Baru | **BAB IV** | Form input judul, tanggal, waktu, lokasi, dan deskripsi |
| 12 | `gambar_validasi_form_kegiatan.png`| **Gambar 4.8** Tampilan Validasi Formulir Kegiatan | **BAB IV** | Pesan error validasi saat field wajib belum diisi |
| 13 | `gambar_detail_kegiatan_admin.png`| **Gambar 4.9** Tampilan Halaman Detail Kegiatan dan Dokumen | **BAB IV** | Rincian kegiatan beserta daftar dokumen arsip terlampir |
| 14 | `gambar_modal_unggah_dokumen.png` | **Gambar 4.10** Tampilan Modal Unggah Dokumen Arsip | **BAB IV** | Form pemilihan jenis dokumen (surat/notulen/dokumentasi) |
| 15 | `gambar_pratinjau_dokumen_pdf.png`| **Gambar 4.11** Tampilan Pratinjau (*Inline Preview*) Berkas PDF | **BAB IV** | Streaming dokumen PDF via proxy server lokal |
| 16 | `gambar_direktori_arsip_admin.png`| **Gambar 4.12** Tampilan Halaman Portal Direktori Arsip Admin | **BAB IV** | Koleksi seluruh berkas arsip yang telah diunggah |
| 17 | `gambar_dashboard_pegawai.png` | **Gambar 4.13** Tampilan Dashboard Utama Pegawai (*Read-Only*) | **BAB IV** | Agenda hari ini dan kegiatan mendatang tanpa tombol aksi |
| 18 | `gambar_pencarian_kegiatan.png` | **Gambar 4.14** Tampilan Hasil Pencarian Data Kegiatan | **BAB IV** | Hasil penyaringan data instan berdasarkan kata kunci |
| 19 | `gambar_halaman_error_404.png` | **Gambar 4.15** Tampilan Halaman Error 404 Kustom SIAPTIKA | **BAB IV** | Halaman ramah pengguna saat mengakses rute tidak valid |
| 20 | `gambar_supabase_dashboard.png` | **Gambar 4.16** Tampilan Pengelolaan Data pada Supabase Cloud | **BAB IV** | Tampilan tabel di Supabase Dashboard (PostgreSQL & Storage) |

---

## 4. Rekomendasi Poin Pembahasan untuk BAB IV

1. **Efektivitas Migrasi ke ID Administrator**:
   - Jelaskan bahwa penggunaan **ID Administrator (`ADM001`)** menyederhanakan proses autentikasi kedinasan dibandingkan penggunaan email pribadi, serta memastikan bahwa hak akses melekat pada jabatan pengarsip instansi, bukan akun perorangan.
2. **Keunggulan Arsitektur Hybrid Desktop**:
   - Jelaskan bahwa arsitektur ini membebaskan instansi dari biaya bulanan sewa server web publik (seperti VPS/cPanel), menjaga privasi server di jaringan lokal kantor, namun tetap memiliki basis data terpusat yang dapat diakses secara bersamaan antar-komputer melalui internet.
3. **Efisiensi Pengarsipan Terpusat**:
   - Pembahasan mengenai kemudahan pegawai dalam menemukan notulen rapat dan surat tugas lama melalui fitur pencarian direktori arsip secara digital, menggantikan pencarian berkas fisik di lemari arsip.
