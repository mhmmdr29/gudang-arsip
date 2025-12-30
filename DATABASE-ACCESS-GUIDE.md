# PANDUAN AKSES DATABASE LANGSUNG
## Cara Akses Database SQLite Langsung di Laptop

### METODE 1: SQLite Browser (GUI - Rekomendasi)

#### 1. Download SQLite Browser
- Windows: https://sqlitebrowser.org/dl/
- Mac: https://sqlitebrowser.org/dl/SQLiteBrowserv3.dmg
- Linux: https://sqlitebrowser.org/dl/SQLiteBrowserv3.tar.gz

#### 2. Buka Database
1. Jalankan SQLite Browser
2. Klik menu **File → Open Database**
3. Navigate ke folder project
4. Pilih file: **`db/custom.db`**

#### 3. Cek Tabel Account
1. Klik tab **"Browse Data"**
2. Di dropdown "Table", pilih: **Account**
3. Anda akan melihat semua akun:
   - admin / admin123
   - kassubag / kassubag123
   - staff / staff123

#### 4. Query SQL Manual
1. Klik tab **"Execute SQL"**
2. Ketik query SQL:
   ```sql
   SELECT * FROM Account;
   ```
3. Klik tombol **"Execute"** (atau tekan F5)

#### 5. Tambah Akun Baru (Opsional)
1. Di tab **"Browse Data"**, pilih table **Account**
2. Klik tombol **"New Record"** (tombol + di atas)
3. Isi kolom:
   - username: `[nama user baru]`
   - email: `[email user baru]`
   - password: `[password user baru]`
   - role: `ADMIN` atau `KASSUBAG_KUL` atau `STAFF`
4. Klik **"Write Changes"**

#### 6. Reset Password Admin (Jika Lupa)
1. Buka table **Account**
2. Cari baris dengan username: **admin**
3. Klik kolom **password**
4. Ganti dengan password baru
5. Klik **"Write Changes"**

---

### METODE 2: Command Line (sqlite3)

#### 1. Download sqlite3 CLI
- Windows:
  - Download: https://www.sqlite.org/download.html
  - Cari bagian **"sqlite-tools"** atau **"sqlite3.exe"**
  - Download sqlite3.exe

#### 2. Buka Database
Buka Command Prompt/PowerShell dan ketik:
```bash
cd path\ke\gudang-arsip\db
sqlite3 custom.db
```

#### 3. Query Akun
```sql
SELECT * FROM Account;
```
Output:
```
id|username|email|password|role|createdAt|updatedAt
admin-id-1|admin|admin@gudang-arsip.com|admin123|ADMIN|2024-12-29 00:00:00|2024-12-29 00:00:00
kassubag-id-2|kassubag|kassubag@gudang-arsip.com|kassubag123|KASSUBAG_KUL|2024-12-29 00:00:00|2024-12-29 00:00:00
staff-id-3|staff|staff@gudang-arsip.com|staff123|STAFF|2024-12-29 00:00:00|2024-12-29 00:00:00
```

#### 4. Update Password
```sql
UPDATE Account
SET password = 'password-baru'
WHERE username = 'admin';
```

#### 5. Tambah Akun Baru
```sql
INSERT INTO Account (id, username, email, password, role, createdAt, updatedAt)
VALUES (
  'user-baru-id',
  'userbaru',
  'userbaru@email.com',
  'password123',
  'ADMIN',
  datetime('now'),
  datetime('now')
);
```

#### 6. Cek Semua Tabel
```sql
.tables
```
Output:
```
Account          Archive          BorrowHistory      MovementHistory
Shelf            Warehouse
```

#### 7. Cek Schema Tabel
```sql
.schema Account
```

#### 8. Keluar dari sqlite3
```sql
.quit
```
ATAU tekan: **Ctrl + D**

---

### METODE 3: DB Browser for SQLite (GUI Alternatif)

#### 1. Download DB Browser for SQLite
- Windows: https://sqlitebrowser.org/dl/DB.Browser.for.SQLite-3.12.2-win64.zip
- Mac: https://sqlitebrowser.org/dl/DB.Browser.for.SQLite-3.12.2-macosx.dmg

#### 2. Buka Database
1. Jalankan aplikasi DB Browser
2. Klik tombol **"Open Database"**
3. Navigate ke: **`path\ke\gudang-arsip\db\custom.db`**
4. Klik **"Open"**

#### 3. Browse Data
1. Di tab **"Database Structure"**, Anda akan lihat semua tabel
2. Di tab **"Browse Data"**, pilih table **Account**
3. Anda bisa edit, tambah, atau hapus data

#### 4. Edit Password
1. Pilih table **Account**
2. Double-click pada baris **admin**
3. Ganti kolom **password**
4. Klik tombol **"Apply"** atau **"Save"**

---

### METODE 4: Script SQL Otomatis

