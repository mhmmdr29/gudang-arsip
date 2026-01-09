# Sistem Gudang Arsip - CodeIgniter + MySQL

## 📋 TENTANG PROJECT

**Nama Project:** Sistem Gudang Arsip
**Framework:** CodeIgniter 4.x
**Language:** PHP
**Database:** MySQL (phpMyAdmin compatible)
**Frontend:** Bootstrap 5 + FontAwesome Icons
**Backend:** CodeIgniter MVC Architecture

---

## ✅ FITUR YANG TERSEDIA

### 1. **Manajemen Arsip (CRUD)**
- ✅ Daftar semua arsip
- ✅ Tambah arsip baru (dengan upload PDF)
- ✅ Edit arsip
- ✅ Hapus arsip
- ✅ Pencarian arsip (berdasarkan nama/kode)
- ✅ Upload file PDF

### 2. **Manajemen Gudang (CRUD)**
- ✅ Daftar semua gudang
- ✅ Tambah gudang baru
- ✅ Edit gudang
- ✅ Hapus gudang

### 3. **Manajemen Rak (CRUD)**
- ✅ Daftar semua rak
- ✅ Tambah rak baru
- ✅ Edit rak
- ✅ Hapus rak
- ✅ Rak terhubung dengan gudang

### 4. **Riwayat Pemindahan Arsip**
- ✅ Daftar semua riwayat pemindahan
- ✅ Tambah riwayat pemindahan baru
- ✅ Edit riwayat pemindahan
- ✅ Hapus riwayat pemindahan
- ✅ Arsip terhubung dengan rak sebelum dan sesudah

### 5. **Riwayat Peminjaman Arsip**
- ✅ Daftar semua riwayat peminjaman
- ✅ Pinjam arsip
- ✅ Kembalikan arsip (peminjaman selesai)
- ✅ Riwayat peminjaman aktif (belum dikembalikan)

### 6. **Manajemen Akun (User Management)**
- ✅ Daftar semua akun
- ✅ Tambah akun baru (ADMIN & KASSUBAG_KUL only)
- ✅ Edit akun
- ✅ Hapus akun
- ✅ Reset password akun
- ✅ Role-based access control:
  - **ADMIN**: Full access (semua fitur)
  - **KASSUBAG_KUL**: Full access (semua fitur)
  - **STAFF**: Akses terbatas (bukan manajemen akun)

### 7. **Sistem Autentikasi**
- ✅ Login session management
- ✅ Logout (destroy session)
- ✅ Role-based access control
- ✅ Session timeout (2 jam)

---

## 🗄️ STRUKTUR PROJECT

```
gudang-arsip-codeigniter/
├── application/
│   ├── config/
│   │   ├── config.php          # Konfigurasi utama CodeIgniter
│   │   ├── database.php        # Konfigurasi koneksi database MySQL
│   │   └── routes.php         # Custom routing
│   ├── controllers/
│   │   ├── Auth.php          # Controller autentikasi (login, logout)
│   │   ├── Archives.php       # Controller CRUD arsip
│   │   ├── Warehouses.php    # Controller CRUD gudang
│   │   ├── Shelves.php       # Controller CRUD rak
│   │   ├── Movements.php      # Controller CRUD pemindahan
│   │   ├── Borrowings.php    # Controller CRUD peminjaman
│   │   ├── Accounts.php       # Controller CRUD manajemen akun
│   │   └── Application.php   # Controller utama (load dashboard)
│   ├── models/
│   │   ├── Archive_model.php   # Model database arsip
│   │   ├── Warehouse_model.php # Model database gudang
│   │   ├── Shelf_model.php    # Model database rak
│   │   ├── Movement_model.php # Model database pemindahan
│   │   ├── Borrowing_model.php# Model database peminjaman
│   │   └── Account_model.php  # Model database akun
│   ├── views/
│   │   ├── templates/
│   │   │   └── dashboard.php # Layout utama dengan sidebar
│   │   ├── auth/
│   │   │   └── login.php     # Halaman login
│   │   ├── archives/
│   │   │   ├── index.php     # Daftar arsip
│   │   │   ├── create.php     # Form tambah arsip
│   │   │   └── edit.php       # Form edit arsip
│   │   ├── warehouses/
│   │   │   ├── index.php     # Daftar gudang
│   │   │   ├── create.php     # Form tambah gudang
│   │   │   └── edit.php       # Form edit gudang
│   │   ├── shelves/
│   │   │   ├── index.php     # Daftar rak
│   │   │   ├── create.php     # Form tambah rak
│   │   │   └── edit.php       # Form edit rak
│   │   ├── movements/
│   │   │   ├── index.php     # Daftar riwayat pemindahan
│   │   │   ├── create.php     # Form tambah pemindahan
│   │   │   └── edit.php       # Form edit pemindahan
│   │   ├── borrowings/
│   │   │   ├── index.php     # Daftar riwayat peminjaman
│   │   │   ├── create.php     # Form pinjam arsip
│   │   │   └── return.php     # Tombol kembalikan (opsional)
│   │   └── accounts/
│   │       ├── index.php     # Daftar akun
│   │       ├── create.php     # Form tambah akun
│   │       └── edit.php       # Form edit akun
│   ├── helpers/
│   ├── libraries/
│   └── cache/                  # Folder cache CodeIgniter
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS (opsional)
│   ├── js/
│   │   └── main.js           # Custom JavaScript (opsional)
│   ├── images/
│   │   └── logo.svg          # Logo aplikasi
│   └── uploads/
│       └── archives/           # Folder untuk upload PDF arsip
├── system/                     # CodeIgniter core (JANGAN DIUBAH!)
├── database.sql                 # SQL script untuk create database
├── index.php                   # Entry point CodeIgniter
├── .htaccess                   # URL rewriting (opsional)
└── README.md                   # File dokumentasi ini
```

