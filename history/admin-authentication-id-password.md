# Perubahan Autentikasi Administrator — ID + Password

## 1. Ringkasan Perubahan
Sistem autentikasi pengguna pada aplikasi SIAPTIKA mengalami perubahan arsitektur autentikasi khusus untuk akun Administrator. Metode autentikasi yang sebelumnya menggunakan **Email + Password** diubah secara permanen menjadi **ID Administrator (`login_id`) + Password**. 

Tujuan utama dari perubahan ini adalah menyesuaikan kebutuhan operasional instansi di mana akun Administrator sistem bersifat tunggal (*single administrator*) dan dikelola berdasarkan Identitas/ID Akses Khusus (`ADM001`) alih-alih alamat email pribadi/institusi. 

Cakupan perubahan meliputi skema tabel database `users`, model Eloquent `User`, validasi Form Request (`LoginRequest`), pengolahan kredensial pada `AuthController`, tampilan halaman login (`login.blade.php`), seeder database (`UserSeeder`), serta migrasi database PostgreSQL/Supabase.

---

## 2. Kondisi Sebelum Perubahan
Sebelum perubahan dilakukan, arsitektur autentikasi Administrator berjalan dengan kondisi sebagai berikut:
- **Kredensial Login**: Memerlukan kombinasi **Email** (contoh: `admin@siaptika.id`) dan **Password**.
- **Skema Database**: Tabel `users` memiliki kolom `email` dengan constraint `UNIQUE` yang digunakan sebagai identifier pencarian akun saat `Auth::attempt()`.
- **Laravel Components**:
  - `LoginRequest`: Memvalidasi aturan `'email' => ['required', 'string', 'email', 'max:255']`.
  - `AuthController`: Mengambil kredensial `$request->only('email', 'password')`.
  - `login.blade.php`: Menampilkan elemen label dan `<input type="email" name="email">`.
  - `UserSeeder`: Menyemaikan pengguna dengan field `'email' => 'admin@siaptika.id'`.
- **Sesi & Middleware**: Menggunakan Laravel web session guard (`Auth::check()`) dan `AdminMiddleware` untuk memproteksi endpoint `/admin/*`.

---

## 3. Kondisi Setelah Perubahan
Setelah perubahan diterapkan, arsitektur autentikasi Administrator menjadi berbasis **ID Administrator (`login_id`) + Password**:
- **Kredensial Login**: Memerlukan kombinasi **ID Administrator** (bernilai `ADM001`) dan **Password**.
- **Skema Database**: Kolom `email` telah dihapus sepenuhnya dari tabel `users`. Kolom baru `login_id` (string, `UNIQUE`, `NOT NULL` untuk admin) ditambahkan untuk identifikasi autentikasi.
- **Alur Autentikasi**:
  ```text
  Pengguna
    ↓ (Input ID Administrator "ADM001" + Password)
  Form Login (resources/views/auth/login.blade.php)
    ↓ (POST /login + CSRF Token)
  LoginRequest (Validasi login_id & password)
    ↓
  AuthController (Auth::attempt(['login_id' => 'ADM001', 'password' => ...]))
    ↓
  Database PostgreSQL (`users` table lookup login_id)
    ↓
  Bcrypt Password Verification (Hash::check)
    ↓
  Session Regeneration (Web Session Guard)
    ↓
  Administrator Dashboard (/admin/dashboard)
  ```

---

## 4. Desain Akun Administrator
Arsitektur akun Administrator pada SIAPTIKA dirancang sebagai **Single Administrator Account**:
- **ID Administrator**: `ADM001`
- **Nama Pengguna**: `Administrator APTIKA`
- **Password**: Tersimpan dalam bentuk hash terenkripsi (*secure hash*).
- **Aturan Akses**:
  - Hanya ada 1 (satu) akun Administrator dalam seluruh sistem.
  - Tidak ada fitur registrasi/pendaftaran Administrator baru.
  - Tidak ada antarmuka manajemen akun Administrator (*no multi-admin management*).
  - Kolom `login_id` memiliki constraint `UNIQUE` untuk mencegah duplikasi identifier.
  - Primary key database `users.id` (bigint/integer auto-increment) tetap dipertahankan sebagai identifier internal database.

