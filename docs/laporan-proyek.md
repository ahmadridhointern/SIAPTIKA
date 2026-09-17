# BAB I  
# PENDAHULUAN

## 1.1 Latar Belakang

Bidang Aplikasi Informatika (APTIKA) pada Dinas Komunikasi Informatika dan Statistik Provinsi Riau bertugas mengelola pengembangan, integrasi, dan layanan sistem pemerintahan berbasis elektronik di lingkungan Pemerintah Provinsi Riau. Pelaksanaan tugas tersebut mencakup berbagai agenda operasional rutin, seperti rapat koordinasi, bimbingan teknis, sosialisasi aplikasi, dan evaluasi layanan teknologi informasi. Tingginya intensitas kegiatan kedinasan ini menuntut adanya pencatatan jadwal serta pengelolaan dokumen administrasi yang terstruktur dan terdokumentasi dengan baik.

Berdasarkan hasil observasi selama pelaksanaan Kerja Praktik, penyampaian informasi jadwal kegiatan serta pembagian berkas arsip pendukung (seperti surat tugas, notulen rapat, dan dokumentasi foto) masih dilakukan secara informal melalui grup pesan instan WhatsApp. Praktik ini menyebabkan informasi jadwal rentan tertimbun percakapan lain, pemantauan status pelaksanaan kegiatan belum terpadu, dan proses temu kembali (*retrieval*) dokumen lama membutuhkan waktu lama saat diperlukan untuk penyusunan laporan pertanggungjawaban maupun audit kinerja. Selain itu, pegawai non-admin belum memiliki media khusus untuk memantau agenda kegiatan terkini secara cepat dan mandiri.

Untuk mengatasi permasalahan tersebut, dirancang sistem informasi administrasi kegiatan berarsitektur *hybrid desktop*. Sistem dibangun menggunakan teknologi web berbasis PHP dan *framework* Laravel untuk menangani alur logika bisnis dan manajemen data, kemudian dikemas menjadi perangkat lunak desktop Windows mandiri (`.exe`) menggunakan Electron. Pendekatan ini memungkinkan aplikasi berjalan langsung pada komputer kerja staf tanpa biaya sewa *web hosting* publik bulanan dan bebas dari keharusan instalasi dependensi perangkat lunak tambahan (*self-contained*), sementara data kegiatan dan berkas arsip tetap tersimpan serta tersinkronisasi secara terpusat melalui *cloud database* Supabase PostgreSQL dan *cloud storage* Supabase Storage S3.

Berdasarkan latar belakang tersebut, dikembangkan **SISTEM INFORMASI ADMINISTRASI KEGIATAN BIDANG APTIKA BERBASIS WEB**. Sistem ini menghasilkan dua produk perangkat lunak aplikasi desktop, yaitu **SIAPTIKA Administrator (`SIAPTIKA Administrator.exe`)** untuk pengelolaan penuh jadwal dan arsip kedinasan, serta **SIAPTIKA Dashboard (`SIAPTIKA Dashboard.exe`)** sebagai portal papan informasi digital agenda kegiatan bagi seluruh pegawai di lingkungan Bidang APTIKA Dinas Komunikasi Informatika dan Statistik Provinsi Riau.

---

## 1.2 Tujuan Kerja Praktek

Tujuan dari pelaksanaan Kerja Praktik dan pengembangan sistem ini adalah sebagai berikut:

1. Menganalisis alur kerja administrasi kegiatan dan kebutuhan pengelolaan arsip dokumen pada Bidang Aplikasi Informatika (APTIKA) Dinas Komunikasi Informatika dan Statistik Provinsi Riau.
2. Merancang sistem informasi administrasi kegiatan berarsitektur *hybrid desktop* yang menggabungkan keunggulan fleksibilitas *framework* web (Laravel) dengan kepraktisan antarmuka desktop (Electron).
3. Membangun dan mengimplementasikan perangkat lunak desktop mandiri **SIAPTIKA Administrator (`.exe`)** dan **SIAPTIKA Dashboard (`.exe`)** yang terintegrasi secara aman dengan layanan basis data Supabase PostgreSQL dan penyimpanan berkas Supabase Storage.
4. Menguji kelayakan fungsional sistem informasi yang dikembangkan menggunakan metode *Black Box Testing* guna memastikan seluruh fitur berjalan stabil dan sesuai dengan spesifikasi kebutuhan pengguna.

