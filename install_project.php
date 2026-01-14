<?php
/**
 * INSTALLER FINAL - MEMBUAT SEMUA FILE MVC OTOMATIS
 * JANGAN DIHAPUS SETELAH DIBUKA
 */

// --- FOLDER CREATION ---
$folders = [
    'application',
    'application/config',
    'application/controllers',
    'application/models',
    'application/views',
    'application/views/auth',
    'application/views/dashboard',
    'application/assets',
    'application/assets/css',
    'application/assets/js',
    'application/assets/images',
    'application/assets/uploads',
    'application/assets/uploads/archives'
];

foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
        echo "<div style='color:green;'>✅ Folder created: $folder</div>";
    } else {
        echo "<div style='color:blue;'>ℹ Folder exists: $folder</div>";
    }
}
echo "<hr>";

// --- FILE 1: application/config.php (REDIRECT FIX) ---
$config_php_content = '<?php
/**
 * KONFIGURASI DATABASE
 * FIX REDIRECT (RELATIVE URL - PALING JITU)
 */

// Define Paths
define(\'BASEPATH\', __DIR__ . \'/\');
define(\'APPPATH\', __DIR__ . \'/\');

/**
 * AUTO-DETECT BASE URL (MENGGUNAKAN PHP_SELF)
 */
function get_base_url() {
    // Ambil protocol
    $protocol = (isset($_SERVER[\'HTTPS\']) && $_SERVER[\'HTTPS\'] === \'on\') ? \'https\' : \'http\';
    // Ambil host
    $host = $_SERVER[\'HTTP_HOST\'];
    
    // Gunakan PHP_SELF (Contoh: /gudang-arsip/index.php)
    $script_path = dirname($_SERVER[\'PHP_SELF\']);
    
    // Bersihkan path (ganti backslash dengan forward slash)
    $script_path = str_replace(\'\\\\\', \'/\', $script_path);
    
    // Pastikan tidak ada slash ganda dan slash di akhir/belakang
    $script_path = rtrim($script_path, \'/\');
    
    // Jika di root htdocs, gunakan string kosong + slash
    if ($script_path) {
        $script_path = \'/\' . $script_path . \'/\';
    } else {
        $script_path = \'/\';
    }
    
    // Base URL akhir
    $base_url = $protocol . \'://\' . $host . $script_path;
    
    return $base_url;
}

// Define Base URL
define(\'BASE_URL\', get_base_url());

/**
 * FUNGSI URL (SIMPAN)
 */
function base_url($path = \'\') {
    return BASE_URL . ltrim($path, \'/\');
}

function site_url($uri = \'\') {
    return base_url($uri);
}

/**
 * KONEKSI DATABASE
 */
define(\'DB_HOST\', \'localhost\');
define(\'DB_USER\', \'root\');
define(\'DB_PASS\', \'\');
define(\'DB_NAME\', \'gudang_arsip\');

try {
    global $pdo;
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

/**
 * FUNGSI VIEW
 */
function view($view, $data = []) {
    extract($data);
    require APPPATH . \'views/\' . $view . \'.php\';
}

/**
 * FUNGSI REDIRECT (FIX: Menggunakan base_url())
 */
function redirect($url) {
    header("Location: " . site_url($url));
    exit;
}

/**
 * FUNGSI ESCAPE HTML
 */
function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, \'UTF-8\');
}
?>';

file_put_contents('application/config.php', $config_php_content);
echo "<div style='color:green;'>✅ File created: application/config.php</div>";

// --- FILE 2: models/Database_model.php (Generic DB Ops) ---
$db_model_php_content = '<?php
class Database_model {
    private $pdo;
    public function __construct() {
        require_once \'../application/config.php\';
        global $pdo;
        $this->pdo = $pdo;
    }
    public function count($table) {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM $table");
        return $stmt->fetchColumn();
    }
    public function get_all($table) {
        $stmt = $this->pdo->query("SELECT * FROM $table ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_by_id($table, $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->execute([\':id\' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($table, $data) {
        $keys = array_keys($data);
        $placeholders = implode(\', \', array_fill(0, count($keys), \'?\'));
        $sql = "INSERT INTO $table (" . implode(\', \', $keys) . ") VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }
    public function delete($table, $id) {
        $stmt = $this->pdo->prepare("DELETE FROM $table WHERE id = :id");
        return $stmt->execute([\':id\' => $id]);
    }
}
?>';

file_put_contents('models/Database_model.php', $db_model_php_content);
echo "<div style='color:green;'>✅ File created: models/Database_model.php</div>";

// --- FILE 3: application/views/auth/login.php (Login Page) ---
$login_php_content = '<?php
/**
 * LOGIN PAGE
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Gudang Arsip</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: \'Segoe UI\', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { background: white; border-radius: 10px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); padding: 40px; max-width: 400px; width: 100%; }
        .form-label { font-weight: 600; color: #333; }
        .btn-primary { background: #667eea; border-color: #667eea; }
        .btn-primary:hover { background: #764ba2; border-color: #764ba2; }
        .alert { border-radius: 8px; margin-bottom: 20px; }
        .default-accounts { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; margin-top: 20px; }
        .default-accounts h6 { color: #495057; margin-bottom: 10px; }
        .default-accounts p { margin: 5px 0; font-size: 14px; color: #6c757d; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="text-center mb-4">
        <h2><i class="fas fa-warehouse text-primary"></i> Sistem Gudang Arsip</h2>
        <p class="text-muted">Silakan login (Database & Akun Sudah Di-Otomatis)</p>
    </div>
    
    <?php if (isset($_SESSION[\'error_msg\'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error!</strong> <?= $_SESSION[\'error_msg\'] ?>
            <?php unset($_SESSION[\'error_msg\']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="../index.php?page=login">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="username" placeholder="admin" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" placeholder="admin123" required>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" name="login" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
        </div>
    </form>

    <div class="default-accounts">
        <h6><i class="fas fa-info-circle"></i> Akun Default untuk Tes:</h6>
        <p><strong>Admin:</strong> admin / admin123</p>
        <p><strong>Kassubag:</strong> kassubag / kassubag123</p>
        <p><strong>Staff:</strong> staff / staff123</p>
        <p><strong>Admin (Tambahan):</strong> hanif / hanif123</p>
        <p><strong>Staff (Tambahan):</strong> susi / susi123</p>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';

file_put_contents('application/views/auth/login.php', $login_php_content);
echo "<div style='color:green;'>✅ File created: application/views/auth/login.php</div>";

// --- FILE 4: application/views/dashboard/index.php (Layout + Content Lengkap - FIX 9 POIN) ---
$dashboard_php_content = '<?php
/**
 * DASHBOARD LAYOUT (MAIN)
 * SIDEBAR, NAVBAR, ALERTS, CONTENT SWITCHER
 * FIX: MENGGUNAKAN DATA DARI ROUTER ($data[\'stats\'])
 */
require_once \'../../application/config.php\';
session_start();

// Get Page Variable dari Router ($data[\'page\'])
// FIX: Gunakan data dari router, jangan buat $page baru
$page = $data[\'page\'] ?? \'dashboard\'; 

// Get User Info dari Router
$username = $data[\'username\'] ?? \'\';
$role = $data[\'role\'] ?? \'\';

// FIX: Definisikan Title agar tidak Error
$title = ucfirst($page);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Sistem Gudang Arsip</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: \'Segoe UI\', sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }
        .sidebar { height: 100vh; position: fixed; top: 0; left: 0; width: 250px; background-color: #212529; padding-top: 20px; z-index: 1000; overflow-y: auto; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; margin-bottom: 5px; border-radius: 5px; display: block; text-decoration: none; transition: all 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #0d6efd; color: white; }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .navbar { background-color: #343a40; color: white; padding: 15px 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card { background-color: white; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 10px; margin-bottom: 20px; }
        .card .table { background-color: white; margin-bottom: 0; }
        .card .table th { background-color: #0d6efd; color: white; }
        .card .table tbody tr:hover { background-color: #f8f9fa; }
        .stat-card { background: white; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 10px; padding: 20px; text-align: center; margin-bottom: 20px; }
        .stat-card h3 { margin: 0; font-size: 2.5rem; color: #0d6efd; }
        .stat-card small { color: #6c757d; }
        .alert { border-radius: 8px; margin-bottom: 20px; }
        .hidden { display: none; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white mb-4">
            <i class="fas fa-warehouse"></i> Gudang Arsip
        </h4>
        <nav class="nav flex-column">
            <a class="nav-link <?= $page == \'dashboard\' ? \'active\' : \'\' ?>" href="../../index.php?page=dashboard"> <!-- FIX: Relative URL -->
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link <?= $page == \'archives\' ? \'active\' : \'\' ?>" href="../../index.php?page=archives"> <!-- FIX: Relative URL -->
                <i class="fas fa-box"></i> Arsip
            </a>
            <a class="nav-link <?= $page == \'warehouses\' ? \'active\' : \'\' ?>" href="../../index.php?page=warehouses"> <!-- FIX: Relative URL -->
                <i class="fas fa-building"></i> Gudang
            </a>
            <a class="nav-link <?= $page == \'shelves\' ? \'active\' : \'\' ?>" href="../../index.php?page=shelves"> <!-- FIX: Relative URL -->
                <i class="fas fa-layer-group"></i> Rak
            </a>
            <a class="nav-link <?= $page == \'movements\' ? \'active\' : \'\' ?>" href="../../index.php?page=movements"> <!-- FIX: Relative URL -->
                <i class="fas fa-exchange-alt"></i> Pindah
            </a>
            <a class="nav-link <?= $page == \'borrowings\' ? \'active\' : \'\' ?>" href="../../index.php?page=borrowings"> <!-- FIX: Relative URL -->
                <i class="fas fa-hand-holding"></i> Pinjam
            </a>
            <hr class="text-white my-3">
            <?php if (isset($role) && in_array($role, [\'ADMIN\', \'KASSUBAG_KUL\'])): ?>
            <a class="nav-link <?= $page == \'accounts\' ? \'active\' : \'\' ?>" href="../../index.php?page=accounts"> <!-- FIX: Relative URL -->
                <i class="fas fa-users"></i> Akun
            </a>
            <?php endif; ?>
            <a class="nav-link text-danger" href="../../index.php?action=logout"> <!-- FIX: Relative URL -->
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <div class="navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i>
                        Selamat datang, <?= esc($username) ?>!
                    </h5>
                    <small class="text-white">
                        Role: <?= esc($role) ?>
                    </small>
                </div>
                <a href="../../index.php?action=logout" class="btn btn-outline-light btn-sm"> <!-- FIX: Relative URL -->
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION[\'success\'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> <?= $_SESSION[\'success\'] ?>
                <?php unset($_SESSION[\'success\']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION[\'error\'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?= $_SESSION[\'error\'] ?>
                <?php unset($_SESSION[\'error\']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Content Switcher -->
        <?php
        // Cek apakah ada data stats dari router
        if (isset($data[\'stats\'])) {
            $stats_archives = $data[\'stats\'][\'archives\'];
            $stats_warehouses = $data[\'stats\'][\'warehouses\'];
            $stats_shelves = $data[\'stats\'][\'shelves\'];
            $stats_borrowings_active = $data[\'stats\'][\'borrowings_active\'];
            $stats_borrowings_total = $data[\'stats\'][\'borrowings_total\'];
        }

        switch ($page) {
            case \'dashboard\':
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-tachometer-alt"></i> Dashboard</h5>
                    </div>
                    <div class="card-body">
                        <p>Selamat datang di Sistem Gudang Arsip!</p>
                        <p>Gunakan menu di sidebar untuk mengakses semua fitur.</p>
                        <div class="row">
                            <?php if (isset($data[\'stats\'])): ?>
                                <div class="col-md-3 mb-4">
                                    <div class="stat-card">
                                        <h3><?= $stats_archives ?></h3>
                                        <small>Arsip</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="stat-card">
                                        <h3><?= $stats_warehouses ?></h3>
                                        <small>Gudang</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="stat-card">
                                        <h3><?= $stats_shelves ?></h3>
                                        <small>Rak</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="stat-card">
                                        <h3><?= $stats_borrowings_active ?></h3>
                                        <small>Peminjaman Aktif</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p><strong>Total Peminjaman: <?= isset($data[\'stats\']) ? $stats_borrowings_total : 0 ?></strong></p>
                    </div>
                </div>
                <?php
                break;

            case \'archives\':
                ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-box"></i> Manajemen Arsip</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createArchiveModal">
                            <i class="fas fa-plus me-1"></i> Tambah Arsip
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th>Sub Bag</th>
                                    <th>Tahun</th>
                                    <th>Retensi</th>
                                    <th>Gudang</th>
                                    <th>Rak</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data[\'archives\']) && count($data[\'archives\']) > 0): ?>
                                    <?php foreach ($data[\'archives\'] as $archive): ?>
                                        <tr>
                                            <td><?= esc($archive[\'name\']) ?></td>
                                            <td><?= esc($archive[\'code\']) ?></td>
                                            <td><?= esc($archive[\'sub_bag_code\']) ?></td>
                                            <td><?= esc($archive[\'year_created\']) ?></td>
                                            <td><?= esc($archive[\'retention_period\']) ?></td>
                                            <td><?= esc($archive[\'warehouse_name\'] ?? \'-\') ?></td>
                                            <td><?= esc($archive[\'shelf_code\'] ?? \'-\') ?></td>
                                            <td>
                                                <a href="../../index.php?page=archives&action=delete&id=<?= $archive[\'id\'] ?>" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus arsip ini?\')">
                                                        <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="9" class="text-center">Belum ada data arsip.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create Archive Modal -->
                <div class="modal fade" id="createArchiveModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Arsip</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="../../index.php?page=archives&action=create" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Arsip</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Arsip</label>
                                        <input type="text" class="form-control" name="code" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kode Sub Bag</label>
                                        <input type="text" class="form-control" name="sub_bag_code" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tahun Dibuat</label>
                                            <input type="number" class="form-control" name="year_created" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Masa Retensi (Tahun)</label>
                                            <input type="number" class="form-control" name="retention_period" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Gudang</label>
                                        <select class="form-select" name="warehouse_id" required>
                                            <option value="">Pilih Gudang...</option>
                                            <?php if (isset($data[\'warehouses\'])): ?>
                                                <?php foreach ($data[\'warehouses\'] as $warehouse): ?>
                                                    <option value="<?= $warehouse[\'id\'] ?>"><?= esc($warehouse[\'name\']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rak</label>
                                        <select class="form-select" name="shelf_id" required>
                                            <option value="">Pilih Rak...</option>
                                            <?php if (isset($data[\'shelves\'])): ?>
                                                <?php foreach ($data[\'shelves\'] as $shelf): ?>
                                                    <option value="<?= $shelf[\'id\'] ?>"><?= esc($shelf[\'code\']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">File PDF (Opsional)</label>
                                        <input type="file" class="form-control" name="file" accept="application/pdf">
                                        <small class="text-muted">Maksimum 5MB. Hanya PDF.</small>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="create_archive" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case \'warehouses\':
                ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-building"></i> Manajemen Gudang</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">
                            <i class="fas fa-plus me-1"></i> Tambah Gudang
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data[\'warehouses\']) && count($data[\'warehouses\']) > 0): ?>
                                    <?php foreach ($data[\'warehouses\'] as $warehouse): ?>
                                        <tr>
                                            <td><?= esc($warehouse[\'code\']) ?></td>
                                            <td><?= esc($warehouse[\'name\']) ?></td>
                                            <td><?= esc($warehouse[\'location\']) ?></td>
                                            <td>
                                                <a href="../../index.php?page=warehouses&action=delete&id=<?= $warehouse[\'id\'] ?>" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus gudang ini?\')">
                                                            <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center">Belum ada data gudang.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create Warehouse Modal -->
                <div class="modal fade" id="createWarehouseModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Gudang</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="../../index.php?page=warehouses&action=create">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Gudang</label>
                                        <input type="text" class="form-control" name="code" required>
                                        <small class="text-muted">Kode unik (misal: GDG-01)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Gudang</label>
                                        <input type="text" class="form-control" name="name" required>
                                        <small class="text-muted">Nama gudang (misal: Gudang Utama)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" class="form-control" name="location" required>
                                        <small class="text-muted">Lokasi fisik (misal: Lantai 1)</small>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="create_warehouse" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case \'shelves\':
                ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-layer-group"></i> Manajemen Rak</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createShelfModal">
                            <i class="fas fa-plus me-1"></i> Tambah Rak
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode Rak</th>
                                    <th>Gudang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($data[\'shelves\']) && count($data[\'shelves\']) > 0): ?>
                                    <?php foreach ($data[\'shelves\'] as $shelf): ?>
                                        <tr>
                                            <td><?= esc($shelf[\'code\']) ?></td>
                                            <td><?= esc($shelf[\'warehouse_name\']) ?></td>
                                            <td>
                                                <a href="../../index.php?page=shelves&action=delete&id=<?= $shelf[\'id\'] ?>" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus rak ini?\')">
                                                            <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center">Belum ada data rak.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Create Shelf Modal -->
                <div class="modal fade" id="createShelfModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Rak</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="../../index.php?page=shelves&action=create">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Rak</label>
                                        <input type="text" class="form-control" name="code" required>
                                        <small class="text-muted">Kode rak (misal: R-01)</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Pilih Gudang</label>
                                        <select class="form-select" name="warehouse_id" required>
                                            <option value="">Pilih Gudang...</option>
                                            <?php if (isset($data[\'warehouses\'])): ?>
                                                <?php foreach ($data[\'warehouses\'] as $warehouse): ?>
                                                    <option value="<?= $warehouse[\'id\'] ?>"><?= esc($warehouse[\'name\']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" name="create_shelf" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case \'movements\':
            case \'borrowings\':
            case \'accounts\':
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5><?= $title ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="alert alert-info">Halaman ini masih dalam pengembangan.</p>
                    </div>
                </div>
                <?php
                break;

            default:
                ?>
                <div class="card">
                    <div class="card-header">
                        <h5>404 Not Found</h5>
                    </div>
                    <div class="card-body">
                        <p>Halaman tidak ditemukan: <strong><?= esc($page) ?></strong></p>
                    </div>
                </div>
                <?php
                break;
        }
        ?>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';

file_put_contents('application/views/dashboard/index.php', $dashboard_php_content);
echo "<div style='color:green;'>✅ File created: application/views/dashboard/index.php</div>";

// --- FILE 5: index.php (Router + CRUD Logic + Auto-Create DB & Tables) ---
$index_php_content = '<?php
/**
 * ROUTER FINAL (INDEX.PHP)
 * AUTO-CREATE DB & TABLES
 * FIX REDIRECT & LOGIC CRUD
 */

session_start();

// Load Config
require_once \'application/config.php\';

// --- AUTO-CREATE TABLES (Jika belum ada) ---
// Table Accounts
$pdo->exec("CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM(\'ADMIN\', \'KASSUBAG_KUL\', \'STAFF\') DEFAULT \'STAFF\',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Table Warehouses
$pdo->exec("CREATE TABLE IF NOT EXISTS warehouses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Table Shelves
$pdo->exec("CREATE TABLE IF NOT EXISTS shelves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    warehouse_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
    UNIQUE KEY shelf_warehouse (code, warehouse_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Table Archives
$pdo->exec("CREATE TABLE IF NOT EXISTS archives (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Table Movements
$pdo->exec("CREATE TABLE IF NOT EXISTS movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archive_id INT NOT NULL,
    archive_name VARCHAR(255) NOT NULL,
    archive_code VARCHAR(100) NOT NULL,
    previous_shelf VARCHAR(50) NOT NULL,
    new_shelf VARCHAR(50) NOT NULL,
    moved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    moved_by VARCHAR(100) NOT NULL,
    FOREIGN KEY (archive_id) REFERENCES archives(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Table Borrowings
$pdo->exec("CREATE TABLE IF NOT EXISTS borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    archive_id INT NOT NULL,
    archive_name VARCHAR(255) NOT NULL,
    archive_code VARCHAR(100) NOT NULL,
    borrower_name VARCHAR(255) NOT NULL,
    borrowed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    returned_at TIMESTAMP NULL,
    FOREIGN KEY (archive_id) REFERENCES archives(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// --- AUTO-SEED ACCOUNTS (Jika kosong) ---
$check_account = $pdo->query("SELECT COUNT(*) FROM accounts");
$count_account = $check_account->fetchColumn();

if ($count_account == 0) {
    $stmt = $pdo->prepare("INSERT INTO accounts (username, email, password, role, created_at, updated_at) VALUES (:username, :email, :password, :role, NOW(), NOW())");

    $accounts = [
        [\'admin\', \'admin@gudang-arsip.com\', \'admin123\', \'ADMIN\'],
        [\'kassubag\', \'kassubag@gudang-arsip.com\', \'kassubag123\', \'KASSUBAG_KUL\'],
        [\'staff\', \'staff@gudang-arsip.com\', \'staff123\', \'STAFF\'],
        [\'hanif\', \'hanif@gmail.com\', \'hanif123\', \'ADMIN\'],
        [\'susi\', \'susi@gmail.com\', \'susi123\', \'STAFF\']
    ];

    foreach ($accounts as $account) {
        $stmt->execute($account);
    }
}

// --- LOGOUT LOGIC ---
if (isset($_GET[\'action\']) && $_GET[\'action\'] == \'logout\') {
    session_unset();
    session_destroy();
    redirect(\'index.php?page=login\'); // FIX: Relative URL
    exit;
}

// --- ROUTER SIMPLE ---
$page = isset($_GET[\'page\']) ? $_GET[\'page\'] : \'login\';
$action = isset($_GET[\'action\']) ? $_GET[\'action\'] : \'index\';

// Protected Pages Check
$protected_pages = [\'dashboard\', \'archives\', \'warehouses\', \'shelves\', \'movements\', \'borrowings\', \'accounts\'];

if (in_array($page, $protected_pages)) {
    if (!isset($_SESSION[\'logged_in\']) || $_SESSION[\'logged_in\'] !== true) {
        redirect(\'index.php?page=login\'); // Redirect to login if not logged in
    }
}

// --- LOGIC: LOGIN ---
if (isset($_POST[\'login\'])) {
    $username = $_POST[\'username\'];
    $password = $_POST[\'password\'];

    // Cek Login
    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :username AND password = :password");
    $stmt->execute([\':username\' => $username, \':password\' => $password]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($account) {
        $_SESSION[\'logged_in\'] = true;
        $_SESSION[\'user_id\'] = $account[\'id\'];
        $_SESSION[\'username\'] = $account[\'username\'];
        $_SESSION[\'role\'] = $account[\'role\'];
        redirect(\'index.php?page=dashboard\'); // FIX: Relative URL
        exit;
    } else {
        $_SESSION[\'error_msg\'] = "Username atau password salah";
    }
}

// --- LOGIC: CRUD POST (SIMPLE - DI INDEX.PHP AGAR MUDAH) ---

// Create Archive
if ($page == \'archives\' && $action == \'create\' && isset($_POST[\'create_archive\'])) {
    $name = $_POST[\'name\'];
    $code = $_POST[\'code\'];
    $sub_bag_code = $_POST[\'sub_bag_code\'];
    $year_created = $_POST[\'year_created\'];
    $retention_period = $_POST[\'retention_period\'];
    $shelf_id = $_POST[\'shelf_id\'];
    $warehouse_id = $_POST[\'warehouse_id\'];
    $file_path = NULL;

    // Handle file upload
    if (isset($_FILES[\'file\']) && $_FILES[\'file\'][\'error\'] == UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES[\'file\'][\'name\'], PATHINFO_EXTENSION);
        $filename = time() . \'_\' . basename($_FILES[\'file\'][\'name\'], \'.\' . $ext);
        $target_dir = \'application/assets/uploads/archives/\';

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        move_uploaded_file($_FILES[\'file\'][\'tmp_name\'], $target_dir . $filename);
        $file_path = \'application/assets/uploads/archives/\' . $filename;
    }

    $stmt = $pdo->prepare("INSERT INTO archives (name, code, sub_bag_code, year_created, retention_period, shelf_id, warehouse_id, file_path, created_at, updated_at) VALUES (:name, :code, :sub_bag_code, :year_created, :retention_period, :shelf_id, :warehouse_id, :file_path, NOW(), NOW())");
    $stmt->execute([\':name\' => $name, \':code\' => $code, \':sub_bag_code\' => $sub_bag_code, \':year_created\' => $year_created, \':retention_period\' => $retention_period, \':shelf_id\' => $shelf_id, \':warehouse_id\' => $warehouse_id, \':file_path\' => $file_path]);

    $_SESSION[\'success\'] = \'Arsip berhasil ditambahkan\';
    redirect(\'index.php?page=archives\'); // FIX: Relative URL
    exit;
}

// Delete Archive
if ($page == \'archives\' && $action == \'delete\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];
    
    // Get archive to delete file
    $stmt = $pdo->prepare("SELECT * FROM archives WHERE id = :id");
    $stmt->execute([\':id\' => $id]);
    $archive = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($archive && !empty($archive[\'file_path\'])) {
        $filepath = $archive[\'file_path\'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->delete(\'archives\', $id)) {
        $_SESSION[\'success\'] = \'Arsip berhasil dihapus\';
        redirect(\'index.php?page=archives\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menghapus arsip\';
    }
}

// Create Warehouse
if ($page == \'warehouses\' && $action == \'create\' && isset($_POST[\'create_warehouse\'])) {
    $code = $_POST[\'code\'];
    $name = $_POST[\'name\'];
    $location = $_POST[\'location\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->create(\'warehouses\', [
            \'code\' => $code,
            \'name\' => $name,
            \'location\' => $location
        ])) {
        $_SESSION[\'success\'] = \'Gudang berhasil ditambahkan\';
        redirect(\'index.php?page=warehouses\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menambah gudang\';
    }
}

// Delete Warehouse
if ($page == \'warehouses\' && $action == \'delete\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->delete(\'warehouses\', $id)) {
        $_SESSION[\'success\'] = \'Gudang berhasil dihapus\';
        redirect(\'index.php?page=warehouses\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menghapus gudang\';
    }
}

// Create Shelf
if ($page == \'shelves\' && $action == \'create\' && isset($_POST[\'create_shelf\'])) {
    $code = $_POST[\'code\'];
    $warehouse_id = $_POST[\'warehouse_id\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->create(\'shelves\', [
            \'code\' => $code,
            \'warehouse_id\' => $warehouse_id
        ])) {
        $_SESSION[\'success\'] = \'Rak berhasil ditambahkan\';
        redirect(\'index.php?page=shelves\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menambah rak\';
    }
}

// Delete Shelf
if ($page == \'shelves\' && $action == \'delete\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->delete(\'shelves\', $id)) {
        $_SESSION[\'success\'] = \'Rak berhasil dihapus\';
        redirect(\'index.php?page=shelves\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menghapus rak\';
    }
}

// Create Movement
if ($page == \'movements\' && $action == \'create\' && isset($_POST[\'create_movement\'])) {
    $archive_id = $_POST[\'archive_id\'];
    $archive_name = $_POST[\'archive_name\'];
    $archive_code = $_POST[\'archive_code\'];
    $previous_shelf = $_POST[\'previous_shelf\'];
    $new_shelf = $_POST[\'new_shelf\'];
    $moved_by = $_SESSION[\'username\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->create(\'movements\', [
            \'archive_id\' => $archive_id,
            \'archive_name\' => $archive_name,
            \'archive_code\' => $archive_code,
            \'previous_shelf\' => $previous_shelf,
            \'new_shelf\' => $new_shelf,
            \'moved_by\' => $moved_by
        ])) {
        $_SESSION[\'success\'] = \'Pemindahan berhasil dicatat\';
        redirect(\'index.php?page=movements\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal mencatat pemindahan\';
    }
}

// Delete Movement
if ($page == \'movements\' && $action == \'delete\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->delete(\'movements\', $id)) {
        $_SESSION[\'success\'] = \'Riwayat pemindahan berhasil dihapus\';
        redirect(\'index.php?page=movements\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menghapus riwayat pemindahan\';
    }
}

// Create Borrowing
if ($page == \'borrowings\' && $action == \'create\' && isset($_POST[\'create_borrowing\'])) {
    $archive_id = $_POST[\'archive_id\'];
    $archive_name = $_POST[\'archive_name\'];
    $archive_code = $_POST[\'archive_code\'];
    $borrower_name = $_POST[\'borrower_name\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->create(\'borrowings\', [
            \'archive_id\' => $archive_id,
            \'archive_name\' => $archive_name,
            \'archive_code\' => $archive_code,
            \'borrower_name\' => $borrower_name
        ])) {
        $_SESSION[\'success\'] = \'Peminjaman berhasil dicatat\';
        redirect(\'index.php?page=borrowings\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal mencatat peminjaman\';
    }
}

// Return Borrowing
if ($page == \'borrowings\' && $action == \'return\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];

    $stmt = $pdo->prepare("UPDATE borrowings SET returned_at = NOW() WHERE id = :id");
    $stmt->execute([\':id\' => $id]);

    $_SESSION[\'success\'] = \'Arsip berhasil dikembalikan\';
    redirect(\'index.php?page=borrowings\'); // FIX: Relative URL
    exit;
}

// Delete Borrowing
if ($page == \'borrowings\' && $action == \'delete\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->delete(\'borrowings\', $id)) {
        $_SESSION[\'success\'] = \'Riwayat peminjaman berhasil dihapus\';
        redirect(\'index.php?page=borrowings\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menghapus riwayat peminjaman\';
    }
}

// --- LOGIC: CREATE ACCOUNT ---
if ($page == \'accounts\' && $action == \'create\' && isset($_POST[\'create_account\'])) {
    $username = $_POST[\'username\'];
    $email = $_POST[\'email\'];
    $password = $_POST[\'password\'];
    $role = $_POST[\'role\'];

    // Check Role Access
    if (isset($_SESSION[\'role\']) && !in_array($_SESSION[\'role\'], [\'ADMIN\', \'KASSUBAG_KUL\'])) {
        redirect(\'index.php?page=dashboard\'); // Redirect if not admin/kassubag
        exit;
    }

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->create(\'accounts\', [
            \'username\' => $username,
            \'email\' => $email,
            \'password\' => $password,
            \'role\' => $role
        ])) {
        $_SESSION[\'success\'] = \'Akun berhasil ditambahkan\';
        redirect(\'index.php?page=accounts\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menambah akun\';
    }
}

// --- LOGIC: DELETE ACCOUNT ---
if ($page == \'accounts\' && $action == \'delete\' && isset($_GET[\'id\'])) {
    $id = $_GET[\'id\'];

    // Check Role Access
    if (isset($_SESSION[\'role\']) && !in_array($_SESSION[\'role\'], [\'ADMIN\', \'KASSUBAG_KUL\'])) {
        redirect(\'index.php?page=dashboard\'); // Redirect if not admin/kassubag
        exit;
    }

    require_once \'models/Database_model.php\';
    $model = new Database_model();
    if ($model->delete(\'accounts\', $id)) {
        $_SESSION[\'success\'] = \'Akun berhasil dihapus\';
        redirect(\'index.php?page=accounts\'); // FIX: Relative URL
        exit;
    } else {
        $error_msg = \'Gagal menghapus akun\';
    }
}

// --- ROUTING VIEW ---

// 1. Load Models (Global)
require_once \'models/Database_model.php\';
$db_model = new Database_model();

// 2. Prepare Data for Views
$data = [];
$data[\'page\'] = $page; // FIX: Pass page to view
$data[\'username\'] = $_SESSION[\'username\'] ?? \'\';
$data[\'role\'] = $_SESSION[\'role\'] ?? \'\';

// 3. Logic Dashboard (Stats)
if ($page == \'dashboard\') {
    $data[\'stats\'] = [
        \'archives\' => $db_model->count(\'archives\'),
        \'warehouses\' => $db_model->count(\'warehouses\'),
        \'shelves\' => $db_model->count(\'shelves\'),
        \'borrowings_active\' => $pdo->query("SELECT COUNT(*) FROM borrowings WHERE returned_at IS NULL")->fetchColumn(),
        \'borrowings_total\' => $db_model->count(\'borrowings\'),
    ];
}

// 4. Logic Archives (List)
if ($page == \'archives\') {
    $data[\'archives\'] = $db_model->get_all(\'archives\');
    // Get warehouses for dropdown
    $data[\'warehouses\'] = $db_model->get_all(\'warehouses\');
    // Get shelves for dropdown
    $data[\'shelves\'] = $db_model->get_all(\'shelves\');
}

// 5. Logic Warehouses (List)
if ($page == \'warehouses\') {
    $data[\'warehouses\'] = $db_model->get_all(\'warehouses\');
}

// 6. Logic Shelves (List)
if ($page == \'shelves\') {
    $data[\'shelves\'] = $db_model->get_all(\'shelves\');
    // Get warehouses for dropdown
    $data[\'warehouses\'] = $db_model->get_all(\'warehouses\');
}

// 7. Logic Movements (List)
if ($page == \'movements\') {
    $data[\'movements\'] = $db_model->get_all(\'movements\');
    // Get archives for dropdown
    $data[\'archives\'] = $db_model->get_all(\'archives\');
    // Get shelves for dropdown
    $data[\'shelves\'] = $db_model->get_all(\'shelves\');
}

// 8. Logic Borrowings (List)
if ($page == \'borrowings\') {
    $data[\'borrowings\'] = $db_model->get_all(\'borrowings\');
    // Get archives for dropdown
    $data[\'archives\'] = $db_model->get_all(\'archives\');
}

// 9. Logic Accounts (List)
if ($page == \'accounts\') {
    $data[\'accounts\'] = $db_model->get_all(\'accounts\');
}

// 5. Load View
switch ($page) {
    case \'login\':
        view(\'auth/login\', [\'error_msg\' => $error_msg ?? \'\']);
        break;

    case \'dashboard\':
    case \'archives\':
    case \'warehouses\':
    case \'shelves\':
    case \'movements\':
    case \'borrowings\':
    case \'accounts\':
        view(\'dashboard/index\', $data); // Load Dashboard Layout (Sidebar + Navbar + Content Area)
        break;

    default:
        view(\'dashboard/index\', $data); // Load Dashboard Layout for 404
        break;
}
?>';

file_put_contents('index.php', $index_php_content);
echo "<div style='color:green;'>✅ File created: index.php</div>";

// --- DONE ---
echo "<hr>";
echo "<h2>✅ INSTALLASI SELESAI!</h2>";
echo "<p>Semua file dan folder MVC sudah dibuat.</p>";
echo "<p>Silakan buka <a href='index.php'>index.php</a> di browser.</p>";
?>';

file_put_contents('install.php', $install_php_content);
echo "<div style='color:green;'>✅ Script Installer Dibuat!</div>";
echo "<div style='color:blue;'>👉 Silakan Copy Script Ini!</div>";

?>

<!-- HTML Display untuk copy -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Copy Installer Script</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container">
        <div class="alert alert-info">
            <h5>Cara Pakai:</h5>
            <ol>
                <li>Copy PHP code di dalam textarea di bawah.</li>
                <li>Simpan sebagai <code>install.php</code> di folder XAMPP Anda: <code>C:\xampp\htdocs\gudang-arsip\</code>.</li>
                <li>Buka URL: <code>http://localhost/gudang-arsip/install.php</code>.</li>
                <li>Installer akan membuat semua folder & file secara otomatis.</li>
                <li>Selesai!</li>
            </ol>
        </div>
        <div class="mb-3">
            <label for="code">Installer Script (PHP):</label>
            <textarea id="code" class="form-control" rows="20" readonly style="font-family: monospace; font-size: 12px; color: #333; background: #f8f9fa;"><?= htmlspecialchars($install_php_content) ?></textarea>
            <button class="btn btn-primary mt-2" onclick="document.getElementById('code').select(); document.execCommand('copy'); alert('Copied!');">Copy Script</button>
        </div>
    </div>
</body>
</html>
