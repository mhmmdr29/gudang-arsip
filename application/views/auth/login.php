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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .btn-primary {
            background: #667eea;
            border-color: #667eea;
        }
        .btn-primary:hover {
            background: #764ba2;
            border-color: #764ba2;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .default-accounts {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        .default-accounts h6 {
            color: #495057;
            margin-bottom: 10px;
        }
        .default-accounts p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="text-center mb-4">
            <h2><i class="fas fa-warehouse text-primary"></i> Sistem Gudang Arsip</h2>
            <p class="text-muted">Silakan login untuk melanjutkan</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?php echo base_url('auth/process_login'); ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk
                </button>
            </div>
        </form>

        <!-- Default Accounts -->
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
</html>
