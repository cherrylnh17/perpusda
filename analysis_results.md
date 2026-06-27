# 📋 Analisis Lengkap Project — Sistem Kepegawaian (HRIS)

> **Nama Database**: `perpusda_db`
> **Framework**: Laravel 13 + Blade + TailwindCSS + Alpine.js + Vite
> **PHP**: 8.3+ | **Database**: MySQL

---

## 🎯 Ringkasan Project

Project ini adalah **Sistem Informasi Kepegawaian** (Human Resource Information System / HRIS) berbasis web yang dibangun dengan Laravel. Aplikasi ini mengelola data karyawan, jabatan, golongan, jenis kontrak, pendidikan, serta **kenaikan berkala (gaji)** dan **kenaikan golongan** secara otomatis melalui sistem scheduler/cron.

---

## 🏗️ Arsitektur & Teknologi

| Layer | Teknologi | Keterangan |
|---|---|---|
| **Backend** | Laravel 13.8 | PHP framework MVC |
| **Frontend** | Blade + Alpine.js | Server-side rendering + reaktif ringan |
| **Styling** | TailwindCSS 3 + `@tailwindcss/forms` | Utility-first CSS |
| **Build Tool** | Vite 8 | Asset bundling (CSS/JS) |
| **Autentikasi** | Laravel Breeze | Login, register, forgot password |
| **Export Excel** | `spatie/simple-excel` | Export/import data karyawan via XLSX |
| **Export PDF** | `barryvdh/laravel-dompdf` | Generate PDF profil karyawan |
| **Database** | MySQL (via `.env`) | Relational database |
| **Testing** | Pest 4 | PHP testing framework |

---

## 📁 Struktur Folder — Penjelasan Detail

### 🔷 Root Files (File Konfigurasi)

