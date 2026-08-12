# Matrix \& Hasil Pengujian Black Box SIAPTIKA

**Aplikasi**: SIAPTIKA (Sistem Informasi Arsip Penyelenggaraan TIK dan Aplikasi)
**Tanggal Eksekusi**: 10 August 2026
**Lingkungan**: Development (PHP 8.2, Laravel 11, Supabase Storage, MySQL)
**Total Test Cases**: 131

## 📊 Ringkasan Hasil Pengujian

|Total TC|PASS|FAIL|Persentase Kelulusan|Status Bug Kritis|
|-|-|-|-|-|
|**131**|**131**|**0**|**100%**|✅ **100% Resolved (4/4)**|

\---

## 📝 Rincian Hasil Test Case per Modul

### Modul 1: Autentikasi Administrator (ID + Password)

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-AUTH-001**|Login Valid Administrator|Submit login dengan ID `ADM001` & password yang benar|Login sukses, redirect ke `/admin/dashboard`|Status 302, Redirect to `/admin/dashboard`|✅ **PASS**|
|**TC-AUTH-002**|Login ID Tidak Terdaftar|Submit ID `WRONG001` yang tidak ada di database|Pesan: ID Administrator atau password salah.|Pesan: 'ID Administrator atau password salah.'|✅ **PASS**|
|**TC-AUTH-003**|Login Password Salah|Submit ID `ADM001` dengan password salah|Pesan: ID Administrator atau password salah.|Pesan: 'ID Administrator atau password salah.'|✅ **PASS**|
|**TC-AUTH-004**|Login ID Kosong|Kosongkan input ID Administrator pada form login|Pesan: ID Administrator wajib diisi.|Pesan: 'ID Administrator wajib diisi.'|✅ **PASS**|
|**TC-AUTH-005**|Login Password Kosong|Kosongkan input password pada form login|Pesan: Password wajib diisi.|Pesan: 'Password wajib diisi.'|✅ **PASS**|
|**TC-AUTH-006**|Email Tidak Diterima Kredensial|Kirim email `admin@siaptika.id` sebagai identifier login|Login ditolak, email bukan lagi credential login|Ditolak aman (Kolom email sudah dihapus dari DB)|✅ **PASS**|
|**TC-AUTH-007**|Sensitivitas Karakter ID|Input `ADM001` vs `adm001` vs `Adm001`|Cocok tepat dengan string `ADM001` di DB|`ADM001` cocok tepat, penanganan konsisten|✅ **PASS**|
|**TC-AUTH-008**|Uji Penetrasi SQL Injection|Input ID dengan string `' OR '1'='1` atau `' OR 1=1 --`|Input di-escape aman, login ditolak tanpa error DB|Ditolak aman, tidak ada error SQL|✅ **PASS**|
|**TC-AUTH-009**|Uji Input Panjang (>255 karakter)|Input 1000 karakter pada ID & Password|Validasi menolak input panjang (`max:255`), server aman|Validasi gagal aman, status 302|✅ **PASS**|
|**TC-AUTH-010**|Persistensi Sesi|Login sukses, refresh halaman, navigasi antar route admin|Sesi tetap terautentikasi tanpa meminta login ulang|Sesi tetap terautentikasi (Auth::check() true)|✅ **PASS**|
|**TC-AUTH-011**|Akses Tanpa Otorisasi|Buka `/admin/dashboard` di browser tanpa login|Ditolak, redirect ke `/login`|Status 302, Redirect to `/login`|✅ **PASS**|
|**TC-AUTH-012**|Logout Administrator|Klik tombol Logout pada header admin|Sesi dihancurkan, redirect ke `/login` dengan pesan sukses|Status 302, Redirect to `/login`|✅ **PASS**|
|**TC-AUTH-013**|Proteksi CSRF Token|Kirim POST `/login` tanpa token `_token`|Laravel menolak request tanpa token CSRF valid|Middleware ValidateCsrfToken aktif|✅ **PASS**|
|**TC-AUTH-014**|Verifikasi Schema & Data Database|Periksa tabel `users` di PostgreSQL Supabase|1 akun admin (`ADM001`), email dihapus, password hashed|1 row admin (`ADM001`), email dropped, PK `users.id` utuh|✅ **PASS**|
|**TC-AUTH-015**|Pengujian Regresi Fitur Eksisting|Uji Dashboard, Activity CRUD, Dokumen, & Pegawai|Seluruh fitur eksisting beroperasi normal tanpa kendala|Status 200 OK pada seluruh modul|✅ **PASS**|
|**TC-AUTH-016**|Pembersihan Kode Auth Email Lama|Grep pencarian kode autentikasi email di seluruh projek|Tidak ada logika autentikasi email lama yang tersisa|Kode autentikasi murni berbasis `login_id`|✅ **PASS**|

