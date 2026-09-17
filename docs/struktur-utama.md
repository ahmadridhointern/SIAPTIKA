# KERANGKA DAN STRUKTUR UTAMA LAPORAN KERJA PRAKTEK (KP)

**Judul Laporan**:  
**PENGEMBANGAN SISTEM INFORMASI ADMINISTRASI KEGIATAN BIDANG APTIKA (SIAPTIKA) BERBASIS DESKTOP MENGGUNAKAN ELECTRON, LARAVEL, DAN SUPABASE PADA DINAS KOMUNIKASI, INFORMATIKA, DAN STATISTIK PROVINSI RIAU**

**Instansi Tempat KP**: Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik) Provinsi Riau  
**Unit/Bidang Penempatan**: Bidang Layanan Aplikasi Informatika dan Informasi Publik (APTIKA)  
**Program Studi**: Teknik Informatika, Universitas Islam Riau (UIR)

---

## I. KETENTUAN FORMAT DAN TATA LETAK PENULISAN

Berdasarkan *Buku Panduan Laporan KP 2025* dan *Gaya Penulisan Laporan Teknik Informatika*, ketentuan penulisan naskah diatur sebagai berikut:

| Parameter | Ketentuan Format | Keterangan Tambahan |
|:---|:---|:---|
| **Ukuran Kertas** | A4 (210 mm x 297 mm) | Berat kertas 80 gram |
| **Batas Margin** | Kiri: 4 cm, Atas: 3 cm, Kanan: 3 cm, Bawah: 3 cm | Ruang penjilidan di sisi kiri |
| **Jenis & Ukuran Font** | Times New Roman, 12 pt | Judul Bab: 14 pt (Kapital, Tebal, Rata Tengah) |
| **Spasi Baris (Line Spacing)** | 1,5 Spasi | Spasi antarbaris dalam tabel: 1 spasi; Antar-paragraf ke sub-bab: 2 spasi |
| **Alinea Baru (Indentasi)** | 7 ketukan huruf (± 1,27 cm / 0.5 inch) | Awal paragraf menjorok ke dalam |
| **Penomoran Halaman Awal** | Huruf Romawi kecil (`i, ii, iii, ...`) | Terletak di tengah bawah margin |
| **Penomoran Halaman Isi** | Angka Latin (`1, 2, 3, ...`) | Kanan atas halaman; Halaman judul bab di tengah bawah |
| **Penomoran Gambar & Tabel** | Mengikuti nomor bab (`Gambar 3.1`, `Tabel 2.1`) | Judul tabel di atas; Judul gambar di bawah |
| **Format Sitasi & Pustaka** | *American Psychological Association* (APA Style) | Minimal 15 daftar pustaka (terbitan 5 tahun terakhir) |
| **Gaya Penulisan Kalimat** | Semi-formal, akademik natural, kalimat pasif | Hindari kata ganti orang pertama ("saya/kami/penulis" di dalam pembahasan isi) |

---

## II. SISTEMATIKA LENGKAP LAPORAN KERJA PRAKTEK