---

## 🚀 CARA INSTALASI (XAMPP / phpMyAdmin)

### Langkah 1: Download CodeIgniter
1. Buka: https://codeigniter.com/download
2. Download: **CodeIgniter 4.x** (versi stabil terbaru)
3. Ekstrak CodeIgniter:
   - Copy folder `application/`, `system/`, `index.php`
   - Timpa ke folder project CodeIgniter lokal Anda

### Langkah 2: Create Database di phpMyAdmin
1. Buka XAMPP Control Panel
2. Start **Apache** dan **MySQL**
3. Buka browser: `http://localhost/phpmyadmin`
4. Click tab **"Databases"**
5. Create new database:
   - Database name: `gudang_arsip`
   - Collation: `utf8mb4_unicode_ci`
6. Click **"Create"**

### Langkah 3: Import SQL File
1. Buka file: `database.sql` (di root folder project)
2. Copy semua SQL query
3. Di phpMyAdmin:
   - Select database: `gudang_arsip`
   - Click tab **"SQL"**
   - Paste SQL dari `database.sql`
   - Click **"Go"**
4. 6 tabel akan dibuat:
   - `accounts` (dengan 5 akun default)
   - `warehouses`
   - `shelves`
   - `archives`
   - `movements`
   - `borrowings`

### Langkah 4: Configure Database Connection
1. Buka file: `application/config/database.php`
2. Edit bagian `$db['default']`:
   ```php
   $db['default'] = array(
       'dsn'   => '',
       'hostname' => 'localhost',
       'username' => 'root',
       'password' => '',    // Password XAMPP (default kosong)
       'database' => 'gudang_arsip',  // Nama database baru
       'dbdriver' => 'mysqli',
       'dbprefix' => '',
       'pconnect' => FALSE,
       'db_debug' => (ENVIRONMENT !== 'production'),
       'cache_on' => FALSE,
       'cachedir' => '',
       'char_set' => 'utf8',
       'dbcollat' => 'utf8_general_ci',
       'swap_pre' => '',
       'encrypt' => FALSE,
       'compress' => FALSE,
       'stricton' => FALSE,
       'failover' => array(),
       'save_queries' => FALSE,
   );
   ```

### Langkah 5: Configure Base URL
1. Buka file: `application/config/config.php`
2. Edit bagian `$config['base_url']`:
   ```php
   $config['base_url'] = 'http://localhost:3000/gudang-arsip-codeigniter/';
   ```
   ⚠️ **PENTING:** Sesuaikan URL sesuai folder project Anda!

### Langkah 6: Configure Routes
1. Buka file: `application/config/routes.php`
2. Pastikan default controller:
   ```php
   $route['default_controller'] = 'auth';
   ```

### Langkah 7: Test Akses
1. Buka browser
2. Akses: `http://localhost:3000/gudang-arsip-codeigniter/`
   - ATAU: `http://localhost/gudang-arsip-codeigniter/`
3. Halaman login akan muncul

---

## 🔐 AKUN LOGIN DEFAULT

| Role | Username | Password | Akses |
|-------|----------|----------|--------|
| **Admin** | admin | admin123 | Full Access (Semua fitur) |
| **Kassubag** | kassubag | kassubag123 | Full Access (Semua fitur) |
| **Staff** | staff | staff123 | Terbatas (Tidak bisa manajemen akun) |
| **Admin (Tambahan)** | hanif | hanif123 | Full Access (Semua fitur) |
| **Staff (Tambahan)** | susi | susi123 | Terbatas (Tidak bisa manajemen akun) |

