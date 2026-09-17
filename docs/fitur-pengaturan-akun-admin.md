# Fitur: Pengaturan Akun Administrator (Ganti ID & Password)

## Deskripsi

Fitur ini memungkinkan Administrator untuk mengubah **Login ID** atau **Password** akun melalui antarmuka modal di dalam aplikasi, tanpa harus mengakses database secara langsung.

---

## File yang Terlibat

| File | Peran |
|------|-------|
| `app/Http/Controllers/Admin/SettingsController.php` | Controller — menangani logika bisnis perubahan kredensial |
| `app/Http/Requests/UpdateCredentialsRequest.php` | Form Request — validasi input sebelum masuk controller |
| `routes/web.php` | Registrasi endpoint API |
| `resources/views/components/layouts/admin.blade.php` | UI modal & JavaScript AJAX |
| `resources/css/app.css` | Styling tombol pengaturan dan modal |

---

## Endpoint

```
PUT /admin/settings/credentials
Route name: admin.settings.credentials
Middleware: admin.auth
```

### Request Body

**Mode Ganti ID (`type = login_id`)**

| Field | Tipe | Aturan |
|-------|------|--------|
| `type` | string | wajib, nilai `login_id` |
| `current_password` | string | wajib |
| `new_login_id` | string | wajib, min 3 karakter, max 50 karakter, unik |

**Mode Ganti Password (`type = password`)**

| Field | Tipe | Aturan |
|-------|------|--------|
| `type` | string | wajib, nilai `password` |
| `current_password` | string | wajib |
| `new_password` | string | wajib, min 8 karakter |
| `new_password_confirmation` | string | wajib, harus sama dengan `new_password` |

### Response

**Berhasil (HTTP 200)**
```json
{ "success": true, "message": "Password berhasil diperbarui." }
```

**Gagal — Password Saat Ini Salah (HTTP 422)**
```json
{ "success": false, "field": "current_password", "message": "Password saat ini tidak sesuai." }
```

**Gagal — Validasi Input (HTTP 422)**
```json
{ "success": false, "errors": { "new_login_id": ["ID Administrator minimal 3 karakter."] } }
```

---

## Alur Kerja

```
Pengguna klik tombol Pengaturan (ikon roda gigi)
    → Modal terbuka dengan dua tab: "Ganti ID" / "Ganti Password"
    → Pengguna mengisi field yang diperlukan
    → Validasi real-time di sisi client
        (tombol simpan disabled jika belum memenuhi syarat)
    → Pengguna klik "SIMPAN PERUBAHAN"
        → UI dikunci (overlay) selama proses berlangsung
        → Fetch PUT /admin/settings/credentials
            → UpdateCredentialsRequest memvalidasi input
            → SettingsController memeriksa Hash::check($current_password)
            → Jika valid: update kolom login_id atau password di database
        → Berhasil: modal tertutup + toast notifikasi sukses
        → Gagal: pesan error ditampilkan di dalam modal
        → Timeout 20 detik: jika tidak ada respons, UI dibuka kembali
```

---

## Aturan Validasi

- **Password saat ini** selalu wajib diisi pada kedua mode sebagai konfirmasi identitas.
- **Login ID baru** tidak boleh sama dengan ID yang sudah digunakan akun lain (`Rule::unique(...)->ignore($userId)`).
- **Password baru** harus dimasukkan dua kali (field konfirmasi) dan minimal 8 karakter.
- Tombol "SIMPAN PERUBAHAN" hanya aktif ketika semua syarat terpenuhi (validasi client-side real-time).

---

## Antarmuka (UI)

- **Tombol Pengaturan** (ikon roda gigi) ditambahkan di top bar sebelah kanan tombol Keluar. Teks tooltip muncul saat di-*hover*.
- **Tombol Keluar** diubah menjadi ikon saja; teks "Keluar" muncul saat di-*hover*.
- **Modal** memiliki dua tab geser: *Ganti ID Administrator* dan *Ganti Password*.
- Saat permintaan sedang diproses, seluruh elemen modal dikunci dengan overlay semi-transparan agar tidak ada interaksi ganda.
