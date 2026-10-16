# SportCourt — Sistem Booking Lapangan Futsal & Badminton

> **Proyek Ujian Tengah Praktikum Pemrograman Web Lanjut (UTP PWL) 2026**  
> **Kelompok 3A**

---

## 👥 Anggota Kelompok & Pembagian Tugas

| No | Nama | Peran & Fitur Utama | Kontribusi CRUD |
|---|---|---|---|
| 1 | **Palwa** | Database, Autentikasi, Role & Middleware | Fondasi Project, Users & Role System |
| 2 | **Dinda** | Booking Lapangan Penyewa | **CRUD Booking** (Tambah, Lihat, Reschedule, Batalkan) |
| 3 | **Achira** | Manajemen Lapangan & UI | **CRUD Lapangan** (Tambah, Edit, Hapus, Fasilitas, Katalog) |
| 4 | **Faris** | Manajemen Transaksi & Data Tim | **CRUD Tim/Komunitas** & **Persetujuan (Approval) Booking** |

---

## ✅ Pemenuhan Ketentuan UTP PWL

1. **Minimal 2 Role Wajib CRUD**  
   - **Role Admin:** CRUD Data Lapangan, Kelola & Persetujuan Booking (Approve / Reject), Pemantauan Tim.
   - **Role Penyewa:** CRUD Booking Lapangan (Pesan, Lihat Jadwal, Reschedule, Batalkan), CRUD Tim/Komunitas (Tambah, Edit, Hapus Klub).
2. **Tema Unik & Orisinal**  
   - Sistem reservasi lapangan olahraga (*SportCourt*) yang menggabungkan penjadwalan lapangan dengan manajemen tim komunitas.
3. **Deadline:** 18 Oktober 2026, 23.59 WIB (Selesai sebelum tenggat waktu).
4. **Wajib Push GitHub:** Seluruh struktur kode, migration, seeder, views, dan tests terdokumentasi rapi di repository.
5. **CRUD 2 Fitur Selain Login & Register:**  
   - **Fitur 1 (Booking):** CRUD Reservasi Lapangan dengan kalkulator tarif otomatis.
   - **Fitur 2 (Lapangan):** CRUD Fasilitas & Spesifikasi Lapangan.
   - **Fitur 3 (Tim/Komunitas):** CRUD Profil Komunitas & Kontak Kapten.
6. **Error Handling Menyeluruh (Tanpa Tampilan Error Laravel Bawaan):**  
   - Custom Error Views: `403.blade.php`, `404.blade.php`, `500.blade.php`, `419.blade.php`.
   - Validasi bentrok jadwal (*conflict schedule prevention*) dengan pesan peringatan berbahasa Indonesia.
   - Flash message alert banner untuk setiap aksi sukses / gagal.
7. **Fitur yang Ada Harus Selesai:**  
   - Alur booking selesai tuntas dari pembuatan oleh penyewa hingga pemrosesan & tiket oleh admin.
8. **UI/UX Modern (Point Plus):**  
   - Antarmuka responsif bernuansa *sporty dark mode* menggunakan Tailwind CSS, badge status dinamis, dan visual e-ticket.

---

## 🔑 Akun Uji Coba Demo

Untuk kemudahan pengujian oleh Dosen / Asisten Praktikum, gunakan akun yang telah disiapkan via seeder:

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Admin** | `admin@sportcourt.test` | `password` | Kelola Lapangan, Approve / Reject Booking, Dashboard Admin |
| **Penyewa** | `faris@sportcourt.test` | `password` | Booking Lapangan, CRUD Tim Komunitas, Tiket Reservasi |
| **Penyewa 2** | `dinda@sportcourt.test` | `password` | Pemesanan Tambahan |

---

## 🚀 Panduan Menjalankan Project

### 1. Clone Repository & Masuk ke Folder
```bash
git clone https://github.com/achiralucy/UTP_Team3_A_SportCourt.git
cd UTP_Team3_A_SportCourt
```

### 2. Install Dependensi PHP & JavaScript
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment & Database
Salin `.env.example` menjadi `.env` lalu generate application key:
```bash
cp .env.example .env
php artisan key:generate
```
> Project secara default menggunakan SQLite (`database/database.sqlite`) yang portabel tanpa perlu konfigurasi MySQL tambahan. Jika menggunakan MySQL di Laragon, cukup sesuaikan `DB_CONNECTION=mysql` pada file `.env`.

### 4. Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Automated Tests (Verifikasi)
```bash
php artisan test
```
> Menjalankan seluruh pengujian unit dan fitur (35 passed, 85 assertions).

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser di: [http://127.0.0.1:8000](http://127.0.0.1:8000)
