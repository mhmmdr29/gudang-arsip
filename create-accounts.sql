-- Script untuk membuat akun default di database
-- Cara pakai: sqlite3 gudang-arsip.db < create-accounts.sql

-- Periksa tabel Account
SELECT 'Tabel Account:' AS Info;

-- Buat akun default
INSERT INTO Account (id, username, email, password, role, createdAt, updatedAt)
VALUES
-- Admin
(
  'admin-id-1',
  'admin',
  'admin@gudang-arsip.com',
  'admin123',
  'ADMIN',
  datetime('now'),
  datetime('now')
),
-- Kassubag
(
  'kassubag-id-2',
  'kassubag',
  'kassubag@gudang-arsip.com',
  'kassubag123',
  'KASSUBAG_KUL',
  datetime('now'),
  datetime('now')
),
-- Staff
(
  'staff-id-3',
  'staff',
  'staff@gudang-arsip.com',
  'staff123',
  'STAFF',
  datetime('now'),
  datetime('now')
);

-- Tampilkan hasil
SELECT 'Akun default berhasil dibuat!' AS Info;
SELECT 'Username: admin / Password: admin123' AS Info;
SELECT 'Username: kassubag / Password: kassubag123' AS Info;
SELECT 'Username: staff / Password: staff123' AS Info;

-- Tampilkan semua akun
SELECT id, username, email, role, createdAt, updatedAt
FROM Account;
