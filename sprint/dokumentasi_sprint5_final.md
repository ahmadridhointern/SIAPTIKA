# Dokumen Final Sprint 5: Testing, Hardening & Refinement SIAPTIKA

## 1. Tujuan Sprint 5 (Sprint Objective)
Sprint 5 bertujuan untuk melakukan pengujian menyeluruh (*Black Box Testing*), perbaikan *bug*, pengerasan validasi (*validation hardening*), penyempurnaan antarmuka (*UI/UX refinement*), serta pengujian regresi (*regression testing*) untuk memastikan aplikasi SIAPTIKA stabil, aman, andal, dan siap digunakan.

---

## 2. Cakupan Pengujian (Testing Scope)
1. **Autentikasi & Sesi**: Login admin, logout, proteksi route, pembatasan akses guest.
2. **Manajemen Kegiatan (Admin)**: Create, Read, Update, Delete kegiatan, validasi input, pencarian, filter status, filter rentang tanggal, lokasi, serta kalkulasi *computed status* (`Direncana`, `Sudah Berlangsung`, `Selesai`).
3. **Manajemen Dokumen & Arsip (Admin)**: Upload berkas ke Supabase Storage (Surat, Notulen, Dokumentasi), preview inline (tab baru), unduh berkas, ganti berkas, hapus dokumen, serta proteksi file >10MB dan ekstensi yang tidak diizinkan.
4. **Portal Pegawai (Publik)**: Dashboard pegawai, daftar kegiatan, detail kegiatan, daftar arsip, preview dan download dokumen (akses read-only tanpa login).
5. **Keamanan & Validasi**: Form Request validation, sanitasi input, whitelist format file, pencegahan kebocoran error internal (stack trace Supabase/DB), halaman error custom (404, 403, 500).
6. **Performa & UI/UX**: Loading state, splash screen, visual locking modal, responsivitas topbar, dan notifikasi toast.

---

## 3. Skenario & Hasil Black Box Testing
Pengujian dilakukan pada 124 Test Case yang mencakup seluruh modul aplikasi:

| Modul | Jumlah TC | PASS | FAIL | Persentase Lolos |
|:---|:---:|:---:|:---:|:---:|
| Modul Autentikasi (TC-AUTH) | 12 | 12 | 0 | 100% |
| Modul Kegiatan Admin (TC-ACT) | 48 | 48 | 0 | 100% |
| Modul Dokumen & Arsip Admin (TC-DOC) | 35 | 35 | 0 | 100% |
| Modul Portal Pegawai (TC-EMP) | 20 | 20 | 0 | 100% |
| Modul Keamanan & Error Handling (TC-SEC) | 9 | 9 | 0 | 100% |
| **TOTAL** | **124** | **124** | **0** | **100%** |

---

## 4. Daftar Bug & Perbaikannya (Bug List & Fixes)

| ID Bug | Deskripsi Bug | Solusi & Perbaikan | Status |
|:---|:---|:---|:---:|
| BUG-001 | Error `ERR_TOO_MANY_REDIRECTS` saat mengakses `/login` dari dashboard admin | Memperbaiki middleware `guest` dan `AdminMiddleware` agar tidak saling lempar redirect | **FIXED** |
| BUG-002 | Dokumen 15MB tidak menampilkan error di UI saat upload | Menambahkan handler `PostTooLargeException` di `bootstrap/app.php` & validasi front-end di bawah dropzone | **FIXED** |
| BUG-003 | Tombol hapus kegiatan yang sudah berjalan masih aktif | Mengunci tombol hapus di UI dan memblokir di controller jika `is_started` bernilai true | **FIXED** |
| BUG-004 | Counter dashboard kegiatan tidak mencerminkan status real-time | Menggunakan accessor `computed_status` pada kueri agregasi dashboard | **FIXED** |
| BUG-005 | Filter urutkan berdasarkan `created_at` belum tersedia | Menambahkan opsi pengurutan `created_newest` & `created_oldest` di Admin & Pegawai | **FIXED** |
| BUG-006 | Teks status pada modal kegiatan tidak berubah otomatis | Mengisi nilai `displayEl.value` untuk kegiatan mendatang dan menghubungkan event listener | **FIXED** |
| BUG-007 | Tombol Logout di topbar terkesan hilang | Menghapus efek `max-width: 0` CSS sehingga label "Keluar" selalu tampil permanen | **FIXED** |
| BUG-008 | Tombol "Kembali ke Beranda" di 404 mengarahkan ke dashboard pegawai | Menggunakan pengenal kontekstual `isAdminContext` di seluruh halaman error | **FIXED** |

---

## 5. Penyempurnaan Validasi & UI/UX (Validation & UI Improvements)
1. **Form Request Hardening**: `StoreActivityRequest` tidak lagi menerima field `status` dari user input (mencegah manipulasi manual).
2. **White-listing Input**: Filter format berkas (`file_format`) di-whitelist ke format yang valid (`pdf`, `jpg`, `png`, `word`, `mp4`).
3. **Pencegahan Data Exposure**: Mengganti pesan exception mentah Supabase/DB dengan pesan ramah pengguna.
4. **Error Pages Custom**: Menyiapkan template `404.blade.php`, `403.blade.php`, dan `500.blade.php` berstandar SIAPTIKA.
5. **Submit Protection**: Menjaga form submit native dari interupsi dengan menggunakan `pointerEvents = 'none'` dan overlay visual.

---

## 6. Hasil Pengujian Regresi (Regression Testing Results)
Seluruh 19 skenario pengujian regresi mencakup Auth, Dashboard, Activity CRUD, Document Upload/Download, Filter/Search, dan Authorization telah diuji ulang dan mendapatkan hasil **100% PASS**.

---

## 7. Batasan Sistem Yang Didefinisikan (Known Limitations)
- Ukuran maksimal berkas dokumen dibatasi hingga **10 MB** per file sesuai kebijakan server & Supabase Storage.
- Penghapusan & pengubahan kegiatan yang **sudah berlangsung** dibatasi untuk menjaga integritas data arsip.

---

## 8. Status Akhir & Rekomendasi
- **Status Akhir Sprint 5**: **LENGKAP & STABIL (COMPLETE & STABLE)**.
- **Rekomendasi Sprint Berikutnya (Sprint 6)**:
  1. Pelaksanaan *User Acceptance Testing* (UAT) bersama pemangku kepentingan / pengguna akhir.
  2. Persiapan deployment produksi & konfigurasi domain resmi.
