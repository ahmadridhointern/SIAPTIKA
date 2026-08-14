# Panduan Pengguna Aplikasi SIAPTIKA (User Manual)

**Sistem Informasi Administrasi Kegiatan Bidang APTIKA**  
*Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau*

---

## 1. Ringkasan Sistem

**SIAPTIKA** adalah aplikasi desktop Windows yang dirancang untuk mengelola, mencatat, dan mengarsipkan seluruh kegiatan serta dokumen kedinasan pada Bidang Layanan Aplikasi Informatika dan Informasi Publik (APTIKA) Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau.

Aplikasi ini hadir dalam **dua varian desktop**:
1. **SIAPTIKA Administrator**: Digunakan oleh Administrator / Pegawai Bagian Pengarsipan untuk mengelola data kegiatan (tambah, ubah, hapus) serta mengunggah dokumen arsip (surat, notulen, dokumentasi). Memerlukan autentikasi **ID Administrator + Password**.
2. **SIAPTIKA Dashboard**: Digunakan oleh seluruh Pegawai Bidang APTIKA untuk melihat jadwal kegiatan hari ini, kegiatan mendatang, detail kegiatan, dan direktori arsip dokumen secara *read-only* (tanpa login).

---

## 2. Kebutuhan Sistem (System Requirements)

- **Sistem Operasi**: Windows 10 atau Windows 11 (64-bit).
- **Prosesor**: Intel / AMD Dual-Core 1.6 GHz atau lebih tinggi.
- **Memori (RAM)**: Minimal 4 GB (Disarankan 8 GB).
- **Ruang Penyimpanan**: Minimal 500 MB ruang kosong pada hard disk.
- **Koneksi Internet**: Diperlukan untuk sinkronisasi basis data dan penyimpanan arsip cloud Supabase.
- **Dependensi Tambahan**: **TIDAK MEMERLUKAN** instalasi PHP, Node.js, Composer, atau XAMPP secara terpisah karena runtime telah terintegrasi di dalam aplikasi.

---

## 3. Instalasi Aplikasi (Installation)

1. Unduh atau salin berkas installer dari folder distribusi:
   - `SIAPTIKA Administrator Setup 1.0.0.exe` (untuk Administrator)
   - `SIAPTIKA Dashboard Setup 1.0.0.exe` (untuk Pegawai)
2. Klik dua kali pada berkas installer yang diinginkan.
3. Ikuti petunjuk instalasi pada wizard:
   - Pilih direktori instalasi (secara default pada `C:\Program Files\SIAPTIKA ...` atau direktori user).
   - Centang opsi untuk membuat jalan pintas (*desktop shortcut*).
4. Klik **Install** dan tunggu hingga proses selesai.
5. Ikon jalan pintas **SIAPTIKA Administrator** atau **SIAPTIKA Dashboard** akan muncul di Desktop dan Start Menu Windows.

---

## 4. Aplikasi SIAPTIKA Administrator

### Membuka Aplikasi
- Klik dua kali ikon **SIAPTIKA Administrator** pada Desktop.
- Splash screen animasi pemuatan server lokal akan muncul selama 2–3 detik.
- Jendela utama akan terbuka otomatis dan menampilkan **Halaman Login Administrator**.

---

## 5. Aplikasi SIAPTIKA Dashboard

### Membuka Aplikasi
- Klik dua kali ikon **SIAPTIKA Dashboard** pada Desktop.
- Jendela aplikasi akan terbuka langsung menuju **Dashboard Utama Pegawai** tanpa meminta login.
- Pegawai dapat langsung melihat ringkasan kegiatan dan mencari arsip dokumen.

---

## 6. Login Administrator

Untuk masuk ke aplikasi Administrator:
1. Pada form login, masukkan kredensial:
   - **ID Administrator**: `ADM001`
   - **Password**: *(masukkan kata sandi administrator)*
2. Klik tombol **Masuk**.
3. Jika kredensial benar, Anda akan diarahkan ke **Dashboard Administrator**.
4. Jika ID atau kata sandi salah, sistem akan menampilkan pesan peringatan berwarna merah.

> **Catatan Keamanan**: Sistem hanya memiliki 1 akun Administrator resmi (`ADM001`) yang terdaftar di basis data terpusat.

---

## 7. Manajemen Kegiatan (Administrator)

### A. Melihat Daftar Kegiatan
- Pilih menu **Kegiatan** pada bilah navigasi atas.
- Tabel akan menampilkan judul kegiatan, tanggal, waktu, lokasi, status kegiatan, dan jumlah dokumen arsip.

### B. Menambah Kegiatan Baru
1. Klik tombol **+ Tambah Kegiatan** pada pojok kanan atas.
2. Isi formulir modal yang muncul:
   - **Judul Kegiatan**: Nama agenda atau rapat dinas.
   - **Tanggal Kegiatan**: Tanggal pelaksanaan.
   - **Waktu Pelaksanaan**: Waktu mulai kegiatan.
   - **Lokasi / Tempat**: Ruang rapat atau lokasi kedinasan.
   - **Deskripsi**: Penjelasan rincian kegiatan.
3. Klik **Simpan Kegiatan**. Status kegiatan akan otomatis dihitung oleh sistem berdasarkan waktu (*Terjadwal*, *Sedang Berlangsung*, atau *Selesai*).