#### Cara Pakai Script create-accounts.sql

1. Download script: **create-accounts.sql** sudah ada di folder project
2. Buka Command Prompt/PowerShell
3. Masuk ke folder project:
   ```bash
   cd path\ke\gudang-arsip
   ```
4. Jalankan script dengan sqlite3:
   ```bash
   sqlite3 db/custom.db < create-accounts.sql
   ```
5. Script akan otomatis membuat 3 akun default

#### Isi Script create-accounts.sql:
```sql
-- Buat akun default: admin, kassubag, staff

INSERT INTO Account (id, username, email, password, role, createdAt, updatedAt)
VALUES
(
  'admin-default',
  'admin',
  'admin@gudang-arsip.com',
  'admin123',
  'ADMIN',
  datetime('now'),
  datetime('now')
),
(
  'kassubag-default',
  'kassubag',
  'kassubag@gudang-arsip.com',
  'kassubag123',
  'KASSUBAG_KUL',
  datetime('now'),
  datetime('now')
),
(
  'staff-default',
  'staff',
  'staff@gudang-arsip.com',
  'staff123',
  'STAFF',
  datetime('now'),
  datetime('now')
);

-- Tampilkan hasil
SELECT 'Akun default berhasil dibuat!' AS message;
SELECT * FROM Account;
```

---

## 📥 DOWNLOAD ZIP TERBARU DARI GITHUB

### Langkah 1: Download ZIP
1. Buka: https://github.com/mhmmdr29/gudang-arsip/actions
2. Klik workflow: **"Build and Create ZIP"**
3. Tunggu status: **✅ Completed**
4. Scroll ke: **"Artifacts"**
5. Klik: **"gudang-arsip-zip"**
6. Download ZIP terbaru (sudah ada perbaikan schema)

### Langkah 2: Ekstrak ZIP
1. Buka folder **Downloads**
2. Klik kanan: **"gudang-arsip-zip.zip"**
3. Pilih: **"Extract All..."**
4. Ekstrak ke folder project lama (timpa file lama)

### Langkah 3: Buat Database
1. Buka Command Prompt/PowerShell di folder project
2. Jalankan:
   ```bash
   npx prisma generate
   ```
3. Output sukses:
   ```
   ✔ Generated Prisma Client
   ```

### Langkah 4: Create/Check Database File
1. Pastikan folder `db/` ada
2. Buka file: **`db/custom.db`** dengan salah satu metode di atas

---

## 🔍 QUERY SQL UNTUK DEBUGGING

### Cek Semua Akun
```sql
SELECT id, username, email, role, password
FROM Account;
```

### Cek User Admin
```sql
SELECT * FROM Account
WHERE username = 'admin';
```

### Reset Password Admin
```sql
UPDATE Account
SET password = 'admin123'
WHERE username = 'admin';
```

### Cek Role
```sql
SELECT username, role
FROM Account;
```

---

## ❓ PERTANYAAN UMUM

### Q: Mengapa API login error "Server error: API mengembalikan response yang tidak valid"?
**A:**
- Typo di schema.prisma: **"datasource"** ditulis **"datasource"** (tanpa 'a' di data)
- Prisma tidak bisa membaca schema dengan benar
- API mengembalikan HTML error page bukan JSON
- Masalah ini SUDAH diperbaiki di GitHub

### Q: Dimana lokasi database file?
**A:**
- Lokasi: **`path\ke\gudang-arsip\db\custom.db`**
- File ini dibuat otomatis saat pertama kali aplikasi berjalan

### Q: Apakah perlu menjalankan `prisma db push`?
**A:**
- SQLite tidak perlu migration
- Hanya perlu: **`npx prisma generate`** (generate Prisma Client)
- Database dan tabel dibuat otomatis saat pertama kali aplikasi berjalan

### Q: Bagaimana cara reset semua data?
**A:**
- Hapus file: **`db/custom.db`**
- Restart aplikasi dengan: **`npm run dev`**
- Database baru akan dibuat otomatis

---

## ✅ CHECKLIST SELESAI

- [ ] **Download ZIP** terbaru dari GitHub Actions
- [ ] **Ekstrak ZIP** dan timpa file lama
- [ ] **Buka database** dengan SQLite Browser/DB Browser/sqlite3
- [ ] **Cek table Account** (apakah ada data?)
- [ ] **Jalankan script** `create-accounts.sql` (opsional)
- [ ] **Login dengan akun** default di aplikasi

---

## 🎯 AKUN DEFAULT

| Username | Password | Role |
|----------|----------|-------|
| **admin** | admin123 | ADMIN |
| **kassubag** | kassubag123 | KASSUBAG_KUL |
| **staff** | staff123 | STAFF |

---

**Jika database kosong atau belum ada data, coba jalankan script SQL di atas!** 🚀