```
HALAMAN JUDUL / COVER
LEMBAR PENGESAHAN LAPORAN KERJA PRAKTEK
KATA PENGANTAR
DAFTAR ISI
DAFTAR TABEL
DAFTAR GAMBAR
DAFTAR LAMPIRAN

BAB I PENDAHULUAN
  1.1 Latar Belakang
  1.2 Tujuan Kerja Praktek
  1.3 Manfaat Kerja Praktek
      1.3.1 Manfaat bagi Mahasiswa
      1.3.2 Manfaat bagi Instansi (Diskominfotik Riau)
      1.3.3 Manfaat bagi Program Studi Teknik Informatika UIR
  1.4 Ringkasan Sistematika Laporan

BAB II DESKRIPSI KERJA PRAKTEK
  2.1 Waktu dan Tempat Pelaksanaan
      2.1.1 Waktu Pelaksanaan
      2.1.2 Tempat Pelaksanaan
  2.2 Profil Instansi Tempat Kerja Praktek
      2.2.1 Profil Singkat Diskominfotik Provinsi Riau
      2.2.2 Visi dan Misi Instansi
      2.2.3 Struktur Organisasi Instansi
      2.2.4 Profil Unit Kerja Bidang APTIKA
      2.2.5 Tugas Pokok dan Fungsi Bidang APTIKA
  2.3 Program Kerja Praktek
      2.3.1 Target Pemecahan Masalah
      2.3.2 Metode Pelaksanaan Tugas (Metodologi Scrum)
      2.3.3 Rencana dan Penjadwalan Kerja (Sprint 1 s.d. Sprint 6)

BAB III PELAKSANAAN DAN PEMBAHASAN KERJA PRAKTEK
  3.1 Landasan Teori dan Teknologi
      3.1.1 Konsep Sistem Informasi Administrasi dan Arsip Digital
      3.1.2 Arsitektur Aplikasi Desktop Hybrid
      3.1.3 Framework Laravel dan Eloquent ORM
      3.1.4 Electron Framework dan Bundled Portable PHP Runtime
      3.1.5 Basis Data Cloud Supabase PostgreSQL dan Supabase Storage (S3 Protocol)
      3.1.6 Metodologi Pengembangan Perangkat Lunak Scrum
  3.2 Analisis dan Perancangan Sistem
      3.2.1 Analisis Permasalahan Sistem Berjalan
      3.2.2 Analisis Kebutuhan Pengguna (User Requirements)
            a. Kebutuhan Fungsional (Functional Requirements)
            b. Kebutuhan Non-Fungsional (Non-Functional Requirements)
      3.2.3 Perancangan Alur Sistem (Flowchart)
      3.2.4 Pemodelan Sistem Berorientasi Objek (UML)
            a. Use Case Diagram
            b. Activity Diagram
      3.2.5 Perancangan Basis Data (Database Design)
            a. Entity Relationship Diagram (ERD)
            b. Struktur Skema dan Spesifikasi Tabel
      3.2.6 Perancangan Arsitektur Perangkat Lunak (Software Architecture)
      3.2.7 Perancangan Antarmuka Pengguna (Wireframe & UI Design)
  3.3 Implementasi Sistem
      3.3.1 Lingkungan Pengembangan Sistem (Hardware & Software)
      3.3.2 Implementasi Basis Data dan Integrasi Cloud Supabase
      3.3.3 Implementasi Modul Autentikasi dan Otorisasi Administrator
      3.3.4 Implementasi Modul Manajemen Kegiatan Kedinasan (CRUD)
      3.3.5 Implementasi Modul Pengarsipan Dokumen Terpusat (Upload & Streaming Proxy)
      3.3.6 Implementasi Dashboard Informasi Pegawai (Portal Read-Only)
      3.3.7 Implementasi Pengemasan Aplikasi Desktop Mandiri (Electron Packaging & Bundling)
  3.4 Hasil dan Pengujian Sistem
      3.4.1 Hasil Implementasi Antarmuka Pengguna
            a. Antarmuka SIAPTIKA Administrator
            b. Antarmuka SIAPTIKA Dashboard Pegawai
      3.4.2 Hasil Pengujian Fungsional (Black Box Testing)
            a. Lingkungan dan Skenario Pengujian
            b. Rincian Hasil Uji Kasus (37 Test Cases)
            c. Evaluasi Hasil Pengujian
      3.4.3 Analisis dan Pembahasan
            a. Analisis Efektivitas Penggunaan ID Administrator (ADM001)
            b. Analisis Keunggulan Arsitektur Hybrid Desktop Tanpa Server Publik
            c. Analisis Efisiensi Pengelolaan Arsip Digital Terintegrasi

BAB IV KESIMPULAN DAN SARAN
  4.1 Kesimpulan
  4.2 Saran

DAFTAR PUSTAKA
LAMPIRAN
```

---

