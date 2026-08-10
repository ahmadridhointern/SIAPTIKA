# Black Box Testing — SIAPTIKA
## Sprint 5 — Stage 2

**Aplikasi:** SIAPTIKA (Sistem Informasi Administrasi Kegiatan Bidang APTIKA)
**Metode:** Black Box Testing
**URL Uji:** `http://localhost:8000`
**Tanggal Dibuat:** 2026-08-10
**Dibuat oleh:** _______________

---

## Konvensi Dokumen

### Kode Status
| Kode | Arti |
|------|------|
| `PASS` | Hasil aktual sesuai hasil yang diharapkan |
| `FAIL` | Hasil aktual tidak sesuai hasil yang diharapkan |
| `SKIP` | Test case dilewati (beri alasan di kolom Catatan) |
| `BLOCK` | Test case tidak dapat dijalankan karena hambatan |

### Singkatan
- **Admin** = Pengguna yang sudah login sebagai Administrator
- **Pegawai** = Pengguna tanpa login (akses publik `/pegawai/*`)
- **Tamu** = Pengguna belum login dan belum mengakses halaman manapun

---

## Persiapan Sebelum Pengujian

### Prasyarat Global
1. Server Laravel berjalan: `php artisan serve`
2. Koneksi internet aktif (untuk Supabase Storage)
3. Browser: Google Chrome versi terbaru (mode normal, bukan incognito)

### Data Uji yang Diperlukan

| Data | Ketentuan |
|------|-----------|
| Akun admin | Email dan password admin yang valid |
| Kegiatan masa depan | Minimal 1 kegiatan dengan tanggal setelah hari ini |
| Kegiatan masa lampau tanpa dokumen | Minimal 1 kegiatan sudah lewat, belum ada arsip |
| Kegiatan masa lampau dengan dokumen | Minimal 1 kegiatan sudah lewat, sudah ada arsip |
| Dokumen tersimpan | Minimal 1 dokumen valid di Supabase Storage |
| Berkas uji valid | File PDF kurang dari 10 MB dan file JPG kurang dari 10 MB |
| Berkas uji terlalu besar | File PDF lebih dari 10 MB |
| Berkas uji tipe tidak valid | File .exe, .zip, atau .txt |

---

## Modul 1 — Autentikasi (TC-AUTH)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-AUTH-001 | Login | Login berhasil dengan kredensial valid | Belum login. Berada di `/login` | Email valid; Password valid; Klik "Masuk" | Redirect ke `/admin/dashboard`. Halaman dashboard admin tampil | | | |
| TC-AUTH-002 | Login | Login gagal karena email tidak terdaftar | Belum login | Email `tidakada@email.com`; Password apapun; Klik "Masuk" | Halaman login tampil kembali. Pesan: "Email atau password salah." | | | |
| TC-AUTH-003 | Login | Login gagal karena password salah | Belum login | Email valid; Password salah; Klik "Masuk" | Halaman login tampil kembali. Pesan: "Email atau password salah." | | | |
| TC-AUTH-004 | Validasi login | Email kosong | Belum login | Email kosong; Password valid; Klik "Masuk" | Validasi gagal. Pesan: "Email wajib diisi." | | | |
| TC-AUTH-005 | Validasi login | Password kosong | Belum login | Email valid; Password kosong; Klik "Masuk" | Validasi gagal. Pesan: "Password wajib diisi." | | | |
| TC-AUTH-006 | Validasi login | Format email tidak valid | Belum login | Email `bukanemail`; Password valid; Klik "Masuk" | Validasi gagal. Pesan: "Format email tidak valid." | | | |
| TC-AUTH-007 | Validasi login | Password kurang dari 6 karakter | Belum login | Email valid; Password `abc` (3 karakter); Klik "Masuk" | Validasi gagal. Pesan: "Password minimal 6 karakter." | | | |
| TC-AUTH-008 | Redirect | Akses `/login` saat sudah login | Sudah login sebagai admin | Navigasi manual ke `/login` | Otomatis diarahkan ke `/admin/dashboard` | | | |
| TC-AUTH-009 | Logout | Logout berhasil | Sudah login sebagai admin | Klik tombol "Keluar" | Sesi dihapus. Redirect ke `/login`. Flash: "Anda berhasil keluar dari sistem." | | | |

---

