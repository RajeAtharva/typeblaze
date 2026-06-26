<?php
require_once '../includes/config.php';
requireLogin();
requireCsrf($_POST['csrf'] ?? null);

$uid = $_SESSION['user_id'];
$db = getDB();
$avatar = trim($_POST['avatar'] ?? 'keyboard');
$newPwd = trim($_POST['new_password'] ?? '');

$allowedAvatars = ['keyboard', 'rocket', 'lightning', 'fire', 'target', 'laptop'];
if (!in_array($avatar, $allowedAvatars, true)) $avatar = 'keyboard';

if ($newPwd !== '') {
    if (strlen($newPwd) < 6) {
        setFlash('error', 'Password must be at least 6 characters.');
        redirect(SITE_URL . '/dashboard.php');
    }

    $hash = password_hash($newPwd, PASSWORD_BCRYPT, ['cost' => 12]);
    $db->prepare("UPDATE users SET avatar = ?, password = ? WHERE id = ?")->execute([$avatar, $hash, $uid]);
} else {
    $db->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute([$avatar, $uid]);
}

$_SESSION['avatar'] = $avatar;
setFlash('success', 'Profile updated.');
redirect(SITE_URL . '/dashboard.php');
