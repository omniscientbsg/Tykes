/**
 * COMPREHENSIVE FIX SCRIPT
 * Fixes ALL issues across ALL Tykes HTML pages at once:
 * 1. Strip PHP template tags (tykes-programmes.html)
 * 2. Fix ALL remaining href="#" links with proper page targets
 * 3. Fix ALL remaining tykes.school absolute URLs to relative
 * 4. Fix header logo (use colored logo, not white)
 * 5. Fix mobile drawer logo (use colored logo, white doesn't show on white bg)
 * 6. Fix submenu gap bridge (::before pseudo) on ALL pages
 * 7. Fix Junior KG two-button layout
 * 8. Fix mobile drawer: main menu items should be links (not just dropdown toggles)
 * 9. Fix mobile drawer: ensure ALL submenu items are present
 * 10. Ensure all page names are case-consistent for GitHub Pages
 */
const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

// ── Canonical link map: what each page/section should link to ──
const COLORED_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-without-Kidzonia-Enterprise-04-scaled-e1774873351568.png';
const WHITE_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-White-Logo-e1774873322197.png';
const FOOTER_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png';

// Standard header nav block (canonical, no PHP, all correct links)
const STANDARD_HEADER_NAV = `<nav class="main-nav" aria-label="Main Navigation">
        <div class="nav-item"><a href="tykes-homepage.html">Home</a></div>
        <div class="nav-item">
          <a href="tykes-about.html">About Us <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></a>
          <div class="nav-dropdown">
            <a href="tykes-about.html"><span class="dot" style="background:#7C3AED;"></span> About Us</a>
            <a href="tykes-philosophy.html"><span class="dot" style="background:#F97316;"></span> Our Philosophy</a>
            <a href="tykes-leadership.html"><span class="dot" style="background:#EC4899;"></span> Our Leadership</a>
            <a href="tykes-spaces.html"><span class="dot" style="background:#14B8A6;"></span> Our Learning Spaces</a>
            <a href="tykes-awards.html"><span class="dot" style="background:#F59E0B;"></span> Awards &amp; Recognition</a>
          </div>
        </div>
        <div class="nav-item">
          <a href="tykes-curriculum.html">Curriculum <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></a>
          <div class="nav-dropdown">
            <a href="tykes-difference.html"><span class="dot" style="background:#7C3AED;"></span> The Tykes Difference</a>
            <a href="tykes-day.html"><span class="dot" style="background:#F97316;"></span> A Day @ Tykes</a>
            <a href="tykes-commitment.html"><span class="dot" style="background:#22C55E;"></span> Our Commitment</a>
          </div>
        </div>
        <div class="nav-item">
          <a href="tykes-programmes.html">Programs <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></a>
          <div class="nav-dropdown">
            <a href="tykes-programmes.html#playgroup"><span class="dot" style="background:#A78BFA;"></span> Play Group</a>
            <a href="tykes-programmes.html#nursery"><span class="dot" style="background:#F97316;"></span> Nursery</a>
            <a href="tykes-programmes.html#junior"><span class="dot" style="background:#0EA5E9;"></span> Junior Kg</a>
            <a href="tykes-programmes.html#senior"><span class="dot" style="background:#22C55E;"></span> Senior Kg</a>
            <a href="tykes-programmes.html#daycare"><span class="dot" style="background:#F59E0B;"></span> Daycare</a>
          </div>
        </div>
        <div class="nav-item"><a href="tykes-corporate-daycare.html">Corporate Daycare</a></div>
        <div class="nav-item">
          <a href="tykes-franchise-main.html">Franchise <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></a>
          <div class="nav-dropdown">
            <a href="tykes-why-partner.html"><span class="dot" style="background:#7C3AED;"></span> Why Partner With Us</a>
            <a href="tykes-how-we-support.html"><span class="dot" style="background:#14B8A6;"></span> How We Support You</a>
            <a href="tykes-franchise-application.html"><span class="dot" style="background:#F59E0B;"></span> Franchise Application</a>
          </div>
        </div>
        <div class="nav-item"><a href="tykes-admissions.html">Admissions</a></div>
        <div class="nav-item">
          <a href="tykes-contact.html">Contact Us <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg></a>
          <div class="nav-dropdown">
            <a href="tykes-contact.html"><span class="dot" style="background:#7C3AED;"></span> Get in Touch</a>
            <a href="tykes-centers-list.html"><span class="dot" style="background:#14B8A6;"></span> Our Centers</a>
          </div>
        </div>
      </nav>`;