---

## 📱 PANDUAN PENGGUNAAN

### 1. Login
1. Buka aplikasi di browser
2. Masukkan **username** dan **password**
3. Klik tombol **"Masuk"**
4. Anda akan diarahkan ke Dashboard sesuai role

### 2. Dashboard
- Menu sidebar di sebelah kiri
- Tampilan sesuai role Anda
- Navigasi: Dashboard, Arsip, Gudang, Rak, Pindah, Pinjam, Akun

### 3. Manajemen Arsip
- **Daftar Arsip:** Lihat semua arsip dengan informasi lengkap
- **Tambah Arsip:**
  1. Klik menu "Arsip"
  2. Klik tombol "Tambah Arsip"
  3. Isi form:
     - Nama Arsip
     - Kode Arsip
     - Kode Sub Bag
     - Tahun Dibuat
     - Masa Retensi (dalam tahun)
     - Pilih Gudang
     - Pilih Rak
     - Upload File PDF (opsional)
  4. Klik tombol "Simpan"
- **Edit Arsip:**
  1. Klik tombol "Edit" pada arsip yang ingin diubah
  2. Update informasi yang diperlukan
  3. Klik tombol "Update"
- **Hapus Arsip:**
  1. Klik tombol "Hapus" pada arsip
  2. Konfirmasi penghapusan

### 4. Manajemen Gudang
- **Daftar Gudang:** Lihat semua gudang
- **Tambah Gudang:**
  1. Klik menu "Gudang"
  2. Klik tombol "Tambah Gudang"
  3. Isi form:
     - Kode Gudang (harus unik)
     - Nama Gudang
     - Lokasi Gudang
  4. Klik tombol "Simpan"
- **Edit/Hapus Gudang:** Sama dengan manajemen arsip

### 5. Manajemen Rak
- **Daftar Rak:** Lihat semua rak di semua gudang
- **Tambah Rak:**
  1. Pilih Gudang terlebih dahulu
  2. Klik tombol "Tambah Rak"
  3. Isi form:
     - Kode Rak
  4. Klik tombol "Simpan"
- **Edit/Hapus Rak:** Sama dengan manajemen arsip

### 6. Pemindahan Arsip (Movement)
- **Daftar Pemindahan:** Lihat riwayat semua pemindahan
- **Tambah Pemindahan:**
  1. Klik menu "Pindah"
  2. Klik tombol "Pindah Arsip"
  3. Pilih Arsip yang ingin dipindah
  4. Pilih Rak baru tujuan
  5. Sistem akan otomatis:
     - Mencatat rak sebelumnya
     - Mencatat rak baru
     - Mengupdate lokasi arsip di database
     - Mencatat siapa yang memindahkan (username)

### 7. Peminjaman Arsip (Borrowing)
- **Daftar Peminjaman:** Lihat riwayat semua peminjaman
- **Pinjam Arsip:**
  1. Klik menu "Pinjam"
  2. Klik tombol "Pinjam Arsip"
  3. Pilih Arsip yang ingin dipinjam
  4. Masukkan nama peminjam
  5. Arsip akan tetap berada di rak asli
  6. Status peminjaman "aktif" (belum dikembalikan)
- **Kembalikan Arsip:**
  1. Klik tombol "Kembalikan" pada riwayat peminjaman
  2. Status peminjaman akan berubah menjadi "selesai"
  3. Tanggal pengembalian akan dicatat

### 8. Manajemen Akun (ADMIN & KASSUBAG_KUL Only)
- **Daftar Akun:** Lihat semua akun
- **Tambah Akun:**
  1. Klik menu "Akun" (hanya ADMIN & KASSUBAG_KUL bisa akses)
  2. Klik tombol "Tambah Akun"
  3. Isi form:
     - Username (harus unik)
     - Email (harus unik)
     - Password (minimal 6 karakter)
     - Role (ADMIN / KASSUBAG_KUL / STAFF)
  4. Klik tombol "Simpan"
- **Edit Akun:** Sama dengan tambah
- **Hapus Akun:** Hapus akun yang tidak dibutuhkan
- **Reset Password:** Reset password ke "password123"

---

## 🗄️ DATABASE SCHEMA

### Tabel: accounts
```
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- username (VARCHAR 100, UNIQUE, NOT NULL)
- email (VARCHAR 255, UNIQUE, NOT NULL)
- password (VARCHAR 255, NOT NULL)
- role (ENUM: 'ADMIN', 'KASSUBAG_KUL', 'STAFF', DEFAULT: 'STAFF')
- created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
```

