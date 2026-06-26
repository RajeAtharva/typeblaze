<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TypeBlaze — Speed Typing Test</title>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<style>
/* ============================================================
   TypeBlaze — index.php  (Complete Standalone Home Page)
   All CSS embedded — no external stylesheet needed
   ============================================================ */
 
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 
:root {
  --green:        #00ff88;
  --green-dim:    #00cc6a;
  --green-glow:   rgba(0,255,136,0.15);
  --bg:           #0a0c0f;
  --bg2:          #111318;
  --bg3:          #181c22;
  --border:       rgba(255,255,255,0.07);
  --border-g:     rgba(0,255,136,0.28);
  --text:         #e8eaf0;
  --text-dim:     #7a8090;
  --text-muted:   #454a56;
  --red:          #ff4d6a;
  --amber:        #ffb84d;
  --blue:         #4d9fff;
  --font-mono:    'JetBrains Mono', monospace;
  --font-display: 'Syne', sans-serif;
  --r:            10px;
  --rl:           16px;
}
 
/* LIGHT MODE */
body.light {
  --bg:        #f2f4f7;
  --bg2:       #ffffff;
  --bg3:       #eaecf0;
  --border:    rgba(0,0,0,0.08);
  --border-g:  rgba(0,160,75,0.3);
  --text:      #141618;
  --text-dim:  #555e70;
  --text-muted:#9aa0ae;
  --green:     #009955;
  --green-dim: #007a44;
  --red:       #d93050;
}
 
html { scroll-behavior: smooth; }
body {
  font-family: var(--font-display);
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  overflow-x: hidden;
  transition: background .3s, color .3s;
}
 
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--text-muted); border-radius: 3px; }
 
a { color: inherit; text-decoration: none; }
button { cursor: pointer; font-family: inherit; }
 
/* ── ANIMATIONS ── */
@keyframes blink   { 0%,100%{opacity:1} 50%{opacity:.3} }
@keyframes fadeIn  { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
@keyframes slideIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
@keyframes prog    { from{width:20%} to{width:80%} }
@keyframes pulse   { 0%,100%{transform:scale(1)} 50%{transform:scale(1.04)} }
 
/* ══════════════════════════════════════
   HEADER
══════════════════════════════════════ */
#site-header {
  position: sticky; top: 0; z-index: 200;
  height: 64px; padding: 0 40px;
  display: flex; align-items: center; justify-content: space-between;
  background: rgba(10,12,15,.9);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--border);
  transition: background .3s;
}
body.light #site-header { background: rgba(255,255,255,.94); }
 
.logo {
  display: flex; align-items: center; gap: 10px;
  font-family: var(--font-display); font-weight: 800; font-size: 22px;
  letter-spacing: -.5px; color: var(--text);
}
.logo-icon {
  width: 32px; height: 32px; background: var(--green);
  border-radius: 8px; display: flex; align-items: center; justify-content: center;
}
.logo-icon svg { width: 18px; height: 18px; }
.logo em { font-style: normal; color: var(--green); }
 
.main-nav { display: flex; align-items: center; gap: 4px; }
.main-nav a {
  font-family: var(--font-mono); font-size: 13px;
  color: var(--text-dim); padding: 6px 14px;
  border-radius: 8px; transition: all .2s; letter-spacing: .02em;
}
.main-nav a:hover  { color: var(--text); background: var(--bg3); }
.main-nav a.active { color: var(--green); }
 
.header-right { display: flex; align-items: center; gap: 12px; }
 
.live-badge {
  font-family: var(--font-mono); font-size: 11px;
  background: rgba(0,255,136,.1); color: var(--green);
  border: 1px solid var(--border-g);
  padding: 3px 10px; border-radius: 20px; letter-spacing: .05em;
  display: flex; align-items: center; gap: 6px;
}
.live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); animation: blink 1.5s infinite; }
 
.icon-btn {
  width: 36px; height: 36px; border: 1px solid var(--border);
  background: var(--bg2); border-radius: var(--r);
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; color: var(--text-dim); transition: all .2s;
}
.icon-btn:hover { border-color: var(--border-g); color: var(--green); }
 
.btn-nav-outline {
  font-family: var(--font-mono); font-size: 13px;
  padding: 8px 20px; border: 1px solid var(--border);
  background: transparent; color: var(--text-dim);
  border-radius: var(--r); transition: all .2s;
}
.btn-nav-outline:hover { border-color: var(--green); color: var(--green); }
 
.btn-nav-solid {
  font-family: var(--font-mono); font-size: 13px; font-weight: 700;
  padding: 8px 20px; background: var(--green); color: #000;
  border: none; border-radius: var(--r); transition: all .2s;
}
.btn-nav-solid:hover { background: #00e67a; transform: translateY(-1px); }
 
/* ══════════════════════════════════════
   SECTION UTILS
══════════════════════════════════════ */
.section        { padding: 90px 40px; }
.section-inner  { max-width: 1100px; margin: 0 auto; }
.section-label  { font-family: var(--font-mono); font-size: 11px; color: var(--green); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 14px; }
.section-title  { font-size: clamp(30px,4vw,48px); font-weight: 800; letter-spacing: -1.5px; line-height: 1.1; color: var(--text); margin-bottom: 16px; }
.section-title em { font-style: normal; color: var(--green); }
.section-desc   { font-family: var(--font-mono); font-size: 13px; color: var(--text-dim); line-height: 1.8; max-width: 520px; }
 
/* ══════════════════════════════════════
   HERO
══════════════════════════════════════ */
.hero {
  min-height: 100vh;
  display: flex; align-items: center;
  padding: 120px 40px 90px;
  position: relative; overflow: hidden;
}
.hero-bg {
  position: absolute; inset: 0; pointer-events: none;
  background:
    radial-gradient(ellipse 60% 50% at 50% 0%, rgba(0,255,136,.07) 0%, transparent 70%),
    radial-gradient(ellipse 40% 60% at 80% 50%, rgba(77,159,255,.04) 0%, transparent 60%);
}
.hero-grid {
  position: absolute; inset: 0; pointer-events: none;
  background-image: linear-gradient(var(--border) 1px,transparent 1px), linear-gradient(90deg,var(--border) 1px,transparent 1px);
  background-size: 60px 60px;
  mask-image: radial-gradient(ellipse 80% 80% at 50% 50%,black 0%,transparent 100%);
  opacity: .4;
}
.hero-inner {
  max-width: 1100px; margin: 0 auto; width: 100%;
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 80px; align-items: center; position: relative;
}
.hero-tag {
  display: inline-flex; align-items: center; gap: 8px;
  font-family: var(--font-mono); font-size: 12px; color: var(--green);
  background: rgba(0,255,136,.08); border: 1px solid var(--border-g);
  padding: 5px 14px; border-radius: 20px;
  margin-bottom: 26px; letter-spacing: .05em;
}
.hero-tag::before { content:''; width:7px; height:7px; background:var(--green); border-radius:50%; animation:blink 1.5s infinite; }
 
.hero-title {
  font-size: clamp(46px,5.5vw,72px); font-weight: 800;
  letter-spacing: -2px; line-height: 1.05; margin-bottom: 22px;
}
.hero-title em { font-style: normal; color: var(--green); }
 
.hero-desc {
  font-family: var(--font-mono); font-size: 15px;
  color: var(--text-dim); line-height: 1.8;
  margin-bottom: 38px; max-width: 430px;
}
.hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 44px; }
 