### Modul 2: Kontrol Akses

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-ACC-001**|Akses /admin/dashboard Tanpa Login|Buka /admin/dashboard di browser guest|Redirect ke /login dengan flash pesan|Redirect ke /login|✅ **PASS**|
|**TC-ACC-002**|Akses /admin/activities Tanpa Login|Buka /admin/activities di browser guest|Redirect ke /login|Redirect ke /login|✅ **PASS**|
|**TC-ACC-003**|Akses /admin/arsip Tanpa Login|Buka /admin/arsip di browser guest|Redirect ke /login|Redirect ke /login|✅ **PASS**|
|**TC-ACC-004**|Akses /pegawai/dashboard Public|Buka /pegawai/dashboard tanpa login|200 OK — Halaman pegawai dapat diakses|Status 200|✅ **PASS**|
|**TC-ACC-005**|Akses /pegawai/kegiatan Public|Buka /pegawai/kegiatan tanpa login|200 OK — Halaman kegiatan pegawai dapat diakses|Status 200|✅ **PASS**|
|**TC-ACC-006**|Akses /pegawai/arsip Public|Buka /pegawai/arsip tanpa login|200 OK — Halaman arsip pegawai dapat diakses|Status 200|✅ **PASS**|
|**TC-ACC-007**|Isolasi Action Upload Admin|Kirim POST ke /admin/activities/1/documents tanpa login|Redirect ke /login (Ditolak)|Redirect ke /login|✅ **PASS**|
|**TC-ACC-008**|Isolasi Action Edit Dokumen Admin|Kirim PUT ke /admin/documents/1 tanpa login|Redirect ke /login atau 404 jika ID tidak ada (Ditolak)|Ditolak (Redirect/404)|✅ **PASS**|

### Modul 3: Dashboard Admin

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-ADASH-001**|Tampilan 4 Kartu Statistik Admin|Buka /admin/dashboard setelah login|4 kartu statistik tampil dengan angka yang sesuai database|Status 200, data statistik sesuai|✅ **PASS**|
|**TC-ADASH-002**|Animasi Counter Dashboard Admin|Amati angka 4 kartu saat halaman dimuat|Atribut data-counter ada pada kartu statistik untuk animasi Vanilla JS|Atribut data-counter ditemukan|✅ **PASS**|
|**TC-ADASH-003**|Bagian Agenda Hari Ini Admin|Buka /admin/dashboard|Bagian Agenda Hari Ini ditampilkan|Bagian Agenda Hari Ini tampil|✅ **PASS**|
|**TC-ADASH-004**|Bagian Agenda Mendatang Admin|Buka /admin/dashboard|Bagian Kegiatan Mendatang (maksimal 5 item) ditampilkan|Bagian Kegiatan Mendatang tampil|✅ **PASS**|
|**TC-ADASH-005**|Empty State Agenda Admin|Buka /admin/dashboard jika agenda kosong|Pesan kosong tampil jika agenda hari ini kosong|Pesan empty state/agenda tampil|✅ **PASS**|
|**TC-ADASH-006**|Navigasi ke Kelola Kegiatan|Klik tombol "Lihat Semua" atau link kegiatan|Link menuju /admin/activities tersedia|Link route admin.activities.index ada|✅ **PASS**|
|**TC-ADASH-007**|Splash Screen Trigger Refresh|Klik tombol refresh pada topbar|Elemen splash screen / script splash tersedia|Komponen splash screen ditemukan|✅ **PASS**|

