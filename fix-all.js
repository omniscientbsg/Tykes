const fs = require('fs');
const path = require('path');

const templatesDir = path.join(__dirname, 'Tykes Html Templates');

const newAwardsCSS = `
    /* ─── NEW AWARDS GALLERY ─── */
    .awards-sec { padding:100px 0; background:var(--bg-lav); }
    .awards-sec-inner { text-align: center; max-width: 800px; margin: 0 auto 50px; }
    .awards-tag { display:inline-flex; align-items:center; gap:8px; font-size:.75rem; font-weight:800; letter-spacing:2px; text-transform:uppercase; color:var(--p); margin-bottom:16px; font-family:'Poppins',sans-serif;}
    .awards-tag::before { content:''; display:block; width:24px; height:2px; background:var(--p); }
    .awards-title { font-family:'Fredoka',cursive; font-size:clamp(2.2rem,4vw,3.2rem); font-weight:700; color:var(--txt); margin-bottom:16px; line-height:1.15; }
    .awards-gallery { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; }
    .award-card { background:white; border-radius:20px; overflow:hidden; box-shadow:0 8px 30px rgba(130,87,189,.06); transition:.35s; border:1px solid rgba(130,87,189,.05); display:flex; flex-direction:column; }
    .award-card:hover { transform:translateY(-6px); box-shadow:0 20px 50px rgba(130,87,189,.15); }
    .award-img-wrap { width:100%; height:200px; padding:20px; display:flex; align-items:center; justify-content:center; background:#fcfbff; }
    .award-img-wrap img { max-height:100%; max-width:100%; object-fit:contain; }
    .award-body { padding:24px; text-align:center; border-top:1px solid rgba(130,87,189,.05); flex-grow:1; display:flex; flex-direction:column; justify-content:center; }
    .award-title-txt { font-family:'Fredoka',cursive; font-size:1.1rem; font-weight:700; color:var(--txt); margin-bottom:8px; line-height:1.3; }
    .award-org { font-size:.85rem; font-weight:600; color:var(--p); text-transform:uppercase; letter-spacing:1px; font-family:'Poppins',sans-serif; }
`;

const newAwardsHTML = `
  <!-- ─── NEW AWARDS GALLERY ─── -->
  <section class="awards-sec" id="recognitions">
    <div class="container">
      <div class="awards-sec-inner">
        <div class="awards-tag">A Legacy of Excellence</div>
        <h2 class="awards-title">Our Recognitions</h2>
      </div>
      <div class="awards-gallery">
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/navi-Mumbai.jpg" alt="India Early Childhood Excellence Index 2025" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">India Early Childhood Excellence Index 2025 (Navi Mumbai)</div>
            <div class="award-org">AIP &amp; CQECCE</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/Pune.jpg" alt="40 Under 40 Edupreneurs" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">India Early Childhood Excellence Index 2025 (Pune)</div>
            <div class="award-org">AIP &amp; CQECCE</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/vlcsnap-2026-03-24-11h00m52s556.png" alt="Most Promising Preschool &amp; Daycare" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Most Promising Preschool &amp; Daycare</div>
            <div class="award-org">Times of India</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/gold.png" alt="Innovation in Curriculum" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Innovation in Curriculum</div>
            <div class="award-org">BW Education</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/9-scaled.png" alt="Leading Preschool of India" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Leading Preschool of India</div>
            <div class="award-org">BW Education</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/Brainwonders-RNT-Award-copy-scaled.jpg" alt="Most Promising Preschool" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Rabindranath Tagore National Directors Award</div>
            <div class="award-org">Brainwonders</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/Award-002-02.png" alt="Pune's #1 Preschool" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">#2 in Navi Mumbai</div>
            <div class="award-org">The Times of India</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/001-scaled.jpeg" alt="Excellence in Early Education" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Leadership and Innovation in Education</div>
            <div class="award-org">The Times of India</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/06.jpg" alt="Top Preschool Brand" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Best Pre School Chain of the Year</div>
            <div class="award-org">Entrepreneur</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/03.jpg" alt="Quality Education Award" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">Certificate of Recognition</div>
            <div class="award-org">elets World Education Summit</div>
          </div>
        </div>
        <div class="award-card">
          <div class="award-img-wrap"><img src="https://tykes.school/wp-content/uploads/2026/03/Education-world-Award-copy.png" alt="Outstanding Preschool" loading="lazy"></div>
          <div class="award-body">
            <div class="award-title-txt">#9 in India</div>
            <div class="award-org">Education World</div>
          </div>
        </div>
      </div>
    </div>
  </section>
`;

const files = fs.readdirSync(templatesDir).filter(f => f.endsWith('.html'));

// Read standard header from tykes-about.html to use for corporate daycare
const aboutPath = path.join(templatesDir, 'tykes-about.html');
const aboutHtml = fs.readFileSync(aboutPath, 'utf8');

const headerCSSMatch = aboutHtml.match(/\/\* ══════════════════════════════════════════════════════════\s*HEADER.*?\/\* ══════════════════════════════════════════════════════════\s*POPUP MODAL/s);
const headerCSS = headerCSSMatch ? headerCSSMatch[0].replace(/\/\* ══════════════════════════════════════════════════════════\s*POPUP MODAL/s, '') : '';

