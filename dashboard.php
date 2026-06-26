<?php
require_once 'includes/config.php';
requireLogin();
$pageTitle = 'Dashboard';

$db = getDB();
$uid = $_SESSION['user_id'];

$userStmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$uid]);
$user = $userStmt->fetch();

$statsStmt = $db->prepare("SELECT COUNT(*) AS total, MAX(wpm) AS best_wpm, ROUND(AVG(wpm),1) AS avg_wpm, ROUND(AVG(accuracy),1) AS avg_acc, SUM(mistakes) AS total_mistakes, SUM(duration) AS total_secs FROM test_results WHERE user_id = ?");
$statsStmt->execute([$uid]);
$stats = $statsStmt->fetch();

$recentStmt = $db->prepare("SELECT wpm, accuracy, taken_at FROM test_results WHERE user_id = ? ORDER BY taken_at DESC LIMIT 10");
$recentStmt->execute([$uid]);
$recent = array_reverse($recentStmt->fetchAll());

$lastStmt = $db->prepare("SELECT * FROM test_results WHERE user_id = ? ORDER BY taken_at DESC LIMIT 5");
$lastStmt->execute([$uid]);
$last5 = $lastStmt->fetchAll();

$totalMin = round(($stats['total_secs'] ?? 0) / 60);
?>
<?php include 'includes/header.php'; ?>

<div class="dash-page">
  <div class="page-header">
    <div class="page-tag">My Dashboard</div>
    <h1 class="page-title">Welcome back, <em><?= sanitize($user['username']) ?></em> <?= sanitize($user['avatar']) ?></h1>
    <p class="page-desc">Track your progress, review your history, and keep improving your typing speed.</p>
  </div>

  <div class="dash-grid">
    <div>
      <div class="profile-card card">
        <div class="avatar-circle"><?= sanitize($user['avatar']) ?></div>
        <div class="profile-name"><?= sanitize($user['username']) ?></div>
        <div class="profile-email"><?= sanitize($user['email']) ?></div>
        <div class="profile-badge">
          <span class="badge <?= $user['role'] === 'admin' ? 'badge-amber' : 'badge-green' ?>">
            <?= ucfirst($user['role']) ?>
          </span>
        </div>
        <div class="profile-stats">
          <div class="ps-item"><div class="ps-val"><?= $stats['best_wpm'] ?? 0 ?></div><div class="ps-key">Best WPM</div></div>
          <div class="ps-item"><div class="ps-val"><?= $stats['avg_wpm'] ?? 0 ?></div><div class="ps-key">Avg WPM</div></div>
          <div class="ps-item"><div class="ps-val"><?= $stats['total'] ?? 0 ?></div><div class="ps-key">Tests</div></div>
          <div class="ps-item"><div class="ps-val"><?= $stats['avg_acc'] ?? 100 ?>%</div><div class="ps-key">Avg Acc</div></div>
          <div class="ps-item"><div class="ps-val"><?= $totalMin ?></div><div class="ps-key">Minutes</div></div>
          <div class="ps-item"><div class="ps-val"><?= $stats['total_mistakes'] ?? 0 ?></div><div class="ps-key">Mistakes</div></div>
        </div>
        <a href="test.php" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:20px">Start New Test</a>
      </div>

      <div class="card" style="margin-top:16px">
        <div class="dash-section-title" style="font-size:15px;margin-bottom:14px">Account Settings</div>
        <form method="POST" action="php/update_profile.php">
          <div class="form-group">
            <label class="form-label">Avatar</label>
            <select class="form-input" name="avatar">
              <?php foreach (['keyboard', 'rocket', 'lightning', 'fire', 'target', 'laptop'] as $av): ?>
              <option value="<?= $av ?>" <?= $user['avatar'] === $av ? 'selected' : '' ?>><?= ucfirst($av) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">New Password <span style="color:var(--text-muted)">(leave blank to keep)</span></label>
            <input class="form-input" type="password" name="new_password" placeholder="New password...">
          </div>
          <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
          <button type="submit" class="btn btn-outline btn-sm" style="width:100%;justify-content:center">Save Changes</button>
        </form>
      </div>
    </div>

    <div class="dash-right">
      <div class="chart-wrap card">
        <div class="dash-section-title">WPM Progress <span style="font-family:var(--font-mono);font-size:11px;color:var(--text-muted);font-weight:400">(last <?= count($recent) ?> tests)</span></div>
        <?php if (count($recent) > 0):
          $maxWpm = max(array_column($recent, 'wpm')) ?: 1;
        ?>
        <div class="mini-chart">
          <?php foreach ($recent as $r):
            $h = max(8, round(($r['wpm'] / $maxWpm) * 100));
          ?>
          <div class="bar-wrap">
            <div class="bar" style="height:<?= $h ?>%" title="<?= $r['wpm'] ?> WPM"></div>
            <div class="bar-lbl"><?= $r['wpm'] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="font-family:var(--font-mono);font-size:13px;color:var(--text-muted);padding:20px 0">No data yet. Complete some tests!</div>
        <?php endif; ?>
      </div>

      <div class="card">
        <div class="dash-section-title">Recent Results</div>
        <?php if (count($last5) > 0): ?>
        <table class="tb-table">
          <thead>
            <tr><th>Mode</th><th>WPM</th><th>Accuracy</th><th>Mistakes</th><th>Time</th><th>Date</th></tr>
          </thead>
          <tbody>
            <?php foreach ($last5 as $r): ?>
            <tr>
              <td><span class="badge badge-green"><?= ucfirst($r['mode']) ?></span></td>
              <td class="td-green"><?= $r['wpm'] ?> wpm</td>
              <td><?= $r['accuracy'] ?>%</td>
              <td><?= $r['mistakes'] ?></td>
              <td><?= $r['duration'] ?>s</td>
              <td style="color:var(--text-muted);font-size:11px"><?= date('M d, H:i', strtotime($r['taken_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><div class="empty-icon">History</div>No results yet. <a href="test.php" style="color:var(--green)">Take your first test!</a></div>
        <?php endif; ?>
      </div>

      <div class="card">
        <div class="dash-section-title">Achievements</div>
        <div style="display:flex;flex-wrap:wrap;gap:10px">
          <?php
          $ach = [];
          if (($stats['total'] ?? 0) >= 1) $ach[] = ['New', 'First Test', 'Complete your first test'];
          if (($stats['total'] ?? 0) >= 10) $ach[] = ['10', '10 Tests', 'Completed 10 tests'];
          if (($stats['best_wpm'] ?? 0) >= 60) $ach[] = ['60', '60+ WPM', 'Reached 60 WPM'];
          if (($stats['best_wpm'] ?? 0) >= 100) $ach[] = ['100', '100+ WPM', 'Reached 100 WPM'];
          if (($stats['avg_acc'] ?? 0) >= 95) $ach[] = ['ACC', 'Sharp Shooter', '95%+ avg accuracy'];
          if (empty($ach)) $ach[] = ['Lock', 'Locked', 'Complete tests to unlock achievements'];
          foreach ($ach as $a):
          ?>
          <div style="background:var(--bg3);border:1px solid var(--border);border-radius:var(--r);padding:12px 16px;display:flex;align-items:center;gap:10px;min-width:180px">
            <span style="font-size:22px"><?= $a[0] ?></span>
            <div>
              <div style="font-size:13px;font-weight:700"><?= $a[1] ?></div>
              <div style="font-family:var(--font-mono);font-size:11px;color:var(--text-muted)"><?= $a[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