## Modul 2 — Kontrol Akses (TC-ACC)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ACC-001 | Proteksi admin | Akses dashboard admin tanpa login | Belum login | Navigasi ke `/admin/dashboard` | Redirect ke `/login`. Flash: "Silakan login terlebih dahulu." | | | |
| TC-ACC-002 | Proteksi admin | Akses daftar kegiatan admin tanpa login | Belum login | Navigasi ke `/admin/activities` | Redirect ke `/login` | | | |
| TC-ACC-003 | Proteksi admin | Akses halaman arsip admin tanpa login | Belum login | Navigasi ke `/admin/arsip` | Redirect ke `/login` | | | |
| TC-ACC-004 | Akses publik | Dashboard pegawai dapat diakses tanpa login | Belum login | Navigasi ke `/pegawai/dashboard` | Halaman dashboard pegawai tampil tanpa perlu login | | | |
| TC-ACC-005 | Akses publik | Daftar kegiatan pegawai dapat diakses tanpa login | Belum login | Navigasi ke `/pegawai/kegiatan` | Daftar kegiatan tampil tanpa login | | | |
| TC-ACC-006 | Akses publik | Arsip pegawai dapat diakses tanpa login | Belum login | Navigasi ke `/pegawai/arsip` | Daftar arsip tampil tanpa login | | | |
| TC-ACC-007 | Isolasi akses | Upload dokumen tidak tersedia untuk non-admin | Tidak ada sesi admin | Navigasi langsung ke URL upload (POST `/admin/activities/1/documents`) | Redirect ke `/login` atau respons 403 | | | |
| TC-ACC-008 | Isolasi akses | Edit dokumen tidak tersedia untuk non-admin | Tidak ada sesi admin | Navigasi langsung ke URL edit dokumen (PUT `/admin/documents/1`) | Redirect ke `/login` atau respons 403 | | | |

---

## Modul 3 — Dashboard Admin (TC-ADASH)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ADASH-001 | Statistik | Empat kartu statistik tampil dengan angka benar | Login. Ada data kegiatan dan arsip | Buka `/admin/dashboard` | Kartu Total Kegiatan, Hari Ini, Mendatang, Total Arsip tampil. Angka sesuai data di database | | | Verifikasi angka dengan query manual |
| TC-ADASH-002 | Animasi counter | Angka counter dimulai dari 0 | Login. Kartu statistik > 0 | Buka `/admin/dashboard`. Perhatikan animasi kartu | Angka tiap kartu dimulai dari 0, kemudian naik secara animasi hingga nilai akhir | | | |
| TC-ADASH-003 | Agenda hari ini | Kegiatan hari ini tampil terurut waktu | Login. Ada kegiatan hari ini | Buka `/admin/dashboard` | Daftar kegiatan hari ini muncul, diurutkan dari waktu paling pagi | | | |
| TC-ADASH-004 | Agenda mendatang | Maks 5 kegiatan mendatang tampil | Login. Ada lebih dari 5 kegiatan mendatang | Buka `/admin/dashboard` | Hanya 5 kegiatan mendatang terdekat yang tampil | | | |
| TC-ADASH-005 | Empty state | Pesan saat tidak ada kegiatan hari ini | Login. Tidak ada kegiatan hari ini | Buka `/admin/dashboard` | Pesan kosong yang informatif tampil di bagian agenda hari ini | | | |
| TC-ADASH-006 | Navigasi | Link ke halaman kegiatan berfungsi | Login | Klik link atau tombol menuju daftar kegiatan dari dashboard | Diarahkan ke `/admin/activities` | | | |
| TC-ADASH-007 | Splash screen | Splash screen muncul setelah login | Belum login | Login dengan kredensial valid | Splash screen dengan progress bar tampil sebelum dashboard muncul | | | |

---

## Modul 4 — Dashboard Pegawai (TC-EDASH)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-EDASH-001 | Statistik | Empat kartu statistik tampil dengan data benar | Tidak perlu login | Buka `/pegawai/dashboard` | Kartu statistik tampil dengan angka sesuai database | | | |
| TC-EDASH-002 | Animasi counter | Counter dimulai dari 0 | Data kegiatan lebih dari 0 | Buka `/pegawai/dashboard`. Perhatikan animasi | Counter mulai dari 0, naik ke nilai akhir | | | |
| TC-EDASH-003 | Agenda hari ini | Kegiatan hari ini tampil | Ada kegiatan hari ini | Buka `/pegawai/dashboard` | Daftar agenda hari ini tampil terurut waktu | | | |
| TC-EDASH-004 | Agenda mendatang | Maks 5 kegiatan mendatang | Ada kegiatan mendatang | Buka `/pegawai/dashboard` | Maks 5 kegiatan terdekat tampil | | | |
| TC-EDASH-005 | Navigasi | Menu ke kegiatan dan arsip berfungsi | Tidak perlu login | Klik menu Jadwal Kegiatan dan Arsip Dokumen | Diarahkan ke `/pegawai/kegiatan` dan `/pegawai/arsip` | | | |

