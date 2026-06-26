<?php // includes/footer.php ?>
<footer id="site-footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="<?= SITE_URL ?>/index.php" class="logo">
          <div class="logo-icon">
            <svg viewBox="0 0 18 18" fill="none"><rect x="1" y="7" width="4" height="10" rx="1" fill="#000"/><rect x="7" y="4" width="4" height="13" rx="1" fill="#000"/><rect x="13" y="1" width="4" height="16" rx="1" fill="#000"/></svg>
          </div>
          Type<em>Blaze</em>
        </a>
        <p>The ultimate typing speed test platform. Measure, improve, and compete.</p>
      </div>
      <div>
        <div class="footer-col-title">Product</div>
        <ul class="footer-links">
          <li><a href="<?= SITE_URL ?>/test.php">Speed Test</a></li>
          <li><a href="<?= SITE_URL ?>/leaderboard.php">Leaderboard</a></li>
          <li><a href="<?= SITE_URL ?>/dashboard.php">Dashboard</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Account</div>
        <ul class="footer-links">
          <li><a href="<?= SITE_URL ?>/login.php">Login</a></li>
          <li><a href="<?= SITE_URL ?>/login.php#signup">Sign Up</a></li>
          <li><a href="<?= SITE_URL ?>/dashboard.php">Profile</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Info</div>
        <ul class="footer-links">
          <li><a href="#">About</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">&copy; <?= date('Y') ?> <span>TypeBlaze</span>. College Project. All rights reserved.</div>
      <div class="footer-status"><div class="status-dot"></div> All systems operational</div>
    </div>
  </div>
</footer>

<script src="<?= SITE_URL ?>/js/main.js"></script>
</body>
</html>
