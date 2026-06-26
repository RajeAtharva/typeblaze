<?php
require_once 'includes/config.php';
$pageTitle = 'Login / Sign Up';

if (isLoggedIn()) {
    redirect(SITE_URL . '/dashboard.php');
}

$loginError = '';
$signupError = '';
$activeTab = isset($_GET['tab']) && $_GET['tab'] === 'signup' ? 'signup' : 'login';

if (strpos($_SERVER['REQUEST_URI'], 'signup') !== false) {
    $activeTab = 'signup';
}

if (isset($_SESSION['flash']) && ($_SESSION['flash']['type'] ?? '') === 'error') {
    $message = $_SESSION['flash']['message'] ?? '';
    if ($activeTab === 'signup') {
        $signupError = $message;
    } else {
        $loginError = $message;
    }
    unset($_SESSION['flash']);
}
?>
<?php include 'includes/header.php'; ?>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-title">Welcome to TypeBlaze</div>
    <div class="auth-sub">Sign in or create a free account to track your progress.</div>

    <div class="auth-tabs">
      <button class="auth-tab <?= $activeTab === 'login' ? 'active' : '' ?>" id="tab-login" type="button" onclick="switchTab('login')">Log In</button>
      <button class="auth-tab <?= $activeTab === 'signup' ? 'active' : '' ?>" id="tab-signup" type="button" onclick="switchTab('signup')">Sign Up</button>
    </div>

    <div id="panel-login" <?= $activeTab !== 'login' ? 'style="display:none"' : '' ?>>
      <?php if ($loginError): ?>
        <div class="form-error" style="margin-bottom:14px;padding:10px 14px;background:rgba(255,77,106,.1);border:1px solid rgba(255,77,106,.25);border-radius:8px"><?= sanitize($loginError) ?></div>
      <?php endif; ?>
      <form method="POST" action="php/login_action.php" id="login-form">
        <div class="form-group">
          <label class="form-label">Email address</label>
          <input class="form-input" type="email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input class="form-input" type="password" name="password" placeholder="Enter your password" required>
        </div>
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;font-size:14px;margin-top:6px">Log In to TypeBlaze</button>
      </form>
      <div class="auth-alt">Don't have an account? <a href="#" onclick="switchTab('signup')">Sign up free</a></div>
    </div>

    <div id="panel-signup" <?= $activeTab !== 'signup' ? 'style="display:none"' : '' ?>>
      <?php if ($signupError): ?>
        <div class="form-error" style="margin-bottom:14px;padding:10px 14px;background:rgba(255,77,106,.1);border:1px solid rgba(255,77,106,.25);border-radius:8px"><?= sanitize($signupError) ?></div>
      <?php endif; ?>
      <form method="POST" action="php/signup_action.php" id="signup-form">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Username</label>
            <input class="form-input" type="text" name="username" placeholder="speedtyper42" required minlength="3" maxlength="50">
          </div>
          <div class="form-group">
            <label class="form-label">Avatar</label>
            <select class="form-input" name="avatar">
              <option value="keyboard">Keyboard</option>
              <option value="rocket">Rocket</option>
              <option value="lightning">Lightning</option>
              <option value="fire">Fire</option>
              <option value="target">Target</option>
              <option value="laptop">Laptop</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Email address</label>
          <input class="form-input" type="email" name="email" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input class="form-input" type="password" name="password" placeholder="Min. 6 characters" required minlength="6">
        </div>
        <div class="form-group">
          <label class="form-label">Confirm Password</label>
          <input class="form-input" type="password" name="confirm_password" placeholder="Re-enter password" required>
        </div>
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;font-size:14px;margin-top:6px">Create Free Account</button>
      </form>
      <div class="auth-alt" style="font-size:11px;margin-top:12px">By signing up you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a></div>
      <div class="auth-alt">Already have an account? <a href="#" onclick="switchTab('login')">Log in</a></div>
    </div>
  </div>
</div>

<script>
function switchTab(tab) {
  document.getElementById('panel-login').style.display = tab === 'login' ? 'block' : 'none';
  document.getElementById('panel-signup').style.display = tab === 'signup' ? 'block' : 'none';
  document.getElementById('tab-login').classList.toggle('active', tab === 'login');
  document.getElementById('tab-signup').classList.toggle('active', tab === 'signup');
}

if (window.location.href.includes('signup')) {
  switchTab('signup');
}
</script>

<?php include 'includes/footer.php'; ?>
