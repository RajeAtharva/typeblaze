<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= SITE_NAME ?> - <?= $pageTitle ?? 'Speed Typing Test' ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/css/style.css?v=1">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="<?= isset($_COOKIE['tb_theme']) && $_COOKIE['tb_theme'] === 'light' ? 'light' : '' ?>">

<header id="site-header">
  <a href="<?= SITE_URL ?>/index.php" class="logo">
    <div class="logo-icon">
      <svg viewBox="0 0 18 18" fill="none"><rect x="1" y="7" width="4" height="10" rx="1" fill="#000"/><rect x="7" y="4" width="4" height="13" rx="1" fill="#000"/><rect x="13" y="1" width="4" height="16" rx="1" fill="#000"/></svg>
    </div>
    Type<em>Blaze</em>
  </a>

  <nav class="main-nav">
    <a href="<?= SITE_URL ?>/index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>">Home</a>
    <a href="<?= SITE_URL ?>/test.php" class="<?= $currentPage === 'test' ? 'active' : '' ?>">Test</a>
    <a href="<?= SITE_URL ?>/leaderboard.php" class="<?= $currentPage === 'leaderboard' ? 'active' : '' ?>">Leaderboard</a>
    <?php if (isLoggedIn()): ?>
    <a href="<?= SITE_URL ?>/dashboard.php" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
    <?php endif; ?>
    <?php if (isAdmin()): ?>
    <a href="<?= SITE_URL ?>/admin/index.php" class="admin-link <?= strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'active' : '' ?>">Admin</a>
    <?php endif; ?>
  </nav>

  <div class="header-right">
    <button class="icon-btn" id="theme-toggle" title="Toggle theme">Dark</button>
    <?php if (isLoggedIn()): ?>
      <div class="user-chip">
        <div class="user-dot"></div>
        <span><?= sanitize($_SESSION['username']) ?></span>
        <?php if (isAdmin()): ?><span class="chip-role">Admin</span><?php endif; ?>
      </div>
      <a href="<?= SITE_URL ?>/php/logout.php" class="btn-nav-outline">Logout</a>
    <?php else: ?>
      <a href="<?= SITE_URL ?>/login.php" class="btn-nav-outline">Log In</a>
      <a href="<?= SITE_URL ?>/login.php#signup" class="btn-nav-solid">Sign Up</a>
    <?php endif; ?>
  </div>
</header>

<?php if ($flash): ?>
<div style="max-width:1200px;margin:18px auto 0;padding:0 24px">
  <div style="padding:12px 16px;border-radius:12px;border:1px solid <?= $flash['type'] === 'error' ? 'rgba(255,77,106,.35)' : 'rgba(0,255,136,.25)' ?>;background:<?= $flash['type'] === 'error' ? 'rgba(255,77,106,.08)' : 'rgba(0,255,136,.08)' ?>;color:<?= $flash['type'] === 'error' ? '#ff6f8c' : '#9ff0bf' ?>;font-family:var(--font-mono);font-size:13px">
    <?= sanitize($flash['message']) ?>
  </div>
</div>
<?php endif; ?>

<div id="toast" class="toast"></div>