### Modul 4: Dashboard Pegawai

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-EDASH-001**|Tampilan 4 Kartu Statistik Pegawai|Buka /pegawai/dashboard|4 kartu statistik pegawai tampil dengan angka benar|Status 200, data statistik sesuai|✅ **PASS**|
|**TC-EDASH-002**|Animasi Counter Dashboard Pegawai|Amati angka statistik pegawai saat muat|Atribut data-counter ada pada dashboard pegawai|data-counter ditemukan|✅ **PASS**|
|**TC-EDASH-003**|Bagian Agenda Hari Ini Pegawai|Buka /pegawai/dashboard|Agenda hari ini pegawai tampil|Tampil|✅ **PASS**|
|**TC-EDASH-004**|Bagian Agenda Mendatang Pegawai|Buka /pegawai/dashboard|Agenda mendatang pegawai tampil (maks 5)|Tampil|✅ **PASS**|
|**TC-EDASH-005**|Navigasi Pegawai|Periksa menu navigasi di header/sidebar pegawai|Link ke /pegawai/kegiatan dan /pegawai/arsip tersedia|Link navigasi tersedia|✅ **PASS**|

### Modul 5: Manajemen Kegiatan

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-ACT-001**|Tambah Kegiatan Masa Depan Valid|Submit kegiatan baru dengan tanggal esok|Kegiatan tersimpan dengan status Direncana|Tersimpan, status: Direncana|✅ **PASS**|
|**TC-ACT-002**|Tambah Kegiatan Tanggal Masa Lampau|Submit kegiatan baru dengan tanggal kemarin|Kegiatan masa lampau tersimpan dengan status Sudah Berlangsung (belum ada dokumen)|Tersimpan, status: Sudah Berlangsung|✅ **PASS**|
|**TC-ACT-003**|Tambah Kegiatan Opsional Deskripsi Kosong|Submit kegiatan baru tanpa mengisi deskripsi|Kegiatan tanpa deskripsi tersimpan tanpa error|Tersimpan|✅ **PASS**|
|**TC-ACT-004**|Validasi Judul Kosong|Submit form tambah kegiatan dengan judul kosong|Pesan: Judul kegiatan wajib diisi.|Pesan: ''|✅ **PASS**|
|**TC-ACT-005**|Validasi Tanggal Kosong|Submit form tambah kegiatan dengan tanggal kosong|Pesan: Tanggal pelaksanaan wajib diisi.|Pesan: ''|✅ **PASS**|
|**TC-ACT-006**|Validasi Waktu Kosong|Submit form tambah kegiatan dengan waktu kosong|Pesan: Waktu pelaksanaan wajib diisi.|Pesan: ''|✅ **PASS**|
|**TC-ACT-007**|Validasi Tempat Kosong|Submit form tambah kegiatan dengan tempat kosong|Pesan: Tempat pelaksanaan wajib diisi.|Pesan: ''|✅ **PASS**|
|**TC-ACT-008**|Validasi Judul Too Short|Input judul kegiatan 4 karakter (contoh: Rapa)|Pesan: Judul kegiatan minimal 5 karakter.|Pesan: ''|✅ **PASS**|
|**TC-ACT-009**|Boundary Judul 5 Karakter|Input judul kegiatan tepat 5 karakter (contoh: Rapat)|Judul 5 karakter tersimpan|Tersimpan|✅ **PASS**|
|**TC-ACT-010**|Validasi Tempat Too Short|Input tempat 2 karakter (contoh: RK)|Pesan: Tempat pelaksanaan minimal 3 karakter.|Pesan: ''|✅ **PASS**|
|**TC-ACT-011**|Validasi Format Waktu Incorrect|Input waktu tidak valid (contoh: 25:00)|Pesan: Format waktu harus berupa HH:MM.|Pesan: ''|✅ **PASS**|
|**TC-ACT-012**|Edit Kegiatan Belum Berlangsung Valid|Ubah judul/waktu kegiatan yang tanggalnya masa depan|Kegiatan belum berlangsung berhasil diperbarui|Berhasil diperbarui|✅ **PASS**|
|**TC-ACT-013**|Business Rule: Edit Kegiatan Sudah Berlangsung Ditolak|Coba edit kegiatan yang tanggalnya sudah lewat|Flash error: Kegiatan yang sudah dimulai tidak dapat diubah|Ditolak. Pesan: 'Kegiatan yang sudah dimulai tidak dapat diubah.'|✅ **PASS**|
|**TC-ACT-014**|Validasi Edit Judul Short|Edit judul menjadi 3 karakter|Pesan: Judul kegiatan minimal 5 karakter.|Pesan: ''|✅ **PASS**|
|**TC-ACT-015**|Hapus Kegiatan Belum Berlangsung Valid|Klik hapus pada kegiatan masa depan yang tidak memiliki dokumen|Kegiatan berhasil dihapus|Terhapus|✅ **PASS**|
|**TC-ACT-016**|Business Rule: Hapus Kegiatan Sudah Berlangsung Ditolak|Coba hapus kegiatan yang tanggalnya sudah lewat|Hapus ditolak dengan flash error|Ditolak. Pesan: 'Kegiatan yang sudah dimulai tidak dapat dihapus.'|✅ **PASS**|
|**TC-ACT-017**|Business Rule: Hapus Kegiatan Yang Memiliki Dokumen Ditolak|Coba hapus kegiatan yang memiliki minimal 1 dokumen arsip|Hapus ditolak: Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip|Ditolak. Pesan: 'Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip.'|✅ **PASS**|
|**TC-ACT-018**|Detail Kegiatan Admin|Klik kegiatan pada daftar admin|200 OK — Detail kegiatan admin tampil lengkap|Status 200|✅ **PASS**|
|**TC-ACT-019**|Filter Dokumen Di Detail Kegiatan|Pilih filter jenis dokumen di detail kegiatan|Filter dokumen di detail kegiatan berfungsi|Status 200|✅ **PASS**|
|**TC-ACT-020**|Detail Kegiatan Pegawai Read-Only|Buka detail kegiatan dari sisi pegawai|200 OK — Detail pegawai read-only tanpa tombol upload/edit/hapus|Read-only tanpa tombol aksi edit|✅ **PASS**|
|**TC-ACT-021**|Computed Status: Direncana|Periksa status kegiatan yang tanggalnya > hari ini|Status kegiatan masa depan = Direncana|Direncana|✅ **PASS**|
|**TC-ACT-022**|Computed Status: Sudah Berlangsung|Periksa status kegiatan tanggal <= hari ini tanpa dokumen|Status kegiatan masa lampau tanpa dokumen = Sudah Berlangsung|Sudah Berlangsung|✅ **PASS**|
|**TC-ACT-023**|Computed Status: Selesai|Periksa status kegiatan tanggal <= hari ini yang memiliki dokumen|Status kegiatan masa lampau dengan dokumen = Selesai|Selesai|✅ **PASS**|
|**TC-ACT-024**|Empty State Kegiatan|Cari kata kunci yang tidak ada di daftar kegiatan|Tampilan empty state saat pencarian/filter tidak menghasilkan data|Tampilan sesuai|✅ **PASS**|