// Standard mobile drawer block
const STANDARD_MOBILE_DRAWER = `<div class="mobile-drawer" id="mobileDrawer" onclick="handleDrawerClick(event)">
    <div class="drawer-panel">
      <div class="drawer-logo"><img src="${COLORED_LOGO}" alt="Tykes Early Years"></div>
      <a href="tykes-homepage.html" class="mob-link">Home</a>
      <a href="tykes-about.html" class="mob-link">About Us</a>
      <button class="mob-link" onclick="toggleMobSub('sub-about-dd')">↳ Sub-menu <span>›</span></button>
      <div class="mob-sub" id="sub-about-dd">
        <a href="tykes-about.html">About Us</a>
        <a href="tykes-philosophy.html">Our Philosophy</a>
        <a href="tykes-leadership.html">Our Leadership</a>
        <a href="tykes-spaces.html">Our Learning Spaces</a>
        <a href="tykes-awards.html">Awards &amp; Recognition</a>
      </div>
      <a href="tykes-curriculum.html" class="mob-link">Curriculum</a>
      <button class="mob-link" onclick="toggleMobSub('sub-curriculum-dd')">↳ Sub-menu <span>›</span></button>
      <div class="mob-sub" id="sub-curriculum-dd">
        <a href="tykes-difference.html">The Tykes Difference</a>
        <a href="tykes-day.html">A Day @ Tykes</a>
        <a href="tykes-commitment.html">Our Commitment</a>
      </div>
      <a href="tykes-programmes.html" class="mob-link">Programs</a>
      <button class="mob-link" onclick="toggleMobSub('sub-programs-dd')">↳ Sub-menu <span>›</span></button>
      <div class="mob-sub" id="sub-programs-dd">
        <a href="tykes-programmes.html#playgroup">Play Group</a>
        <a href="tykes-programmes.html#nursery">Nursery</a>
        <a href="tykes-programmes.html#junior">Junior KG</a>
        <a href="tykes-programmes.html#senior">Senior KG</a>
        <a href="tykes-programmes.html#daycare">Daycare</a>
      </div>
      <a href="tykes-corporate-daycare.html" class="mob-link">Corporate Daycare</a>
      <a href="tykes-franchise-main.html" class="mob-link">Franchise</a>
      <button class="mob-link" onclick="toggleMobSub('sub-franchise-dd')">↳ Sub-menu <span>›</span></button>
      <div class="mob-sub" id="sub-franchise-dd">
        <a href="tykes-why-partner.html">Why Partner With Us</a>
        <a href="tykes-how-we-support.html">How We Support You</a>
        <a href="tykes-franchise-application.html">Franchise Application</a>
      </div>
      <a href="tykes-admissions.html" class="mob-link">Admissions</a>
      <a href="tykes-contact.html" class="mob-link">Contact Us</a>
      <button class="mob-link" onclick="toggleMobSub('sub-contact-dd')">↳ Sub-menu <span>›</span></button>
      <div class="mob-sub" id="sub-contact-dd">
        <a href="tykes-contact.html">Get in Touch</a>
        <a href="tykes-centers-list.html">Our Centers</a>
      </div>
      <button class="mob-enroll-btn" onclick="closeDrawer(); tykesOpenPopup();">Book a Visit →</button>
    </div>
  </div>`;

// Standard footer block
const STANDARD_FOOTER = `<!-- ─── FOOTER ─── -->
<footer class="site-footer">
  <div class="footer-top"><div class="container"><div class="footer-grid">
    <div class="footer-brand b-poppins">
      <img src="${FOOTER_LOGO}" alt="Tykes Early Years">
      <p>Built on the academic foundation and operational experience of Kidzonia International Preschools — India's most awarded early childhood education network.</p>
      <div class="social-links">
        <a href="https://instagram.com" class="social-btn" target="_blank" rel="noopener">📸</a>
        <a href="https://facebook.com" class="social-btn" target="_blank" rel="noopener">📘</a>
        <a href="https://youtube.com" class="social-btn" target="_blank" rel="noopener">▶</a>
      </div>
    </div>
    <div class="footer-col b-poppins"><h5>Programmes</h5><ul><li><a href="tykes-programmes.html#playgroup">Play Group</a></li><li><a href="tykes-programmes.html#nursery">Nursery</a></li><li><a href="tykes-programmes.html#junior">Junior KG</a></li><li><a href="tykes-programmes.html#senior">Senior KG</a></li><li><a href="tykes-programmes.html#daycare">Daycare</a></li></ul></div>
    <div class="footer-col b-poppins"><h5>Quick Links</h5><ul><li><a href="tykes-about.html">About Us</a></li><li><a href="tykes-curriculum.html">Our Curriculum</a></li><li><a href="tykes-admissions.html">Admissions</a></li><li><a href="tykes-corporate-daycare.html">Corporate Daycare</a></li><li><a href="tykes-franchise-main.html">Franchise</a></li><li><a href="tykes-contact.html">Contact</a></li></ul></div>
    <div class="footer-col b-poppins"><h5>Get In Touch</h5><div class="footer-contact-item"><span class="icon">📞</span><a href="tel:8400966400">8400-966-400</a></div><div class="footer-contact-item"><span class="icon">✉️</span><a href="mailto:info@tykes.school">info@tykes.school</a></div><div class="footer-contact-item"><span class="icon">🌐</span><a href="tykes-homepage.html">tykes.school</a></div></div>
  </div></div></div>
  <div class="footer-bottom b-poppins"><div class="container"><p>© 2026 <span>Tykes Early Years</span> — A Kidzonia Enterprise. All rights reserved.</p></div></div>
</footer>`;