---

## 1.3 Manfaat Kerja Praktek

Pelaksanaan Kerja Praktik ini diharapkan memberikan manfaat kepada berbagai pihak terkait, yaitu:

### 1.3.1 Manfaat bagi Mahasiswa
1. Menerapkan konsep dan teori rekayasa perangkat lunak yang diperoleh selama masa perkuliahan pada studi kasus nyata di instansi pemerintah.
2. Mengasah keterampilan teknis dalam arsitektur aplikasi *hybrid desktop*, khususnya integrasi antara *framework* Laravel, Electron wrapper, dan layanan *cloud storage*.
3. Memahami proses bisnis, budaya kerja, serta tata kelola administrasi teknologi informasi di lingkungan instansi pemerintahan.

### 1.3.2 Manfaat bagi Instansi (Diskominfotik Provinsi Riau)
1. Membantu staf administrasi Bidang APTIKA dalam mencatat, memperbarui, dan mengelola jadwal kegiatan dinas secara terstruktur dan terpusat melalui aplikasi desktop khusus.
2. Mempermudah dan mempercepat proses pencarian serta pengunduhan arsip dokumen kegiatan (surat tugas, notulen, dan dokumentasi) saat diperlukan untuk keperluan audit dan pelaporan kinerja.
3. Menyediakan portal informasi agenda kegiatan yang transparan dan dapat diakses langsung dari komputer kerja seluruh pegawai tanpa memerlukan proses login yang rumit.
4. Menghemat anggaran operasional instansi karena sistem tidak memerlukan sewa server hosting publik bulanan dan dapat diinstalasi secara mandiri (*self-contained*).

### 1.3.3 Manfaat bagi Program Studi Teknik Informatika UIR
1. Mempererat hubungan kerja sama dan kemitraan antara Program Studi Teknik Informatika Universitas Islam Riau dengan instansi pemerintah daerah.
2. Menjadi referensi akademik dan dokumentasi studi kasus penerapan arsitektur *hybrid desktop* (Electron + Laravel + Supabase) dalam digitalisasi tata kelola administrasi pemerintahan.
3. Memberikan gambaran mengenai kesesuaian kurikulum akademik dengan kebutuhan kompetensi praktis pada instansi dunia kerja.

---

## 1.4 Ringkasan Sistematika Laporan

Laporan Kerja Praktik ini disusun dalam empat bab pembahasan dengan sistematika penulisan sebagai berikut:

- **BAB I PENDAHULUAN**  
  Bab ini memuat latar belakang pemilihan judul dan tempat Kerja Praktik, penjelasan arsitektur *hybrid desktop*, rumusan tujuan yang ingin dicapai, manfaat yang diharapkan bagi mahasiswa, instansi, dan program studi, serta ringkasan sistematika penulisan laporan.

- **BAB II DESKRIPSI KERJA PRAKTEK**  
  Bab ini menguraikan waktu dan tempat pelaksanaan Kerja Praktik, profil umum Dinas Komunikasi Informatika dan Statistik Provinsi Riau, visi dan misi, struktur organisasi, tugas pokok dan fungsi Bidang APTIKA, target pemecahan masalah, metodologi pelaksanaan kerja (*Scrum Framework*), serta rencana dan jadwal kerja per *sprint*.

- **BAB III PELAKSANAAN DAN PEMBAHASAN KERJA PRAKTEK**  
  Bab ini membahas landasan teori teknologi yang digunakan, analisis permasalahan sistem berjalan, spesifikasi kebutuhan fungsional dan non-fungsional, perancangan sistem (*use case diagram*, *activity diagram*, *flowchart*, ERD, dan *wireframe*), proses implementasi modul perangkat lunak serta pengemasan desktop Electron, penyajian hasil antarmuka sistem, serta hasil pengujian fungsional menggunakan metode *Black Box Testing* beserta pembahasannya.

- **BAB IV KESIMPULAN DAN SARAN**  
  Bab ini berisi kesimpulan menyeluruh mengenai hasil pelaksanaan Kerja Praktik dan pengembangan perangkat lunak desktop yang telah dilakukan, serta saran-saran perbaikan yang bermanfaat untuk pengembangan sistem lebih lanjut di masa mendatang.

---

# BAB II  
# DESKRIPSI KERJA PRAKTEK

## 2.1 Waktu dan Tempat Pelaksanaan