### Tabel: warehouses
```
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- code (VARCHAR 50, UNIQUE, NOT NULL)
- name (VARCHAR 255, NOT NULL)
- location (VARCHAR 255, NOT NULL)
- created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
```

### Tabel: shelves
```
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- code (VARCHAR 50, NOT NULL)
- warehouse_id (INT, FOREIGN KEY: warehouses(id), ON DELETE CASCADE, NOT NULL)
- created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
- UNIQUE KEY: (code, warehouse_id)
```

### Tabel: archives
```
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- name (VARCHAR 255, NOT NULL)
- code (VARCHAR 100, NOT NULL)
- sub_bag_code (VARCHAR 50, NOT NULL)
- year_created (INT, NOT NULL)
- retention_period (INT, NOT NULL)
- shelf_id (INT, FOREIGN KEY: shelves(id), ON DELETE RESTRICT, NOT NULL)
- warehouse_id (INT, FOREIGN KEY: warehouses(id), ON DELETE RESTRICT, NOT NULL)
- file_path (VARCHAR 500, NULLABLE)
- created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
- UNIQUE KEY: (code, warehouse_id)
```

### Tabel: movements
```
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- archive_id (INT, FOREIGN KEY: archives(id), ON DELETE CASCADE, NOT NULL)
- archive_name (VARCHAR 255, NOT NULL)
- archive_code (VARCHAR 100, NOT NULL)
- previous_shelf (VARCHAR 50, NOT NULL)
- new_shelf (VARCHAR 50, NOT NULL)
- moved_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- moved_by (VARCHAR 100, NOT NULL)
```

### Tabel: borrowings
```
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- archive_id (INT, FOREIGN KEY: archives(id), ON DELETE CASCADE, NOT NULL)
- archive_name (VARCHAR 255, NOT NULL)
- archive_code (VARCHAR 100, NOT NULL)
- borrower_name (VARCHAR 255, NOT NULL)
- borrowed_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)
- returned_at (TIMESTAMP, NULLABLE)
```

---

## 🔧 TROUBLESHOOTING

### Masalah 1: "Database connection failed"
**Solusi:**
- Pastikan XAMPP Apache dan MySQL sudah berjalan
- Pastikan database `gudang_arsip` sudah dibuat di phpMyAdmin
- Cek file `application/config/database.php`
- Pastikan username: `root` dan password: `''` (kosong untuk XAMPP default)

### Masalah 2: "404 Page Not Found"
**Solusi:**
- Cek file `index.php` di root folder
- Cek URL di browser: `http://localhost/gudang-arsip-codeigniter/`
- Cek file `.htaccess` jika ada

### Masalah 3: "Session expired"
**Solusi:**
- Login ulang
- Session timeout: 2 jam
- Cek file `application/config/config.php`:
  - `$config['sess_expiration'] = 7200;` (dalam detik)

### Masalah 4: "File upload failed"
**Solusi:**
- Pastikan folder `assets/uploads/archives/` ada dan bisa di-write
- Cek permissions folder upload (chmod 755 atau 777 di Linux/Mac)
- Cek file size maksimum (di Archives controller: 5MB)
- Pastikan file PDF (mime type: application/pdf)

### Masalah 5: "Access denied / 403 Forbidden"
**Solusi:**
- Cek permissions folder project (755 atau 777)
- Cek file `.htaccess` jika ada
- Pastikan Apache sudah start di XAMPP

### Masalah 6: "Cannot select database"
**Solusi:**
- Pastikan sudah import SQL `database.sql` ke phpMyAdmin
- Cek nama database di `application/config/database.php`
- Restart MySQL di XAMPP Control Panel

### Masalah 7: "No input file specified"
**Solusi:**
- Pastikan file `index.php` sudah ada di root folder
- Pastikan sudah copy CodeIgniter core ke folder `system/`
- Pastikan sudah copy application folder dari project ini

---

## 🌟 TIPS OPTIMISASI

### 1. Performance
- Gunakan database connection pooling (mysqli)
- Optimize SQL queries dengan indexes
- Gunakan caching untuk static files (CSS, JS, images)
- Enable Gzip compression di Apache

### 2. Security
- Validasi semua input user (XSS prevention)
- Gunakan prepared statements (SQL injection prevention)
- Validasi file upload (type, size, extension)
- Sanitasi semua output ke views (escape HTML)

### 3. Backup Database
- Backup database secara rutin (harian/mingguan)
- Gunakan phpMyAdmin Export untuk backup
- Simpan file SQL backup di tempat yang aman

