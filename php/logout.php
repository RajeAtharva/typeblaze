<?php
// php/logout.php
require_once '../includes/config.php';
session_destroy();
setcookie('tb_theme', '', time()-3600, '/');
redirect(SITE_URL . '/login.php');
