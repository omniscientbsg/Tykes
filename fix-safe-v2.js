/**
 * SAFE COMPREHENSIVE FIX v2
 * Uses targeted, non-greedy replacements to avoid destroying hero sections.
 * Key difference: Instead of replacing entire mobile drawer blocks with regex,
 * we inject a NEW standardized drawer block AFTER the header and REMOVE the old one
 * by matching its exact opening/closing pattern carefully.
 */
const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

const COLORED_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-without-Kidzonia-Enterprise-04-scaled-e1774873351568.png';
const FOOTER_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png';

// ── CSS snippet for submenu bridge ──
const SUBMENU_BRIDGE_CSS = '.nav-dropdown::before { content: ""; position: absolute; top: -15px; left: 0; right: 0; height: 15px; background: transparent; }';

// ── Standard nav HTML ──
const STANDARD_NAV = `<nav class="main-nav" aria-label="Main Navigation">
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

// ── Standard mobile drawer ──
const STANDARD_DRAWER = `<div class="mobile-drawer" id="mobileDrawer" onclick="handleDrawerClick(event)">
    <div class="drawer-panel">
      <div class="drawer-logo"><img src="${COLORED_LOGO}" alt="Tykes Early Years"></div>
      <a href="tykes-homepage.html" class="mob-link">Home</a>
      <a href="tykes-about.html" class="mob-link">About Us</a>
      <button class="mob-link" onclick="toggleMobSub('sub-about-dd')">↳ Sub-menu <span>›</span></button>
      <div class="mob-sub" id="sub-about-dd">
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

// ── Standard footer ──
const STANDARD_FOOTER = `<footer class="site-footer">
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

// ═══════════════════════════════════════
// SAFE REPLACEMENT FUNCTIONS
// ═══════════════════════════════════════

/**
 * Safely find and replace the <nav class="main-nav"...>...</nav> block.
 * Uses indexOf/lastIndexOf for precise boundaries instead of greedy regex.
 */
function safeReplaceNav(content) {
  const startTag = '<nav class="main-nav"';
  const endTag = '</nav>';
  const startIdx = content.indexOf(startTag);
  if (startIdx === -1) return content;
  const endIdx = content.indexOf(endTag, startIdx);
  if (endIdx === -1) return content;
  return content.substring(0, startIdx) + STANDARD_NAV + content.substring(endIdx + endTag.length);
}

/**
 * Safely find and replace the mobile drawer block.
 * Strategy: Find the EXACT start marker, then find its matching end
 * by counting div open/close tags.
 */
function safeReplaceDrawer(content) {
  const marker = '<div class="mobile-drawer" id="mobileDrawer"';
  const startIdx = content.indexOf(marker);
  if (startIdx === -1) return content;
  
  // Count nested divs to find the matching close
  let depth = 0;
  let i = startIdx;
  let foundStart = false;
  while (i < content.length) {
    if (content.substring(i, i + 4) === '<div') {
      depth++;
      foundStart = true;
    }
    if (content.substring(i, i + 6) === '</div>') {
      depth--;
      if (foundStart && depth === 0) {
        // Found the matching close of the outermost mobile-drawer div
        const endIdx = i + 6;
        return content.substring(0, startIdx) + STANDARD_DRAWER + content.substring(endIdx);
      }
    }
    i++;
  }
  return content; // Safety: return unchanged if we can't find match
}

/**
 * Safely replace the footer block.
 */
function safeReplaceFooter(content) {
  const startTag = '<footer class="site-footer">';
  const endTag = '</footer>';
  const startIdx = content.indexOf(startTag);
  if (startIdx === -1) return content;
  const endIdx = content.indexOf(endTag, startIdx);
  if (endIdx === -1) return content;
  
  // Also remove any comment line right before the footer
  let actualStart = startIdx;
  const linesBefore = content.substring(Math.max(0, startIdx - 100), startIdx);
  const commentMatch = linesBefore.match(/(<!--[^>]*FOOTER[^>]*-->[\s\n]*$)/i);
  if (commentMatch) {
    actualStart = startIdx - commentMatch[1].length;
  }
  
  return content.substring(0, actualStart) + STANDARD_FOOTER + content.substring(endIdx + endTag.length);
}

// ═══════════════════════════════════════
// PROCESS ALL FILES
// ═══════════════════════════════════════
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');
  const hasHeroBefore = content.includes('hero-sec') || content.includes('about-hero') || content.includes('class="hero"') || content.includes('hero-left');

  // 1. Strip PHP
  content = content.replace(/<\?php[\s\S]*?\?>\s*/g, '');

  // 2. Fix header logo src (use colored logo)
  content = content.replace(/(<a[^>]*class="header-logo"[^>]*>\s*<img src=")[^"]*(")/g, `$1${COLORED_LOGO}$2`);

  // 3. Replace nav (SAFE)
  content = safeReplaceNav(content);

  // 4. Replace mobile drawer (SAFE)
  content = safeReplaceDrawer(content);

  // 5. Replace footer (SAFE)  
  content = safeReplaceFooter(content);

  // 6. Submenu bridge CSS
  if (content.includes('.nav-dropdown') && !content.includes('.nav-dropdown::before')) {
    content = content.replace(/\.nav-item:hover \.nav-dropdown\s*\{/, SUBMENU_BRIDGE_CSS + '\n    .nav-item:hover .nav-dropdown {');
  }

  // 7. Fix remaining absolute links
  content = content.replace(/href="https:\/\/tykes\.school\/corporate-daycare\/?"/g, 'href="tykes-corporate-daycare.html"');
  content = content.replace(/href="https:\/\/tykes\.school\/tykes-programmes\/(#[^"]*)"/g, 'href="tykes-programmes.html$1"');
  content = content.replace(/href="https:\/\/tykes\.school\/tykes-programmes\/?"/g, 'href="tykes-programmes.html"');
  content = content.replace(/href="https:\/\/tykes\.school\/?"/g, 'href="tykes-homepage.html"');

  // 8. Fix Junior KG single button
  if (f === 'tykes-programmes.html') {
    content = content.replace(
      /<button class="btn-primary" style="background:linear-gradient\(135deg,#0EA5E9,#14B8A6\);color:white;box-shadow:0 8px 24px rgba\(14,165,233,\.3\);" onclick="tykesOpenPopup\(\)">Learn More <span class="arrow">→<\/span><\/button>/,
      `<div class="prog-btn-group">
          <button class="btn-primary" style="background:linear-gradient(135deg,#0EA5E9,#14B8A6);color:white;box-shadow:0 8px 24px rgba(14,165,233,.3);" onclick="tykesOpenPopup()">Enroll Now <span class="arrow">→</span></button>
          <button class="btn-secondary" style="color:#0EA5E9;" onclick="document.getElementById('junior').scrollIntoView({behavior:'smooth'})">Learn More →</button>
        </div>`
    );
  }

  // VERIFY: hero section is still present
  const hasHeroAfter = content.includes('hero-sec') || content.includes('about-hero') || content.includes('class="hero"') || content.includes('hero-left');
  if (hasHeroBefore && !hasHeroAfter) {
    console.log(`🚨 HERO DESTROYED in ${f}! Aborting save for this file.`);
    return; // Don't save this file
  }

  fs.writeFileSync(path.join(dir, f), content);
  console.log(`✅ Fixed: ${f}`);
});

console.log('\n═══ All template files safely fixed. ═══');