---

## Modul 5 — Manajemen Kegiatan CRUD (TC-ACT)

### 5A — Tambah Kegiatan

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ACT-001 | Tambah kegiatan | Data lengkap dan valid, tanggal masa depan | Login | Buka modal tambah; Isi semua field dengan data valid; Klik Simpan | Kegiatan tersimpan. Flash sukses tampil. Kegiatan muncul di daftar dengan status Direncana | | | |
| TC-ACT-002 | Tambah kegiatan | Tanggal masa lampau | Login | Isi form dengan tanggal kemarin; Field lain valid; Klik Simpan | Kegiatan tersimpan. Status otomatis Sudah Berlangsung (jika belum ada dokumen) | | | |
| TC-ACT-003 | Tambah kegiatan | Deskripsi dikosongkan (nullable) | Login | Isi semua field wajib; Kosongkan deskripsi; Klik Simpan | Kegiatan tersimpan tanpa error. Deskripsi tidak wajib | | | |
| TC-ACT-004 | Validasi wajib | Judul kosong | Login | Biarkan judul kosong; Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Judul kegiatan wajib diisi." | | | |
| TC-ACT-005 | Validasi wajib | Tanggal kosong | Login | Biarkan tanggal kosong; Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Tanggal pelaksanaan wajib diisi." | | | |
| TC-ACT-006 | Validasi wajib | Waktu kosong | Login | Biarkan waktu kosong; Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Waktu pelaksanaan wajib diisi." | | | |
| TC-ACT-007 | Validasi wajib | Tempat kosong | Login | Biarkan tempat kosong; Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Tempat pelaksanaan wajib diisi." | | | |
| TC-ACT-008 | Validasi panjang | Judul kurang dari 5 karakter | Login | Judul `Abcd` (4 karakter); Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Judul kegiatan minimal 5 karakter." | | | |
| TC-ACT-009 | Validasi panjang | Judul tepat 5 karakter (boundary valid) | Login | Judul `Abcde` (5 karakter); Isi field lain; Klik Simpan | Validasi berhasil. Kegiatan tersimpan | | | |
| TC-ACT-010 | Validasi panjang | Tempat kurang dari 3 karakter | Login | Tempat `AB` (2 karakter); Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Tempat pelaksanaan minimal 3 karakter." | | | |
| TC-ACT-011 | Validasi format | Format waktu salah | Login | Waktu `25:00` atau `8:0`; Isi field lain; Klik Simpan | Validasi gagal. Pesan: "Format waktu harus berupa HH:MM (contoh: 08:00)." | | | |

### 5B — Edit Kegiatan

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ACT-012 | Edit kegiatan | Edit kegiatan yang belum berlangsung | Login. Ada kegiatan masa depan | Klik edit kegiatan masa depan; Ubah judul; Simpan | Data diperbarui. Flash sukses tampil | | | |
| TC-ACT-013 | Business rule | Edit kegiatan yang sudah berlangsung ditolak | Login. Ada kegiatan masa lampau | Coba edit kegiatan masa lampau | Tombol edit tidak tersedia ATAU redirect dengan pesan: "Kegiatan yang sudah dimulai tidak dapat diubah." | | | |
| TC-ACT-014 | Validasi edit | Edit dengan judul terlalu pendek | Login. Ada kegiatan masa depan | Buka edit; Ubah judul ke `Abc` (3 karakter); Simpan | Validasi gagal. Pesan minimal 5 karakter | | | |

### 5C — Hapus Kegiatan

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ACT-015 | Hapus kegiatan | Hapus kegiatan belum berlangsung tanpa dokumen | Login. Ada kegiatan masa depan tanpa dokumen | Klik hapus; Konfirmasi | Kegiatan terhapus. Flash sukses. Tidak lagi tampil di daftar | | | |
| TC-ACT-016 | Business rule | Hapus kegiatan yang sudah berlangsung ditolak | Login. Ada kegiatan masa lampau | Coba hapus kegiatan masa lampau | Redirect dengan pesan: "Kegiatan yang sudah dimulai tidak dapat dihapus." | | | |
| TC-ACT-017 | Business rule | Hapus kegiatan yang punya dokumen ditolak | Login. Ada kegiatan dengan dokumen | Coba hapus kegiatan berdokumen | Redirect dengan pesan: "Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip." | | | |