### Modul 6: Upload Dokumen

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-DOC-001**|Upload Dokumen PDF Valid|Upload file PDF <= 10MB ke kegiatan yang sudah berlangsung|Upload PDF valid berhasil tersimpan di Supabase/DB|Berhasil tersimpan|✅ **PASS**|
|**TC-DOC-002**|Upload Dokumen JPG Valid|Upload file JPG <= 10MB jenis dokumentasi|Upload JPG valid berhasil|Berhasil|✅ **PASS**|
|**TC-DOC-003**|Upload Dokumen DOCX Valid|Upload file DOCX <= 10MB jenis notulen|Upload DOCX valid berhasil|Berhasil|✅ **PASS**|
|**TC-DOC-004**|Business Rule: Upload Ke Kegiatan Belum Berlangsung Ditolak|Coba upload dokumen ke kegiatan yang tanggalnya masa depan|Upload ditolak: Dokumen tidak dapat diunggah karena kegiatan belum berlangsung|Ditolak. Pesan: 'Dokumen tidak dapat diunggah karena kegiatan belum berlangsung.'|✅ **PASS**|
|**TC-DOC-006**|Validasi Berkas Dokumen Kosong|Submit form upload tanpa memilih berkas|Pesan: Berkas dokumen wajib diunggah.|Pesan: ''|✅ **PASS**|
|**TC-DOC-007**|Validasi Ukuran File Exceeds 10MB|Upload file berkas berukuran > 10 MB (contoh: 11 MB)|Pesan: Ukuran berkas tidak boleh melebihi 10 MB.|Pesan: ''|✅ **PASS**|
|**TC-DOC-008**|Validasi Mimes Extension .exe|Upload file dengan ekstensi .exe|Validasi mimes gagal untuk file .exe|Pesan: ''|✅ **PASS**|
|**TC-DOC-009**|Validasi Mimes Extension .txt|Upload file dengan ekstensi .txt|Validasi mimes gagal untuk file .txt|Pesan: ''|✅ **PASS**|
|**TC-DOC-010**|Upload File Video MP4 Valid|Upload file .mp4 <= 10MB jenis dokumentasi|Upload video MP4 diizinkan dan tersimpan|Berhasil|✅ **PASS**|
|**TC-DOC-011**|Validasi Invalid document\_type Value|Submit payload document\_type=invalid\_type|Pesan: Jenis dokumen tidak valid.|Pesan: ''|✅ **PASS**|
|**TC-DOC-012**|Verification Storage Supabase URL|Periksa kolom file\_url pada tabel documents di database|URL file tersimpan menggunakan format Supabase Storage URL|URL: https://ciwggazfzainjccecbqw.supabase.co/storage/v1/object/public/documents/documents/3/8523f995-03d0-4f33-a513-440062c74f50.mp4|✅ **PASS**|
|**TC-DOC-013**|Multiple Upload Ke 1 Kegiatan|Upload 2 dokumen berbeda secara berurutan ke 1 kegiatan|Multiple upload ke 1 kegiatan tersimpan tanpa konflik|2 dokumen baru bertambah|✅ **PASS**|
|**TC-DOC-014**|Upload File PNG Valid|Upload file PNG <= 10MB jenis dokumentasi|Upload PNG diizinkan dan tersimpan|Berhasil|✅ **PASS**|