### 2.1.1 Waktu Pelaksanaan
Kegiatan Kerja Praktik dilaksanakan selama kurang lebih 1 (satu) bulan, terhitung mulai tanggal 13 Juli 2026 sampai dengan tanggal 28 Agustus 2026. Jam kerja operasional mengikuti ketentuan hari kerja aparatur sipil negara di lingkungan Pemerintah Provinsi Riau, yaitu hari Senin hingga Rabu pukul 07.30–16.00 WIB, hari Kamis pukul 07.30–16.30 WIB dan hari Jum’at Work From Home (WFH).

### 2.1.2 Tempat Pelaksanaan
Kegiatan Kerja Praktek dilaksanakan di kantor Dinas Komunikasi Informatika dan Statistik Provinsi Riau (DISKOMINFOTIK Riau) pada bidang Aplikasi Informatika (APTIKA). Adapun Lokasi kantor tersebut yaitu Jalan Diponegoro Nomor 24 A, Simpang Empat, Kec. Pekanbaru Kota, Kota Pekanbaru, Riau 28127.

---

## 2.2 Profil Instansi Tempat Kerja Praktek

### 2.2.1 Profil Singkat Diskominfotik Provinsi Riau
Dinas Komunikasi, Informatika, dan Statistik (Diskominfotik) Provinsi Riau merupakan unsur pelaksana urusan pemerintahan daerah di bidang komunikasi, informatika, statistik, dan persandian yang berada di bawah dan bertanggung jawab kepada Gubernur Riau melalui Sekretaris Daerah. Pembentukan instansi ini didasarkan pada Peraturan Daerah Provinsi Riau Nomor 4 Tahun 2016 tentang Pembentukan dan Susunan Perangkat Daerah Provinsi Riau serta Peraturan Gubernur Riau Nomor 78 Tahun 2016 tentang Kedudukan, Susunan Organisasi, Tugas dan Fungsi, serta Tata Kerja Dinas Komunikasi, Informatika, dan Statistik Provinsi Riau.

Diskominfotik Provinsi Riau memegang peranan krusial sebagai penggerak utama transformasi digital di lingkungan Pemerintah Provinsi Riau. Instansi ini bertanggung jawab menyelenggarakan infrastruktur teknologi informasi, mengoordinasikan pengembangan sistem pemerintahan berbasis elektronik (SPBE), mengelola diseminasi informasi publik daerah, menyusun statistik sektoral, serta mengamankan persandian dan keamanan informasi kedinasan.


### 2.2.2 Visi dan Misi Instansi
Sebagai bagian integral dari Pemerintah Provinsi Riau, Diskominfotik Provinsi Riau mengacu pada Visi Pembangunan Daerah Provinsi Riau, yaitu “Terwujudnya Layanan Komunikasi, Informatika  dan Statistik yang handal dan berdaya saing“
Dalam mendukung pencapaian visi tersebut, Diskominfotik Provinsi Riau menjalankan misi yang selaras dengan tata kelola teknologi dan informasi, khususnya “Mewujudkan manajemen penyelenggaraan pemerintahan yang baik (good governance), efektif dan efisien, professional, transparan dan akuntabel.”

### 2.2.3 Struktur Organisasi Instansi
Struktur organisasi Diskominfotik Provinsi Riau dipimpin oleh seorang Kepala Dinas yang membawahi sekretariat, beberapa bidang teknis, serta kelompok jabatan fungsional. Susunan organisasi terdiri atas:
1. Kepala Dinas
2. Sekretariat, membawahi:
  a. Subbagian Kepegawaian dan Umum
  b. Subbagian Keuangan dan Perlengkapan
  c. Subbagian Perencanaan Program
3. Bidang Arsiparis
4. Bidang Informasi dan Komunikasi Publik (IKP)
5. Bidang Aplikasi Informatika (APTIKA)
6. Bidang Statistik
7. Bidang Persandian


---

*(Tempat Peletakan Gambar Struktur Organisasi)*  
```
[TEMPAT GAMBAR: Struktur Organisasi Diskominfotik Provinsi Riau]
```
**Gambar 2.1** Struktur Organisasi Dinas Komunikasi Informatika dan Statistik Provinsi Riau  

---

