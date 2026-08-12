# Business Rules

## Login

Hanya Admin yang login (menggunakan ID Administrator `login_id` dan Password).

Pegawai tidak login.

---

## Kegiatan

Setiap kegiatan memiliki:

- Judul
- Tanggal
- Waktu
- Tempat
- Deskripsi

Tidak ada:

- Waktu Mulai
- Waktu Selesai

Hanya satu field waktu.

---

## Arsip

Jenis arsip hanya:

- Dokumen / Surat
- Notulen
- Dokumentasi

---

## Penghapusan

Kegiatan yang sudah lewat tidak boleh dihapus.

Kegiatan yang belum berlangsung boleh diubah dan dihapus.

Arsip tidak boleh dihapus.

---

## Pegawai

Pegawai dapat:

Melihat dashboard

Melihat jadwal

Melihat arsip

Mengunduh dokumen arsip

Pegawai tidak dapat:

Login

Upload

Edit

Delete

---

## Status Kegiatan

Status kegiatan ditentukan otomatis oleh sistem berdasarkan tanggal, waktu, dan ketersediaan dokumen arsip:

- Direncana: kegiatan belum berlangsung (tanggal + waktu belum lewat)
- Sudah Berlangsung: kegiatan sudah lewat tetapi belum ada dokumen arsip
- Selesai: kegiatan sudah lewat dan sudah memiliki dokumen arsip

Admin tidak dapat mengubah status secara manual.

---

## Tanggal Kegiatan

Admin dapat membuat kegiatan dengan tanggal di masa depan maupun masa lampau.

Tidak ada batasan tanggal minimum atau maksimum.

---

## Dashboard Admin

Menampilkan:

Total Kegiatan

Hari Ini

Mendatang

Total Arsip

Aktivitas Terbaru