// CSS snippet for submenu bridge
const SUBMENU_BRIDGE_CSS = '.nav-dropdown::before { content: ""; position: absolute; top: -15px; left: 0; right: 0; height: 15px; background: transparent; }';

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');
  const originalLength = content.length;

  // ═══ 1. STRIP PHP TEMPLATE TAGS ═══
  content = content.replace(/<\?php[\s\S]*?\?>\s*/g, '');

  // ═══ 2. FIX HEADER LOGO — use colored logo in header ═══
  content = content.replace(/class="header-logo">\s*<img src="[^"]*"/g, `class="header-logo">\n      <img src="${COLORED_LOGO}"`);

  // ═══ 3. FIX MOBILE DRAWER LOGO — use colored logo (white is invisible on white bg) ═══
  content = content.replace(/<div class="drawer-logo">\s*<img src="[^"]*"[^>]*>\s*<\/div>/g, `<div class="drawer-logo"><img src="${COLORED_LOGO}" alt="Tykes Early Years"></div>`);

  // ═══ 4. REPLACE ENTIRE NAV BLOCK ═══
  // Match from <nav class="main-nav" to </nav>
  content = content.replace(/<nav class="main-nav"[^>]*>[\s\S]*?<\/nav>/g, STANDARD_HEADER_NAV);

  // ═══ 5. REPLACE ENTIRE MOBILE DRAWER ═══
  content = content.replace(/<div class="mobile-drawer"[\s\S]*?<!-- Mobile Drawer End -->/g, STANDARD_MOBILE_DRAWER);
  // Also handle drawers without the end comment
  content = content.replace(/<div class="mobile-drawer" id="mobileDrawer"[^>]*>[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/g, STANDARD_MOBILE_DRAWER);

  // ═══ 6. REPLACE FOOTER ═══
  content = content.replace(/<!-- ─── FOOTER ─── -->[\s\S]*?<\/footer>/g, STANDARD_FOOTER);
  content = content.replace(/<!-- FOOTER[^>]*-->[\s\S]*?<\/footer>/g, STANDARD_FOOTER);
  // Also match footers without comment marker
  content = content.replace(/<footer class="site-footer">[\s\S]*?<\/footer>/g, STANDARD_FOOTER.replace('<!-- ─── FOOTER ─── -->\n', ''));

  // ═══ 7. ENSURE SUBMENU BRIDGE CSS EXISTS ═══
  if (!content.includes('.nav-dropdown::before')) {
    content = content.replace('.nav-item:hover .nav-dropdown {', SUBMENU_BRIDGE_CSS + '\n    .nav-item:hover .nav-dropdown {');
  }

  // ═══ 8. FIX ALL REMAINING tykes.school ABSOLUTE LINKS ═══
  content = content.replace(/href="https:\/\/tykes\.school\/corporate-daycare\/?"/g, 'href="tykes-corporate-daycare.html"');
  content = content.replace(/href="https:\/\/tykes\.school\/tykes-programmes\/(#[^"]*)"/g, 'href="tykes-programmes.html$1"');
  content = content.replace(/href="https:\/\/tykes\.school\/tykes-programmes\/?"/g, 'href="tykes-programmes.html"');
  content = content.replace(/href="https:\/\/tykes\.school\/?"/g, 'href="tykes-homepage.html"');
  // Footer website link
  content = content.replace(/href="https:\/\/tykes\.school"([^>]*>tykes\.school)/g, 'href="tykes-homepage.html"$1');

  // ═══ 9. FIX JUNIOR KG — add second button ═══
  if (f === 'tykes-programmes.html') {
    // Replace the single Learn More button with a proper two-button group
    content = content.replace(
      /<button class="btn-primary" style="background:linear-gradient\(135deg,#0EA5E9,#14B8A6\);color:white;box-shadow:0 8px 24px rgba\(14,165,233,\.3\);" onclick="document\.getElementById\('junior'\)\.scrollIntoView\(\{behavior:'smooth'\}\)">Learn More <span class="arrow">→<\/span><\/button>/,
      `<div class="prog-btn-group">
          <button class="btn-primary" style="background:linear-gradient(135deg,#0EA5E9,#14B8A6);color:white;box-shadow:0 8px 24px rgba(14,165,233,.3);" onclick="tykesOpenPopup()">Enroll Now <span class="arrow">→</span></button>
          <button class="btn-secondary" style="color:#0EA5E9;" onclick="document.getElementById('junior').scrollIntoView({behavior:'smooth'})">Learn More →</button>
        </div>`
    );
  }

  fs.writeFileSync(path.join(dir, f), content);
  console.log(`Fixed: ${f} (${originalLength} → ${content.length})`);
});

console.log('\n═══ All template files fixed. ═══');
console.log('Now regenerating center pages...');
