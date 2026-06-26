<?php
require_once 'includes/config.php';
$pageTitle = 'Leaderboard';

$db = getDB();
$leaders = $db->query("SELECT * FROM leaderboard LIMIT 50")->fetchAll();
$myRank = null;

if (isLoggedIn()) {
    $rankQ = $db->prepare("SELECT rank_pos FROM (SELECT id, RANK() OVER (ORDER BY best_wpm DESC) AS rank_pos FROM leaderboard) r WHERE id = ?");
    $rankQ->execute([$_SESSION['user_id']]);
    $myRank = $rankQ->fetchColumn();
}
?>
<?php include 'includes/header.php'; ?>

<div class="lb-page">
  <div class="page-header">
    <div class="page-tag">Global Leaderboard</div>
    <h1 class="page-title">Top <em>Typists</em></h1>
    <p class="page-desc">Updated in real-time. Rankings based on best WPM score across all tests.</p>
    <?php if ($myRank): ?>
    <div style="margin-top:14px;display:inline-flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:13px;background:rgba(0,255,136,.08);border:1px solid var(--border-g);padding:8px 16px;border-radius:20px;color:var(--green)">
      Your current rank: <strong>#<?= $myRank ?></strong>
    </div>
    <?php endif; ?>
  </div>

  <div class="lb-wrap">
    <table class="tb-table">
      <thead>
        <tr><th>#</th><th>User</th><th>Best WPM</th><th>Avg Accuracy</th><th>Tests</th><th>Last Active</th></tr>
      </thead>
      <tbody>
        <?php if (empty($leaders)): ?>
        <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">Rank</div>No entries yet. Be the first!</div></td></tr>
        <?php else: foreach ($leaders as $i => $l):
          $rank = $i + 1;
          $rc = $rank === 1 ? 'rank-gold' : ($rank === 2 ? 'rank-silver' : ($rank === 3 ? 'rank-bronze' : ''));
          $isMe = isLoggedIn() && $l['id'] == $_SESSION['user_id'];
        ?>
        <tr style="<?= $isMe ? 'background:rgba(0,255,136,.04)' : '' ?>">
          <td class="<?= $rc ?>"><?= $rank <= 3 ? ['1', '2', '3'][$rank - 1] : "#$rank" ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;border-radius:50%;background:var(--bg3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:12px"><?= sanitize($l['avatar']) ?></div>
              <div>
                <div style="font-weight:600;font-size:14px"><?= sanitize($l['username']) ?><?= $isMe ? ' <span style="color:var(--green);font-size:11px">(you)</span>' : '' ?></div>
              </div>
            </div>
          </td>
          <td class="td-green"><?= $l['best_wpm'] ?> wpm</td>
          <td><?= $l['avg_accuracy'] ?>%</td>
          <td><?= $l['total_tests'] ?></td>
          <td style="font-size:11px;color:var(--text-muted)"><?= date('M d, Y', strtotime($l['last_played'])) ?></td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!isLoggedIn()): ?>
  <div style="text-align:center;margin-top:28px;font-family:var(--font-mono);font-size:13px;color:var(--text-muted)">
    <a href="login.php" style="color:var(--green)">Log in</a> to appear on the leaderboard.
  </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