| File | Fungsi |
|---|---|
| [composer.json](file:///e:/Perpustakaan/website-new/composer.json) | Mendefinisikan dependensi PHP (Laravel, Breeze, DomPDF, dll) dan script automasi |
| [package.json](file:///e:/Perpustakaan/website-new/package.json) | Mendefinisikan dependensi Node.js (Vite, Tailwind, Alpine.js) |
| [vite.config.js](file:///e:/Perpustakaan/website-new/vite.config.js) | Konfigurasi Vite — menentukan entry point CSS/JS |
| [tailwind.config.js](file:///e:/Perpustakaan/website-new/tailwind.config.js) | Konfigurasi TailwindCSS — font Figtree, scan file Blade |
| [.env](file:///e:/Perpustakaan/website-new/.env) | Konfigurasi environment (database, mail, cache, session) |
| [phpunit.xml](file:///e:/Perpustakaan/website-new/phpunit.xml) | Konfigurasi test runner |
| [postcss.config.js](file:///e:/Perpustakaan/website-new/postcss.config.js) | PostCSS pipeline (autoprefixer) |

---

### 🔷 `app/` — Logika Utama Aplikasi

Folder ini mengikuti pola **MVC** (Model-View-Controller) dari Laravel.

---

#### 📦 `app/Models/` — Data & Relasi Database

Setiap model merepresentasikan 1 tabel di database dan mendefinisikan relasi antar tabel.

| Model | Tabel | Fungsi |
|---|---|---|
| [Karyawan.php](file:///e:/Perpustakaan/website-new/app/Models/Karyawan.php) | `karyawans` | **Model utama** — menyimpan data karyawan (NIP, NIK, nama, gender, tanggal lahir/masuk, alamat, agama, foto, status aktif). Berelasi ke jabatan, pendidikan, kontrak, golongan, kenaikan berkala, dan kenaikan golongan |
| [Jabatan.php](file:///e:/Perpustakaan/website-new/app/Models/Jabatan.php) | `jabatans` | Master data jabatan (nama jabatan) |
| [Pendidikan.php](file:///e:/Perpustakaan/website-new/app/Models/Pendidikan.php) | `pendidikans` | Master data pendidikan (nama pendidikan + jenjang) |
| [JenisKontrak.php](file:///e:/Perpustakaan/website-new/app/Models/JenisKontrak.php) | `jenis_kontraks` | Master data jenis kontrak (nama kontrak + jam kerja per hari) |
| [Golongan.php](file:///e:/Perpustakaan/website-new/app/Models/Golongan.php) | `golongans` | Master data golongan (tipe: PNS/PPPK, nama golongan misal I/A, II/B) |
| [KenaikanBerkala.php](file:///e:/Perpustakaan/website-new/app/Models/KenaikanBerkala.php) | `kenaikan_berkalas` | Jadwal kenaikan gaji berkala tiap 2 tahun. Status: `scheduled → pending → diterima/stop` |
| [KenaikanGolongan.php](file:///e:/Perpustakaan/website-new/app/Models/KenaikanGolongan.php) | `kenaikan_golongans` | Jadwal kenaikan golongan/pangkat. Saat approve: update golongan karyawan + catat histori |
| [HistoriGolongan.php](file:///e:/Perpustakaan/website-new/app/Models/HistoriGolongan.php) | `histori_golongans` | Riwayat permanen setiap kali karyawan naik golongan (audit trail) |
| [NotifikasiKenaikan.php](file:///e:/Perpustakaan/website-new/app/Models/NotifikasiKenaikan.php) | `notifikasi_kenaikans` | Notifikasi H-30 sebelum tanggal kenaikan (gaji/jabatan). Di-generate via scheduler |
| [User.php](file:///e:/Perpustakaan/website-new/app/Models/User.php) | `users` | User admin yang login ke sistem |

##### Diagram Relasi (ERD):

```mermaid
erDiagram
    Karyawan ||--o| Jabatan : "id_jabatan"
    Karyawan ||--o| Pendidikan : "id_pendidikan"
    Karyawan ||--o| JenisKontrak : "id_jenis_kontrak"
    Karyawan ||--o| Golongan : "id_golongan"
    Karyawan ||--o{ KenaikanBerkala : "id_karyawan"
    Karyawan ||--o{ KenaikanGolongan : "id_karyawan"
    Karyawan ||--o{ HistoriGolongan : "id_karyawan"
    KenaikanGolongan ||--o| Golongan : "golongan_lama_id"
    KenaikanGolongan ||--o| Golongan : "golongan_baru_id"
    KenaikanGolongan ||--o| HistoriGolongan : "id_golongan_kenaikan"
    HistoriGolongan ||--o| Golongan : "golongan_lama_id"
    HistoriGolongan ||--o| Golongan : "golongan_baru_id"
    KenaikanBerkala ||--o| User : "diproses_oleh"
    KenaikanGolongan ||--o| User : "diproses_oleh"
    HistoriGolongan ||--o| User : "dicatat_oleh"
```

---

#### 🎮 `app/Http/Controllers/` — Logika Request/Response

| Controller | Fungsi |
|---|---|
| [DashboardController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/DashboardController.php) | Halaman utama setelah login. Menampilkan statistik: total karyawan, distribusi per jabatan/pendidikan/kontrak/gender, status PNS/PPPK/Outsourcing, karyawan terbaru, kenaikan berkala & golongan yang upcoming |
| [KaryawanController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/KaryawanController.php) | **CRUD lengkap** data karyawan + export Excel, export PDF (massal & single), import Excel, upload/hapus foto, download template |
| [GolonganController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/GolonganController.php) | CRUD master data golongan (PNS/PPPK) |
| [JabatanController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/JabatanController.php) | CRUD master data jabatan |
| [PendidikanController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/PendidikanController.php) | CRUD master data pendidikan + API endpoint cascading dropdown |
| [JenisKontrakController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/JenisKontrakController.php) | CRUD master data jenis kontrak |
| [KenaikanBerkalaController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/KenaikanBerkalaController.php) | Daftar kenaikan berkala pending, approve (buat jadwal +2 tahun), reject/stop |
| [KenaikanGolonganController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/KenaikanGolonganController.php) | Daftar kenaikan golongan pending, approve (update golongan + histori), reject/stop |
| [KenaikanController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/KenaikanController.php) | Landing page / hub halaman kenaikan |
| [ProfileController.php](file:///e:/Perpustakaan/website-new/app/Http/Controllers/ProfileController.php) | Edit profil user yang login (nama, email, password) |
| `Auth/` (folder) | Controller bawaan Laravel Breeze untuk login, register, forgot password, verifikasi email |

---

#### 📤 `app/Exports/` & 📥 `app/Imports/` — Excel Export/Import

| File | Fungsi |
|---|---|
| [KaryawanExport.php](file:///e:/Perpustakaan/website-new/app/Exports/KaryawanExport.php) | Export data karyawan ke file XLSX dengan filter (status, jabatan, kontrak, golongan). Menggunakan `Spatie\SimpleExcel` |
| [KaryawanImport.php](file:///e:/Perpustakaan/website-new/app/Imports/KaryawanImport.php) | Import data karyawan dari XLSX. Fitur: normalisasi header kolom (fleksibel), parsing tanggal multi-format (termasuk Excel serial number), lookup relasi otomatis, `updateOrCreate` berdasarkan NIP |

---

#### 🔔 `app/View/Composers/` — Inject Data ke View

| File | Fungsi |
|---|---|
| [NotifikasiComposer.php](file:///e:/Perpustakaan/website-new/app/View/Composers/NotifikasiComposer.php) | **Otomatis** inject data notifikasi kenaikan (berkala H-30 + golongan pending) ke semua halaman yang menggunakan `layouts.navigation`. Sehingga badge/popup notifikasi selalu tersedia di navbar |

Didaftarkan di [AppServiceProvider.php](file:///e:/Perpustakaan/website-new/app/Providers/AppServiceProvider.php):
```php
View::composer('layouts.navigation', NotifikasiComposer::class);
```

---

#### ⏰ `app/Console/Commands/` — Artisan Command (Cron Job)

| File | Fungsi |
|---|---|
| [ProsesPendingKenaikan.php](file:///e:/Perpustakaan/website-new/app/Console/Commands/ProsesPendingKenaikan.php) | Command `kenaikan:proses-pending` — dijalankan harian via cron. Mengubah status kenaikan yang sudah jatuh tempo dari `scheduled` → `pending` agar muncul di halaman approval admin |

##### Alur Status Kenaikan:
```mermaid
flowchart LR
    A["scheduled"] -->|"Cron harian<br/>tanggal ≤ hari ini"| B["pending"]
    B -->|"Admin approve"| C["diterima"]
    B -->|"Admin reject/stop"| D["stop"]
    C -->|"Sistem otomatis"| E["Buat jadwal baru<br/>(scheduled +2 thn)"]
```

---

#### 🎨 `app/View/Components/` — Blade Components Class

| File | Fungsi |
|---|---|
| [AppLayout.php](file:///e:/Perpustakaan/website-new/app/View/Components/AppLayout.php) | Component class untuk layout utama (authenticated) |
| [GuestLayout.php](file:///e:/Perpustakaan/website-new/app/View/Components/GuestLayout.php) | Component class untuk layout guest (login/register) |

---

### 🔷 `routes/` — Definisi URL

| File | Fungsi |
|---|---|
| [web.php](file:///e:/Perpustakaan/website-new/routes/web.php) | **Route utama**: Dashboard, Profile, CRUD master data (pendidikan, jabatan, kontrak, golongan), CRUD karyawan + export/import, kenaikan berkala/golongan. Semua dilindungi middleware `auth` + `verified` |
| [auth.php](file:///e:/Perpustakaan/website-new/routes/auth.php) | Route autentikasi dari Laravel Breeze: register, login, forgot/reset password, verifikasi email, logout |

##### Ringkasan Endpoint:
| URL | Method | Fungsi |
|---|---|---|
| `/` | GET | Welcome page |
| `/dashboard` | GET | Dashboard statistik |
| `/karyawan` | CRUD | Kelola data karyawan |
| `/karyawan/export/excel` | GET | Download Excel |
| `/karyawan/export/pdf` | GET | Download PDF massal |
| `/karyawan/{id}/export-pdf` | GET | Download PDF single |
| `/karyawan/import` | POST | Upload & import Excel |
| `/karyawan/template` | GET | Download template Excel |
| `/pendidikan` | CRUD | Master pendidikan |
| `/jabatan` | CRUD | Master jabatan |
| `/kontrak` | CRUD | Master jenis kontrak |
| `/golongan` | CRUD | Master golongan |
| `/kenaikan` | GET | Landing page kenaikan |
| `/kenaikan-berkala` | GET | List pending kenaikan berkala |
| `/kenaikan-berkala/{id}/approve` | POST | Approve kenaikan berkala |
| `/kenaikan-golongan` | GET | List pending kenaikan golongan |
| `/kenaikan-golongan/{id}/approve` | POST | Approve kenaikan golongan |

---

### 🔷 `resources/views/` — Tampilan (Blade Templates)

| Folder/File | Fungsi |
|---|---|
| `layouts/` | Template induk: [app.blade.php](file:///e:/Perpustakaan/website-new/resources/views/layouts/app.blade.php) (layout utama), [guest.blade.php](file:///e:/Perpustakaan/website-new/resources/views/layouts/guest.blade.php) (halaman login/register), [navigation.blade.php](file:///e:/Perpustakaan/website-new/resources/views/layouts/navigation.blade.php) (navbar + sidebar + popup notifikasi) |
| `components/` | 15 reusable Blade component: button, dropdown, modal, form-field, searchable-select, dll |
| [dashboard.blade.php](file:///e:/Perpustakaan/website-new/resources/views/dashboard.blade.php) | Halaman dashboard — chart, statistik, tabel karyawan terbaru |
| [welcome.blade.php](file:///e:/Perpustakaan/website-new/resources/views/welcome.blade.php) | Landing page / halaman awal |
| `karyawan/` | 6 views: index (list+filter), create, edit, show (detail), pdf.blade.php (PDF massal), pdf-single.blade.php (PDF per karyawan) |
| `golongan/` | 3 views: index, create, edit |
| `jabatan/` | Views untuk CRUD jabatan |
| `pendidikan/` | Views untuk CRUD pendidikan |
| `kontrak/` | Views untuk CRUD jenis kontrak |
| `kenaikan/` | Landing page hub kenaikan |
| `kenaikan-berkala/` | List + approval kenaikan berkala |
| `kenaikan-golongan/` | List + approval kenaikan golongan |
| `profile/` | Edit profil user |
| `auth/` | Login, register, forgot password (dari Breeze) |

---

### 🔷 `database/` — Skema & Data Awal

#### Migrations (Struktur Tabel)

| Migration | Tabel yang Dibuat |
|---|---|
| [create_users_table](file:///e:/Perpustakaan/website-new/database/migrations/0001_01_01_000000_create_users_table.php) | `users`, `password_reset_tokens`, `sessions` |
| [create_cache_table](file:///e:/Perpustakaan/website-new/database/migrations/0001_01_01_000001_create_cache_table.php) | `cache`, `cache_locks` |
| [create_jobs_table](file:///e:/Perpustakaan/website-new/database/migrations/0001_01_01_000002_create_jobs_table.php) | `jobs`, `job_batches`, `failed_jobs` |
| [create_master_tables](file:///e:/Perpustakaan/website-new/database/migrations/2026_05_16_061000_create_master_tables.php) | `pendidikans`, `jabatans`, `jenis_kontraks`, `golongans` |
| [create_karyawans_table](file:///e:/Perpustakaan/website-new/database/migrations/2026_05_16_070000_create_karyawans_table.php) | `karyawans` (tabel utama karyawan) |
| [create_kenaikan_tables](file:///e:/Perpustakaan/website-new/database/migrations/2026_06_01_000001_create_kenaikan_tables.php) | `kenaikan_berkalas`, `kenaikan_golongans`, `histori_golongans` |
| [add_jenjang](file:///e:/Perpustakaan/website-new/database/migrations/2026_06_18_084628_add_jenjang_to_pendidikans_table.php) | Tambah kolom `jenjang` ke `pendidikans` |
| [restructure_pendidikan](file:///e:/Perpustakaan/website-new/database/migrations/2026_06_18_160000_restructure_pendidikan_remove_nama_add_to_karyawan.php) | Pindahkan `nama_pendidikan` ke tabel karyawan |
| [change_jenjang](file:///e:/Perpustakaan/website-new/database/migrations/2026_06_25_065734_change_jenjang_from_enum_to_string_in_pendidikans_table.php) | Ubah tipe `jenjang` dari enum ke string |

#### Seeders (Data Awal)

| Seeder | Fungsi |
|---|---|
| [DatabaseSeeder.php](file:///e:/Perpustakaan/website-new/database/seeders/DatabaseSeeder.php) | Memanggil semua seeder lainnya |
| [GolonganSeeder.php](file:///e:/Perpustakaan/website-new/database/seeders/GolonganSeeder.php) | Isi data golongan PNS & PPPK |
| [JabatanSeeder.php](file:///e:/Perpustakaan/website-new/database/seeders/JabatanSeeder.php) | Isi data jabatan contoh |
| [JenisKontrakSeeder.php](file:///e:/Perpustakaan/website-new/database/seeders/JenisKontrakSeeder.php) | Isi data jenis kontrak contoh |
| [KaryawanSeeder.php](file:///e:/Perpustakaan/website-new/database/seeders/KaryawanSeeder.php) | Isi data karyawan dummy |
| [PendidikanSeeder.php](file:///e:/Perpustakaan/website-new/database/seeders/PendidikanSeeder.php) | Isi data pendidikan contoh |

---

### 🔷 `resources/css/` & `resources/js/` — Frontend Assets

| File | Fungsi |
|---|---|
| `resources/css/app.css` | Entry point CSS — di-compile Vite bersama TailwindCSS |
| `resources/js/app.js` | Entry point JS — inisialisasi Alpine.js |

---

### 🔷 `config/` — Konfigurasi Laravel

| File | Fungsi |
|---|---|
| [dompdf.php](file:///e:/Perpustakaan/website-new/config/dompdf.php) | Konfigurasi library DomPDF (ukuran kertas, font, dll) |
| `app.php`, `auth.php`, `cache.php`, `database.php`, `filesystems.php`, `logging.php`, `mail.php`, `queue.php`, `services.php`, `session.php` | Konfigurasi standar Laravel |

---

### 🔷 `public/` — File Statis (Accessible dari Browser)

| Item | Fungsi |
|---|---|
| `index.php` | Entry point utama — semua request masuk lewat sini |
| `image/logo.png` | Logo aplikasi |
| `build/` | Hasil compile Vite (CSS/JS production) |
| `storage/` | Symlink ke `storage/app/public` (foto karyawan, dll) |
| `.htaccess` | Rewrite rule Apache |

---

### 🔷 Folder Pendukung Lainnya

| Folder | Fungsi |
|---|---|
| `bootstrap/` | Bootstrap framework Laravel (cache autoload) |
| `storage/` | Tempat penyimpanan file: logs, cache, session, upload foto karyawan |
| `tests/` | File test menggunakan Pest |
| `vendor/` | Dependensi PHP (auto-generate oleh Composer, **jangan diedit**) |

---

## 🔄 Alur Kerja Utama Aplikasi

### 1. Manajemen Karyawan
```mermaid
flowchart TD
    A["Admin Login"] --> B["Dashboard"]
    B --> C["Kelola Karyawan"]
    C --> D["Tambah / Edit / Hapus"]
    C --> E["Import dari Excel"]
    C --> F["Export ke Excel / PDF"]
    C --> G["Lihat Detail + Foto"]
```

### 2. Sistem Kenaikan Otomatis
```mermaid
flowchart TD
    A["Karyawan Baru Ditambahkan"] --> B["Jadwal Kenaikan Dibuat<br/>(status: scheduled)"]
    B --> C["Cron Harian<br/>kenaikan:proses-pending"]
    C --> D{"Sudah Jatuh Tempo?"}
    D -->|Ya| E["Status → pending<br/>Muncul di Dashboard + Notifikasi"]
    D -->|Belum| C
    E --> F{"Admin Action"}
    F -->|Approve| G["Status → diterima<br/>Buat jadwal baru +2 tahun"]
    F -->|Stop| H["Status → stop<br/>Tidak ada jadwal baru"]
```

### 3. Notifikasi
```mermaid
flowchart LR
    A["NotifikasiComposer"] --> B["Query kenaikan berkala H-30"]
    A --> C["Query kenaikan golongan pending"]
    B --> D["Inject ke navigation<br/>Badge & popup"]
    C --> D
```

---

## 📊 Ringkasan Fitur

| Fitur | Status |
|---|---|
| ✅ Login/Register/Forgot Password | Laravel Breeze |
| ✅ Dashboard dengan statistik & chart | Chart per jabatan, pendidikan, kontrak, gender |
| ✅ CRUD Karyawan (lengkap) | + upload foto, filter, search |
| ✅ CRUD Master Data | Jabatan, Pendidikan, Golongan, Jenis Kontrak |
| ✅ Export ke Excel | Dengan filter |
| ✅ Import dari Excel | Normalisasi otomatis |
| ✅ Export ke PDF | Massal & per karyawan |
| ✅ Download template Excel | Untuk import |
| ✅ Kenaikan Berkala Otomatis | Scheduler + approval |
| ✅ Kenaikan Golongan | Dengan histori & audit trail |
| ✅ Notifikasi Kenaikan | H-30, badge di navbar |
| ✅ Cron Job | `kenaikan:proses-pending` |