## III. RINCIAN KONTEN DAN PEMBAHASAN PER BAGIAN

### 1. Bagian Awal (Halaman Pengantar)

- **Halaman Judul / Cover**: Memuat judul lengkap, logo resmi Universitas Islam Riau (UIR), nama mahasiswa, NPM, program studi Teknik Informatika, fakultas, dan tahun penyusunan.
- **Lembar Pengesahan**: Pengesahan resmi oleh Dosen Pembimbing KP dan Pembimbing Lapangan di Diskominfotik Riau.
- **Kata Pengantar**: Berisi ucapan syukur dan ucapan terima kasih kepada pimpinan Diskominfotik Riau, Kepala Bidang APTIKA, Pembimbing Lapangan, Dosen Pembimbing, dan rekan kerja praktek.
- **Daftar Isi, Daftar Tabel, Daftar Gambar, Daftar Lampiran**: Struktur indeks penomoran halaman otomatis.

---

### 2. BAB I PENDAHULUAN

#### 1.1 Latar Belakang
- Menjelaskan peran strategis Bidang Layanan Aplikasi Informatika dan Informasi Publik (APTIKA) Diskominfotik Provinsi Riau dalam mengelola program dan kegiatan digital kedinasan.
- Mengidentifikasi kendala pada sistem administrasi berjalan:
  1. Pencatatan agenda kegiatan masih tersebar dalam catatan manual dan grup pesan instan, menyebabkan risiko terlewatnya jadwal penting.
  2. Berkas arsip kegiatan (surat undangan, notulen rapat, dokumentasi foto) tersimpan secara lokal di komputer masing-masing staf atau lemari fisik, menyulitkan temu kembali (*retrieval*) saat audit atau pelaporan kinerja.
  3. Pegawai non-admin kesulitan memantau agenda harian dan rekap arsip secara cepat dan terpusat.
- Menjelaskan urgensi pengembangan aplikasi **SIAPTIKA** (*Sistem Informasi Administrasi Kegiatan Bidang APTIKA*) berbasis desktop *hybrid* yang menggabungkan kemudahan aplikasi desktop lokal dengan keandalan basis data dan *cloud storage* terpusat (Supabase).

#### 1.2 Tujuan Kerja Praktek
1. Menganalisis kebutuhan sistem administrasi kegiatan dan pengelolaan arsip kedinasan pada Bidang APTIKA Diskominfotik Riau.
2. Merancang sistem informasi administrasi kegiatan berbasis desktop dengan arsitektur *hybrid* (Electron + Local Laravel + Supabase Cloud).
3. Mengembangkan dan mengimplementasikan aplikasi **SIAPTIKA Administrator** (pengelolaan data penuh) dan **SIAPTIKA Dashboard** (portal informasi pegawai *read-only*).
4. Menguji fungsionalitas sistem menggunakan metode *Black Box Testing* untuk memastikan keandalan, keamanan otorisasi, dan validitas penyimpanan data/arsip.

#### 1.3 Manfaat Kerja Praktek
- **Bagi Mahasiswa**: Menerapkan kompetensi rekayasa perangkat lunak dalam proyek nyata kedinasan, menguasai integrasi framework modern (Laravel, Electron, Supabase), dan memahami alur kerja kedinasan di sektor pemerintahan.
- **Bagi Instansi (Diskominfotik Riau)**: Memperoleh perangkat lunak mandiri yang meningkatkan efisiensi pencatatan kegiatan, mempermudah koordinasi antarpegawai, mengamankan arsip kedinasan di *cloud storage*, serta menghemat biaya operasional karena tidak membutuhkan server web publik berbayar.
- **Bagi Program Studi Teknik Informatika UIR**: Mempererat hubungan kemitraan dengan instansi pemerintah daerah dan memperkaya perbendaharaan laporan studi kasus pengembangan aplikasi *hybrid desktop*.

