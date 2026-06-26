<?php
require_once 'includes/config.php';
$pageTitle = 'Speed Test';

$db = getDB();
$stmt = $db->prepare("SELECT content FROM typing_texts WHERE mode = 'sample' AND is_active = 1 ORDER BY RAND() LIMIT 1");
$stmt->execute();
$serverText = $stmt->fetchColumn() ?: '';
?>
<?php include 'includes/header.php'; ?>

<div class="test-page">
  <div class="page-header">
    <div class="page-tag">Live Speed Test</div>
    <h1 class="page-title">How fast do you <em>type?</em></h1>
    <p class="page-desc">Select a mode, hit Start, and begin typing. Results are saved automatically to your profile.</p>
  </div>

  <div class="mode-bar">
    <button class="mode-pill active" data-mode="sample" type="button">Sample</button>
    <button class="mode-pill" data-mode="quote" type="button">Quotes</button>
    <button class="mode-pill" data-mode="code" type="button">Code</button>
    <button class="mode-pill" data-mode="custom" type="button">Custom</button>
    <select class="dur-select" id="dur-select">
      <option value="0">No limit</option>
      <option value="15">15 sec</option>
      <option value="30">30 sec</option>
      <option value="60">60 sec</option>
    </select>
    <input type="hidden" id="server-text" value="<?= htmlspecialchars($serverText) ?>">
    <input type="hidden" id="csrf-token" value="<?= csrfToken() ?>">
  </div>

  <div class="result-flash" id="result-flash">
    <div class="rf-title">// test complete - your results</div>
    <div class="rf-grid">
      <div><div class="rf-val" id="rf-wpm">0</div><div class="rf-lbl">WPM</div></div>
      <div><div class="rf-val" id="rf-acc">100%</div><div class="rf-lbl">Accuracy</div></div>
      <div><div class="rf-val" id="rf-mis">0</div><div class="rf-lbl">Mistakes</div></div>
      <div><div class="rf-val" id="rf-time">0s</div><div class="rf-lbl">Time</div></div>
    </div>
  </div>

  <div class="sample-box">
    <div class="box-label">// sample text</div>
    <div id="sample-text"></div>
    <div class="prog-wrap2"><div id="prog-bar"></div></div>
  </div>

  <div class="input-box">
    <div class="box-label">// your input</div>
    <textarea id="typing-input" placeholder="Click 'Start Test' then begin typing here..." disabled></textarea>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="sk">Time</div>
      <div class="sv" id="sv-time">0</div>
      <div class="su">seconds</div>
    </div>
    <div class="stat-card active">
      <div class="sk">WPM</div>
      <div class="sv" id="sv-wpm">0</div>
      <div class="su">words/min</div>
    </div>
    <div class="stat-card">
      <div class="sk">Accuracy</div>
      <div class="sv" id="sv-acc">100%</div>
      <div class="su">correct</div>
    </div>
    <div class="stat-card">
      <div class="sk">Mistakes</div>
      <div class="sv" id="sv-mis">0</div>
      <div class="su">errors</div>
    </div>
  </div>

  <div class="ctrl-row">
    <button class="btn btn-primary btn-lg" id="start-btn" type="button">Start Test</button>
    <button class="btn btn-outline btn-lg" id="reset-btn" type="button">Reset</button>
  </div>

  <div>
    <div class="hist-header">
      <div class="hist-title">Recent Results <span class="hist-count" id="hist-count">(loading...)</span></div>
      <?php if (isLoggedIn()): ?>
      <button class="btn btn-danger btn-sm" id="clear-hist-btn" type="button">Clear History</button>
      <?php endif; ?>
    </div>
    <div class="hist-wrap">
      <table class="tb-table">
        <thead>
          <tr>
            <th>Mode</th><th>WPM</th><th>Accuracy</th><th>Mistakes</th><th>Duration</th><th>Date</th>
          </tr>
        </thead>
        <tbody id="hist-tbody">
          <tr><td colspan="6" style="text-align:center;color:var(--text-muted);font-family:var(--font-mono);font-size:13px;padding:36px">Loading...</td></tr>
        </tbody>
      </table>
    </div>
    <?php if (!isLoggedIn()): ?>
    <p style="font-family:var(--font-mono);font-size:12px;color:var(--text-muted);margin-top:12px;text-align:center">
      <a href="login.php" style="color:var(--green)">Log in</a> to save your results permanently.
    </p>
    <?php endif; ?>
  </div>
</div>

<script src="<?= SITE_URL ?>/js/test.js"></script>
<?php include 'includes/footer.php'; ?>