### 5D — Detail dan Status Kegiatan

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ACT-018 | Detail admin | Detail kegiatan admin tampil lengkap | Login. Ada kegiatan dengan dokumen | Klik nama kegiatan | Tampil judul, tanggal, waktu, tempat, deskripsi, status, dan daftar dokumen | | | |
| TC-ACT-019 | Filter dokumen | Filter dokumen di detail berdasarkan jenis | Login. Ada kegiatan dengan beberapa jenis dokumen | Buka detail; Klik filter "Notulen" | Hanya dokumen jenis notulen yang tampil | | | |
| TC-ACT-020 | Detail pegawai | Detail kegiatan pegawai read-only | Tidak login | Navigasi ke `/pegawai/kegiatan/{id}` | Detail tampil tanpa tombol upload, edit, atau hapus | | | |
| TC-ACT-021 | Status Direncana | Badge status Direncana tampil | Login. Ada kegiatan masa depan | Buka daftar kegiatan | Badge Direncana tampil pada kegiatan masa depan | | | |
| TC-ACT-022 | Status Berlangsung | Badge status Sudah Berlangsung tampil | Login. Ada kegiatan masa lampau tanpa dokumen | Buka daftar kegiatan | Badge Sudah Berlangsung tampil | | | |
| TC-ACT-023 | Status Selesai | Badge status Selesai tampil | Login. Ada kegiatan masa lampau dengan dokumen | Buka daftar kegiatan | Badge Selesai tampil | | | |
| TC-ACT-024 | Empty state | Pesan saat tidak ada hasil kegiatan | Login | Terapkan filter yang tidak ada hasilnya | Pesan informatif tampil, bukan halaman kosong atau error | | | |

---

## Modul 6 — Upload Dokumen (TC-DOC)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-DOC-001 | Upload PDF | Upload dokumen PDF valid | Login. Ada kegiatan masa lampau | Di detail kegiatan: Pilih jenis Surat; Pilih file PDF kurang dari 10 MB; Klik Upload | Dokumen tersimpan di Supabase. Flash sukses. Dokumen muncul di daftar | | | |
| TC-DOC-002 | Upload JPG | Upload dokumen gambar JPG valid | Login. Ada kegiatan masa lampau | Pilih jenis Dokumentasi; Pilih file JPG; Klik Upload | Dokumen tersimpan dan tampil di daftar | | | |
| TC-DOC-003 | Upload DOCX | Upload dokumen Word valid | Login. Ada kegiatan masa lampau | Pilih jenis Notulen; Pilih file DOCX; Klik Upload | Dokumen tersimpan dan tampil di daftar | | | |
| TC-DOC-004 | Business rule | Upload ke kegiatan yang belum berlangsung ditolak | Login. Ada kegiatan masa depan | Coba upload dokumen ke kegiatan masa depan | Pesan error: "Dokumen tidak dapat diunggah karena kegiatan belum berlangsung." | | | |
| TC-DOC-005 | Validasi wajib | Upload tanpa memilih jenis dokumen | Login. Ada kegiatan masa lampau | Biarkan dropdown jenis kosong; Pilih file; Klik Upload | Validasi gagal. Pesan: "Jenis dokumen wajib dipilih." | | | |
| TC-DOC-006 | Validasi wajib | Upload tanpa memilih file | Login. Ada kegiatan masa lampau | Pilih jenis dokumen; Biarkan file kosong; Klik Upload | Validasi gagal. Pesan: "Berkas dokumen wajib diunggah." | | | |
| TC-DOC-007 | Validasi ukuran | Upload file lebih dari 10 MB | Login. Ada kegiatan masa lampau. Siapkan file lebih dari 10 MB | Pilih jenis; Pilih file lebih dari 10 MB; Klik Upload | Validasi gagal. Pesan: "Ukuran berkas tidak boleh melebihi 10 MB." | | | |
| TC-DOC-008 | Validasi tipe | Upload file .exe | Login. Ada kegiatan masa lampau | Pilih jenis; Pilih file .exe; Klik Upload | Validasi gagal. Pesan tipe file tidak valid | | | |
| TC-DOC-009 | Validasi tipe | Upload file .txt | Login. Ada kegiatan masa lampau | Pilih jenis; Pilih file .txt; Klik Upload | Validasi gagal. Pesan tipe file tidak valid | | | |
| TC-DOC-010 | Validasi tipe | Upload file MP4 video (diizinkan) | Login. Ada kegiatan masa lampau | Pilih jenis Dokumentasi; Pilih file .mp4 kurang dari 10 MB; Klik Upload | Dokumen tersimpan dan tampil di daftar | | | |
| TC-DOC-011 | Validasi jenis | Jenis dokumen di luar pilihan valid | Login | Manipulasi nilai POST dengan `document_type=invalid` | Validasi gagal. Pesan: "Jenis dokumen tidak valid." | | | |
| TC-DOC-012 | Supabase Storage | File benar-benar tersimpan di Supabase | Login. TC-DOC-001 sudah PASS | Buka Supabase dashboard lalu buka Storage bucket `documents` | File ditemukan di path `documents/{activity_id}/...` | | | |
| TC-DOC-013 | Upload multiple | Upload beberapa dokumen ke kegiatan yang sama | Login. Ada kegiatan masa lampau | Upload 3 file berbeda ke 1 kegiatan | Ketiga dokumen tampil di daftar. Tidak ada konflik | | | |
| TC-DOC-014 | Upload MP | Upload file PNG (diizinkan) | Login. Ada kegiatan masa lampau | Pilih jenis Dokumentasi; Pilih file .png; Klik Upload | Dokumen tersimpan | | | |

