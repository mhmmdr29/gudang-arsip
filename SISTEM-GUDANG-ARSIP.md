# Sistem Gudang Arsip

Sistem manajemen gudang arsip yang lengkap dengan role-based access control.

## Fitur

### 1. Daftar Arsip
- List Arsip - Melihat seluruh arsip yang tersimpan
- Tambah Arsip - Menambahkan arsip baru dengan informasi lengkap:
  - Nama Arsip
  - Kode Arsip
  - Kode Sub Bagian
  - Tahun Dibuat
  - Masa Retensi (Tahun)
  - Rak
  - Gudang
  - Upload File PDF (opsional)
- Edit Arsip - Mengubah informasi arsip

### 2. Riwayat Pemindahan
- List Riwayat - Melihat seluruh riwayat pemindahan dan peminjaman arsip
- Pindah Arsip - Memindahkan arsip dari satu rak ke rak lain
- Pinjam Arsip - Meminjam arsip untuk pengguna tertentu
  - Nama Arsip
  - Kode Arsip
  - Nama Peminjam
  - Tanggal Pinjam
  - Status (Dipinjam/Dikembalikan)
  - Kembalikan Arsip

### 3. Daftar Gudang
- List Gudang - Melihat seluruh gudang yang tersedia
- Tambah Gudang - Menambahkan gudang baru:
  - Kode Gudang
  - Nama Gudang
  - Lokasi
- Edit Gudang - Mengubah informasi gudang
- Hapus Gudang - Menghapus gudang

### 4. Daftar Rak
- List Rak - Melihat seluruh rak yang tersedia
- Tambah Rak - Menambahkan rak baru:
  - Kode Rak
  - Gudang (diambil dari daftar gudang)
- Edit Rak - Mengubah informasi rak
- Hapus Rak - Menghapus rak

### 5. Daftar Akun
- List Akun - Melihat seluruh akun pengguna
- Tambah Akun - Menambahkan akun baru:
  - Username
  - Email
  - Password
  - Role
- Edit Akun - Mengubah informasi akun
- Hapus Akun - Menghapus akun

## Role & Akses

### Role A: Kassubag KUL (KASSUBAG_KUL)
**Akses Penuh** - Dapat mengakses semua menu dan sub menu:
- Daftar Arsip ✓
- Riwayat Pemindahan ✓
- Daftar Gudang ✓
- Daftar Rak ✓
- Daftar Akun ✓

### Role B: Admin (ADMIN)
**Akses Terbatas** - Dapat mengakses:
- Daftar Arsip ✓
- Riwayat Pemindahan ✓
- Daftar Gudang ✓
- Daftar Rak ✓
- Daftar Akun ✗

### Role C: Staff (STAFF)
**Akses Dasar** - Dapat mengakses:
- Daftar Arsip ✓
- Riwayat Pemindahan ✓
- Daftar Gudang ✗
- Daftar Rak ✗
- Daftar Akun ✗

## Akun Default

Sistem telah disiapkan dengan 3 akun default:

| Username | Password | Role |
|----------|-----------|-------|
| admin | admin123 | Admin |
| kassubag | kassubag123 | Kassubag KUL |
| staff | staff123 | Staff |

## Cara Penggunaan

1. **Login**
   - Buka aplikasi di browser
   - Masukkan username dan password
   - Klik tombol "Masuk"

2. **Navigasi**
   - Gunakan sidebar di sebelah kiri untuk navigasi
   - Pilih menu yang ingin diakses
   - Menu akan muncul sesuai dengan role pengguna

3. **Kelola Arsip**
   - Klik "Tambah Arsip" untuk menambah arsip baru
   - Isi semua field yang diperlukan
   - Upload file PDF jika diperlukan
   - Klik "Tambah Arsip"
   - Edit atau hapus arsip dari daftar

4. **Pindah Arsip**
   - Pilih menu "Riwayat Pemindahan" → "Pindah Arsip"
   - Pilih arsip yang ingin dipindahkan
   - Pilih rak tujuan
   - Klik "Pindahkan Arsip"
   - Riwayat akan tersimpan secara otomatis

5. **Pinjam Arsip**
   - Pilih menu "Riwayat Pemindahan" → "Pinjam Arsip"
   - Pilih arsip yang ingin dipinjam
   - Masukkan nama peminjam
   - Klik "Pinjam Arsip"
   - Klik "Kembalikan" pada daftar riwayat untuk mengembalikan arsip

## Struktur Database

### Account
- id, username, email, password, role, createdAt, updatedAt

### Warehouse
- id, code, name, location, createdAt, updatedAt

### Shelf
- id, code, warehouseId, createdAt, updatedAt

### Archive
- id, name, code, subBagCode, yearCreated, retentionPeriod, shelfId, warehouseId, filePath, createdAt, updatedAt

### MovementHistory
- id, archiveId, archiveName, archiveCode, previousShelf, newShelf, movedAt, movedBy

### BorrowHistory
- id, archiveId, archiveName, archiveCode, borrowerName, borrowedAt, returnedAt

## Teknologi

- **Framework**: Next.js 15 (App Router)
- **Language**: TypeScript
- **Styling**: Tailwind CSS 4
- **UI Components**: shadcn/ui
- **Database**: SQLite dengan Prisma ORM
- **State Management**: Zustand
- **Authentication**: Custom auth system

## Catatan

- Password disimpan dalam bentuk plain text untuk demonstrasi. Di production, gunakan bcrypt atau library sejenis.
- File PDF diupload ke folder `public/uploads`.
- Relasi database sudah dikonfigurasi dengan proper foreign keys dan cascading deletes.
- Role-based access control diimplementasikan di level UI dan API.
