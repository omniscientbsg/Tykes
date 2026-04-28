const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, 'Tykes Html Templates', 'tykes-curriculum.html');
let html = fs.readFileSync(filePath, 'utf8');

// 1. Replace CSS
const oldCss = `    /* hero right - spinning curriculum wheel */
    .curr-hero-visual{animation:popIn .9s .2s ease both;position:relative;display:flex;justify-content:center;align-items:center}
    .wheel-wrap{position:relative;width:380px;height:380px}
    .wheel-ring{position:absolute;inset:0;border-radius:50%;border:2px dashed rgba(255,255,255,.2);animation:spin 40s linear infinite}
    .wheel-ring-inner{position:absolute;inset:40px;border-radius:50%;border:2px dashed rgba(253,188,2,.25);animation:spin 28s linear infinite reverse}
    .wheel-center{position:absolute;inset:90px;border-radius:50%;background:rgba(255,255,255,.08);backdrop-filter:blur(10px);border:2px solid rgba(255,255,255,.15);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px}
    .wheel-center .big{font-family:'Fredoka',cursive;font-size:2.8rem;font-weight:700;color:var(--gold);line-height:1}
    .wheel-center .small{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:rgba(255,255,255,.7)}
    .wheel-orb{position:absolute;width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.2);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;font-size:1.6rem;box-shadow:0 8px 24px rgba(0,0,0,.25)}
    .orb-top{top:-10px;left:50%;transform:translateX(-50%)}
    .orb-right{top:50%;right:-10px;transform:translateY(-50%)}
    .orb-bottom{bottom:-10px;left:50%;transform:translateX(-50%)}
    .orb-left{top:50%;left:-10px;transform:translateY(-50%)}`;

const newCss = `    /* hero right - image box */
    .curr-hero-visual { animation:popIn .95s .18s ease both; position:relative; }
    .hero-img-wrap { position:relative; }
    .hero-img-frame { background:rgba(255,255,255,.07); border:1.5px solid rgba(255,255,255,.18); border-radius:28px; padding:10px; backdrop-filter:blur(6px); box-shadow:0 32px 80px rgba(0,0,0,.40); position:relative; z-index:1; }
    .hero-img-main { width:100%; height:390px; object-fit:cover; border-radius:20px; display:block; }
    .hb-curriculum { position:absolute; top:-18px; left:-18px; background:white; border-radius:16px; padding:13px 18px; box-shadow:0 12px 40px rgba(0,0,0,.2); display:flex; align-items:center; gap:10px; min-width:170px; z-index:2; }
    .hb-curriculum .hb-icon { font-size:1.8rem; flex-shrink:0; }
    .hb-curriculum h5 { font-family:'Fredoka',cursive; font-size:1rem; font-weight:700; color:var(--p); margin-bottom:1px; line-height:1.1; }
    .hb-curriculum p { font-size:.72rem; color:var(--muted); font-weight:600; }`;

html = html.replace(oldCss, newCss);

// 2. Replace HTML
const oldHtml = `    <!-- Spinning wheel visual -->
    <div class="curr-hero-visual">
      <div class="wheel-wrap">
        <div class="wheel-ring"></div>
        <div class="wheel-ring-inner"></div>
        <div class="wheel-center">
          <span class="big">🎓</span>
          <span class="small">Our Curriculum</span>
        </div>
        <div class="wheel-orb orb-top">🎨</div>
        <div class="wheel-orb orb-right">🔬</div>
        <div class="wheel-orb orb-bottom">📚</div>
        <div class="wheel-orb orb-left">🧩</div>
      </div>
    </div>`;

const newHtml = `    <!-- Image card with floating badges -->
    <div class="curr-hero-visual">
      <div class="hero-img-wrap">
        <div class="hb-curriculum b-poppins">
          <span class="hb-icon">🎯</span>
          <div>
            <h5>A+ Framework</h5>
            <p>Kidzonia Standard</p>
          </div>
        </div>
        <div class="hero-img-frame">
          <img
            src="https://tykes.school/wp-content/uploads/2026/04/6.png"
            alt="Tykes Early Years Curriculum"
            class="hero-img-main">
        </div>
      </div>
    </div>`;

html = html.replace(oldHtml, newHtml);

fs.writeFileSync(filePath, html);
console.log('Curriculum hero replaced!');