---

## Modul 7 — Edit dan Metadata Dokumen (TC-DEDIT)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-DEDIT-001 | Edit jenis | Ubah jenis dokumen tanpa ganti file | Login. Ada dokumen tersimpan | Buka form edit dokumen; Ubah jenis dari Surat ke Notulen; Simpan (tanpa upload file baru) | Jenis dokumen diperbarui. Flash sukses. File di Supabase tidak berubah | | | |
| TC-DEDIT-002 | Ganti file | Edit jenis dan ganti file sekaligus | Login. Ada dokumen tersimpan | Buka edit; Ubah jenis; Upload file baru; Simpan | Metadata dan file diperbarui. File lama terhapus dari Supabase | | | Verifikasi di Supabase |
| TC-DEDIT-003 | Validasi edit | Edit tanpa memilih jenis | Login. Ada dokumen | Kosongkan jenis; Simpan | Validasi gagal. Pesan: "Jenis dokumen wajib dipilih." | | | |
| TC-DEDIT-004 | Validasi edit | Ganti file dengan tipe tidak valid | Login. Ada dokumen | Buka edit; Upload file .exe; Simpan | Validasi gagal. Pesan tipe file tidak valid | | | |
| TC-DEDIT-005 | Validasi edit | Ganti file dengan ukuran lebih dari 10 MB | Login. Ada dokumen | Buka edit; Upload file lebih dari 10 MB; Simpan | Validasi gagal. Pesan ukuran melebihi batas | | | |
| TC-DEDIT-006 | Business rule | Dokumen tidak dapat dihapus | Login. Ada dokumen | Cari tombol hapus dokumen di seluruh UI | Tombol hapus dokumen tidak tersedia di manapun | | | |
| TC-DEDIT-007 | Business rule | Edit dokumen tidak tersedia untuk pegawai | Tidak login. Buka `/pegawai/arsip` | Cari tombol edit di halaman arsip pegawai | Tombol edit tidak ada. Hanya view dan download yang tersedia | | | |

---

## Modul 8 — View dan Download Dokumen (TC-DVIEW)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-DVIEW-001 | Preview admin | Preview PDF inline oleh admin | Login. Ada dokumen PDF | Klik tombol Lihat pada dokumen PDF | PDF terbuka di tab baru sebagai inline preview | | | |
| TC-DVIEW-002 | Preview admin | Preview JPG inline oleh admin | Login. Ada dokumen JPG | Klik tombol Lihat pada dokumen JPG | Gambar terbuka di tab baru (inline) | | | |
| TC-DVIEW-003 | Download admin | Download dokumen oleh admin | Login. Ada dokumen | Klik tombol Unduh | File terunduh ke komputer | | | |
| TC-DVIEW-004 | Preview pegawai | Preview dokumen oleh pegawai | Tidak login. Ada dokumen | Buka arsip pegawai; Klik Lihat | Dokumen terbuka di tab baru (inline) | | | |
| TC-DVIEW-005 | Download pegawai | Download dokumen oleh pegawai | Tidak login. Ada dokumen | Buka arsip pegawai; Klik Unduh | File terunduh. Pegawai diizinkan download sesuai business rules | | | |
| TC-DVIEW-006 | Error 404 | File tidak ada di Supabase Storage | Ada record di DB tapi file dihapus manual dari Storage | Akses URL view/download dokumen tersebut | Halaman error 404 dengan pesan: "Berkas dokumen tidak ditemukan di storage." | | | |

