<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    jsonResponse(['results' => []]);
}

$db   = getDB();
$stmt = $db->prepare("SELECT mode, wpm, accuracy, mistakes, duration, char_count, taken_at FROM test_results WHERE user_id = ? ORDER BY taken_at DESC LIMIT 20");
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();

// Format date
foreach ($rows as &$r) {
    $r['taken_at'] = date('M d, Y H:i', strtotime($r['taken_at']));
}

jsonResponse(['results' => $rows]);