#### 1.4 Ringkasan Sistematika Laporan
- Uraian ringkas satu paragraf untuk masing-masing bab (Bab I Pendahuluan, Bab II Deskripsi Kerja Praktek, Bab III Pelaksanaan dan Pembahasan, Bab IV Kesimpulan dan Saran).

---

### 3. BAB II DESKRIPSI KERJA PRAKTEK

#### 2.1 Waktu dan Tempat Pelaksanaan
- Jadwal rentang waktu pelaksanaan Kerja Praktik (tanggal mulai hingga tanggal selesai).
- Lokasi kantor Diskominfotik Provinsi Riau (Jl. Jenderal Sudirman, Pekanbaru).

#### 2.2 Profil Instansi Tempat Kerja Praktek
- **Profil Singkat**: Sejarah, kedudukan, dan peran Diskominfotik Provinsi Riau dalam transformasi digital pemerintahan daerah.
- **Visi dan Misi**: Visi mewujudkan tata kelola pemerintahan berbasis elektronik (SPBE) yang terintegrasi di Provinsi Riau.
- **Struktur Organisasi**: Bagan struktur organisasi dinas beserta deskripsi unit eselon.
- **Profil dan Tupoksi Bidang APTIKA**: Uraian unit kerja penempatan, fokus layanan pengembangan aplikasi, integrasi sistem informasi, dan pengelolaan informasi publik.

#### 2.3 Program Kerja Praktek
- **Target Pemecahan Masalah**: Digitalisasi pencatatan kegiatan, penyediaan direktori arsip terpusat, dan penyediaan media informasi agenda dinas untuk pegawai.
- **Metode Pelaksanaan Tugas**: Penerapan *Scrum Agile Framework* dengan siklus iteratif terukur.
- **Rencana dan Penjadwalan Kerja (Sprint 1–6)**:
  - *Sprint 1*: Analisis kebutuhan, perancangan UML, ERD, dan wireframe UI.
  - *Sprint 2*: Inisialisasi arsitektur, konfigurasi Supabase PostgreSQL, dan modul autentikasi Admin.
  - *Sprint 3*: Implementasi modul manajemen kegiatan kedinasan (CRUD, filtering status, pagination).
  - *Sprint 4*: Integrasi modul pengarsipan dokumen via Supabase Storage S3 (upload, edit metadata, delete).
  - *Sprint 5*: Pengembangan SIAPTIKA Dashboard Pegawai (*read-only*), pencarian cepat, dan standarisasi login ID (`ADM001`).
  - *Sprint 6*: Pengemasan desktop mandiri dengan Electron, bundling runtime portable PHP 8.3, pengujian *Black Box*, dan dokumentasi akhir.

---

### 4. BAB III PELAKSANAAN DAN PEMBAHASAN KERJA PRAKTEK

#### 3.1 Landasan Teori dan Teknologi
- **Konsep Administrasi & Arsip Digital**: Definisi manajemen kegiatan kedinasan, penomoran dokumen, dan integritas pengarsipan digital.
- **Arsitektur Desktop Hybrid**: Konsep integrasi Electron sebagai antarmuka desktop lokal yang mengontrol proses web engine (Laravel) secara internal di latar belakang (*loopback* `127.0.0.1`).
- **Laravel Framework & Eloquent ORM**: Pola arsitektur MVC (*Model-View-Controller*), *Service Repository Pattern*, dan manajemen migrasi database.
- **Electron & Portable PHP Runtime**: Mekanisme pembungkusan desktop, *child process spawning*, *single instance locking*, dan isolasi runtime PHP tanpa dependensi global.
- **Supabase Cloud (PostgreSQL & Storage S3)**: Basis data relasional berbasis cloud dengan enkripsi SSL/TLS serta penyimpanan objek dokumen terpusat.
- **Scrum Agile Framework**: Tahapan *Product Backlog, Sprint Planning, Daily Standup, Sprint Review*, dan *Sprint Retrospective*.