---

## Modul 9 — Daftar Arsip (TC-ARC)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-ARC-001 | Tampilan arsip admin | Semua arsip tampil dengan info kegiatan terkait | Login. Ada arsip | Buka `/admin/arsip` | Daftar dokumen tampil. Setiap baris menampilkan nama file, jenis, dan kegiatan terkait | | | |
| TC-ARC-002 | Filter jenis | Filter jenis Surat | Login. Ada arsip berbagai jenis | Pilih filter jenis Surat; Terapkan | Hanya dokumen jenis surat tampil | | | |
| TC-ARC-003 | Filter jenis | Filter jenis Notulen | Login. Ada arsip berbagai jenis | Pilih filter jenis Notulen; Terapkan | Hanya dokumen jenis notulen tampil | | | |
| TC-ARC-004 | Filter format | Filter format file PDF | Login. Ada arsip berbagai format | Pilih filter format PDF; Terapkan | Hanya file .pdf tampil | | | |
| TC-ARC-005 | Search arsip | Cari berdasarkan nama file | Login. Ada arsip | Ketik sebagian nama file | Dokumen yang namanya cocok tampil | | | |
| TC-ARC-006 | Search arsip | Cari berdasarkan judul kegiatan | Login. Ada arsip | Ketik sebagian judul kegiatan terkait | Dokumen yang kegiatannya cocok tampil | | | |
| TC-ARC-007 | Tampilan arsip pegawai | Arsip pegawai tampil tanpa tombol edit atau hapus | Tidak login | Buka `/pegawai/arsip` | Daftar arsip tampil. Tidak ada tombol edit atau hapus | | | |
| TC-ARC-008 | Empty state | Pesan kosong saat filter tidak ada hasil | Ada arsip. Gunakan filter yang tidak ada hasilnya | Buka arsip dengan filter tipe yang tidak cocok | Pesan informatif tampil, bukan halaman error | | | |

---