### 2.2.4 Profil Unit Kerja Bidang APTIKA
Bidang Aplikasi Informatika (APTIKA) adalah salah satu unit eselon III pada Diskominfotik Provinsi Riau yang bertugas memimpin penyelenggaraan tata kelola aplikasi dan layanan informatika pemerintahan. Bidang ini mengawal implementasi Sistem Pemerintahan Berbasis Elektronik (SPBE) pada seluruh Organisasi Perangkat Daerah (OPD) di lingkungan Pemerintah Provinsi Riau.

Aktivitas kerja di Bidang APTIKA mencakup rekayasa perangkat lunak aplikasi kedinasan, integrasi basis data antarsistem, pemeliharaan sistem informasi yang telah berjalan, serta pendampingan teknis kepada seluruh instansi pemerintah daerah dalam pemanfaatan teknologi informasi.

### 2.2.5 Tugas Pokok dan Fungsi Bidang APTIKA
Berdasarkan regulasi yang berlaku, Bidang APTIKA mempunyai tugas pokok melaksanakan perumusan kebijakan, pengoordinasian, pembinaan, fasilitasi, pemantauan, dan evaluasi di bidang aplikasi informatika.

Dalam melaksanakan tugas pokok tersebut, Bidang APTIKA menyelenggarakan fungsi:
1. Penyiapan bahan perumusan kebijakan teknis di bidang layanan aplikasi informatika dan ekosistem digital pemerintahan.
2. Pelaksanaan perancangan, pengembangan, pengujian, dan penerapan sistem aplikasi pemerintahan daerah.
3. Pengoordinasian integrasi sistem informasi dan interoperabilitas data antar-perangkat daerah.
4. Pemberian fasilitasi bimbingan teknis, sosialisasi, dan pendampingan implementasi aplikasi kepada instansi pengguna.
5. Pemantauan, evaluasi, dan penyusunan laporan berkala terhadap kinerja layanan aplikasi dan SPBE di Provinsi Riau.

---

## 2.3 Program Kerja Praktek

### 2.3.1 Target Pemecahan Masalah
Selama pelaksanaan Kerja Praktik, mahasiswa ditugaskan untuk mengatasi permasalahan administrasi dan dokumentasi kegiatan pada Bidang APTIKA. Target utama yang harus dicapai meliputi:
1. **Digitalisasi Manajemen Agenda Melalui Aplikasi Desktop**: Menggantikan penyampaian jadwal informal via grup pesan instan dengan software desktop yang mampu mencatat judul agenda, tanggal, waktu, lokasi, deskripsi, dan status kegiatan secara terstruktur.
2. **Sentralisasi Pengarsipan Dokumen**: Membangun mekanisme pengunggahan dan penyimpanan arsip berkas kegiatan (surat tugas, notulen rapat, dokumentasi foto) yang terhubung langsung ke penyimpanan awan (*cloud storage*) sehingga dokumen aman dan tidak hilang.
3. **Penyediaan Media Informasi bagi Pegawai**: Menyediakan aplikasi desktop khusus pegawai (**SIAPTIKA Dashboard**) yang menyajikan agenda kegiatan harian, kegiatan mendatang, serta direktori arsip yang mudah dicari (*searchable*) secara mandiri.

### 2.3.2 Metode Pelaksanaan Tugas (Metodologi Scrum)
Pengembangan sistem SIAPTIKA dilaksanakan dengan menerapkan kerangka kerja **Scrum Agile**. Metodologi ini dipilih karena memiliki fleksibilitas tinggi, pendekatan berbasis iterasi terukur (*sprint*), serta memungkinkan adaptasi cepat terhadap masukan pembimbing lapangan.

Siklus kerja Scrum yang diterapkan terdiri dari tahapan berikut:
1. **Product Backlog**: Menyusun daftar keseluruhan kebutuhan sistem berdasarkan hasil wawancara dan observasi kerja.
2. **Sprint Planning**: Menentukan target fitur dan membagi *backlog* prioritas ke dalam iterasi kerja berdurasi 1–2 minggu.
3. **Daily Scrum / Koordinasi**: Melakukan reviu kemajuan teknis harian dan mengatasi kendala implementasi kode secara cepat.
4. **Sprint Review**: Mempresentasikan luaran modul yang telah selesai kepada pembimbing lapangan untuk memperoleh evaluasi dan pengujian langsung.
5. **Sprint Retrospective**: Mengevaluasi proses kerja pada *sprint* berjalan untuk meningkatkan efektivitas pada *sprint* berikutnya.

---