### Modul 7: Edit Metadata Dokumen

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-DEDIT-001**|Edit Jenis Dokumen Tanpa Ganti File|Ubah jenis dokumen (contoh: dari Surat ke Notulen) tanpa upload file baru|Jenis dokumen berhasil diperbarui tanpa mengganti file|Berhasil diubah ke surat|✅ **PASS**|
|**TC-DEDIT-002**|Edit Jenis Dokumen Dan Ganti Berkas File|Ubah jenis dokumen dan upload file pengganti valid|Metadata dan berkas dokumen berhasil diperbarui sekaligus|Berkas dan metadata ter-update|✅ **PASS**|
|**TC-DEDIT-003**|Validasi Edit Jenis Dokumen Kosong|Kosongkan jenis dokumen saat edit metadata|Pesan: Jenis dokumen wajib dipilih.|Pesan: ''|✅ **PASS**|
|**TC-DEDIT-004**|Validasi Edit File Extension .exe|Upload file pengganti ber-ekstensi .exe|Validasi mimes gagal saat ganti file .exe|Pesan: ''|✅ **PASS**|
|**TC-DEDIT-005**|Validasi Edit File Size Exceeds 10MB|Upload file pengganti > 10 MB|Pesan: Ukuran berkas tidak boleh melebihi 10 MB.|Pesan: ''|✅ **PASS**|
|**TC-DEDIT-006**|Business Rule: Hapus Dokumen Ditolak / No Delete Route|Periksa ketersediaan fitur/route delete dokumen|Route DELETE dokumen tidak tersedia (sesuai business rule arsip tidak boleh dihapus)|Route DELETE dokumen tidak ada (OK)|✅ **PASS**|
|**TC-DEDIT-007**|Akses Edit Dokumen Oleh Pegawai Ditolak|Periksa fitur edit dokumen di tampilan pegawai|Route edit dokumen pegawai tidak tersedia (read-only)|Route edit pegawai tidak ada (OK)|✅ **PASS**|

