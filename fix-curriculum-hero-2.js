const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, 'Tykes Html Templates', 'tykes-curriculum.html');
let html = fs.readFileSync(filePath, 'utf8');

// Normalize line endings for replacement
let normalizedHtml = html.replace(/\r\n/g, '\n');

// 1. Replace CSS
const oldCssRegex = /\/\*\s*hero right - spinning curriculum wheel\s*\*\/[\s\S]*?\.orb-left\{[^}]+\}/;
const newCss = `    /* hero right - image box */
    .curr-hero-visual { animation:popIn .95s .18s ease both; position:relative; }
    .hero-img-wrap { position:relative; }
    .hero-img-frame { background:rgba(255,255,255,.07); border:1.5px solid rgba(255,255,255,.18); border-radius:28px; padding:10px; backdrop-filter:blur(6px); box-shadow:0 32px 80px rgba(0,0,0,.40); position:relative; z-index:1; }
    .hero-img-main { width:100%; height:390px; object-fit:cover; border-radius:20px; display:block; }
    .hb-curriculum { position:absolute; top:-18px; left:-18px; background:white; border-radius:16px; padding:13px 18px; box-shadow:0 12px 40px rgba(0,0,0,.2); display:flex; align-items:center; gap:10px; min-width:170px; z-index:2; }
    .hb-curriculum .hb-icon { font-size:1.8rem; flex-shrink:0; }
    .hb-curriculum h5 { font-family:'Fredoka',cursive; font-size:1rem; font-weight:700; color:var(--p); margin-bottom:1px; line-height:1.1; }
    .hb-curriculum p { font-size:.72rem; color:var(--muted); font-weight:600; }`;

normalizedHtml = normalizedHtml.replace(oldCssRegex, newCss);

// 2. Replace HTML
const oldHtmlRegex = /<!-- Spinning wheel visual -->[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/;
const newHtml = `<!-- Image card with floating badges -->
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

normalizedHtml = normalizedHtml.replace(oldHtmlRegex, newHtml);

fs.writeFileSync(filePath, normalizedHtml);
console.log('Curriculum hero replaced properly!');
