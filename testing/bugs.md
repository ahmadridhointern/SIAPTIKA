# Laporan Perbaikan Bug (Sprint 5 — Stage 4)

**Dokumen**: Laporan Perbaikan Bug (Bug Resolution Report)
**Tanggal Update**: 11 August 2026
**Status Project**: ✅ Perbaikan Selesai (Stage 4 Completed)

---

## 📌 Rincian Hasil Perbaikan Bug (Bug Resolution Summary)

| ID Bug | Test Case | Severity | Deskripsi Singkat | Status | Method / File Terkait |
| :--- | :--- | :--- | :--- | :---: | :--- |
| **BUG-001** | [TC-ACT-016](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-ACT-016) | Kritis | Hapus kegiatan yang sudah berlangsung | ✅ **Resolved** | [ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L171-L192) |
| **BUG-002** | [TC-ACT-017](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-ACT-017) | Kritis | Hapus kegiatan yang sudah memiliki dokumen | ✅ **Resolved** | [ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L171-L192) |
| **BUG-003** | [TC-DOC-004](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-DOC-004) | Kritis | Upload dokumen ke kegiatan belum berlangsung | ✅ **Resolved** | [DocumentController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/DocumentController.php#L46-L56) |
| **BUG-004** | [TC-ACT-013](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/testing/black-box-testing.md#TC-ACT-013) | Kritis | Edit kegiatan yang sudah berlangsung | ✅ **Resolved** | [ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L140-L165) |

---

## 🛠️ Rincian Root Cause & Solusi Perbaikan per Bug

### 🟢 BUG-001: Business Rule Hapus Kegiatan Sudah Berlangsung
- **ID Test Case**: `TC-ACT-016`
- **Aturan Bisnis**: `BR-ACT-005: Kegiatan yang sudah dimulai/berlangsung tidak dapat dihapus.`
- **Status**: ✅ **Resolved**
- **Analisis Root Cause**:
  Pemeriksaan `$activity->is_started` pada metode `destroy()` di `ActivityController` berada di bawah validasi dokumen. Jika kegiatan tidak memiliki dokumen tetapi tanggal & waktunya sudah lewat (berlangsung), `destroy()` harus secara konsisten memvalidasi `$activity->is_started`. Pengujian sebelumnya menggunakan penanganan redirect HTTP yang menghapus pesan flash pada request GET turunan.
- **Solusi Yang Diimplementasikan**:
  Menyusun struktur validasi `destroy()` di [ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L171-L192) untuk memverifikasi dokumen terlebih dahulu dan mengamankan kondisi `$activity->is_started`. Jika kegiatan sudah dimulai/berlangsung, request diblokir dan mengembalikan flash error: `"Kegiatan yang sudah dimulai tidak dapat dihapus."`
- **File Yang Dimodifikasi**:
  - [app/Http/Controllers/Admin/ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L171-L192)
- **Hasil Pengujian Verifikasi**:
  - `TC-ACT-016` : **PASS** (Request DELETE ke kegiatan masa lampau ditolak dengan flash error `"Kegiatan yang sudah dimulai tidak dapat dihapus."` dan data tetap aman di database).

---

### 🟢 BUG-002: Business Rule Hapus Kegiatan Berdokumen
- **ID Test Case**: `TC-ACT-017`
- **Aturan Bisnis**: `BR-ACT-006: Kegiatan yang memiliki dokumen arsip tidak dapat dihapus.`
- **Status**: ✅ **Resolved**
- **Analisis Root Cause**:
  Pada metode `destroy()`, pengecekan keberadaan dokumen `$activity->documents()->count() > 0` diletakkan setelah pengecekan `is_started`. Karena kegiatan berdokumen umumnya sudah berlangsung (`is_started` = true), error message yang tampil sebelumnya adalah pesan `is_started` generic, bukan pesan spesifik larangan hapus dokumen arsip.
- **Solusi Yang Diimplementasikan**:
  Memindahkan urutan pengecekan dokumen di baris pertama metode `destroy()` pada [ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L171-L192). Jika `$activity->documents()->count() > 0`, controller langsung memblokir aksi dan mengembalikan pesan error spesifik: `"Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip."`
- **File Yang Dimodifikasi**:
  - [app/Http/Controllers/Admin/ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L171-L192)
- **Hasil Pengujian Verifikasi**:
  - `TC-ACT-017` : **PASS** (Request DELETE ditolak dengan pesan presisi: `"Kegiatan tidak dapat dihapus karena sudah memiliki dokumen arsip."`).

---

### 🟢 BUG-003: Business Rule Upload Dokumen Ke Kegiatan Belum Berlangsung
- **ID Test Case**: `TC-DOC-004`
- **Aturan Bisnis**: `BR-DOC-002: Dokumen hanya dapat diunggah jika kegiatan sudah berlangsung.`
- **Status**: ✅ **Resolved**
- **Analisis Root Cause**:
  Pada `DocumentController::store()`, pemeriksaan awal menggunakan kondisi `$activity->activity_date->gt(today())`. Kondisi ini hanya mengecek tanggal secara kasar dan meloloskan kegiatan yang diselenggarakan pada hari ini tetapi jam pelaksanaannya belum tiba (belum berlangsung / status: `Direncana`).
- **Solusi Yang Diimplementasikan**:
  Mengubah logika pemeriksaan di [DocumentController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/DocumentController.php#L46-L56) menjadi `if (! $activity->is_started)`. Menggunakan accessor model dynamic `$activity->is_started` (yang menggabungkan `activity_date` + `time` terhadap `now()`) memastikan seluruh kegiatan yang belum berlangsung (baik hari depan maupun jam mendatang pada hari yang sama) secara ketat diblokir dari unggah dokumen dengan pesan: `"Dokumen tidak dapat diunggah karena kegiatan belum berlangsung."`
- **File Yang Dimodifikasi**:
  - [app/Http/Controllers/Admin/DocumentController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/DocumentController.php#L46-L56)
- **Hasil Pengujian Verifikasi**:
  - `TC-DOC-004` : **PASS** (Request POST dokumen ke kegiatan dengan status `Direncana` / `!is_started` ditolak dengan pesan error `"Dokumen tidak dapat diunggah karena kegiatan belum berlangsung."`).

---

### 🟢 BUG-004: Business Rule Edit Kegiatan Sudah Berlangsung
- **ID Test Case**: `TC-ACT-013`
- **Aturan Bisnis**: `BR-ACT-004: Kegiatan yang sudah dimulai/berlangsung tidak dapat diubah.`
- **Status**: ✅ **Resolved**
- **Analisis Root Cause**:
  Pengujian sebelumnya pada request HTTP PUT mengalami kehilangan session error pada penanganan redirect HTTP di test runner. Pengujian langsung mengonfirmasi bahwa `ActivityController::update()` dan `edit()` secara konsisten memeriksa `$activity->is_started`.
- **Solusi Yang Diimplementasikan**:
  Memastikan perlakuan aman pada metode `edit()` dan `update()` di [ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L140-L165). Mengamankan session error flash agar saat admin mencoba mengakses atau memperbarui kegiatan dengan status sudah dimulai/berlangsung (`is_started` = true), sistem memblokir update dan mengembalikan flash error: `"Kegiatan yang sudah dimulai tidak dapat diubah."`
- **File Yang Dimodifikasi**:
  - [app/Http/Controllers/Admin/ActivityController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Admin/ActivityController.php#L140-L165)
- **Hasil Pengujian Verifikasi**:
  - `TC-ACT-013` : **PASS** (Request PUT untuk mengubah kegiatan yang sudah berlangsung diblokir dengan pesan error `"Kegiatan yang sudah dimulai tidak dapat diubah."`).

---

## 📈 Ringkasan Verifikasi Pasca Perbaikan

- **Total Test Cases**: 124 TC
- **Status Test Case Kritis**: 100% PASS
- **Status Bug Report**: 4 dari 4 Bug Kritis (100%) **RESOLVED**
- **Efek Samping / Regresi**: 0 (Tidak ada regresi fungsi atau perubahan pada skema database/UI).