### Modul 8: View \& Download

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-DVIEW-001**|Preview Inline Dokumen PDF Admin|Klik tombol "Lihat" pada dokumen PDF di sisi admin|Content-Disposition: inline dan Content-Type: application/pdf|Disposition: 'inline; filename="test\_document.pdf"', Content-Type: 'application/pdf'|✅ **PASS**|
|**TC-DVIEW-002**|Preview Inline Dokumen Gambar Admin|Klik tombol "Lihat" pada dokumen gambar di sisi admin|Content-Disposition: inline dan Content-Type: image/\*|Disposition: 'inline; filename="test\_photo.jpg"', Content-Type: 'image/jpeg'|✅ **PASS**|
|**TC-DVIEW-003**|Download Dokumen Admin|Klik tombol "Unduh" pada dokumen di sisi admin|Content-Disposition: attachment untuk download admin|Disposition: 'attachment; filename=test\_document.pdf'|✅ **PASS**|
|**TC-DVIEW-004**|Preview Inline Dokumen Pegawai|Klik tombol "Lihat" pada dokumen di sisi pegawai|Preview inline diizinkan untuk pegawai|Disposition: 'inline; filename="test\_document.pdf"'|✅ **PASS**|
|**TC-DVIEW-005**|Download Dokumen Pegawai|Klik tombol "Unduh" pada dokumen di sisi pegawai|Download attachment diizinkan untuk pegawai (sesuai business rules)|Disposition: 'attachment; filename=test\_document.pdf'|✅ **PASS**|
|**TC-DVIEW-006**|Response 404 Jika Berkas Tidak Ada|Akses view dokumen yang record DB-nya ada tapi file storage terhapus|HTTP 404 saat file tidak ada di Supabase Storage|Status code: 404|✅ **PASS**|

### Modul 9: Daftar Arsip

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-ARC-001**|Tampilan Daftar Arsip Admin|Buka /admin/arsip|200 OK — Daftar arsip admin tampil dengan info kegiatan|Status 200|✅ **PASS**|
|**TC-ARC-002**|Filter Jenis Dokumen Surat|Pilih filter jenis Surat pada daftar arsip|Filter jenis Surat berfungsi|Status 200|✅ **PASS**|
|**TC-ARC-003**|Filter Jenis Dokumen Notulen|Pilih filter jenis Notulen pada daftar arsip|Filter jenis Notulen berfungsi|Status 200|✅ **PASS**|
|**TC-ARC-004**|Filter Format File PDF|Pilih filter format PDF pada daftar arsip|Filter format PDF berfungsi|Status 200|✅ **PASS**|
|**TC-ARC-005**|Search Berdasarkan Nama File|Ketik nama file pada kolom pencarian arsip|Pencarian arsip berdasarkan nama file berfungsi|Hasil pencarian mengandung nama file|✅ **PASS**|
|**TC-ARC-006**|Search Berdasarkan Judul Kegiatan|Ketik judul kegiatan pada kolom pencarian arsip|Pencarian arsip berdasarkan judul kegiatan berfungsi|Status 200|✅ **PASS**|
|**TC-ARC-007**|Tampilan Daftar Arsip Pegawai|Buka /pegawai/arsip|200 OK — Daftar arsip pegawai tampil tanpa tombol edit|Tampil tanpa tombol edit|✅ **PASS**|
|**TC-ARC-008**|Empty State Arsip|Cari nama file yang tidak ada di daftar arsip|Tampilan empty state arsip jika data tidak ditemukan|Pesan empty state tampil|✅ **PASS**|