#### 3.2 Analisis dan Perancangan Sistem
- **3.2.1 Analisis Permasalahan Sistem Berjalan**: Deskripsi proses bisnis pencatatan kegiatan sebelum adanya sistem (flow diagram sistem lama / manual).
- **3.2.2 Analisis Kebutuhan Sistem**:
  - *Kebutuhan Fungsional*: Login Admin berbasis ID (`ADM001`), pencatatan/pengeditan/penghapusan kegiatan, pengunggahan 3 kategori dokumen (surat/notulen/dokumentasi), pratinjau inline PDF/gambar, portal direktori arsip, dan dashboard pegawai *read-only*.
  - *Kebutuhan Non-Fungsional*: Keamanan kredensial terenkripsi (Bcrypt), isolasi rute middleware, waktu respons startup < 3 detik, *offline-safe packaging* (bebas Laragon/Composer/Node di komputer target).
- **3.2.3 Perancangan Alur Sistem (Flowchart)**:
  - Flowchart Sistem Keseluruhan
  - Flowchart Pengelolaan Kegiatan oleh Administrator
  - Flowchart Pengunggahan Dokumen Arsip
  - Flowchart Pemuatan Dashboard Pegawai
- **3.2.4 Pemodelan UML (Unified Modeling Language)**:
  - *Use Case Diagram*: Aktor Administrator (kelola penuh) dan Pegawai (lihat/unduh).
  - *Activity Diagram*: Alur login admin, alur CRUD kegiatan, alur upload arsip, dan alur pencarian arsip.
- **3.2.5 Perancangan Basis Data**:
  - *Entity Relationship Diagram (ERD)*: Relasi tabel `users` (1:N) `activities` (1:N) `documents`.
  - *Kamus Data & Spesifikasi Skema Tabel*:
    - Tabel `users` (`id`, `name`, `login_id`, `password`, `role`, `created_at`, `updated_at`).
    - Tabel `activities` (`id`, `user_id`, `title`, `activity_date`, `time`, `location`, `description`, `created_at`, `updated_at`).
    - Tabel `documents` (`id`, `activity_id`, `document_type`, `file_name`, `file_url`, `created_at`, `updated_at`).
- **3.2.6 Perancangan Arsitektur Perangkat Lunak**:
  - Diagram blok arsitektur: `Desktop Executable ➔ Electron Process ➔ Local Laravel (127.0.0.1:8000) ➔ Supabase Cloud (PostgreSQL 17.6 + Storage S3)`.
- **3.2.7 Perancangan Antarmuka Pengguna (Wireframe)**:
  - Sketsa tata letak form login, dashboard admin, daftar tabel kegiatan, modal unggah arsip, dan dashboard pegawai.

#### 3.3 Implementasi Sistem
- **3.3.1 Lingkungan Pengembangan**: Spesifikasi perangkat keras, sistem operasi, IDE VS Code, Node.js, dan lingkungan pengujian.
- **3.3.2 Implementasi Basis Data & Cloud**: Pembuatan migrasi Laravel, konfigurasi koneksi pooler Supabase PostgreSQL via SSL, dan bucket S3 `documents`.
- **3.3.3 Implementasi Modul Autentikasi Admin**: Controller autentikasi, enkripsi password Bcrypt, middleware proteksi rute, dan session management.
- **3.3.4 Implementasi Modul Manajemen Kegiatan**: Controller `ActivityController`, perhitungan status otomatis (*computed status*: Direncana, Sudah Berlangsung, Selesai), validasi input, dan pagination.
- **3.3.5 Implementasi Modul Pengarsipan Dokumen**: `DocumentService` berbasis transaksi database atomik (unggah S3 ➔ insert metadata DB ➔ rollback jika gagal), dan controller streaming proxy untuk pratinjau dokumen privat.
- **3.3.6 Implementasi Dashboard Pegawai**: Controller `PegawaiDashboardController`, pencarian instan agenda harian, dan penyajian data *read-only*.
- **3.3.7 Implementasi Pengemasan Desktop Mandiri**: Modul `laravel-bridge.js`, bundling portable PHP 8.3 + DLL VC++ CRT + sertifikat CA Mozilla `cacert.pem`, serta konfigurasi NSIS installer pada `electron-builder`.

