<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(SITE_URL . '/login.php');
}

requireCsrf($_POST['csrf'] ?? null);

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    setFlash('error', 'Please fill in all fields.');
    redirect(SITE_URL . '/login.php');
}

$db = getDB();
$stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    setFlash('error', 'Invalid email or password.');
    redirect(SITE_URL . '/login.php');
}

$db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);

session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['avatar'] = $user['avatar'];
$_SESSION['csrf'] = bin2hex(random_bytes(32));

if ($user['role'] === 'admin') {
    redirect(SITE_URL . '/admin/index.php');
}

redirect(SITE_URL . '/dashboard.php');