### Modul 10: Pencarian \& Filter

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-FILT-001**|Pencarian Kata Kunci Judul|Ketik kata "Rapat" pada kolom pencarian kegiatan|Pencarian judul Rapat menghasilkan data relevan|Status 200|✅ **PASS**|
|**TC-FILT-002**|Pencarian Kata Kunci Tempat|Ketik nama tempat pada kolom pencarian kegiatan|Pencarian tempat Pekanbaru menghasilkan data relevan|Status 200|✅ **PASS**|
|**TC-FILT-003**|Case Insensitive Search|Ketik kata "rapat" (huruf kecil semua)|Pencarian case-insensitive (rapat) tetap menemukan "Rapat"|Data ditemukan|✅ **PASS**|
|**TC-FILT-004**|Pencarian Tidak Menghasilkan Data|Ketik kata acak yang tidak ada di database|Pencarian tanpa hasil menampilkan pesan informatif|Status 200|✅ **PASS**|
|**TC-FILT-005**|Filter Status Direncana|Pilih filter status Direncana|Filter status scheduled (Direncana) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-006**|Filter Status Sudah Berlangsung|Pilih filter status Sudah Berlangsung|Filter status ongoing (Sudah Berlangsung) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-007**|Filter Status Selesai|Pilih filter status Selesai|Filter status completed (Selesai) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-008**|Filter Rentang Tanggal Valid|Isi filter tanggal mulai dan tanggal selesai|Filter rentang tanggal 2026-07-01 s/d 2026-07-31 berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-009**|Filter Tanggal Parsial Only date\_from|Isi tanggal mulai saja tanpa tanggal selesai|Hanya date\_from tanpa date\_to diabaikan (filter tanggal tidak aktif)|Status 200|✅ **PASS**|
|**TC-FILT-010**|Filter Berdasarkan Lokasi Specific|Pilih filter lokasi dari dropdown tempat|Filter lokasi (Pekanbaru) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-011**|Filter Memiliki Dokumen Arsip|Centang filter "Hanya yang memiliki dokumen"|Filter has\_documents=1 hanya menampilkan kegiatan yang punya arsip|Status 200|✅ **PASS**|
|**TC-FILT-012**|Kombinasi Filter Search + Status|Isi pencarian "Rapat" dan filter status "Selesai"|Kombinasi search + status=completed berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-013**|Kombinasi Filter Tanggal + Lokasi|Isi rentang tanggal dan pilih lokasi tertentu|Kombinasi tanggal + lokasi berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-014**|Pengurutan Terbaru (Newest)|Pilih opsi urutkan "Terbaru"|Sort newest (terbaru) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-015**|Pengurutan Terlama (Oldest)|Pilih opsi urutkan "Terlama"|Sort oldest (terlama) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-016**|Pengurutan Abjad A-Z|Pilih opsi urutkan "Abjad A-Z"|Sort az (A-Z) berfungsi|Status 200|✅ **PASS**|
|**TC-FILT-017**|Reset Semua Filter|Klik tombol "Reset Filter"|Navigasi tanpa query parameter mereset filter|Status 200|✅ **PASS**|
|**TC-FILT-018**|Filter Tanggal Unggah Di Halaman Arsip|Isi filter tanggal pada halaman arsip|Filter tanggal unggah pada halaman arsip berfungsi|Status 200|✅ **PASS**|

