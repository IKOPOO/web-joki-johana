# BilikFoto Web Booking System

Web aplikasi booking studio self-photo berbasis Laravel.

## 📋 Persyaratan Sistem

Pastikan komputer Anda sudah terinstall:
- **PHP** >= 8.1
- **Composer**
- **MySQL** / MariaDB
- **Node.js** & **NPM** (Opsional, jika ingin mengedit aset frontend)

## 🚀 Panduan Instalasi (Untuk Pengguna Baru)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer Anda:

### 1. Download & Ekstrak
Clone repository ini atau download file ZIP proyek, lalu ekstrak ke folder di komputer Anda.

### 2. Install Dependensi PHP
Buka terminal (Command Prompt/PowerShell) di dalam folder project, lalu jalankan:
```bash
composer install
```

### 3. Konfigurasi Database
1. Copy file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   *(Atau secara manual rename file `.env.example` menjadi `.env`)*

2. Buka file `.env` dan sesuaikan pengaturan database Anda:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_booking_studio  <-- Ganti dengan nama database Anda
   DB_USERNAME=root               <-- Default XAMPP biasanya 'root'
   DB_PASSWORD=                   <-- Default XAMPP biasanya kosong
   ```

3. Buat database baru di MySQL (misal via phpMyAdmin) dengan nama yang sesuai (contoh: `db_booking_studio`).

### 4. Generate App Key
Jalankan perintah ini untuk membuat key enkripsi aplikasi:
```bash
php artisan key:generate
```

### 5. Setup Database & Data Awal
Jalankan perintah berikut untuk membuat tabel dan mengisi data awal (Seeding):
```bash
php artisan migrate:fresh --seed
```
*Perintah ini akan membuat ulang database dari nol dan mengisi data dummy user, studio, dan paket.*

### 6. Setup Storage Link
Agar gambar bisa tampil, jalankan:
```bash
php artisan storage:link
```

### 7. Jalankan Aplikasi
Terakhir, jalankan server lokal:
```bash
php artisan serve
```
Akses web di browser melalui: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔑 Akun Default (Login)

Setelah melakukan seeding (`migrate:fresh --seed`), Anda bisa menggunakan akun berikut:

**Administrator (Super Admin):**
- **Email:** `admin@lensia.com`
- **Password:** `admin123`

**Customer & Staff:**
- Data customer dan staff dibuat secara acak oleh database seeder. Cek database tabel `users` untuk melihat email yang terdaftar jika ingin mencoba login sebagai user lain. Password default untuk user dummy adalah `password`.

---

## 🛠️ Fitur & Catatan Pembaruan Terkini

Project ini telah mendapatkan pembaruan struktur database dan fitur, meliputi:
- **Operational Hours**: Tabel baru untuk mengatur jam operasional studio.
- **Session Slots**: Detail slot waktu booking dengan tanggal spesifik.
- **Studio Details**: Penambahan info detail pada tabel studio.
- **Auth UI**: Tampilan Login/Register menggunakan model *Sliding Panel* satu halaman.

Pastikan selalu menjalankan `php artisan migrate` jika ada update file migration baru.