### C. Mengubah Data Kegiatan
1. Klik ikon **Ubah (Edit)** pada baris kegiatan yang ingin diperbarui.
2. Lakukan perubahan pada form modal.
3. Klik **Perbarui Kegiatan**.
> *Catatan*: Sesuai aturan kedinasan, kegiatan yang sudah dimulai/selesai tidak dapat diubah kembali.

### D. Menghapus Kegiatan
1. Klik ikon **Hapus (Delete)** pada kegiatan terkait.
2. Konfirmasi penghapusan.
> *Catatan*: Kegiatan yang telah memiliki dokumen arsip terlampir tidak dapat dihapus untuk mencegah hilangnya riwayat pengarsipan.

---

## 8. Manajemen Dokumen Arsip (Archive Management)

### A. Mengunggah Dokumen Arsip
1. Buka halaman **Detail Kegiatan** dengan mengklik judul kegiatan pada tabel.
2. Pada bagian **Dokumen Arsip**, klik tombol **+ Unggah Dokumen**.
3. Pilih **Jenis Dokumen**:
   - *Surat Undangan / Tugas*
   - *Notulen Rapat / Laporan*
   - *Dokumentasi Foto / Video Kegiatan*
4. Pilih berkas dari komputer (Format yang didukung: PDF, DOC, DOCX, JPG, PNG, MP4; Maksimal 10 MB).
5. Klik **Unggah Berkas**. Berkas akan tersimpan aman di Supabase Storage Cloud.

### B. Melihat (Pratinjau) Dokumen
- Klik tombol **Pratinjau (Lihat)** pada berkas yang diinginkan. Berkas PDF atau gambar akan ditampilkan langsung secara aman di jendela aplikasi.

### C. Mengunduh Dokumen
- Klik tombol **Unduh** untuk menyimpan salinan berkas ke folder Download komputer Anda.

---

## 9. Pencarian & Penyaringan (Search and Filtering)

- **Pencarian Kata Kunci**: Masukkan judul kegiatan atau lokasi pada kotak pencarian di bagian atas tabel untuk menyaring data seketika.
- **Penyaringan Status**: Gunakan dropdown status untuk memfilter kegiatan *Terjadwal*, *Sedang Berlangsung*, atau *Selesai*.
- **Pengurutan Data**: Urutkan data berdasarkan *Terbaru*, *Terlama*, atau abjad *A–Z*.
- **Paginasi**: Navigasi halaman 1, 2, 3 di bagian bawah tabel untuk melihat data historis sebelumnya.

---

## 10. Panduan Pemecahan Masalah (Troubleshooting)

| Gejala Masalah | Penyebab Umum | Solusi |
|:---|:---|:---|
| **Aplikasi tidak terbuka atau tertutup otomatis saat dibuka** | Port `8000` sedang dipakai oleh aplikasi lain atau proses sebelumnya masih menggantung. | Tutup aplikasi lain yang menggunakan port 8000 atau buka Task Manager dan akhiri proses `SIAPTIKA Administrator.exe` / `SIAPTIKA Dashboard.exe`, lalu buka kembali. |
| **Pesan "Gagal terhubung ke database" saat login atau simpan data** | Komputer tidak terhubung ke jaringan internet atau koneksi internet tidak stabil. | Pastikan komputer Anda terhubung ke internet (Wi-Fi/LAN) karena basis data Supabase tersimpan secara online. |
| **Gagal mengunggah dokumen arsip** | Ukuran berkas melebihi 10 MB atau format berkas tidak didukung. | Periksa ukuran berkas (maksimal 10 MB) dan pastikan format berupa PDF, DOC, DOCX, JPG, PNG, atau MP4. |
| **Login Administrator gagal terus menerus** | Salah memasukkan ID Administrator atau Password. | Pastikan ID Administrator adalah `ADM001` (huruf kapital) dan periksa tombol Caps Lock pada keyboard saat mengetikkan password. |

---

## 11. Ketergantungan Internet & Supabase Cloud

Aplikasi SIAPTIKA menggunakan arsitektur **Hybrid Desktop**:
- **Aplikasi & Server**: Berjalan secara lokal di komputer Windows tanpa memerlukan sewa server web publik.
- **Penyimpanan Terpusat**: Menggunakan **Supabase PostgreSQL** dan **Supabase Storage** agar seluruh data kegiatan dan berkas arsip yang diinput oleh Administrator dapat langsung dilihat secara *real-time* oleh seluruh pegawai melalui aplikasi Dashboard di komputer masing-masing.
- **Kebutuhan Jaringan**: Memerlukan koneksi internet aktif untuk pertukaran data dengan Supabase Cloud.

---

## 12. Batasan Sistem (Known Limitations)

1. **Akses Offline**: Dalam kondisi tanpa internet, aplikasi desktop tetap dapat diluncurkan secara lokal, namun pembacaan data kegiatan terbaru dan pengunggahan arsip memerlukan koneksi internet aktif.
2. **Kapasitas Unggah**: Maksimal ukuran satu berkas dokumen arsip adalah 10 MB per berkas.
3. **Akun Administrator Tunggal**: Sistem dirancang khusus untuk 1 akun Administrator pengarsipan Bidang APTIKA (`ADM001`) guna menjamin akuntabilitas pengarsipan.
