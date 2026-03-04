<?php
require_once '../app/bootstrap.php';

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectFail('Invalid request', $redirectTo);
}

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$role     = $_POST['role'] ?? 'customer';
$redirectTo = $_POST['redirect_to'] ?? 'index.php';

if ($email === '' || $password === '') {
    redirectFail('Please enter both email and password.', $redirectTo);
}

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$user = $userModel->findByEmail($email);

if (!$user) {
    clearSession();
    redirectFail('No account found with that email address.', $redirectTo);
}

if ($user['password'] !== $password) {
    clearSession();
    redirectFail('Incorrect password. Please try again.', $redirectTo);
}

if ($user['role'] !== $role) {
    clearSession();
    redirectFail('Access Denied: You do not have the required permissions.', $redirectTo);
}

Session::set('user_id', $user['id']);
Session::set('user_email', $user['email']);
Session::set('role', $user['role']);

$allowedRedirects = [
    'index.php',
    'customer/shop/shop.php',
    'customer/shop/mobile.php',
    'customer/shop/laptop.php',
    'customer/shop/desktop.php'
];

if (!in_array($redirectTo, $allowedRedirects, true)) {
    $redirectTo = 'index.php';
}

if ($role === 'customer') {
    successRedirect($redirectTo);
}

if ($role === 'admin') {
    successRedirect('admin/dashboard.php');
}

if ($role === 'processing team') {
    successRedirect('processing/dashboard.php');
}

clearSession();
redirectFail('Login Failed', $redirectTo);

function clearSession(): void
{
    $_SESSION = [];
    Session::destroy();
}

function redirectFail(string $msg, string $path): void
{
    echo "<script>alert('$msg'); window.location.href='$path';</script>";
    exit;
}

function successRedirect(string $path): void
{
    echo "<script>alert('Login Successful'); window.location.href='$path';</script>";
    exit;
}