const headerHTMLMatch = aboutHtml.match(/<!-- ══════════════════════════════════════════════════════════\s*HEADER.*?<!-- ══════════════════════════════════════════════════════════\s*HERO/s);
let headerHTML = headerHTMLMatch ? headerHTMLMatch[0].replace(/<!-- ══════════════════════════════════════════════════════════\s*HERO/s, '') : '';

files.forEach(file => {
  const filePath = path.join(templatesDir, file);
  let html = fs.readFileSync(filePath, 'utf8');
  let original = html;

  // 1. Mobile Hero Visibility
  html = html.replace(/\.[\w-]*hero-visual\s*\{\s*display:\s*none;?\s*\}/g, '/* removed */');
  
  // 2. Awards Replacement
  if (file === 'tykes-homepage.html' || file === 'tykes-center-template.html') {
    // Append CSS
    html = html.replace('</style>', newAwardsCSS + '\n</style>');
    
    // Replace achieve-sec
    const startIdx = html.indexOf('<section class="achieve-sec"');
    if (startIdx !== -1) {
      const endIdx = html.indexOf('</section>', startIdx);
      if (endIdx !== -1) {
        html = html.substring(0, startIdx) + newAwardsHTML + html.substring(endIdx + 10);
      }
    }
    
    // Remove awards-ticker from homepage
    if (file === 'tykes-homepage.html') {
      const tickerStart = html.indexOf('<!-- ─── AWARDS TICKER ─── -->');
      if (tickerStart !== -1) {
        const tickerEnd = html.indexOf('</div>', html.indexOf('</div>', tickerStart) + 1); // rough but enough if we just find the end
        // Let's use string operations safely
        const nextSec = html.indexOf('<!-- ─── CTA BAND ─── -->', tickerStart);
        if (nextSec !== -1) {
          html = html.substring(0, tickerStart) + html.substring(nextSec);
        }
      }
    }
  }
  
  // 3. Corporate Daycare Header Replacement
  if (file === 'tykes-corporate-daycare.html') {
    // Replace CSS: from /* ─── HEADER ─── */ to /* ─── TYPOGRAPHY HELPERS ─── */
    const cssStart = html.indexOf('/* ─── HEADER ─── */');
    const cssEnd = html.indexOf('/* ─── TYPOGRAPHY HELPERS ─── */');
    if (cssStart !== -1 && cssEnd !== -1) {
      html = html.substring(0, cssStart) + headerCSS + html.substring(cssEnd);
    }
    
    // Replace HTML: from <!-- ─── HEADER (Exact Match) ─── --> to <!-- ─── HERO ─── -->
    const htmlStart = html.indexOf('<!-- ─── HEADER (Exact Match) ─── -->');
    const htmlEnd = html.indexOf('<!-- ─── HERO ─── -->');
    if (htmlStart !== -1 && htmlEnd !== -1) {
      html = html.substring(0, htmlStart) + headerHTML + html.substring(htmlEnd);
    }
  }

  // 4. Programmes Page Updates
  if (file === 'tykes-programmes.html') {
    // Replace Junior KG button
    const oldBtn = '<button class="btn-primary" style="background:linear-gradient(135deg,#0EA5E9,#14B8A6);color:white;box-shadow:0 8px 24px rgba(14,165,233,.3);" onclick="document.getElementById(\\\'junior\\\').scrollIntoView({behavior:\\\'smooth\\\'})">Learn More <span class="arrow">→</span></button>';
    const newBtns = `
        <div class="prog-btn-group">
          <button class="btn-primary" style="background:linear-gradient(135deg,#0EA5E9,#14B8A6);color:white;box-shadow:0 8px 24px rgba(14,165,233,.3);" onclick="tykesOpenPopup()">Enroll Now <span class="arrow">→</span></button>
          <button class="btn-secondary" style="color:#0EA5E9;" onclick="window.location.href='tykes-junior-kg.html'">Learn More →</button>
        </div>`;
    // We can't rely on exact whitespace, so let's use regex for Junior KG replacement
    const juniorSectionStr = html.substring(html.indexOf('id="junior"'), html.indexOf('id="senior"'));
    const jrMatch = html.match(/<button class="btn-primary"[^>]*onclick="document.getElementById\('junior'\).scrollIntoView\(\{behavior:'smooth'\}\)"[^>]*>Learn More[^<]*<span[^>]*>→<\/span><\/button>/);
    if(jrMatch) {
       html = html.replace(jrMatch[0], newBtns);
    }
    
    // Update all Learn More buttons linking
    html = html.replace(/onclick="document\.getElementById\('playgroup'\)\.scrollIntoView\(\{behavior:'smooth'\}\)"/g, `onclick="window.location.href='tykes-playgroup.html'"`);
    html = html.replace(/onclick="document\.getElementById\('nursery'\)\.scrollIntoView\(\{behavior:'smooth'\}\)"/g, `onclick="window.location.href='tykes-nursery.html'"`);
    html = html.replace(/onclick="document\.getElementById\('senior'\)\.scrollIntoView\(\{behavior:'smooth'\}\)"/g, `onclick="window.location.href='tykes-senior-kg.html'"`);
    html = html.replace(/onclick="document\.getElementById\('daycare'\)\.scrollIntoView\(\{behavior:'smooth'\}\)"/g, `onclick="window.location.href='tykes-premium-daycare.html'"`);
  }
  
  if (original !== html) {
    fs.writeFileSync(filePath, html);
    console.log(`Updated ${file}`);
  }
});
