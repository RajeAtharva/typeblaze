<?php
require_once '../includes/config.php';
requireAdmin();
$pageTitle = 'Admin Panel';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf($_POST['csrf'] ?? null);

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'toggle_user':
                $db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ? AND role != 'admin'")->execute([$_POST['uid']]);
                redirect(SITE_URL . '/admin/index.php?msg=User+updated');

            case 'delete_user':
                $db->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'")->execute([$_POST['uid']]);
                redirect(SITE_URL . '/admin/index.php?msg=User+deleted');

            case 'delete_result':
                $db->prepare("DELETE FROM test_results WHERE id = ?")->execute([$_POST['rid']]);
                redirect(SITE_URL . '/admin/index.php?msg=Result+deleted&tab=results');

            case 'add_text':
                $db->prepare("INSERT INTO typing_texts (mode, content, author, difficulty) VALUES (?,?,?,?)")
                   ->execute([
                       sanitize($_POST['mode']),
                       trim($_POST['content']),
                       trim($_POST['author']),
                       sanitize($_POST['difficulty'])
                   ]);
                redirect(SITE_URL . '/admin/index.php?msg=Text+added&tab=texts');

            case 'delete_text':
                $db->prepare("DELETE FROM typing_texts WHERE id = ?")->execute([$_POST['tid']]);
                redirect(SITE_URL . '/admin/index.php?msg=Text+deleted&tab=texts');
        }
    }
}

$totalUsers = $db->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$totalTests = $db->query("SELECT COUNT(*) FROM test_results")->fetchColumn();
$totalTexts = $db->query("SELECT COUNT(*) FROM typing_texts")->fetchColumn();
$avgWpm = $db->query("SELECT ROUND(AVG(wpm),1) FROM test_results")->fetchColumn();
$users = $db->query("SELECT u.*, (SELECT COUNT(*) FROM test_results r WHERE r.user_id=u.id) AS test_count, (SELECT MAX(wpm) FROM test_results r WHERE r.user_id=u.id) AS best_wpm FROM users u ORDER BY u.created_at DESC")->fetchAll();
$recentResults = $db->query("SELECT r.*, u.username FROM test_results r JOIN users u ON r.user_id=u.id ORDER BY r.taken_at DESC LIMIT 20")->fetchAll();
$texts = $db->query("SELECT * FROM typing_texts ORDER BY created_at DESC")->fetchAll();

$msg = isset($_GET['msg']) ? sanitize($_GET['msg']) : '';
$activeTab = $_GET['tab'] ?? 'users';
?>
<?php include '../includes/header.php'; ?>