#### 3.4 Hasil dan Pengujian Sistem
- **3.4.1 Hasil Antarmuka Pengguna**:
  - Tangkapan layar (*screenshot*) dan pembahasan antarmuka: Splash screen, Login Admin, Dashboard Admin, Tabel Kegiatan, Modal Tambah Kegiatan, Detail Kegiatan & Arsip, Modal Unggah Dokumen, Pratinjau PDF, Direktori Arsip, Dashboard Pegawai, dan Halaman Error 404 Kustom.
- **3.4.2 Hasil Pengujian Fungsional (Black Box Testing)**:
  - Tabel 37 skenario uji fungsional (Autentikasi, CRUD Kegiatan, Upload/Hapus Arsip, Filter/Search, Otorisasi Pegawai, dan Lifecycle Desktop).
  - Ringkasan hasil pengujian: **37 Kasus Uji Dijalankan (100% PASS / Sesuai Harapan)**.
- **3.4.3 Analisis dan Pembahasan**:
  - *Efektivitas ID Administrator*: Penggunaan `ADM001` menjaga integritas hak akses berbasis peran kedinasan.
  - *Keunggulan Arsitektur Hybrid Desktop*: Menghilangkan ketergantungan sewa server publik bulanan sambil mempertahankan basis data terpusat antar-perangkat.
  - *Efisiensi Pengarsipan Digital*: Pengurangan waktu pencarian dokumen dari hitungan menit/jam menjadi hitungan detik melalui pencarian terpadu.

---

### 5. BAB IV KESIMPULAN DAN SARAN

#### 4.1 Kesimpulan
- Poin kesimpulan yang menjawab seluruh tujuan pelaksanaan KP:
  1. Sistem informasi SIAPTIKA berhasil dikembangkan sesuai kebutuhan analisis masalah administrasi dan arsip kegiatan pada Bidang APTIKA Diskominfotik Riau.
  2. Implementasi arsitektur desktop *hybrid* (Electron, Laravel lokal, dan Supabase Cloud) menghasilkan dua aplikasi mandiri: **SIAPTIKA Administrator** dan **SIAPTIKA Dashboard** yang siap pakai tanpa instalasi perangkat lunak tambahan (*zero-dependency*).
  3. Fitur pengelolaan kegiatan dan pengarsipan dokumen berbasis *cloud storage* berhasil mendigitalkan dan mengamankan seluruh berkas kedinasan (surat, notulen, dokumentasi).
  4. Pengujian fungsional *Black Box Testing* terhadap 37 skenario pengujian membuktikan bahwa sistem bekerja 100% stabil, aman dari akses tanpa izin, dan akurat dalam pemrosesan data.

#### 4.2 Saran
- Poin saran untuk pengembangan dan penelitian lanjutan:
  1. Penambahan fitur ekspor laporan berkala otomatis dalam format PDF atau Excel per periode anggaran.
  2. Penambahan sistem notifikasi pengingat kegiatan (*desktop push notification*) menjelang waktu pelaksanaan agenda.
  3. Pengembangan fitur tanda tangan digital (*digital signature*) pada dokumen notulen rapat yang diunggah.

---

### 6. Bagian Akhir (Daftar Pustaka & Lampiran)

- **Daftar Pustaka**: Minimal 15 referensi ilmiah (jurnal terakreditasi, prosiding, dan buku teks 5 tahun terakhir) dengan format APA Style.
- **Lampiran**:
  - Lampiran 1: Surat Keterangan / SK Kerja Praktik
  - Lampiran 2: Lembar Penilaian & Kartu Bimbingan Kerja Praktik
  - Lampiran 3: Logbook Catatan Harian Kegiatan KP
  - Lampiran 4: Potongan Kode Sumber Inti (*Core Code Snippets*: `DocumentService.php`, `Activity.php`, `laravel-bridge.js`)
  - Lampiran 5: Tabel Lengkap Rincian 37 Kasus Uji Black Box Testing
  - Lampiran 6: Dokumentasi Foto Pelaksanaan Kerja Praktik di Kantor Diskominfotik Riau