## Modul 10 — Pencarian dan Filter Kegiatan (TC-FILT)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-FILT-001 | Pencarian judul | Cari kegiatan berdasarkan judul | Login. Ada kegiatan | Ketik sebagian kata dari judul kegiatan | Kegiatan yang judulnya mengandung kata itu tampil | | | |
| TC-FILT-002 | Pencarian tempat | Cari kegiatan berdasarkan tempat | Login. Ada kegiatan | Ketik sebagian nama tempat kegiatan | Kegiatan yang tempatnya cocok tampil | | | |
| TC-FILT-003 | Case-insensitive | Pencarian tidak terpengaruh kapitalisasi | Login. Ada kegiatan "Rapat Koordinasi" | Ketik `rapat koordinasi` dengan huruf kecil | Kegiatan Rapat Koordinasi tetap tampil | | | |
| TC-FILT-004 | Tidak ada hasil | Pencarian tanpa hasil | Login. Ada kegiatan | Ketik `xyzxyz123` | Pesan tidak ada hasil tampil | | | |
| TC-FILT-005 | Filter Direncana | Filter status Direncana | Login. Ada kegiatan berbagai status | Pilih filter Direncana | Hanya kegiatan masa depan tampil | | | |
| TC-FILT-006 | Filter Berlangsung | Filter status Sudah Berlangsung | Login. Ada kegiatan masa lampau tanpa dokumen | Pilih filter Sudah Berlangsung | Hanya kegiatan sudah lewat tanpa arsip tampil | | | |
| TC-FILT-007 | Filter Selesai | Filter status Selesai | Login. Ada kegiatan dengan dokumen | Pilih filter Selesai | Hanya kegiatan sudah lewat dan punya arsip tampil | | | |
| TC-FILT-008 | Filter tanggal | Rentang tanggal valid | Login. Ada kegiatan | Isi date_from dan date_to; Terapkan | Hanya kegiatan dalam rentang tanggal itu tampil | | | |
| TC-FILT-009 | Filter tanggal | Hanya date_from tanpa date_to | Login | Isi date_from saja; Submit | Filter tanggal tidak aktif (keduanya wajib diisi) | | | |
| TC-FILT-010 | Filter lokasi | Filter berdasarkan lokasi | Login. Ada kegiatan berbagai lokasi | Pilih lokasi dari dropdown; Terapkan | Hanya kegiatan di lokasi itu tampil | | | |
| TC-FILT-011 | Filter dokumen | Filter punya dokumen | Login. Ada kegiatan dengan dan tanpa dokumen | Aktifkan filter Punya Dokumen | Hanya kegiatan yang punya arsip tampil | | | |
| TC-FILT-012 | Kombinasi filter | Pencarian dan filter status sekaligus | Login. Ada berbagai kegiatan | Ketik kata kunci + pilih status; Terapkan | Hanya kegiatan yang memenuhi keduanya tampil | | | |
| TC-FILT-013 | Kombinasi filter | Filter tanggal dan lokasi sekaligus | Login | Isi rentang tanggal + pilih lokasi; Terapkan | Hanya kegiatan sesuai kombinasi tampil | | | |
| TC-FILT-014 | Sort terbaru | Urutan default dari terbaru | Login. Ada kegiatan | Buka daftar tanpa sort khusus | Kegiatan diurutkan dari tanggal terbaru ke terlama | | | |
| TC-FILT-015 | Sort terlama | Urutkan dari terlama | Login. Ada kegiatan | Pilih sort Terlama | Kegiatan diurutkan dari tanggal terlama ke terbaru | | | |
| TC-FILT-016 | Sort A-Z | Urutkan A-Z berdasarkan judul | Login. Ada kegiatan | Pilih sort A-Z | Kegiatan diurutkan alfabetis berdasarkan judul | | | |
| TC-FILT-017 | Reset filter | Reset semua filter | Login. Filter aktif | Klik tombol Reset atau navigasi ke `/admin/activities` tanpa parameter | Semua kegiatan tampil. Filter bersih | | | |
| TC-FILT-018 | Filter arsip | Filter arsip berdasarkan tanggal unggah | Login. Ada arsip | Isi date_from dan date_to di halaman arsip | Hanya dokumen yang diunggah dalam rentang itu tampil | | | |

---

## Modul 11 — Paginasi (TC-PAG)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-PAG-001 | Paginasi kegiatan | 10 kegiatan per halaman | Login. Ada lebih dari 10 kegiatan | Buka `/admin/activities` | Maks 10 kegiatan per halaman. Kontrol paginasi tampil | | | |
| TC-PAG-002 | Navigasi halaman | Pindah ke halaman berikutnya | Login. Ada lebih dari 10 kegiatan | Klik halaman 2 di kontrol paginasi | Halaman 2 tampil dengan 10 kegiatan berikutnya | | | |
| TC-PAG-003 | Query string | Filter tetap aktif saat pindah halaman | Login. Ada filter aktif | Aktifkan search atau filter; Klik halaman 2 | URL mengandung parameter filter. Data di halaman 2 sudah terfilter | | | |
| TC-PAG-004 | Paginasi arsip | 10 arsip per halaman | Login. Ada lebih dari 10 arsip | Buka `/admin/arsip` | Maks 10 arsip per halaman | | | |
| TC-PAG-005 | Paginasi pegawai | Paginasi berfungsi di halaman kegiatan pegawai | Tidak login. Ada lebih dari 10 kegiatan | Buka `/pegawai/kegiatan` halaman 2 | Halaman 2 tampil dengan benar | | | |

---

