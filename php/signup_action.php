<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(SITE_URL . '/login.php');
}

requireCsrf($_POST['csrf'] ?? null);

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');
$avatar = trim($_POST['avatar'] ?? 'keyboard');

$errors = [];
if ($username === '' || strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
if ($password !== $confirm) $errors[] = 'Passwords do not match.';

$allowedAvatars = ['keyboard', 'rocket', 'lightning', 'fire', 'target', 'laptop'];
if (!in_array($avatar, $allowedAvatars, true)) $avatar = 'keyboard';

if (!empty($errors)) {
    setFlash('error', implode(' ', $errors));
    redirect(SITE_URL . '/login.php#signup');
}

$db = getDB();
$check = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$check->execute([$email, $username]);
if ($check->fetch()) {
    setFlash('error', 'Email or username already exists.');
    redirect(SITE_URL . '/login.php#signup');
}

$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$db->prepare("INSERT INTO users (username, email, password, avatar) VALUES (?,?,?,?)")
   ->execute([$username, $email, $hash, $avatar]);

$newId = $db->lastInsertId();

session_regenerate_id(true);
$_SESSION['user_id'] = $newId;
$_SESSION['username'] = $username;
$_SESSION['role'] = 'user';
$_SESSION['avatar'] = $avatar;
$_SESSION['csrf'] = bin2hex(random_bytes(32));

redirect(SITE_URL . '/dashboard.php');
