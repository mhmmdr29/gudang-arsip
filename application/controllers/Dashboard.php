<?php
session_start();
require_once '../config.php';

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    redirect('index.php?page=login');
}

// Logic Dashboard (Stats)
if ($_GET['page'] == 'dashboard') {
    $data['stats'] = [
        'archives' => $pdo->query("SELECT COUNT(*) FROM archives")->fetchColumn(),
        'warehouses' => $pdo->query("SELECT COUNT(*) FROM warehouses")->fetchColumn(),
        'shelves' => $pdo->query("SELECT COUNT(*) FROM shelves")->fetchColumn(),
        'borrowings_active' => $pdo->query("SELECT COUNT(*) FROM borrowings WHERE returned_at IS NULL")->fetchColumn(),
        'borrowings_total' => $pdo->query("SELECT COUNT(*) FROM borrowings")->fetchColumn()
    ];
}

// Logic Archives
if ($_GET['page'] == 'archives' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM archives WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    $_SESSION['success'] = 'Arsip Dihapus';
    redirect('index.php?page=archives');
}
if ($_GET['page'] == 'archives') {
    $data['archives'] = $pdo->query("SELECT archives.*, s.code as shelf_code, w.name as warehouse_name FROM archives a LEFT JOIN shelves s ON a.shelf_id=s.id LEFT JOIN warehouses w ON a.warehouse_id=w.id ORDER BY a.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    $data['warehouses'] = $pdo->query("SELECT * FROM warehouses")->fetchAll(PDO::FETCH_ASSOC);
    $data['shelves'] = $pdo->query("SELECT * FROM shelves")->fetchAll(PDO::FETCH_ASSOC);
}

// Logic Warehouses
if ($_GET['page'] == 'warehouses' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM warehouses WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    $_SESSION['success'] = 'Gudang Dihapus';
    redirect('index.php?page=warehouses');
}
if ($_GET['page'] == 'warehouses') {
    $data['warehouses'] = $pdo->query("SELECT * FROM warehouses")->fetchAll(PDO::FETCH_ASSOC);
}

// Logic Shelves
if ($_GET['page'] == 'shelves' && isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM shelves WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    $_SESSION['success'] = 'Rak Dihapus';
    redirect('index.php?page=shelves');
}
if ($_GET['page'] == 'shelves') {
    $data['shelves'] = $pdo->query("SELECT shelves.*, w.name as warehouse_name FROM shelves s LEFT JOIN warehouses w ON s.warehouse_id=w.id")->fetchAll(PDO::FETCH_ASSOC);
    $data['warehouses'] = $pdo->query("SELECT * FROM warehouses")->fetchAll(PDO::FETCH_ASSOC);
}

// Logic Movements & Borrowings & Accounts (Simple List)
if (in_array($_GET['page'], ['movements','borrowings','accounts'])) {
    $data['archives'] = $pdo->query("SELECT * FROM archives")->fetchAll(PDO::FETCH_ASSOC);
    $data['shelves'] = $pdo->query("SELECT * FROM shelves")->fetchAll(PDO::FETCH_ASSOC);
}

// Load View
$data['page'] = $_GET['page'] ?? 'dashboard';
$data['username'] = $_SESSION['username'] ?? '';
$data['role'] = $_SESSION['role'] ?? '';

view('dashboard/index', $data);
?>
