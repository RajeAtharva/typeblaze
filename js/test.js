$(function () {
  let mode = 'sample';
  let duration = 0;
  let running = false;
  let startTime = null;
  let timerInt = null;
  let elapsed = 0;
  let mistakes = 0;
  let currentText = '';
  const csrfToken = $('#csrf-token').val();

  const TEXTS = {
    sample: [
      "The quick brown fox jumps over the lazy dog. This sentence contains every letter of the alphabet and is great for warming up your fingers before a long typing session.",
      "Typing is a skill that improves with consistent daily practice. Focus on accuracy first then gradually build your speed as your muscle memory develops over time.",
      "A programmer must think logically and write precisely. Every character matters every space is intentional and every mistake can cause an unexpected bug in production code."
    ],
    quote: [
      "The only way to do great work is to love what you do. If you have not found it yet keep looking. Do not settle for anything less than passion.",
      "In the middle of every difficulty lies opportunity. Imagination is more important than knowledge for knowledge is limited but imagination encircles the world.",
      "It does not matter how slowly you go as long as you do not stop. Perseverance is the key to mastery in any craft or discipline."
    ],
    code: [
      "function calculateWPM(words, seconds) { return Math.round((words / seconds) * 60); } const result = calculateWPM(50, 30); console.log(result);",
      "const fetchData = async (url) => { try { const res = await fetch(url); const data = await res.json(); return data; } catch (err) { console.error(err); } };",
      "class Timer { constructor() { this.start = Date.now(); } elapsed() { return ((Date.now() - this.start) / 1000).toFixed(1); } reset() { this.start = Date.now(); } }"
    ],
    custom: [
      "Practice with your own content to improve typing speed for work or study. Custom mode lets you train on any text that is relevant to your daily tasks and goals."
    ]
  };

  function init() {
    const serverText = $('#server-text').val();
    currentText = serverText && serverText.trim() ? serverText : randomText();
    renderText();
    resetStats();
  }

  function randomText() {
    const pool = TEXTS[mode] || TEXTS.sample;
    return pool[Math.floor(Math.random() * pool.length)];
  }

  function renderText() {
    const html = currentText.split('').map((ch, i) =>
      `<span class="char${i === 0 ? ' current' : ''}" data-i="${i}">${ch === ' ' ? ' ' : escHtml(ch)}</span>`
    ).join('');
    $('#sample-text').html(html);
    $('#prog-bar').css('width', '0%');
  }

  function escHtml(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }

  $(document).on('click', '.mode-pill', function () {
    $('.mode-pill').removeClass('active');
    $(this).addClass('active');
    mode = $(this).data('mode');
    resetTest();
  });

  $('#dur-select').on('change', function () {
    duration = parseInt($(this).val(), 10) || 0;
    resetTest();
  });

  $('#start-btn').on('click', startTest);

  function startTest() {
    if (running) return;
    running = true;
    startTime = Date.now();
    mistakes = 0;
    elapsed = 0;

    $('#typing-input').prop('disabled', false).val('').focus();
    $('#start-btn').text('Running...');
    $('#result-flash').removeClass('show');

    timerInt = setInterval(function () {
      elapsed = Math.floor((Date.now() - startTime) / 1000);
      $('#sv-time').text(elapsed);
      if (duration > 0 && elapsed >= duration) endTest();
    }, 500);

    showToast('Test started - begin typing!');
  }

  $('#reset-btn').on('click', resetTest);

  function resetTest() {
    running = false;
    clearInterval(timerInt);
    elapsed = 0;
    mistakes = 0;

    $('#typing-input').prop('disabled', true).val('');
    $('#start-btn').text('Start Test');
    $('#result-flash').removeClass('show');
    resetStats();

    currentText = randomText();
    renderText();
  }

  function resetStats() {
    $('#sv-time').text('0');
    $('#sv-wpm').text('0');
    $('#sv-acc').text('100%');
    $('#sv-mis').text('0');
    $('#prog-bar').css('width', '0%');
  }

  $('#typing-input').on('input', function () {
    if (!running) return;
    const typed = $(this).val();
    const chars = currentText.split('');
    let errCount = 0;

    chars.forEach(function (ch, i) {
      const $span = $(`[data-i="${i}"]`);
      $span.removeClass('correct wrong current');
      if (i < typed.length) {
        $span.addClass(typed[i] === ch ? 'correct' : 'wrong');
        if (typed[i] !== ch) errCount++;
      } else if (i === typed.length) {
        $span.addClass('current');
      }
    });

    mistakes = errCount;
    const words = typed.trim().split(/\s+/).filter(Boolean).length;
    const secs = Math.max(1, (Date.now() - startTime) / 1000);
    const wpm = Math.round((words / secs) * 60);
    const correct = typed.length - errCount;
    const acc = typed.length > 0 ? Math.round((correct / typed.length) * 100) : 100;
    const prog = Math.min(100, Math.round((typed.length / currentText.length) * 100));

    $('#sv-wpm').text(wpm);
    $('#sv-acc').text(acc + '%');
    $('#sv-mis').text(mistakes);
    $('#prog-bar').css('width', prog + '%');

    if (typed.length >= currentText.length) endTest();
  });

  function endTest() {
    if (!running) return;
    running = false;
    clearInterval(timerInt);

    const typed = $('#typing-input').val();
    const words = typed.trim().split(/\s+/).filter(Boolean).length;
    const totalSec = Math.max(1, (Date.now() - startTime) / 1000);
    const wpm = Math.round((words / totalSec) * 60);
    const correct = typed.length - mistakes;
    const acc = typed.length > 0 ? Math.round((correct / typed.length) * 100) : 100;
    const mins = Math.floor(totalSec / 60);
    const secs = Math.floor(totalSec % 60);
    const timeStr = mins > 0 ? `${mins}m ${secs}s` : `${secs}s`;

    $('#rf-wpm').text(wpm);
    $('#rf-acc').text(acc + '%');
    $('#rf-mis').text(mistakes);
    $('#rf-time').text(timeStr);
    $('#result-flash').addClass('show');

    $('#start-btn').text('Start Test');
    $('#typing-input').prop('disabled', true);

    $.post('php/save_result.php', {
      csrf: csrfToken,
      mode: mode,
      wpm: wpm,
      accuracy: acc,
      mistakes: mistakes,
      duration: Math.floor(totalSec),
      char_count: typed.length
    }, function (res) {
      if (res.success) {
        showToast(`Saved: ${wpm} WPM, ${acc}% accuracy`);
        loadHistory();
      } else if (res.error === 'not_logged_in') {
        showToast('Log in to save your results!', 'error');
      } else if (res.error === 'invalid_csrf') {
        showToast('Session expired. Refresh the page and try again.', 'error');
      }
    }, 'json').fail(function () {
      showToast(`Done: ${wpm} WPM, ${acc}% accuracy`);
    });
  }

  function loadHistory() {
    $.get('php/get_history.php', function (res) {
      const $tbody = $('#hist-tbody');
      $tbody.empty();
      if (!res.results || res.results.length === 0) {
        $tbody.html('<tr><td colspan="6" style="text-align:center;color:var(--text-muted);font-family:var(--font-mono);font-size:13px;padding:36px">No results yet. Complete a test!</td></tr>');
        $('#hist-count').text('(0 tests)');
        return;
      }
      $('#hist-count').text(`(${res.results.length} tests)`);
      res.results.forEach(function (r) {
        $tbody.append(`
          <tr>
            <td><span class="badge badge-green">${r.mode}</span></td>
            <td class="td-green">${r.wpm} wpm</td>
            <td>${r.accuracy}%</td>
            <td>${r.mistakes}</td>
            <td>${r.duration}s</td>
            <td style="color:var(--text-muted);font-size:11px">${r.taken_at}</td>
          </tr>`);
      });
    }, 'json');
  }

  $('#clear-hist-btn').on('click', function () {
    if (!confirm('Clear all your test history? This cannot be undone.')) return;
    $.post('php/clear_history.php', { csrf: csrfToken }, function (res) {
      if (res.success) {
        loadHistory();
        showToast('History cleared.');
      } else if (res.error === 'invalid_csrf') {
        showToast('Session expired. Refresh the page and try again.', 'error');
      }
    }, 'json');
  });

  init();
  loadHistory();
});