.btn-hero {
  font-family: var(--font-mono); font-size: 14px; font-weight: 700;
  padding: 14px 32px; background: var(--green); color: #000;
  border: none; border-radius: var(--r); transition: all .2s; letter-spacing: .03em;
}
.btn-hero:hover { background: #00e67a; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,255,136,.25); }
 
.btn-hero-outline {
  font-family: var(--font-mono); font-size: 14px;
  padding: 14px 32px; background: transparent; color: var(--text-dim);
  border: 1px solid var(--border); border-radius: var(--r); transition: all .2s;
}
.btn-hero-outline:hover { border-color: var(--text-dim); color: var(--text); }
 
.hero-stats { display: flex; gap: 38px; padding-top: 38px; border-top: 1px solid var(--border); }
.stat-num   { font-size: 30px; font-weight: 800; letter-spacing: -1px; color: var(--text); margin-bottom: 4px; }
.stat-num span { color: var(--green); }
.stat-label { font-family: var(--font-mono); font-size: 11px; color: var(--text-muted); letter-spacing: .06em; text-transform: uppercase; }
 
/* DEMO CARD */
.demo-card {
  background: var(--bg2); border: 1px solid var(--border);
  border-radius: var(--rl); padding: 24px; position: relative; overflow: hidden;
}
.demo-card::before { content:''; position:absolute; top:0;left:0;right:0;height:1px; background:linear-gradient(90deg,transparent,var(--green),transparent); opacity:.5; }
.card-dots { display:flex; gap:6px; margin-bottom:18px; }
.card-dot  { width:10px; height:10px; border-radius:50%; }
.cd-r { background:var(--red); } .cd-y { background:var(--amber); } .cd-g { background:var(--green); }
.card-titlebar { font-family:var(--font-mono); font-size:11px; color:var(--text-muted); margin-left:auto; letter-spacing:.05em; }
 
.typing-modes { display:flex; gap:4px; margin-bottom:16px; padding:4px; background:var(--bg3); border-radius:8px; }
.mode-btn {
  flex:1; text-align:center; font-family:var(--font-mono); font-size:12px;
  padding:6px; border-radius:6px; color:var(--text-muted); cursor:pointer;
  letter-spacing:.04em; background:transparent; border:none; transition:all .2s;
}
.mode-btn.active { background:var(--bg2); color:var(--green); border:1px solid var(--border-g); }
 
.mini-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:16px; }
.mini-stat  { background:var(--bg3); border:1px solid var(--border); border-radius:8px; padding:10px; text-align:center; }
.mini-val   { font-family:var(--font-mono); font-size:22px; font-weight:700; color:var(--green); letter-spacing:-1px; }
.mini-key   { font-family:var(--font-mono); font-size:10px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.07em; margin-top:2px; }
 
.demo-text-area {
  background:var(--bg3); border:1px solid var(--border);
  border-radius:8px; padding:14px 16px;
  font-family:var(--font-mono); font-size:15px; line-height:1.8; margin-bottom:12px;
}
.t-ok  { color:var(--text); }
.t-err { color:var(--red); background:rgba(255,77,106,.12); border-radius:2px; }
.t-cur { display:inline-block; width:2px; height:18px; background:var(--green); vertical-align:middle; animation:blink 1s infinite; margin:0 1px; }
.t-dim { color:var(--text-muted); }
 
.prog-wrap { height:3px; background:var(--bg3); border-radius:2px; overflow:hidden; }
.prog-fill { height:100%; background:linear-gradient(90deg,var(--green-dim),var(--green)); border-radius:2px; animation:prog 3s ease-in-out infinite alternate; }
 
/* ══════════════════════════════════════
   FEATURES
══════════════════════════════════════ */
.features-section { background:var(--bg2); border-top:1px solid var(--border); border-bottom:1px solid var(--border); }
.features-grid {
  display:grid; grid-template-columns:repeat(3,1fr);
  gap:1px; background:var(--border);
  border:1px solid var(--border); border-radius:var(--rl);
  overflow:hidden; margin-top:52px;
}
.feat-card { background:var(--bg2); padding:32px 28px; transition:background .2s; }
.feat-card:hover { background:var(--bg3); }
.feat-icon {
  width:44px; height:44px; background:rgba(0,255,136,.08);
  border:1px solid var(--border-g); border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  font-size:20px; margin-bottom:18px;
}
.feat-name { font-size:17px; font-weight:700; letter-spacing:-.3px; margin-bottom:9px; }
.feat-desc { font-family:var(--font-mono); font-size:13px; color:var(--text-dim); line-height:1.7; }
 