*(Tempat Peletakan Gambar Metodologi Scrum)*  
```
[TEMPAT GAMBAR: Diagram Alur Metodologi Scrum]
```
**Gambar 2.2** Alur Tahapan Pengembangan Sistem Menggunakan Metodologi Scrum  

---

### 2.3.3 Rencana dan Penjadwalan Kerja (Sprint 1 s.d. Sprint 6)
Pelaksanaan Kerja Praktik dan pengembangan perangkat lunak dibagi ke dalam 6 (enam) tahapan *sprint* terstruktur sebagaimana disajikan pada Tabel 2.1.

**Tabel 2.1** Rencana dan Jadwal Kerja Pengembangan Sistem Berdasarkan *Sprint*

| Sprint | Rencana Aktivitas Kerja | Target Luaran (*Output*) |
|:---:|:---|:---|
| **Sprint 1** | - Observasi alur kerja administrasi Bidang APTIKA.<br>- Wawancara kebutuhan pengguna dengan pembimbing lapangan.<br>- Penyusunan *Product Backlog* dan dokumen kebutuhan sistem.<br>- Pemodelan sistem (*Use Case*, *Activity Diagram*, *Flowchart*, ERD, dan *Wireframe*). | - Dokumen analisis kebutuhan (*SRS*).<br>- Diagram rancangan sistem dan basis data.<br>- Rancangan antarmuka awal (*Wireframe*). |
| **Sprint 2** | - Inisialisasi struktur proyek Laravel.<br>- Konfigurasi koneksi basis data Supabase PostgreSQL.<br>- Pembuatan tabel dan skema database relasional.<br>- Implementasi autentikasi Admin berbasis sesi.<br>- Implementasi modul CRUD kegiatan kedinasan. | - Halaman login Administrator.<br>- Dashboard utama Administrator.<br>- Fitur tambah, ubah, dan hapus kegiatan.<br>- Basis data terhubung aktif di Supabase. |
| **Sprint 3** | - Konfigurasi integrasi Supabase Storage S3.<br>- Implementasi modul pengunggahan berkas arsip (surat tugas, notulen rapat, dokumentasi foto).<br>- Pengelolaan metadata dokumen pada basis data.<br>- Pembuatan halaman rincian (*detail*) kegiatan beserta daftar arsip terlampir. | - Fitur unggah dan ganti dokumen arsip.<br>- Berkas tersimpan aman pada *bucket cloud*.<br>- Pratinjau daftar arsip per kegiatan. |
| **Sprint 4** | - Perancangan dan pembuatan antarmuka Dashboard Pegawai.<br>- Implementasi penyajian data agenda hari ini dan agenda mendatang.<br>- Implementasi fitur pencarian cepat (*keyword search*) dan filter status kegiatan.<br>- Pembuatan direktori rekapitulasi arsip publik bagi pegawai (*read-only*). | - Dashboard informasi khusus Pegawai.<br>- Fitur pencarian instan dan penyaringan data.<br>- Portal arsip digital tanpa hak ubah/hapus. |
| **Sprint 5** | - Penyusunan skenario pengujian fungsional (*Black Box Testing*).<br>- Pelaksanaan pengujian menyeluruh terhadap 37 kasus uji fungsional.<br>- Perbaikan *bug*, penyesuaian validasi formulir, dan optimasi kueri basis data.<br>- Standardisasi autentikasi akun Administrator (`ADM001`). | - Dokumen pengujian fungsional (*test matrix*).<br>- Daftar catatan perbaikan galat (*bug fix*).<br>- Aplikasi berstatus stabil dan lolos uji 100%. |
| **Sprint 6** | - Pengemasan aplikasi desktop menggunakan Electron Wrapper.<br>- *Bundling* runtime PHP 8.3 portable mandiri dan pustaka CRT pendukung.<br>- Pembuatan installer aplikasi Windows setup (`.exe`) untuk Administrator dan Pegawai.<br>- Penyusunan dokumentasi teknis sistem dan panduan pengguna (*user manual*).<br>- Penyusunan naskah laporan Kerja Praktik (BAB III dan BAB IV). | - Paket installer `SIAPTIKA Administrator.exe` dan `SIAPTIKA Dashboard.exe`.<br>- Aplikasi desktop mandiri (*self-contained*).<br>- Buku panduan penggunaan (*user manual*).<br>- Naskah laporan Kerja Praktik final. |
