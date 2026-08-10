# Daftar Bug & Defek Aplikasi SIAPTIKA (Hasil Stage 3 Black Box Testing)

**Dokumen**: Laporan Defek / Bug Black Box Testing Sprint 5 Stage 3
**Tanggal**: 10 August 2026
**Status Project**: Menunggu Perbaikan (Stage 4)

## 📌 Klasifikasi Bug Berdasarkan Tingkat Keparahan (Severity)

1. **Kritis (Critical)**: Pelanggaran *Business Rules* utama (misal: bisa menghapus/edit kegiatan yang sudah berlangsung, bisa upload dokumen ke kegiatan belum berlangsung).
2. **Tinggi (High)**: Kegagalan fungsi utama yang mempengaruhi alur kerja pengoperasian arsip atau validasi keamanan mimes file.
3. **Sedang (Medium)**: Ketidaksesuaian tampilan, query filter, atau pesan respons yang membingungkan pengembang/pengguna.
4. **Rendah (Low)**: Minor UI alignment atau konsistensi pesan teks pada session flash.

---

## Laporan Rincian Bug

### 🔴 [Kritis] BUG-001: Business Rule Hapus Kegiatan Sudah Berlangsung
- **ID Test Case**: [TC-ACT-016](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-ACT-016)
- **Aturan Bisnis Terkait**: `BR-ACT-005: Kegiatan yang sudah dimulai/berlangsung tidak dapat dihapus.`
- **Langkah Reproduksi**:
  1. Login sebagai Admin.
  2. Pilih kegiatan yang tanggal pelaksanaannya sudah lewat (misal: kemarin).
  3. Klik tombol "Hapus" dan konfirmasi.
- **Hasil Yang Diharapkan**: Aksi hapus ditolak oleh sistem dengan flash message error: "Kegiatan yang sudah dimulai tidak dapat dihapus".
- **Hasil Aktual**: Kegiatan yang sudah berlangsung berhasil dihapus dari database. `ActivityController::destroy` hanya memeriksa `$activity->status === "completed"` tetapi tidak mengecek `computed_status` atau `activity_date <= today`.
- **Status**: 🔓 Open (Perlu diperbaiki di Stage 4)

### 🔴 [Kritis] BUG-002: Business Rule Hapus Kegiatan Berdokumen
- **ID Test Case**: [TC-ACT-017](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-ACT-017)
- **Aturan Bisnis Terkait**: `BR-ACT-006: Kegiatan yang memiliki dokumen arsip tidak dapat dihapus.`
- **Langkah Reproduksi**:
  1. Login sebagai Admin.
  2. Pilih kegiatan yang telah memiliki minimal 1 dokumen arsip.
  3. Klik tombol "Hapus" dan konfirmasi.
- **Hasil Yang Diharapkan**: Aksi hapus ditolak oleh sistem dengan flash message error: "Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip".
- **Hasil Aktual**: Kegiatan dengan dokumen terhapus (atau tidak divalidasi `documents()->count() > 0` sebelum pemanggilan destroy).
- **Status**: 🔓 Open (Perlu diperbaiki di Stage 4)

### 🔴 [Kritis] BUG-003: Business Rule Upload Dokumen Ke Kegiatan Belum Berlangsung
- **ID Test Case**: [TC-DOC-004](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-DOC-004)
- **Aturan Bisnis Terkait**: `BR-DOC-002: Dokumen hanya dapat diunggah jika kegiatan sudah berlangsung.`
- **Langkah Reproduksi**:
  1. Login sebagai Admin.
  2. Buka detail kegiatan yang tanggal pelaksanaannya di masa depan (status: Direncana).
  3. Unggah file dokumen baru.
- **Hasil Yang Diharapkan**: Upload ditolak oleh sistem dengan pesan error: "Dokumen tidak dapat diunggah karena kegiatan belum berlangsung".
- **Hasil Aktual**: Dokumen berhasil diunggah ke kegiatan masa depan. `DocumentController::store` tidak memeriksa tanggal pelaksanaan kegiatan atau status `Direncana`.
- **Status**: 🔓 Open (Perlu diperbaiki di Stage 4)

### 🔴 [Kritis] BUG-004: Business Rule Edit Kegiatan Sudah Berlangsung
- **ID Test Case**: [TC-ACT-013](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-ACT-013)
- **Aturan Bisnis Terkait**: `BR-ACT-004: Kegiatan yang sudah dimulai/berlangsung tidak dapat diubah.`
- **Langkah Reproduksi**:
  1. Login sebagai Admin.
  2. Buka form edit untuk kegiatan yang sudah berlangsung (tanggal lewat).
  3. Ubah judul atau lokasi lalu submit.
- **Hasil Yang Diharapkan**: Perubahan ditolak dengan pesan error: "Kegiatan yang sudah dimulai tidak dapat diubah".
- **Hasil Aktual**: Sistem memperbolehkan pengeditan data kegiatan yang sudah berlangsung jika status DB belum secara manual di-set ke `completed`.
- **Status**: 🔓 Open (Perlu diperbaiki di Stage 4)