<div class="admin-page">
  <div class="page-header">
    <div class="page-tag">Admin Panel</div>
    <h1 class="page-title"><em>Control Center</em></h1>
    <p class="page-desc">Manage users, test results, and typing text library.</p>
  </div>

  <?php if ($msg): ?>
  <div style="background:rgba(0,255,136,.08);border:1px solid var(--border-g);border-radius:var(--r);padding:12px 18px;font-family:var(--font-mono);font-size:13px;color:var(--green);margin-bottom:20px">
    <?= $msg ?>
  </div>
  <?php endif; ?>

  <div class="admin-grid">
    <div class="admin-stat"><div class="as-val"><?= $totalUsers ?></div><div class="as-key">Total Users</div></div>
    <div class="admin-stat"><div class="as-val"><?= $totalTests ?></div><div class="as-key">Total Tests</div></div>
    <div class="admin-stat"><div class="as-val"><?= $avgWpm ?: '-' ?></div><div class="as-key">Avg WPM</div></div>
    <div class="admin-stat"><div class="as-val"><?= $totalTexts ?></div><div class="as-key">Typing Texts</div></div>
  </div>

  <div class="admin-tabs">
    <button class="adm-tab <?= $activeTab === 'users' ? 'active' : '' ?>" data-tab="users" type="button">Users</button>
    <button class="adm-tab <?= $activeTab === 'results' ? 'active' : '' ?>" data-tab="results" type="button">Results</button>
    <button class="adm-tab <?= $activeTab === 'texts' ? 'active' : '' ?>" data-tab="texts" type="button">Texts</button>
  </div>

  <div class="adm-panel <?= $activeTab === 'users' ? 'active' : '' ?>" id="adm-users">
    <div class="card" style="padding:0;overflow:hidden">
      <table class="tb-table">
        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Tests</th><th>Best WPM</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach ($users as $u): ?>
          <tr>
            <td style="color:var(--text-muted)">#<?= $u['id'] ?></td>
            <td style="font-weight:600"><?= sanitize($u['avatar']) ?> <?= sanitize($u['username']) ?></td>
            <td style="color:var(--text-muted);font-size:12px"><?= sanitize($u['email']) ?></td>
            <td><span class="badge <?= $u['role'] === 'admin' ? 'badge-amber' : 'badge-green' ?>"><?= $u['role'] ?></span></td>
            <td><?= $u['test_count'] ?></td>
            <td class="td-green"><?= $u['best_wpm'] ?: '-' ?></td>
            <td style="font-size:11px;color:var(--text-muted)"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
            <td><span class="badge <?= $u['is_active'] ? 'badge-green' : 'badge-red' ?>"><?= $u['is_active'] ? 'Active' : 'Banned' ?></span></td>
            <td>
              <?php if ($u['role'] !== 'admin'): ?>
              <div style="display:flex;gap:6px">
                <form method="POST" style="display:inline">
                  <input type="hidden" name="action" value="toggle_user">
                  <input type="hidden" name="uid" value="<?= $u['id'] ?>">
                  <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                  <button type="submit" class="btn btn-outline btn-sm"><?= $u['is_active'] ? 'Ban' : 'Unban' ?></button>
                </form>
                <form method="POST" style="display:inline" onsubmit="return confirm('Delete user <?= sanitize($u['username']) ?>?')">
                  <input type="hidden" name="action" value="delete_user">
                  <input type="hidden" name="uid" value="<?= $u['id'] ?>">
                  <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </div>
              <?php else: ?>
              <span style="color:var(--text-muted);font-size:12px">Protected</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="adm-panel <?= $activeTab === 'results' ? 'active' : '' ?>" id="adm-results">
    <div class="card" style="padding:0;overflow:hidden">
      <table class="tb-table">
        <thead><tr><th>ID</th><th>User</th><th>Mode</th><th>WPM</th><th>Accuracy</th><th>Mistakes</th><th>Duration</th><th>Date</th><th>Delete</th></tr></thead>
        <tbody>
          <?php foreach ($recentResults as $r): ?>
          <tr>
            <td style="color:var(--text-muted)">#<?= $r['id'] ?></td>
            <td><?= sanitize($r['username']) ?></td>
            <td><span class="badge badge-blue"><?= sanitize($r['mode']) ?></span></td>
            <td class="td-green"><?= $r['wpm'] ?> wpm</td>
            <td><?= $r['accuracy'] ?>%</td>
            <td><?= $r['mistakes'] ?></td>
            <td><?= $r['duration'] ?>s</td>
            <td style="font-size:11px;color:var(--text-muted)"><?= date('M d, H:i', strtotime($r['taken_at'])) ?></td>
            <td>
              <form method="POST" onsubmit="return confirm('Delete this result?')">
                <input type="hidden" name="action" value="delete_result">
                <input type="hidden" name="rid" value="<?= $r['id'] ?>">
                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="adm-panel <?= $activeTab === 'texts' ? 'active' : '' ?>" id="adm-texts">
    <div class="card" style="margin-bottom:20px">
      <div class="dash-section-title" style="margin-bottom:16px">Add New Typing Text</div>
      <form method="POST">
        <input type="hidden" name="action" value="add_text">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Mode</label>
            <select class="form-input" name="mode">
              <option value="sample">Sample</option>
              <option value="quote">Quote</option>
              <option value="code">Code</option>
              <option value="custom">Custom</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Difficulty</label>
            <select class="form-input" name="difficulty">
              <option value="easy">Easy</option>
              <option value="medium">Medium</option>
              <option value="hard">Hard</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Author <span style="color:var(--text-muted)">(optional)</span></label>
          <input class="form-input" type="text" name="author" placeholder="e.g. Steve Jobs">
        </div>
        <div class="form-group">
          <label class="form-label">Text Content</label>
          <textarea class="form-input" name="content" rows="3" placeholder="Enter the typing text here..." required style="resize:vertical"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Text</button>
      </form>
    </div>

    <div class="card" style="padding:0;overflow:hidden">
      <table class="tb-table">
        <thead><tr><th>ID</th><th>Mode</th><th>Difficulty</th><th>Author</th><th>Preview</th><th>Delete</th></tr></thead>
        <tbody>
          <?php foreach ($texts as $t): ?>
          <tr>
            <td style="color:var(--text-muted)">#<?= $t['id'] ?></td>
            <td><span class="badge badge-green"><?= sanitize($t['mode']) ?></span></td>
            <td><span class="badge badge-blue"><?= sanitize($t['difficulty']) ?></span></td>
            <td style="color:var(--text-muted)"><?= sanitize($t['author'] ?: '-') ?></td>
            <td style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:12px;color:var(--text-dim)"><?= sanitize(substr($t['content'], 0, 80)) ?>...</td>
            <td>
              <form method="POST" onsubmit="return confirm('Delete this text?')">
                <input type="hidden" name="action" value="delete_text">
                <input type="hidden" name="tid" value="<?= $t['id'] ?>">
                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.querySelectorAll('.adm-tab').forEach((button) => {
  button.addEventListener('click', () => {
    const tab = button.dataset.tab;
    document.querySelectorAll('.adm-tab').forEach((item) => item.classList.remove('active'));
    document.querySelectorAll('.adm-panel').forEach((panel) => panel.classList.remove('active'));
    document.getElementById('adm-' + tab).classList.add('active');
    button.classList.add('active');
  });
});
</script>

<?php include '../includes/footer.php'; ?>
