<?php
// Auto detect - Railway ya Local
if (getenv('MYSQLHOST')) {
    define('DB_HOST', 'mysql.railway.internal');
    define('DB_USER', 'root');
    define('DB_PASS', 'ENhrsoxTXDiolklwNLmKrXJnrxwRfSkh');
    define('DB_NAME', 'railway');
    define('SITE_URL', '');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'atharva@07raje');
    define('DB_NAME', 'typeblaze');
    define('SITE_URL', '/typeblaze');
}

define('SITE_NAME', 'TypeBlaze');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/login.php');
    }
}

function requireAdmin(): void {
    if (!isAdmin()) {
        redirect(SITE_URL . '/index.php');
    }
}

function sanitize(?string $value): string {
    return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
}

function csrfToken(): string {
    return $_SESSION['csrf'];
}

function verifyCsrf(?string $token): bool {
    return is_string($token) && hash_equals($_SESSION['csrf'] ?? '', $token);
}

function requireCsrf(?string $token, bool $json = false): void {
    if (verifyCsrf($token)) return;
    if ($json) {
        jsonResponse(['error' => 'invalid_csrf'], 403);
    }
    setFlash('error', 'Your session expired. Please try again.');
    redirect(SITE_URL . '/login.php');
}

function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}
?>