---

## IV. DAFTAR GAMBAR DAN TABEL TERENCANA

### Daftar Gambar
1. **Gambar 2.1** Struktur Organisasi Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau
2. **Gambar 2.2** Alur Tahapan Pengembangan Sistem Menggunakan Metodologi Scrum
3. **Gambar 3.1** Arsitektur Sistem Hybrid Desktop SIAPTIKA
4. **Gambar 3.2** *Entity Relationship Diagram* (ERD) Basis Data SIAPTIKA
5. **Gambar 3.3** *Use Case Diagram* Sistem SIAPTIKA
6. **Gambar 3.4** *Activity Diagram* Alur Autentikasi Administrator
7. **Gambar 3.5** *Activity Diagram* Pengelolaan Kegiatan Kedinasan
8. **Gambar 3.6** *Activity Diagram* Pengunggahan Dokumen Arsip
9. **Gambar 3.7** *Flowchart* Sistem Administrasi Kegiatan SIAPTIKA
10. **Gambar 3.8** Perancangan Antarmuka (*Wireframe*) Dashboard Administrator
11. **Gambar 3.9** Perancangan Antarmuka (*Wireframe*) Dashboard Pegawai
12. **Gambar 4.1** File Installer Setup Aplikasi Desktop Windows (`.exe`)
13. **Gambar 4.2** Tampilan *Splash Screen* Pemuatan Server Lokal
14. **Gambar 4.3** Tampilan Halaman Login Administrator
15. **Gambar 4.4** Tampilan Pesan Validasi Kesalahan Login
16. **Gambar 4.5** Tampilan Dashboard Utama Administrator
17. **Gambar 4.6** Tampilan Tabel Daftar Seluruh Kegiatan Kedinasan
18. **Gambar 4.7** Tampilan Modal Formulir Tambah Kegiatan Baru
19. **Gambar 4.8** Tampilan Validasi Formulir Kegiatan
20. **Gambar 4.9** Tampilan Halaman Detail Kegiatan dan Dokumen Arsip
21. **Gambar 4.10** Tampilan Modal Unggah Dokumen Arsip
22. **Gambar 4.11** Tampilan Pratinjau (*Inline Preview*) Dokumen PDF
23. **Gambar 4.12** Tampilan Halaman Direktori Arsip Dokumen Administrator
24. **Gambar 4.13** Tampilan Dashboard Utama Pegawai (*Read-Only*)
25. **Gambar 4.14** Tampilan Hasil Pencarian Cepat Data Kegiatan
26. **Gambar 4.15** Tampilan Halaman Error 404 Kustom SIAPTIKA
27. **Gambar 4.16** Tampilan Pengelolaan Basis Data & Storage pada Dashboard Supabase Cloud

### Daftar Tabel
1. **Tabel 1.1** Matriks Perbandingan Sistem Berjalan dan Sistem Usulan
2. **Tabel 2.1** Rencana dan Jadwal Kerja Pengembangan Sistem Berdasarkan Sprint
3. **Tabel 3.1** Spesifikasi Lingkungan Perangkat Keras dan Perangkat Lunak
4. **Tabel 3.2** Deskripsi Aktor dalam Sistem SIAPTIKA
5. **Tabel 3.3** Definisi *Use Case* Sistem SIAPTIKA
6. **Tabel 3.4** Spesifikasi Skema Tabel `users`
7. **Tabel 3.5** Spesifikasi Skema Tabel `activities`
8. **Tabel 3.6** Spesifikasi Skema Tabel `documents`
9. **Tabel 4.1** Rincian Hasil Pengujian *Black Box Testing* Fungsional (37 Skenario Uji)
10. **Tabel 4.2** Matriks Evaluasi Keberhasilan Implementasi Kebutuhan Sistem
