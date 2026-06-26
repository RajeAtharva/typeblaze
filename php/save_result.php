<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    jsonResponse(['error' => 'not_logged_in'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['error' => 'invalid_request'], 400);
}

requireCsrf($_POST['csrf'] ?? null, true);

$uid = $_SESSION['user_id'];
$mode = in_array($_POST['mode'] ?? '', ['sample', 'quote', 'code', 'custom'], true) ? $_POST['mode'] : 'sample';
$wpm = max(0, intval($_POST['wpm'] ?? 0));
$accuracy = max(0, min(100, floatval($_POST['accuracy'] ?? 100)));
$mistakes = max(0, intval($_POST['mistakes'] ?? 0));
$duration = max(0, intval($_POST['duration'] ?? 0));
$charCount = max(0, intval($_POST['char_count'] ?? 0));

$db = getDB();
$db->prepare("INSERT INTO test_results (user_id, mode, wpm, accuracy, mistakes, duration, char_count) VALUES (?,?,?,?,?,?,?)")
   ->execute([$uid, $mode, $wpm, $accuracy, $mistakes, $duration, $charCount]);

jsonResponse(['success' => true, 'id' => $db->lastInsertId()]);
