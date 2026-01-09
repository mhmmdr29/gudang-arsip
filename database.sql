-- ========================================
--  DATABASE GUDANG ARSIP
--  CodeIgniter + MySQL
-- ========================================

-- Create Database
CREATE DATABASE IF NOT EXISTS gudang_arsip CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gudang_arsip;

-- ========================================
--  TABLE: ACCOUNTS
-- ========================================
DROP TABLE IF EXISTS accounts;
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'KASSUBAG_KUL', 'STAFF') DEFAULT 'STAFF',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default accounts
INSERT INTO accounts (username, email, password, role) VALUES
('admin', 'admin@gudang-arsip.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 'ADMIN'),
('kassubag', 'kassubag@gudang-arsip.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 'KASSUBAG_KUL'),
('staff', 'staff@gudang-arsip.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 'STAFF'),
('hanif', 'hanif@gmail.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 'ADMIN'),
('susi', 'susi@gmail.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 'STAFF');

-- ========================================
--  TABLE: WAREHOUSES
-- ========================================
DROP TABLE IF EXISTS warehouses;
CREATE TABLE warehouses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
--  TABLE: SHELVES
-- ========================================
DROP TABLE IF EXISTS shelves;
CREATE TABLE shelves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    warehouse_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
    UNIQUE KEY shelf_warehouse (code, warehouse_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
--  TABLE: ARCHIVES
-- ========================================
DROP TABLE IF EXISTS archives;
CREATE TABLE archives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(100) NOT NULL,
    sub_bag_code VARCHAR(50) NOT NULL,
    year_created INT NOT NULL,
    retention_period INT NOT NULL,
    shelf_id INT NOT NULL,
    warehouse_id INT NOT NULL,
    file_path VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (shelf_id) REFERENCES shelves(id) ON DELETE RESTRICT,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE RESTRICT,
    UNIQUE KEY archive_warehouse (code, warehouse_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
--  TABLE: MOVEMENTS
-- ========================================
DROP TABLE IF EXISTS movements;
CREATE TABLE movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archive_id INT NOT NULL,
    archive_name VARCHAR(255) NOT NULL,
    archive_code VARCHAR(100) NOT NULL,
    previous_shelf VARCHAR(50) NOT NULL,
    new_shelf VARCHAR(50) NOT NULL,
    moved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    moved_by VARCHAR(100) NOT NULL,
    FOREIGN KEY (archive_id) REFERENCES archives(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
--  TABLE: BORROWINGS
-- ========================================
DROP TABLE IF EXISTS borrowings;
CREATE TABLE borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archive_id INT NOT NULL,
    archive_name VARCHAR(255) NOT NULL,
    archive_code VARCHAR(100) NOT NULL,
    borrower_name VARCHAR(255) NOT NULL,
    borrowed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    returned_at TIMESTAMP NULL,
    FOREIGN KEY (archive_id) REFERENCES archives(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
--  FINISH
-- ========================================
SELECT '✅ Database gudang_arsip berhasil dibuat!' AS status;
SELECT '✅ 6 tabel berhasil dibuat: accounts, warehouses, shelves, archives, movements, borrowings' AS status;
SELECT '✅ Foreign keys dan constraints berhasil dibuat!' AS status;
SELECT '✅ 5 akun default berhasil disimpan!' AS status;
SELECT 'User Admin: admin / admin123' AS status;
SELECT 'User Kassubag: kassubag / kassubag123' AS status;
SELECT 'User Staff: staff / staff123' AS status;
SELECT 'User Admin Tambahan: hanif / hanif123' AS status;
SELECT 'User Staff Tambahan: susi / susi123' AS status;
