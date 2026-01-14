<?php
session_start();
require 'application/config.php';

// Auto Create Tables
$pdo->exec("CREATE TABLE IF NOT EXISTS accounts(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(100) UNIQUE NOT NULL,email VARCHAR(255) UNIQUE NOT NULL,password VARCHAR(255) NOT NULL,role ENUM('ADMIN','KASSUBAG_KUL','STAFF') DEFAULT 'STAFF',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$pdo->exec("CREATE TABLE IF NOT EXISTS warehouses(id INT AUTO_INCREMENT PRIMARY KEY,code VARCHAR(50) UNIQUE NOT NULL,name VARCHAR(255) NOT NULL,location VARCHAR(255) NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$pdo->exec("CREATE TABLE IF NOT EXISTS shelves(id INT AUTO_INCREMENT PRIMARY KEY,code VARCHAR(50) NOT NULL,warehouse_id INT NOT NULL,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,UNIQUE KEY shelf_warehouse (code, warehouse_id))ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$pdo->exec("CREATE TABLE IF NOT EXISTS archives(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(255) NOT NULL,code VARCHAR(100) NOT NULL,sub_bag_code VARCHAR(50) NOT NULL,year_created INT NOT NULL,retention_period INT NOT NULL,shelf_id INT NOT NULL,warehouse_id INT NOT NULL,file_path VARCHAR(500),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,FOREIGN KEY (shelf_id) REFERENCES shelves(id) ON DELETE RESTRICT,FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE RESTRICT,UNIQUE KEY archive_warehouse (code, warehouse_id))ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$pdo->exec("CREATE TABLE IF NOT EXISTS movements(id INT AUTO_INCREMENT PRIMARY KEY,archive_id INT NOT NULL,archive_name VARCHAR(255) NOT NULL,archive_code VARCHAR(100) NOT NULL,previous_shelf VARCHAR(50) NOT NULL,new_shelf VARCHAR(50) NOT NULL,moved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,moved_by VARCHAR(100) NOT NULL,FOREIGN KEY (archive_id) REFERENCES archives(id) ON DELETE CASCADE)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$pdo->exec("CREATE TABLE IF NOT EXISTS borrowings(id INT AUTO_INCREMENT PRIMARY KEY,archive_id INT NOT NULL,archive_name VARCHAR(255) NOT NULL,archive_code VARCHAR(100) NOT NULL,borrower_name VARCHAR(255) NOT NULL,borrowed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,returned_at TIMESTAMP NULL,FOREIGN KEY (archive_id) REFERENCES archives(id) ON DELETE CASCADE)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Seed Accounts
$chk = $pdo->query("SELECT COUNT(*) FROM accounts")->fetchColumn();
if($chk==0){
    $stmt = $pdo->prepare("INSERT INTO accounts (username,email,password,role) VALUES (?,?,?,?)");
    $stmt->execute(['admin','admin@ga.com','admin123','ADMIN']);
    $stmt->execute(['kassubag','kas@ga.com','kassubag123','KASSUBAG_KUL']);
    $stmt->execute(['staff','staff@ga.com','staff123','STAFF']);
}

// Router
$page = $_GET['page'] ?? 'login';
if(in_array($page,['dashboard','archives','warehouses','shelves','movements','borrowings','accounts'])){
    if(!isset($_SESSION['logged_in'])){ $page = 'login'; }
}

if($page=='login'){ require 'application/controllers/Auth.php'; }
else { require 'application/controllers/Dashboard.php'; }
?>