---

## 5. Perubahan Database
Perubahan pada skema database `users`:

### Perbandingan Struktur Tabel `users`

**Sebelum Perubahan:**
```text
users
├── id (PK, bigint, auto-increment)
├── name (varchar)
├── email (varchar, unique)  <-- DIHAPUS
├── password (varchar)
├── remember_token (varchar, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

**Setelah Perubahan:**
```text
users
├── id (PK, bigint, auto-increment)
├── login_id (varchar, unique)  <-- DITAMBAHKAN
├── name (varchar)
├── password (varchar)
├── remember_token (varchar, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)
```

> **Catatan Penting**: Kolom `email` telah **dihapus sepenuhnya** dari skema tabel `users` di database PostgreSQL Supabase. Foreign key `activities.user_id` tetap merujuk ke `users.id` dan **tidak mengalami perubahan**.

---

## 6. Migration Changes
Dibuat berkas migrasi database baru:
- **File**: [database/migrations/2026_08_12_000000_add_login_id_and_drop_email_from_users_table.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/database/migrations/2026_08_12_000000_add_login_id_and_drop_email_from_users_table.php)
- **Tujuan**: Menambahkan kolom `login_id`, memperbarui record admin yang ada dengan `login_id = 'ADM001'`, dan menghapus kolom `email` dari tabel `users`.
- **Kompatibilitas**: Menggunakan perintah DDl Laravel Blueprint yang kompatibel dengan PostgreSQL Supabase (`Schema::table`).
- **Data Preservation**: Sebelum kolom `email` dihapus, migrasi memastikan kolom `login_id` pada record admin (ID 1) diisi terlebih dahulu dengan `'ADM001'` sehingga tidak terjadi kehilangan akses atau integritas data.

---

## 7. Laravel Code Changes

Berikut adalah berkas-berkas yang dimodifikasi beserta rincian perubahannya:

| Berkas | Perubahan yang Dilakukan | Alasan Perubahan |
|:---|:---|:---|
| [app/Models/User.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Models/User.php) | Menghapus `'email'` dan menambahkan `'login_id'` pada properti `$fillable`. | Mengizinkan mass-assignment untuk atribut `login_id` dan mencegah atribut `email` yang sudah tidak ada. |
| [app/Http/Requests/Auth/LoginRequest.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Requests/Auth/LoginRequest.php) | Mengubah aturan validasi dari `email` menjadi `login_id` (`['required', 'string', 'max:255']`) dan menyesuaikan pesan kesalahan Bahasa Indonesia. | Memvalidasi input ID Administrator saat form login dikirimkan. |
| [app/Http/Controllers/Auth/AuthController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Auth/AuthController.php) | Mengubah `$request->only('email', 'password')` menjadi `$request->only('login_id', 'password')` pada method `login()`. | Melakukan verifikasi kredensial autentikasi berbasis `login_id` via `Auth::attempt()`. |
| [resources/views/auth/login.blade.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/resources/views/auth/login.blade.php) | Mengubah label dari "Email" menjadi "ID Administrator", nama input `id`/`name` dari `email` menjadi `login_id`, dan placeholder dari `admin@siaptika.id` menjadi `ADM001`. | Menyesuaikan antarmuka halaman login bagi pengguna tanpa mengubah desain UI/UX. |
| [database/seeders/UserSeeder.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/database/seeders/UserSeeder.php) | Menggunakan `User::updateOrCreate` untuk menyemaikan akun Administrator tunggal dengan `login_id = 'ADM001'` dan `name = 'Administrator APTIKA'` tanpa field email. | Memastikan seeder menghasilkan 1 akun Administrator yang konsisten di database. |
| [business-rules.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/business-rules.md) | Memperbarui bagian Login untuk menjelaskan autentikasi Administrator berbasis ID Administrator (`login_id`) dan Password. | Menjaga keselarasan dokumentasi aturan bisnis proyek. |

---

## 8. Seeder / Administrator Account
Penyemaian (*seeding*) akun Administrator dilakukan melalui `UserSeeder.php`:
```php
User::updateOrCreate(
    ['id' => 1],
    [
        'login_id' => 'ADM001',
        'name'     => 'Administrator APTIKA',
        'password' => 'password123', // Otomatis di-hash oleh cast Eloquent ('password' => 'hashed')
    ]
);
```
- Menggunakan method `updateOrCreate(['id' => 1])` untuk menjamin tidak ada duplikasi akun Administrator.
- Password di-hash secara aman menggunakan algoritma default Laravel (`bcrypt`) melalui pemeta cast Eloquent.

---

## 9. Perubahan User Interface
Perubahan antarmuka dilakukan pada berkas `resources/views/auth/login.blade.php`:

**Sebelum Perubahan:**
- Label Input: `Email`
- Tipe Input: `type="email"`
- Nama Input: `name="email"`
- Placeholder: `admin@siaptika.id`

**Setelah Perubahan:**
- Label Input: `ID Administrator`
- Tipe Input: `type="text"`
- Nama Input: `name="login_id"`
- Placeholder: `ADM001`

Seluruh elemen visual, skema warna (Surat & Serif theme), komponen tombol, visual locking overlay, dan font (`Source Sans 3`) tetap dipertahankan 100% konsisten dengan sistem desain SIAPTIKA.

---

## 10. Keamanan (Security)
Keamanan sistem autentikasi baru tetap terjaga dengan standar keamanan berikut:
1. **Password Hashing**: Password disimpan menggunakan hashing `bcrypt` via Laravel `Hash` facade / Eloquent cast.
2. **Server-side Verification**: Verifikasi kredensial dilakukan 100% di sisi server.
3. **Session Security**: Memperbarui sesi (*session regeneration*) setelah login berhasil untuk mencegah *session fixation*.
4. **CSRF Protection**: Proteksi CSRF aktif di seluruh route POST (`ValidateCsrfToken`).
5. **SQL Injection Prevention**: Penggunaan Eloquent ORM dan prapemrosesan query (*parameter binding*) mencegah serangan SQL Injection.
6. **Route Protection**: Seluruh endpoint `/admin/*` dilindungi oleh `AdminMiddleware` (`admin.auth`).
7. **No Plaintext Secrets**: Tidak ada kredensial atau password plaintext yang tersimpan di kode frontend, views, maupun log.

---

## 11. Testing (Pengujian)
Telah dilaksanakan 16 Test Case *Black Box Testing* untuk memverifikasi autentikasi baru:

| ID Test Case | Skenario / Tujuan | Input Test | Hasil Diharapkan | Hasil Aktual | Status |
|:---|:---|:---|:---|:---|:---:|
| **TC-AUTH-001** | Valid Login | `login_id = ADM001`, password valid | Login sukses, redirect ke `/admin/dashboard` | Redirect 302 to `/admin/dashboard` | ✅ **PASS** |
| **TC-AUTH-002** | Incorrect ID | `login_id = WRONG001`, password valid | Login gagal, error ditampilkan | Ditolak aman, pesan error tampil | ✅ **PASS** |
| **TC-AUTH-003** | Incorrect Password | `login_id = ADM001`, password salah | Login gagal, user unauthenticated | Ditolak aman, Auth::check() false | ✅ **PASS** |
| **TC-AUTH-004** | Empty ID | `login_id = ""` | Validasi gagal (*"ID Administrator wajib diisi"*) | Validasi gagal aman | ✅ **PASS** |
| **TC-AUTH-005** | Empty Password | `password = ""` | Validasi gagal (*"Password wajib diisi"*) | Validasi gagal aman | ✅ **PASS** |
| **TC-AUTH-006** | Email Must Not Authenticate | `login_id = admin@siaptika.id` | Email tidak diterima sebagai login ID | Ditolak aman (Kolom email sudah dihapus) | ✅ **PASS** |
| **TC-AUTH-007** | ID Case Sensitivity | `ADM001` vs `adm001` vs `Adm001` | String `ADM001` cocok tepat di DB | Exact `ADM001` cocok tepat | ✅ **PASS** |
| **TC-AUTH-008** | SQL Injection Test | `' OR '1'='1`, `' OR 1=1 --` | Input di-escape aman, login ditolak | Ditolak aman, 0 SQL error | ✅ **PASS** |
| **TC-AUTH-009** | Long Input (>255 char) | 1000 karakter pada ID & Password | Validasi menolak input panjang (`max:255`) | Validasi gagal aman | ✅ **PASS** |
| **TC-AUTH-010** | Session Persistence | Refresh & navigasi admin | Sesi tetap terautentikasi | Sesi persisten | ✅ **PASS** |
| **TC-AUTH-011** | Unauthorized Access | GET `/admin/dashboard` tanpa login | Redirect ke `/login` (302) | Status 302, redirect to `/login` | ✅ **PASS** |
| **TC-AUTH-012** | Logout | Klik Logout | Sesi terhapus, redirect ke `/login` | Sesi terhapus | ✅ **PASS** |
| **TC-AUTH-013** | CSRF Protection | POST `/login` tanpa token | Request ditolak middleware CSRF | ValidateCsrfToken aktif | ✅ **PASS** |
| **TC-AUTH-014** | Database Verification | Kueri tabel `users` PostgreSQL | 1 row admin (`ADM001`), email dropped, PK `users.id` utuh | 1 row admin, email dropped, PK `users.id` utuh | ✅ **PASS** |
| **TC-AUTH-015** | Regression Test | Uji Kegiatan, Arsip, Pegawai | Seluruh fitur eksisting beroperasi normal | Status 200 OK | ✅ **PASS** |
| **TC-AUTH-016** | Code Cleanup Check | Grep kode autentikasi email lama | Tidak ada kode auth email lama tersisa | Kode auth murni berbasis `login_id` | ✅ **PASS** |

---

## 12. Regression Testing
Pengujian regresi mengonfirmasi bahwa perubahan autentikasi **TIDAK mengganggu** fungsi-fungsi sistem eksisting berikut:
- **Dashboard Administrator**: Berfungsi normal (HTTP 200 OK).
- **CRUD Kegiatan**: Pembuatan, pembacaan, pengubahan, dan penghapusan kegiatan berjalan normal.
- **Modul Arsip & Dokumen**: Pengunggahan berkas, pratinjau inline (tab baru), pengunduhan, dan penghapusan dokumen berjalan normal.
- **Supabase Storage Integration**: Integrasi penyimpanan berkas Supabase tetap stabil.
- **Dashboard & Halaman Pegawai**: Akses publik read-only tanpa login berjalan normal (HTTP 200 OK).
- **Search, Filter, & Pagination**: Fitur pencarian dan penyaringan data kegiatan/arsip berfungsi seperti sebelumnya.

---

## 13. Files Changed
Tabel ringkasan berkas yang dibuat dan dimodifikasi:

| Berkas | Status | Deskripsi |
|:---|:---:|:---|
| [database/migrations/2026_08_12_000000_add_login_id_and_drop_email_from_users_table.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/database/migrations/2026_08_12_000000_add_login_id_and_drop_email_from_users_table.php) | **Created** | Migrasi database untuk menambahkan `login_id` dan menghapus kolom `email` dari tabel `users`. |
| [app/Models/User.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Models/User.php) | **Modified** | Memperbarui `$fillable` (menambahkan `login_id`, menghapus `email`). |
| [app/Http/Requests/Auth/LoginRequest.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Requests/Auth/LoginRequest.php) | **Modified** | Memperbarui aturan validasi dan pesan kesalahan untuk `login_id`. |
| [app/Http/Controllers/Auth/AuthController.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/app/Http/Controllers/Auth/AuthController.php) | **Modified** | Memperbarui proses verifikasi `Auth::attempt()` menggunakan `login_id`. |
| [resources/views/auth/login.blade.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/resources/views/auth/login.blade.php) | **Modified** | Mengubah field form login dari Email menjadi ID Administrator (`login_id`). |
| [database/seeders/UserSeeder.php](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/database/seeders/UserSeeder.php) | **Modified** | Memperbarui seeder Administrator tunggal dengan `login_id = ADM001`. |
| [business-rules.md](file:///c:/Project/KERJA%20PRAKTEK/SIAPTIKA/business-rules.md) | **Modified** | Memperbarui dokumentasi aturan bisnis login Administrator. |

---

## 14. Database State After Change
Struktur akhir tabel database yang relevan:

```text
users
├── id (PK, bigint)
├── login_id (varchar, unique)
├── name (varchar)
├── password (varchar)
├── remember_token (varchar, nullable)
├── created_at (timestamp)
└── updated_at (timestamp)

activities
├── id (PK, bigint)
├── user_id (FK -> users.id)  <-- Tetap merujuk ke primary key users.id
├── title (varchar)
├── activity_date (date)
├── time (time)
├── location (varchar)
├── description (text)
├── created_at (timestamp)
└── updated_at (timestamp)
```

---

## 15. Before vs After
Perbandingan ringkas kondisi sebelum dan sesudah perubahan:

| Aspek | Sebelum Perubahan | Sesudah Perubahan |
|:---|:---|:---|
| **Identifier Login** | Email (`admin@siaptika.id`) | ID Administrator (`ADM001`) |
| **Kolom Database `email`** | Ada (`varchar`, unique) | **Dihapus Sepenuhnya** |
| **Kolom Database `login_id`** | Tidak Ada | **Ada (`varchar`, unique)** |
| **Penyimpanan Password** | Hashed (`bcrypt`) | Hashed (`bcrypt`) |
| **Jumlah Akun Administrator** | 1 | 1 |
| **Primary Key Tabel `users`** | `users.id` | `users.id` |
| **Relasi `activities.user_id`** | Merujuk ke `users.id` | Merujuk ke `users.id` |
| **Proteksi Route Admin** | `AdminMiddleware` | `AdminMiddleware` |

---

## 16. Impact on Existing System
Hal-hal yang secara sengaja **TIDAK DIUBAH** untuk menjaga stabilitas aplikasi:
- Fungsi CRUD Kegiatan tidak mengalami perubahan.
- Sistem Dokumen dan Arsip (Surat, Notulen, Dokumentasi) tidak mengalami perubahan.
- Integrasi penyimpanan Supabase Storage tidak mengalami perubahan.
- Halaman dan fitur Pegawai (read-only) tidak mengalami perubahan.
- Relasi antar tabel database (`activities.user_id -> users.id`) tidak mengalami perubahan.
- Middleware otorisasi dan kontrol akses tetap berfungsi sebagaimana mestinya.

---

## 17. Known Limitations
- Sistem hanya mendukung 1 (satu) akun Administrator tunggal.
- Tidak tersedia antarmuka/halaman untuk menambah atau mengelola akun Administrator baru (*no multi-admin management UI*).
- Pengubahan kredensial Administrator dilakukan melalui mekanisme database/seeder yang didukung proyek.

---

## 18. Status
**Status: Selesai**

Perubahan arsitektur autentikasi Administrator dari Email + Password menjadi **ID Administrator (`login_id = ADM001`) + Password** beserta penghapusan kolom email dari database telah selesai dilaksanakan, dimigrasikan, dan diverifikasi 100% lolos pengujian (*PASS*). Seluruh fitur eksisting aplikasi SIAPTIKA tetap beroperasi dengan stabil dan aman.