## Modul 12 — State Kosong dan Error (TC-STATE)

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-STATE-001 | Flash sukses | Pesan sukses setelah tambah kegiatan | Login | Tambah kegiatan valid; Simpan | Flash message sukses tampil di atas halaman | | | |
| TC-STATE-002 | Flash error | Pesan error setelah aksi ditolak | Login. Ada kegiatan masa lampau | Coba hapus kegiatan masa lampau | Flash error: "Kegiatan yang sudah dimulai tidak dapat dihapus." | | | |
| TC-STATE-003 | Flash dari middleware | Flash dari redirect proteksi login | Belum login | Akses `/admin/dashboard` tanpa login | Flash: "Silakan login terlebih dahulu." muncul di halaman login | | | |
| TC-STATE-004 | Empty state kegiatan | Tampilan saat filter tidak ada hasil | Login | Terapkan filter yang tidak ada hasilnya | Tampilan kosong yang informatif, bukan blank atau error | | | |
| TC-STATE-005 | Empty state arsip | Tampilan saat tidak ada arsip | Tidak ada arsip atau filter tidak ada hasil | Buka halaman arsip dengan filter tidak cocok | Tampilan kosong yang informatif | | | |
| TC-STATE-006 | Error 404 storage | Akses file yang tidak ada di Storage | Ada record di DB tapi file dihapus manual dari Supabase | Akses URL view dokumen tersebut | HTTP 404 dengan pesan yang jelas | | | |
| TC-STATE-007 | Splash screen | Splash screen saat klik refresh dari dashboard | Login | Klik tombol refresh di dashboard admin | Splash screen tampil, kemudian halaman diperbarui | | | |

---

## Modul 13 — Responsivitas UI (TC-RESP)

> Gunakan DevTools Chrome (F12 lalu Toggle Device Toolbar) untuk mengubah ukuran viewport.

| TC ID | Fitur | Skenario | Prasyarat | Input / Aksi | Hasil yang Diharapkan | Hasil Aktual | Status | Catatan |
|-------|-------|----------|-----------|--------------|----------------------|--------------|--------|---------|
| TC-RESP-001 | Desktop | Tampilan dashboard di desktop 1920 piksel | Login | Set viewport 1920 piksel; Buka dashboard | Semua elemen tampil rapi. Tidak ada overflow horizontal | | | |
| TC-RESP-002 | Tablet | Tampilan daftar kegiatan di tablet 768 piksel | Login | Set viewport 768 piksel; Buka daftar kegiatan | Layout menyesuaikan. Navigasi dan konten tetap usable | | | |
| TC-RESP-003 | Mobile | Tampilan daftar kegiatan di mobile 375 piksel | Login | Set viewport 375 piksel; Buka daftar kegiatan | Layout satu kolom. Tidak ada scroll horizontal. Tombol dapat diklik | | | |
| TC-RESP-004 | Mobile form | Form tambah kegiatan di mobile | Login. Viewport 375 piksel | Buka modal tambah kegiatan; Isi dan kirim | Form dapat diisi dan dikirim dari mobile tanpa masalah | | | |
| TC-RESP-005 | Mobile arsip | Halaman arsip pegawai di mobile | Viewport 375 piksel | Buka `/pegawai/arsip` | Daftar arsip tampil dengan baik di mobile | | | |
| TC-RESP-006 | Mobile nav | Navigasi di mobile | Login. Viewport 375 piksel | Periksa menu navigasi | Navigasi mobile berfungsi dan dapat diakses | | | |

---

## Rekap Test Case

| Modul | Total TC | Positif | Negatif | Boundary |
|-------|----------|---------|---------|---------|
| TC-AUTH | 9 | 2 | 6 | 1 |
| TC-ACC | 8 | 3 | 5 | 0 |
| TC-ADASH | 7 | 5 | 0 | 2 |
| TC-EDASH | 5 | 5 | 0 | 0 |
| TC-ACT | 24 | 8 | 12 | 4 |
| TC-DOC | 14 | 4 | 9 | 1 |
| TC-DEDIT | 7 | 2 | 4 | 1 |
| TC-DVIEW | 6 | 4 | 2 | 0 |
| TC-ARC | 8 | 5 | 2 | 1 |
| TC-FILT | 18 | 12 | 4 | 2 |
| TC-PAG | 5 | 5 | 0 | 0 |
| TC-STATE | 7 | 2 | 4 | 1 |
| TC-RESP | 6 | 6 | 0 | 0 |
| **Total** | **124** | **63** | **48** | **13** |

---

## Rekap Hasil Pengujian

> Isi bagian ini setelah seluruh pengujian selesai.

| Tanggal Uji | Penguji | Total TC | PASS | FAIL | SKIP | BLOCK |
|-------------|---------|----------|------|------|------|-------|
| | | 124 | | | | |

### Daftar Bug Ditemukan

| Bug ID | TC Terkait | Deskripsi | Keparahan | Status |
|--------|-----------|-----------|-----------|--------|
| | | | | |

### Kesimpulan

> Tulis kesimpulan setelah semua TC dieksekusi.
