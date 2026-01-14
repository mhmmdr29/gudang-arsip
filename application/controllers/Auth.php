<?php
session_start();
require_once '../config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :u AND password = :p");
    $stmt->execute([':u'=>$username, ':p'=>$password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        redirect('index.php?page=dashboard');
    } else {
        $_SESSION['error_msg'] = "Username/Pass Salah";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    redirect('index.php?page=login');
}
?>
