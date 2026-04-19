<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'pencari_kerja';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

function base_url($path = '') {
    return 'http://localhost/pencari_kerja/' . ltrim($path, '/');
}

function check_login() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . base_url('login.php'));
        exit();
    }
}
?>
