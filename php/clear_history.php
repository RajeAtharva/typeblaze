<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    jsonResponse(['error' => 'not_logged_in'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'invalid_request'], 400);
}

requireCsrf($_POST['csrf'] ?? null, true);

$db = getDB();
$db->prepare("DELETE FROM test_results WHERE user_id = ?")->execute([$_SESSION['user_id']]);

jsonResponse(['success' => true]);