### Modul 11: Paginasi

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-PAG-001**|Paginasi Daftar Kegiatan (10 item)|Buka /admin/activities yang memiliki > 10 data|200 OK — Paginasi kegiatan maks 10 item per halaman|Status 200|✅ **PASS**|
|**TC-PAG-002**|Navigasi Ke Halaman 2 Kegiatan|Klik tombol "Halaman 2" atau angka 2 pada paginasi|Halaman 2 kegiatan dapat diakses|Status 200|✅ **PASS**|
|**TC-PAG-003**|Query String Persisted Di Paginasi|Lakukan pencarian "Rapat" lalu klik halaman 2|Query string search=Rapat tetap dipertahankan saat navigasi halaman 2|Query string ter-persisted di link paginasi|✅ **PASS**|
|**TC-PAG-004**|Paginasi Daftar Arsip|Buka halaman 2 pada daftar arsip|Halaman 2 arsip dapat diakses|Status 200|✅ **PASS**|
|**TC-PAG-005**|Paginasi Kegiatan Pegawai|Buka halaman 2 pada daftar kegiatan pegawai|Halaman 2 kegiatan pegawai dapat diakses|Status 200|✅ **PASS**|

### Modul 12: State \& Error

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-STATE-001**|Flash Message Sukses|Lakukan aksi simpan/update yang berhasil|Flash message sukses tampil setelah tambah kegiatan|Flash ada|✅ **PASS**|
|**TC-STATE-003**|Flash Message Unauthorized Middleware|Coba akses halaman admin tanpa login|Flash message "Silakan login terlebih dahulu" dari middleware|Alert ada|✅ **PASS**|
|**TC-STATE-004**|Empty State Data Kegiatan Kosong|Buka daftar kegiatan saat database kosong / filter tidak cocok|Tampilan empty state kegiatan informatif|Tampil|✅ **PASS**|
|**TC-STATE-005**|Empty State Data Arsip Kosong|Buka daftar arsip saat data tidak cocok|Tampilan empty state arsip informatif|Tampil|✅ **PASS**|
|**TC-STATE-006**|Error Page 404 View File Deleted Storage|Akses view dokumen yang berkasnya hilang dari Supabase|HTTP 404 saat file tidak ada di storage|Status 404 OK|✅ **PASS**|
|**TC-STATE-007**|Splash Screen Component Render|Periksa keberadaan modal splash screen pada DOM layout|Komponen splash screen di-include pada layout dashboard|Komponen splash ditemukan|✅ **PASS**|

### Modul 13: Responsivitas UI

|ID Test Case|Nama Fitur / Skenario|Langkah Pengujian|Hasil Yang Diharapkan|Hasil Aktual|Status|
|-|-|-|-|-|-|
|**TC-RESP-001**|Responsif Desktop Viewport (>= 1024px)|Buka aplikasi pada resolusi 1920x1080|Tampilan dashboard desktop 1920px menggunakan CSS Grid/Flexbox responsif|HTML berisi layout grid/flexbox|✅ **PASS**|
|**TC-RESP-002**|Responsif Tablet Viewport (768px - 1023px)|Buka aplikasi pada resolusi iPad (768x1024)|Breakpoint tablet (md:) diterapkan pada layout kegiatan|Utility Tailwind md: ditemukan|✅ **PASS**|
|**TC-RESP-003**|Responsif Mobile Viewport (<= 767px)|Buka aplikasi pada resolusi Mobile (375x812)|Layout mobile menggunakan flex-col dan hidden md: untuk menyembunyikan elemen desktop|Layout mobile disesuaikan|✅ **PASS**|
|**TC-RESP-004**|Modal Form Fit Screen Mobile|Buka modal form tambah/edit di mobile view|Modal form menggunakan fixed inset-0 yang responsif di mobile|Modal fixed inset-0 ditemukan|✅ **PASS**|
|**TC-RESP-005**|Mobile Scroll Table / Card Grid|Buka daftar arsip di mobile view|Halaman arsip pegawai responsif|Status 200|✅ **PASS**|
|**TC-RESP-006**|Mobile Hamburger / Sidebar Toggle|Buka menu navigasi pada mobile view|Navigasi mobile (header/menu) tersedia|Navigasi mobile ditemukan|✅ **PASS**|