### 4. File Management
- Hapus file PDF yang tidak dibutuhkan secara rutin
- Kompresi file PDF jika size terlalu besar
- Gunakan storage terpisah untuk production

---

## 📚 REFERENSI CODEIGNITER

- **Documentation:** https://codeigniter.com/userguide
- **API Reference:** https://codeigniter.com/userguide/libraries/database.html
- **Controller Reference:** https://codeigniter.com/userguide/general/controllers.html
- **Model Reference:** https://codeigniter.com/userguide/general/models.html
- **Helper Reference:** https://codeigniter.com/userguide/general/helpers.html

---

## 📞 SUPPORT

### Jika ada masalah:
1. Cek file `README.md` ini terlebih dahulu
2. Lihat bagian **Troubleshooting**
3. Cek error logs di CodeIgniter (folder `application/logs/`)
4. Cek error logs Apache di XAMPP

### Error Logs CodeIgniter:
- Location: `application/logs/`
- Filename: `log-YYYY-MM-DD.php`
- Lihat logs untuk debugging error

---

## 🎯 RINGKASAN LANGKAH INSTALASI

| Langkah | Deskripsi | Waktu |
|--------|----------|-------|
| 1. Download CodeIgniter | Download framework CI | ~2-5 menit |
| 2. Ekstrak & Copy | Copy ke project lokal | ~5-10 menit |
| 3. Create Database | Create di phpMyAdmin | ~5-10 menit |
| 4. Import SQL | Import database.sql | ~2-5 menit |
| 5. Config Database | Edit database.php | ~5-10 menit |
| 6. Config Base URL | Edit config.php | ~2-5 menit |
| 7. Test Akses | Buka di browser | ~1-2 menit |
| **TOTAL** | - | **~30-60 menit** |

---

## 🔐 SECURITY NOTES

### Password Hashing
- Project ini menggunakan password text (sederhana untuk development)
- **PRODUCTION WAJIB:** Gunakan password hashing (bcrypt/Argon2)
- Contoh: `$password = password_hash($password, PASSWORD_DEFAULT);`

### Session Security
- Session ID regenerasi saat login
- Session timeout: 2 jam
- Session data: user_id, username, email, role, logged_in

### File Upload Security
- Validasi file type (PDF only)
- Validasi file size (maksimum 5MB)
- Validasi file extension
- Rename file uploaded dengan timestamp untuk mencegah overwrite
- Simpan di luar web root (assets/uploads/)

### CSRF Protection
- Implementasi CSRF token untuk semua form submissions
- Validasi token di server-side
- Renew token per request atau per session

### XSS Prevention
- Escape semua output ke views (gunakan CodeIgniter `$this->security->xss_clean()`)
- Validasi semua input user
- Gunakan prepared statements SQL

---

## 📝 CATATAN PENGEMBANG

### Untuk Pengembangan Lanjutan:
1. **AJAX:** Implementasi AJAX untuk load data tanpa refresh halaman
2. **API REST:** Buat REST API untuk integrasi dengan aplikasi lain
3. **WebSocket:** Implementasi real-time updates (jika diperlukan)
4. **Email Notifications:** Implementasi email notification untuk peminjaman habis retensi
5. **Barcode/QR Code:** Implementasi scan barcode/QR code untuk akses cepat arsip
6. **Advanced Search:** Implementasi advanced search dengan filter ganda
7. **Export to Excel/PDF:** Implementasi export laporan ke Excel/PDF
8. **Audit Log:** Implementasi audit log untuk semua aktivitas pengguna

---

## 🎉 TERIMA KASIH

Terima kasih telah menggunakan **Sistem Gudang Arsip - CodeIgniter**!

**Sistem ini dirancang untuk:**
- ✅ Manajemen arsip yang mudah dan efisien
- ✅ Tracking lokasi arsip (Gudang dan Rak)
- ✅ Riwayat pemindahan dan peminjaman yang lengkap
- ✅ Manajemen akun dengan role-based access control
- ✅ Upload file PDF untuk dokumentasi arsip

**Framework: CodeIgniter 4.x** (PHP MVC Framework terbukti)
**Frontend:** Bootstrap 5 + FontAwesome (Modern & Responsive)
**Backend:** CodeIgniter MVC Architecture
**Database:** MySQL (phpMyAdmin compatible)

---

## 📞 KONTAK & BANTUAN

### Jika Butuh Bantuan:
1. Cek file `README.md` ini
2. Lihat dokumentasi CodeIgniter: https://codeigniter.com/userguide
3. Lihat Troubleshooting section di atas
4. Cek error logs di `application/logs/`

---

**Dibuat dengan ❤️ menggunakan CodeIgniter 4.x**