/* ══════════════════════════════════════
   CHALLENGE / TEST MODES
══════════════════════════════════════ */
.challenge-section { padding-top:10px; }
.challenge-head { max-width:760px; margin-bottom:40px; }
.challenge-grid {
  display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:18px;
}
.challenge-card {
  background:#0b0f14; border:1px solid rgba(255,255,255,.08);
  border-radius:20px; padding:28px 24px; min-height:200px;
  transition:transform .2s ease, border-color .2s ease, box-shadow .2s ease;
}
.challenge-card:hover { transform:translateY(-4px); border-color:var(--border-g); box-shadow:0 16px 30px rgba(0,0,0,.24); }
.challenge-card-active { border-color:rgba(0,255,136,.65); box-shadow:0 0 0 1px rgba(0,255,136,.12) inset; }
.challenge-icon { font-size:28px; margin-bottom:18px; }
.challenge-card h3 { margin:0 0 12px; font-size:18px; font-weight:700; }
.challenge-card p  { margin:0; color:var(--text-muted); line-height:1.7; font-size:14px; font-family:var(--font-mono); }
 
/* ══════════════════════════════════════
   HOME LEADERBOARD
══════════════════════════════════════ */
.home-leaderboard-section { padding-top:10px; }
.home-leaderboard-head  { max-width:760px; margin-bottom:34px; }
.home-leaderboard-card  { background:#12161c; border:1px solid rgba(255,255,255,.08); border-radius:22px; overflow:hidden; }
.home-leaderboard-table { width:100%; }
 
.home-lb-row {
  display:grid;
  grid-template-columns:70px minmax(0,1.8fr) 120px 120px 130px;
  align-items:center; gap:16px;
  padding:18px 24px;
  border-top:1px solid rgba(255,255,255,.06);
}
.home-lb-row:first-child { border-top:0; }
.home-lb-head {
  background:rgba(255,255,255,.03);
  color:var(--text-muted); font-size:12px;
  text-transform:uppercase; letter-spacing:.08em; font-family:var(--font-mono);
}
.rank        { font-weight:700; font-family:var(--font-mono); color:#dfe7f5; }
.rank.gold   { color:#ffd84d; }
.rank.silver { color:#cfd6df; }
.rank.bronze { color:#ff9f43; }
 
.user-cell { display:flex; align-items:center; gap:14px; }
.user-badge {
  width:36px; height:36px; border-radius:50%;
  background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08);
  display:flex; align-items:center; justify-content:center;
  font-size:12px; font-weight:700; color:var(--green); font-family:var(--font-mono);
  flex-shrink:0;
}
.user-name { font-weight:700; font-size:15px; }
.user-meta { color:var(--text-muted); font-size:12px; margin-top:3px; font-family:var(--font-mono); }
.wpm-cell  { color:var(--green); font-weight:700; font-size:16px; font-family:var(--font-mono); }
.acc-cell  { font-family:var(--font-mono); font-size:13px; color:var(--text-dim); }
.time-cell { font-family:var(--font-mono); font-size:12px; color:var(--text-muted); }
 
/* ══════════════════════════════════════
   AUTH SHOWCASE (join section)
══════════════════════════════════════ */
.auth-showcase-section { padding-top:10px; background:var(--bg2); border-top:1px solid var(--border); }
 
.auth-showcase-grid {
  display:grid; grid-template-columns:1fr 1fr;
  gap:80px; align-items:center;
}
.auth-showcase-title {
  font-size:clamp(36px,4.5vw,58px); font-weight:800;
  letter-spacing:-2px; line-height:1.05; margin-bottom:20px;
}
.auth-showcase-title em { font-style:normal; color:var(--green); }
.auth-showcase-desc {
  font-family:var(--font-mono); font-size:14px;
  color:var(--text-dim); line-height:1.8;
  margin-bottom:36px; max-width:460px;
}
.auth-showcase-subtitle { font-size:20px; font-weight:700; margin-bottom:20px; }
.auth-showcase-perks { list-style:none; display:flex; flex-direction:column; gap:14px; padding:0; }
.auth-showcase-perks li {
  display:flex; gap:12px; align-items:flex-start;
  font-family:var(--font-mono); font-size:13px;
  color:var(--text-dim); line-height:1.7;
}
.auth-showcase-perks li span {
  width:20px; height:20px; flex-shrink:0;
  border-radius:6px; display:inline-flex; align-items:center; justify-content:center;
  background:rgba(0,255,136,.12); border:1px solid var(--border-g);
  color:var(--green); font-size:12px; font-weight:700; margin-top:1px;
}
 
/* FORM SIDE */
.auth-showcase-form { position:relative; top:auto; }
.auth-showcase-tabs {
  display:flex; border:1px solid var(--border);
  border-radius:var(--r); overflow:hidden; margin-bottom:24px;
}
.auth-showcase-tab {
  flex:1; border:0; background:transparent;
  color:var(--text-muted); padding:11px;
  font-family:var(--font-mono); font-size:13px;
  transition:all .2s; letter-spacing:.04em;
}
.auth-showcase-tab.active { background:var(--green); color:#000; font-weight:700; }
 
.auth-panel { display:none; }
.auth-panel.active { display:block; }
 
.auth-showcase-social { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px; }
.auth-showcase-social-btn {
  display:flex; align-items:center; justify-content:center; gap:8px;
  padding:11px; border:1px solid var(--border);
  background:var(--bg3); color:var(--text-dim);
  border-radius:var(--r); font-family:var(--font-mono);
  font-size:13px; cursor:pointer; transition:all .2s;
}
.auth-showcase-social-btn:hover { border-color:var(--text-dim); color:var(--text); }
 
.auth-showcase-divider {
  display:flex; align-items:center; gap:12px;
  margin:16px 0; color:var(--text-muted);
  font-family:var(--font-mono); font-size:11px;
}
.auth-showcase-divider::before,.auth-showcase-divider::after { content:""; flex:1; height:1px; background:var(--border); }
 
.auth-showcase-fields { display:flex; flex-direction:column; gap:14px; }
.auth-showcase-group  { display:flex; flex-direction:column; gap:7px; }
.auth-showcase-group label {
  font-family:var(--font-mono); font-size:10px;
  letter-spacing:.1em; text-transform:uppercase; color:var(--text-muted);
}
.auth-showcase-group input, .auth-showcase-group select {
  width:100%; background:var(--bg3); border:1px solid var(--border);
  border-radius:var(--r); padding:12px 16px;
  font-family:var(--font-mono); font-size:14px; color:var(--text);
  outline:none; transition:border-color .2s;
}
.auth-showcase-group input:focus { border-color:var(--green); }
.auth-showcase-group input::placeholder { color:var(--text-muted); }
.auth-form-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
 
.auth-showcase-submit {
  width:100%; margin-top:6px; padding:13px;
  border:0; border-radius:var(--r);
  background:var(--green); color:#000;
  font-family:var(--font-mono); font-size:14px; font-weight:700;
  cursor:pointer; transition:all .2s; letter-spacing:.04em;
}
.auth-showcase-submit:hover { filter:brightness(1.08); transform:translateY(-1px); box-shadow:0 6px 20px rgba(0,255,136,.22); }
 
.auth-showcase-note {
  margin-top:14px; text-align:center;
  font-family:var(--font-mono); font-size:12px; color:var(--text-muted);
}
.auth-showcase-note span  { color:var(--green); cursor:pointer; }
.auth-showcase-note a     { color:var(--text-dim); }
 
/* ══════════════════════════════════════
   TESTIMONIALS
══════════════════════════════════════ */
.info-section { background:var(--bg); }
.testimonials-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-top:56px; }
.testimonial {
  background:var(--bg2); border:1px solid var(--border);
  border-radius:var(--rl); padding:28px;
  transition:border-color .2s;
}
.testimonial:hover { border-color:var(--border-g); }
.testimonial-stars { color:var(--amber); font-size:13px; letter-spacing:2px; margin-bottom:14px; }
.testimonial-text  { font-family:var(--font-mono); font-size:13px; color:var(--text-dim); line-height:1.8; margin-bottom:20px; }
.testimonial-user  { display:flex; align-items:center; gap:12px; }
.t-avatar {
  width:38px; height:38px; border-radius:50%;
  background:var(--bg3); border:1px solid var(--border);
  display:flex; align-items:center; justify-content:center;
  font-family:var(--font-mono); font-size:12px; font-weight:700; color:var(--green);
  flex-shrink:0;
}
.t-name { font-size:14px; font-weight:600; margin-bottom:2px; }
.t-role { font-family:var(--font-mono); font-size:11px; color:var(--text-muted); }
 
/* ══════════════════════════════════════
   CTA SECTION
══════════════════════════════════════ */
.cta-section {
  background:var(--bg2); border-top:1px solid var(--border);
  border-bottom:1px solid var(--border);
  text-align:center;
}
 
/* ══════════════════════════════════════
   FOOTER
══════════════════════════════════════ */
#site-footer {
  background:var(--bg);
  border-top:1px solid var(--border);
  padding:64px 40px 0;
}
.footer-inner { max-width:1100px; margin:0 auto; }
.footer-grid {
  display:grid; grid-template-columns:2fr 1fr 1fr 1fr;
  gap:48px; margin-bottom:56px;
}
.footer-brand p {
  font-family:var(--font-mono); font-size:13px;
  color:var(--text-dim); line-height:1.7;
  margin:14px 0 24px; max-width:280px;
}
.footer-socials { display:flex; gap:10px; }
.social-btn {
  width:36px; height:36px;
  background:var(--bg2); border:1px solid var(--border);
  border-radius:8px; display:flex; align-items:center; justify-content:center;
  color:var(--text-dim); font-family:var(--font-mono); font-size:12px;
  transition:all .2s;
}
.social-btn:hover { border-color:var(--green); color:var(--green); }
.footer-col-title {
  font-family:var(--font-mono); font-size:10px;
  color:var(--text-muted); letter-spacing:.12em; text-transform:uppercase;
  margin-bottom:20px;
}
.footer-links { list-style:none; display:flex; flex-direction:column; gap:12px; }
.footer-links a { font-family:var(--font-mono); font-size:13px; color:var(--text-dim); transition:color .2s; }
.footer-links a:hover { color:var(--green); }
.footer-bottom {
  border-top:1px solid var(--border); padding:24px 0;
  display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;
}
.footer-copyright { font-family:var(--font-mono); font-size:12px; color:var(--text-muted); }
.footer-copyright span { color:var(--green); }
.footer-status { display:flex; align-items:center; gap:6px; font-family:var(--font-mono); font-size:12px; color:var(--green); }
.status-dot { width:7px; height:7px; border-radius:50%; background:var(--green); animation:blink 2s infinite; }
.footer-links-bottom { display:flex; gap:22px; }
.footer-links-bottom a { font-family:var(--font-mono); font-size:12px; color:var(--text-muted); transition:color .2s; }
.footer-links-bottom a:hover { color:var(--text-dim); }
 
/* TOAST */
#toast {
  position:fixed; bottom:24px; right:24px; z-index:999;
  background:var(--bg2); border:1px solid var(--border-g);
  border-radius:var(--r); padding:13px 20px;
  font-family:var(--font-mono); font-size:13px; color:var(--text);
  display:none; align-items:center; gap:10px;
  box-shadow:0 8px 32px rgba(0,0,0,.35);
  animation:slideIn .3s ease;
}
#toast.show { display:flex; }
.toast-dot { width:8px; height:8px; border-radius:50%; background:var(--green); flex-shrink:0; }
 
/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media (max-width:900px) {
  .hero-inner            { grid-template-columns:1fr; gap:40px; }
  .features-grid         { grid-template-columns:1fr; }
  .challenge-grid        { grid-template-columns:1fr 1fr; }
  .testimonials-grid     { grid-template-columns:1fr; }
  .auth-showcase-grid    { grid-template-columns:1fr; gap:48px; }
  .footer-grid           { grid-template-columns:1fr 1fr; }
  .main-nav              { display:none; }
  .home-lb-row { grid-template-columns:60px minmax(0,1fr) 100px 100px; }
  .home-lb-row > div:last-child { display:none; }
}
@media (max-width:640px) {
  #site-header           { padding:0 18px; }
  .hero                  { padding:100px 20px 60px; }
  .section               { padding:60px 20px; }
  #site-footer           { padding:48px 20px 0; }
  .challenge-grid        { grid-template-columns:1fr; }
  .footer-grid           { grid-template-columns:1fr; }
  .auth-form-row         { grid-template-columns:1fr; }
  .auth-showcase-social  { grid-template-columns:1fr; }
  .home-lb-row           { grid-template-columns:48px 1fr; gap:10px; }
  .home-lb-row > div:nth-child(3),
  .home-lb-row > div:nth-child(4),
  .home-lb-row > div:nth-child(5) { display:none; }
  .footer-bottom         { flex-direction:column; align-items:flex-start; }
}
</style>
</head>
<body>
 
<!-- ══════════════ HEADER ══════════════ -->
<header id="site-header">
  <a href="index.php" class="logo">
    <div class="logo-icon">
      <svg viewBox="0 0 18 18" fill="none">
        <rect x="1" y="7"  width="4" height="10" rx="1" fill="#000"/>
        <rect x="7" y="4"  width="4" height="13" rx="1" fill="#000"/>
        <rect x="13" y="1" width="4" height="16" rx="1" fill="#000"/>
      </svg>
    </div>
    Type<em>Blaze</em>
  </a>
 
  <nav class="main-nav">
    <a href="index.php"       class="active">Home</a>
    <a href="test.php">Test</a>
    <a href="leaderboard.php">Leaderboard</a>
    <a href="dashboard.php">Dashboard</a>
  </nav>
 
  <div class="header-right">
    <div class="live-badge"><div class="live-dot"></div>LIVE</div>
    <button class="icon-btn" id="theme-btn" title="Toggle theme">🌙</button>
    <a href="login.php"         class="btn-nav-outline">Log In</a>
    <a href="login.php#signup"  class="btn-nav-solid">Sign Up</a>
  </div>
</header>
 
<!-- ══════════════ HERO ══════════════ -->
<section class="hero" id="home">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-inner">
 
    <!-- LEFT -->
    <div>
      <div class="hero-tag">The Ultimate Typing Challenge</div>
      <h1 class="hero-title">
        How fast do you<br><em>really type?</em>
      </h1>
      <p class="hero-desc">
        Test your typing speed, track your progress, and compete with
        typists worldwide. Real-time stats, detailed analytics, and
        daily challenges await.
      </p>
      <div class="hero-actions">
        <a href="test.php"          class="btn-hero">▶ Start Typing Test</a>
        <a href="login.php#signup"  class="btn-hero-outline">Create Free Account</a>
      </div>
      <div class="hero-stats">
        <div>
          <div class="stat-num" id="stat-users">2<span>M+</span></div>
          <div class="stat-label">Users</div>
        </div>
        <div>
          <div class="stat-num" id="stat-tests">48<span>M+</span></div>
          <div class="stat-label">Tests taken</div>
        </div>
        <div>
          <div class="stat-num">220<span> wpm</span></div>
          <div class="stat-label">Top score</div>
        </div>
      </div>
    </div>
 
    <!-- DEMO CARD -->
    <div class="demo-card">
      <div class="card-dots" style="display:flex;align-items:center">
        <div class="card-dot cd-r"></div>
        <div class="card-dot cd-y"></div>
        <div class="card-dot cd-g"></div>
        <div class="card-titlebar">typeblaze.io — speed test</div>
      </div>
 
      <div class="typing-modes">
        <button class="mode-btn active">15s</button>
        <button class="mode-btn">30s</button>
        <button class="mode-btn">60s</button>
        <button class="mode-btn">words</button>
        <button class="mode-btn">code</button>
      </div>
 
      <div class="mini-stats">
        <div class="mini-stat">
          <div class="mini-val" id="wpm-counter">87</div>
          <div class="mini-key">WPM</div>
        </div>
        <div class="mini-stat">
          <div class="mini-val">96%</div>
          <div class="mini-key">Accuracy</div>
        </div>
        <div class="mini-stat">
          <div class="mini-val" id="time-counter">11</div>
          <div class="mini-key">Seconds</div>
        </div>
      </div>
 
      <div class="demo-text-area">
        <span class="t-ok">the quick brown fox </span><span class="t-err">jums</span><span class="t-cur"></span><span class="t-dim">ps over the lazy dog and runs away</span>
      </div>
 
      <div class="prog-wrap"><div class="prog-fill"></div></div>
    </div>
 
  </div>
</section>
 
<!-- ══════════════ FEATURES ══════════════ -->
<section class="section features-section" id="features">
  <div class="section-inner">
    <div class="section-label">// why typeblaze</div>
    <h2 class="section-title">Built for speed.<br><em>Designed to improve.</em></h2>
    <p class="section-desc">Everything you need to go from average to elite, all in one place, completely free.</p>
 
    <div class="features-grid">
      <div class="feat-card"><div class="feat-icon">⚡</div><div class="feat-name">Real-time WPM</div><div class="feat-desc">Live words-per-minute tracking updates every keystroke so you always know exactly where you stand.</div></div>
      <div class="feat-card"><div class="feat-icon">🎯</div><div class="feat-name">Accuracy Tracking</div><div class="feat-desc">Detailed error analysis highlights your weak keys and helps you eliminate typos permanently over time.</div></div>
      <div class="feat-card"><div class="feat-icon">📊</div><div class="feat-name">Progress Analytics</div><div class="feat-desc">Historical charts and trend lines show your improvement over days, weeks, and months of practice.</div></div>
      <div class="feat-card"><div class="feat-icon">🏆</div><div class="feat-name">Leaderboard</div><div class="feat-desc">Compete with typists from across the platform. Daily, weekly, and all-time rankings always available.</div></div>
      <div class="feat-card"><div class="feat-icon">💻</div><div class="feat-name">Code Typing</div><div class="feat-desc">Practice with real code snippets in JavaScript, Python, SQL and more. Perfect for developers.</div></div>
      <div class="feat-card"><div class="feat-icon">🌙</div><div class="feat-name">Dark & Light Mode</div><div class="feat-desc">Switch between dark terminal mode or a clean light theme. Your preference is saved automatically.</div></div>
    </div>
  </div>
</section>
 
<!-- ══════════════ TEST MODES ══════════════ -->
<section class="section challenge-section" id="modes">
  <div class="section-inner">
    <div class="challenge-head">
      <div class="section-label">// test modes</div>
      <h2 class="section-title">Choose your <em>challenge.</em></h2>
      <p class="section-desc">Multiple test formats to match your goals — whether you're warming up or pushing your limits.</p>
    </div>
    <div class="challenge-grid">
      <div class="challenge-card challenge-card-active">
        <div class="challenge-icon">⏱️</div>
        <h3>Timed Test</h3>
        <p>15s, 30s, or 60s sprints. Pure speed mode for chasing your personal best WPM.</p>
      </div>
      <div class="challenge-card">
        <div class="challenge-icon">📝</div>
        <h3>Word Count</h3>
        <p>Type a fixed number of words (10, 25, 50, 100). Great for consistency training.</p>
      </div>
      <div class="challenge-card">
        <div class="challenge-icon">💻</div>
        <h3>Code Mode</h3>
        <p>Real syntax from actual open-source repos. Train fingers for brackets, underscores, and more.</p>
      </div>
      <div class="challenge-card">
        <div class="challenge-icon">📖</div>
        <h3>Quote Test</h3>
        <p>Famous quotes and literary passages. Variable length keeps your sessions interesting.</p>
      </div>
    </div>
  </div>
</section>
 
<!-- ══════════════ LEADERBOARD ══════════════ -->
<section class="section home-leaderboard-section" id="leaderboard">
  <div class="section-inner">
    <div class="home-leaderboard-head">
      <div class="section-label">// global leaderboard</div>
      <h2 class="section-title">Top typists, <em>ranked.</em></h2>
      <p class="section-desc">Updated in real-time. Can you break into the top 10?</p>
    </div>
 
    <div class="home-leaderboard-card">
      <div class="home-leaderboard-table">
 
        <div class="home-lb-row home-lb-head">
          <div>#</div><div>User</div><div>WPM</div><div>Accuracy</div><div>Time Ago</div>
        </div>
 
        <div class="home-lb-row">
          <div class="rank gold">🥇 01</div>
          <div class="user-cell">
            <div class="user-badge">SK</div>
            <div><div class="user-name">speedking99</div><div class="user-meta">🇰🇷 Korea</div></div>
          </div>
          <div class="wpm-cell">218 wpm</div>
          <div class="acc-cell">99.1%</div>
          <div class="time-cell">2 min ago</div>
        </div>
 
        <div class="home-lb-row">
          <div class="rank silver">🥈 02</div>
          <div class="user-cell">
            <div class="user-badge">FM</div>
            <div><div class="user-name">fingermage</div><div class="user-meta">🇩🇪 Germany</div></div>
          </div>
          <div class="wpm-cell">207 wpm</div>
          <div class="acc-cell">98.4%</div>
          <div class="time-cell">14 min ago</div>
        </div>
 
        <div class="home-lb-row">
          <div class="rank bronze">🥉 03</div>
          <div class="user-cell">
            <div class="user-badge">QZ</div>
            <div><div class="user-name">qwertyzap</div><div class="user-meta">🇺🇸 USA</div></div>
          </div>
          <div class="wpm-cell">198 wpm</div>
          <div class="acc-cell">97.8%</div>
          <div class="time-cell">31 min ago</div>
        </div>
 
        <div class="home-lb-row">
          <div class="rank">04</div>
          <div class="user-cell">
            <div class="user-badge">KT</div>
            <div><div class="user-name">keytapper_x</div><div class="user-meta">🇯🇵 Japan</div></div>
          </div>
          <div class="wpm-cell">194 wpm</div>
          <div class="acc-cell">99.6%</div>
          <div class="time-cell">1 hr ago</div>
        </div>
 
        <div class="home-lb-row">
          <div class="rank">05</div>
          <div class="user-cell">
            <div class="user-badge">RS</div>
            <div><div class="user-name">rapidstroke</div><div class="user-meta">🇮🇳 India</div></div>
          </div>
          <div class="wpm-cell">187 wpm</div>
          <div class="acc-cell">98.1%</div>
          <div class="time-cell">2 hr ago</div>
        </div>
 
      </div>
    </div>
 
    <div style="text-align:center;margin-top:24px">
      <a href="leaderboard.php" class="btn-hero-outline" style="display:inline-block;padding:11px 28px;font-family:var(--font-mono);font-size:13px;border:1px solid var(--border);border-radius:var(--r);color:var(--text-dim);transition:all .2s">View Full Leaderboard →</a>
    </div>
  </div>
</section>
 
<!-- ══════════════ AUTH SHOWCASE ══════════════ -->
<section class="section auth-showcase-section" id="join">
  <div class="section-inner auth-showcase-grid">
 
    <!-- INFO -->
    <div class="auth-showcase-info">
      <div class="section-label">// join typeblaze</div>
      <h2 class="auth-showcase-title">Track every<br><em>keystroke.</em></h2>
      <p class="auth-showcase-desc">
        Create a free account to save your stats, unlock all test
        modes, and compete on the leaderboard.
      </p>
      <div class="auth-showcase-subtitle">Why create an account?</div>
      <ul class="auth-showcase-perks">
        <li><span>✓</span>Save unlimited test results and track your WPM history over time</li>
        <li><span>✓</span>Appear on the global leaderboard and compete with top typists worldwide</li>
        <li><span>✓</span>Get personalized training recommendations based on your weak spots</li>
        <li><span>✓</span>Unlock all test modes including code, quotes, and custom word lists</li>
        <li><span>✓</span>Access 40+ themes and full keyboard layout customization</li>
        <li><span>✓</span>Daily and weekly challenges with exclusive achievements and badges</li>
      </ul>
    </div>
 
    <!-- FORM -->
    <div class="auth-showcase-form">
      <div class="auth-showcase-tabs">
        <button class="auth-showcase-tab active" id="tab-login"  onclick="switchAuthTab('login')">Log In</button>
        <button class="auth-showcase-tab"         id="tab-signup" onclick="switchAuthTab('signup')">Sign Up</button>
      </div>
 
      <!-- LOGIN PANEL -->
      <div class="auth-panel active" id="panel-login">
        <div class="auth-showcase-social">
          <button class="auth-showcase-social-btn">
            <svg width="15" height="15" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Google
          </button>
          <button class="auth-showcase-social-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.44 9.8 8.2 11.39.6.11.82-.26.82-.58v-2.23c-3.34.73-4.03-1.42-4.03-1.42-.55-1.39-1.34-1.76-1.34-1.76-1.09-.74.08-.73.08-.73 1.2.08 1.84 1.24 1.84 1.24 1.07 1.83 2.8 1.3 3.49.99.11-.78.42-1.3.76-1.6-2.67-.3-5.47-1.33-5.47-5.93 0-1.31.47-2.38 1.24-3.22-.14-.3-.54-1.52.1-3.18 0 0 1.01-.32 3.3 1.23a11.5 11.5 0 0 1 3-.4c1.02 0 2.04.14 3 .4 2.28-1.55 3.29-1.23 3.29-1.23.65 1.66.24 2.88.12 3.18.77.84 1.23 1.91 1.23 3.22 0 4.61-2.81 5.63-5.48 5.92.43.37.81 1.1.81 2.22v3.29c0 .32.21.69.82.57C20.57 21.8 24 17.3 24 12c0-6.63-5.37-12-12-12z"/></svg>
            GitHub
          </button>
        </div>
        <div class="auth-showcase-divider">or continue with email</div>
        <div class="auth-showcase-fields">
          <div class="auth-showcase-group">
            <label>Email Address</label>
            <input type="email" placeholder="you@example.com">
          </div>
          <div class="auth-showcase-group">
            <label>Password</label>
            <input type="password" placeholder="••••••••">
          </div>
          <a href="login.php"><button class="auth-showcase-submit" type="button">Log In to TypeBlaze</button></a>
        </div>
        <div class="auth-showcase-note">
          Forgot password? <a href="login.php">Reset here</a><br>
          Don't have an account? <span onclick="switchAuthTab('signup')">Sign up free →</span>
        </div>
      </div>
 
      <!-- SIGNUP PANEL -->
      <div class="auth-panel" id="panel-signup">
        <div class="auth-showcase-social">
          <button class="auth-showcase-social-btn">
            <svg width="15" height="15" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Google
          </button>
          <button class="auth-showcase-social-btn">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.44 9.8 8.2 11.39.6.11.82-.26.82-.58v-2.23c-3.34.73-4.03-1.42-4.03-1.42-.55-1.39-1.34-1.76-1.34-1.76-1.09-.74.08-.73.08-.73 1.2.08 1.84 1.24 1.84 1.24 1.07 1.83 2.8 1.3 3.49.99.11-.78.42-1.3.76-1.6-2.67-.3-5.47-1.33-5.47-5.93 0-1.31.47-2.38 1.24-3.22-.14-.3-.54-1.52.1-3.18 0 0 1.01-.32 3.3 1.23a11.5 11.5 0 0 1 3-.4c1.02 0 2.04.14 3 .4 2.28-1.55 3.29-1.23 3.29-1.23.65 1.66.24 2.88.12 3.18.77.84 1.23 1.91 1.23 3.22 0 4.61-2.81 5.63-5.48 5.92.43.37.81 1.1.81 2.22v3.29c0 .32.21.69.82.57C20.57 21.8 24 17.3 24 12c0-6.63-5.37-12-12-12z"/></svg>
            GitHub
          </button>
        </div>
        <div class="auth-showcase-divider">or sign up with email</div>
        <div class="auth-showcase-fields">
          <div class="auth-form-row">
            <div class="auth-showcase-group">
              <label>First Name</label>
              <input type="text" placeholder="Alex">
            </div>
            <div class="auth-showcase-group">
              <label>Last Name</label>
              <input type="text" placeholder="Chen">
            </div>
          </div>
          <div class="auth-showcase-group">
            <label>Username</label>
            <input type="text" placeholder="speedtyper42">
          </div>
          <div class="auth-showcase-group">
            <label>Email Address</label>
            <input type="email" placeholder="you@example.com">
          </div>
          <div class="auth-showcase-group">
            <label>Password</label>
            <input type="password" placeholder="Min. 6 characters">
          </div>
          <a href="login.php#signup"><button class="auth-showcase-submit" type="button">Create Free Account</button></a>
        </div>
        <div class="auth-showcase-note">
          By signing up you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a><br>
          Already have an account? <span onclick="switchAuthTab('login')">Log in →</span>
        </div>
      </div>
 
    </div>
  </div>
</section>
 
<!-- ══════════════ TESTIMONIALS ══════════════ -->
<section class="section info-section" id="about">
  <div class="section-inner">
    <div class="section-label">// community voices</div>
    <h2 class="section-title">Trusted by <em>2.4 million</em> typists.</h2>
    <p class="section-desc">From casual users to professional stenographers — TypeBlaze works for everyone.</p>
 
    <div class="testimonials-grid">
      <div class="testimonial">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"Went from 65 WPM to 112 WPM in 3 months. The error analysis feature completely transformed how I type. I had no idea I was making the same mistakes every single time."</p>
        <div class="testimonial-user">
          <div class="t-avatar">PR</div>
          <div><div class="t-name">Priya Rao</div><div class="t-role">Software Engineer · 112 WPM</div></div>
        </div>
      </div>
      <div class="testimonial">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"The code typing mode is insane. Finally a test that makes me practice brackets and semicolons. My actual programming speed went up noticeably after just two weeks."</p>
        <div class="testimonial-user">
          <div class="t-avatar">MK</div>
          <div><div class="t-name">Marcus Klein</div><div class="t-role">Backend Developer · 98 WPM</div></div>
        </div>
      </div>
      <div class="testimonial">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"I use this during breaks at work. The leaderboard keeps me coming back. Hit 140 WPM last week and made the weekly top 100. Addictive in the best way possible."</p>
        <div class="testimonial-user">
          <div class="t-avatar">SL</div>
          <div><div class="t-name">Sophie Laurent</div><div class="t-role">UX Designer · 140 WPM</div></div>
        </div>
      </div>
    </div>
  </div>
</section>
 
<!-- ══════════════ CTA ══════════════ -->
<section class="section cta-section">
  <div class="section-inner">
    <div class="section-label" style="display:flex;justify-content:center">// get started today</div>
    <h2 class="section-title" style="text-align:center;max-width:520px;margin:0 auto 16px">Ready to find out how fast you type?</h2>
    <p class="section-desc" style="text-align:center;margin:0 auto 36px">Join thousands of typists improving their speed every day. Free forever.</p>
    <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap">
      <a href="test.php"          class="btn-hero">▶ Try Without Account</a>
      <a href="login.php#signup"  class="btn-hero-outline">Create Free Account</a>
    </div>
  </div>
</section>
 
<!-- ══════════════ FOOTER ══════════════ -->
<footer id="site-footer">
  <div class="footer-inner">
    <div class="footer-grid">
 
      <div class="footer-brand">
        <a href="index.php" class="logo">
          <div class="logo-icon">
            <svg viewBox="0 0 18 18" fill="none"><rect x="1" y="7" width="4" height="10" rx="1" fill="#000"/><rect x="7" y="4" width="4" height="13" rx="1" fill="#000"/><rect x="13" y="1" width="4" height="16" rx="1" fill="#000"/></svg>
          </div>
          Type<em>Blaze</em>
        </a>
        <p>The world's fastest growing typing test platform. Measure your speed, improve your accuracy, and compete with the best.</p>
        <div class="footer-socials">
          <a href="#" class="social-btn">𝕏</a>
          <a href="#" class="social-btn">in</a>
          <a href="#" class="social-btn">gh</a>
          <a href="#" class="social-btn">dc</a>
          <a href="#" class="social-btn">yt</a>
        </div>
      </div>
 
      <div>
        <div class="footer-col-title">Product</div>
        <ul class="footer-links">
          <li><a href="test.php">Speed Test</a></li>
          <li><a href="test.php">Code Mode</a></li>
          <li><a href="test.php">Quote Mode</a></li>
          <li><a href="leaderboard.php">Leaderboard</a></li>
          <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
      </div>
 
      <div>
        <div class="footer-col-title">Resources</div>
        <ul class="footer-links">
          <li><a href="#">Typing Tips</a></li>
          <li><a href="#">Keyboard Guide</a></li>
          <li><a href="#">WPM Calculator</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Changelog</a></li>
        </ul>
      </div>
 
      <div>
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press Kit</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
        </ul>
      </div>
 
    </div>
 
    <div class="footer-bottom">
      <div class="footer-copyright">© 2026 <span>TypeBlaze</span>. All rights reserved. Made with ⚡ for fast fingers everywhere.</div>
      <div class="footer-status"><div class="status-dot"></div>All systems operational</div>
      <div class="footer-links-bottom">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Cookies</a>
        <a href="#">Sitemap</a>
      </div>
    </div>
  </div>
</footer>
 
<!-- TOAST -->
<div id="toast"><div class="toast-dot"></div><span id="toast-msg"></span></div>
 
<script>
$(function () {
 
  /* ── THEME TOGGLE ── */
  const saved = document.cookie.replace(/(?:(?:^|.*;\s*)tb_theme\s*=\s*([^;]*).*$)|^.*$/, '$1');
  if (saved === 'light') $('body').addClass('light');
  updateThemeBtn();
 
  $('#theme-btn').on('click', function () {
    $('body').toggleClass('light');
    const t = $('body').hasClass('light') ? 'light' : 'dark';
    document.cookie = `tb_theme=${t};path=/;max-age=31536000`;
    updateThemeBtn();
  });
 
  function updateThemeBtn() {
    $('#theme-btn').text($('body').hasClass('light') ? '☀️' : '🌙');
  }
 
  /* ── DEMO WPM COUNTER ── */
  let wpm = 87, dir = 1;
  setInterval(() => {
    wpm += dir * Math.floor(Math.random() * 3);
    if (wpm > 130) dir = -1;
    if (wpm < 60)  dir =  1;
    $('#wpm-counter').text(wpm);
  }, 900);
 
  /* ── DEMO TIMER ── */
  let t = 11;
  setInterval(() => {
    t = t > 0 ? t - 1 : 15;
    $('#time-counter').text(t);
  }, 1000);
 
  /* ── MODE BUTTONS ── */
  $('.typing-modes .mode-btn').on('click', function () {
    $('.typing-modes .mode-btn').removeClass('active');
    $(this).addClass('active');
  });
 
  /* ── SMOOTH SCROLL FOR NAV ── */
  $('a[href^="#"]').on('click', function (e) {
    const target = $(this.getAttribute('href'));
    if (target.length) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: target.offset().top - 70 }, 400);
    }
  });
 
});
 
/* ── AUTH TAB SWITCH ── */
function switchAuthTab(tab) {
  $('#panel-login, #panel-signup').removeClass('active');
  $('#tab-login, #tab-signup').removeClass('active');
  $('#panel-' + tab).addClass('active');
  $('#tab-' + tab).addClass('active');
}
 
/* ── TOAST ── */
let _tt;
function showToast(msg) {
  $('#toast-msg').text(msg);
  $('#toast').addClass('show');
  clearTimeout(_tt);
  _tt = setTimeout(() => $('#toast').removeClass('show'), 3000);
}
</script>
</body>
</html>
