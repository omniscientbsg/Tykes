const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

const KIDZONIA_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png';

// New mobile drawer with proper accordion UX
const NEW_MOBILE_DRAWER = `<div class="mobile-drawer" id="mobileDrawer" onclick="handleDrawerClick(event)">
    <div class="drawer-panel">
      <div class="drawer-logo"><img src="${KIDZONIA_LOGO}" alt="Tykes Early Years"></div>
      <a href="tykes-homepage.html" class="mob-link">Home</a>
      <button class="mob-link has-sub" onclick="toggleMobSub('sub-about-dd')">About Us <span class="mob-chevron">›</span></button>
      <div class="mob-sub" id="sub-about-dd">
        <a href="tykes-about.html">About Us</a>
        <a href="tykes-philosophy.html">Our Philosophy</a>
        <a href="tykes-leadership.html">Our Leadership</a>
        <a href="tykes-spaces.html">Our Learning Spaces</a>
        <a href="tykes-awards.html">Awards &amp; Recognition</a>
      </div>
      <button class="mob-link has-sub" onclick="toggleMobSub('sub-curriculum-dd')">Curriculum <span class="mob-chevron">›</span></button>
      <div class="mob-sub" id="sub-curriculum-dd">
        <a href="tykes-curriculum.html">Our Curriculum</a>
        <a href="tykes-difference.html">The Tykes Difference</a>
        <a href="tykes-day.html">A Day @ Tykes</a>
        <a href="tykes-commitment.html">Our Commitment</a>
      </div>
      <button class="mob-link has-sub" onclick="toggleMobSub('sub-programs-dd')">Programs <span class="mob-chevron">›</span></button>
      <div class="mob-sub" id="sub-programs-dd">
        <a href="tykes-programmes.html">Our Programmes</a>
        <a href="tykes-programmes.html#playgroup">Play Group</a>
        <a href="tykes-programmes.html#nursery">Nursery</a>
        <a href="tykes-programmes.html#junior">Junior KG</a>
        <a href="tykes-programmes.html#senior">Senior KG</a>
        <a href="tykes-programmes.html#daycare">Daycare</a>
      </div>
      <a href="tykes-corporate-daycare.html" class="mob-link">Corporate Daycare</a>
      <button class="mob-link has-sub" onclick="toggleMobSub('sub-franchise-dd')">Franchise <span class="mob-chevron">›</span></button>
      <div class="mob-sub" id="sub-franchise-dd">
        <a href="tykes-franchise-main.html">Franchise Overview</a>
        <a href="tykes-why-partner.html">Why Partner With Us</a>
        <a href="tykes-how-we-support.html">How We Support You</a>
        <a href="tykes-franchise-application.html">Franchise Application</a>
      </div>
      <a href="tykes-admissions.html" class="mob-link">Admissions</a>
      <button class="mob-link has-sub" onclick="toggleMobSub('sub-contact-dd')">Contact Us <span class="mob-chevron">›</span></button>
      <div class="mob-sub" id="sub-contact-dd">
        <a href="tykes-contact.html">Get in Touch</a>
        <a href="tykes-centers-list.html">Our Centers</a>
      </div>
      <button class="mob-enroll-btn" onclick="closeDrawer(); tykesOpenPopup();">Book a Visit →</button>
    </div>
  </div>`;

// Mobile drawer CSS additions
const MOB_DRAWER_CSS_ADDITIONS = `
    /* Mobile accordion chevron */
    .mob-link .mob-chevron{transition:transform .3s;font-size:1.1rem;color:var(--muted)}
    .mob-link.has-sub.active .mob-chevron{transform:rotate(90deg);color:var(--p)}
    .mob-sub a{padding:10px 14px;border-radius:10px;font-size:.9rem;font-weight:600;color:var(--muted);display:flex;align-items:center;gap:8px}
    .mob-sub a::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--pl);flex-shrink:0}
    .mob-sub a:hover{color:var(--p);background:var(--pxl)}`;

// Contact hero right side - replace cards with image
const CONTACT_HERO_IMAGE = `<!-- Hero image box -->
    <div class="ct-hero-visual" style="animation:popIn .9s .2s ease both">
      <img src="https://tykes.school/wp-content/uploads/2026/04/6.png" alt="Tykes Early Years Contact" style="border-radius:24px;width:100%;max-width:480px;box-shadow:0 30px 60px rgba(0,0,0,.35);border:6px solid rgba(255,255,255,.15)">
      <div style="position:absolute;bottom:20px;right:20px;background:rgba(255,255,255,.15);backdrop-filter:blur(12px);border:1.5px solid rgba(255,255,255,.25);border-radius:16px;padding:14px 20px;color:white;text-align:center">
        <div style="font-family:'Fredoka',cursive;font-size:1.8rem;font-weight:700;color:var(--gold);line-height:1">45+</div>
        <div style="font-size:.72rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.8)">Centres Across India</div>
      </div>
    </div>`;

function safeReplaceDrawer(content) {
  const marker = '<div class="mobile-drawer" id="mobileDrawer"';
  const startIdx = content.indexOf(marker);
  if (startIdx === -1) return content;
  let depth = 0, i = startIdx, foundStart = false;
  while (i < content.length) {
    if (content.substring(i, i + 4) === '<div') { depth++; foundStart = true; }
    if (content.substring(i, i + 6) === '</div>') {
      depth--;
      if (foundStart && depth === 0) return content.substring(0, startIdx) + NEW_MOBILE_DRAWER + content.substring(i + 6);
    }
    i++;
  }
  return content;
}

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');

  // 1. Replace ALL logos (header + drawer) with Kidzonia logo
  content = content.replace(/(<a[^>]*class="header-logo"[^>]*>\s*<img src=")[^"]*(")/g, `$1${KIDZONIA_LOGO}$2`);

  // 2. Replace mobile drawer with new accordion-style
  content = safeReplaceDrawer(content);

  // 3. Add mobile drawer CSS if not already present
  if (!content.includes('.mob-chevron') && content.includes('.mob-enroll-btn')) {
    content = content.replace('.mob-enroll-btn{', MOB_DRAWER_CSS_ADDITIONS + '\n    .mob-enroll-btn{');
  }

  // 4. Contact page: replace hero cards with image
  if (f === 'tykes-contact.html') {
    const cardsStart = content.indexOf('<div class="ct-hero-cards">');
    if (cardsStart !== -1) {
      // Find matching end
      let depth = 0, i = cardsStart, found = false;
      while (i < content.length) {
        if (content.substring(i, i + 4) === '<div') { depth++; found = true; }
        if (content.substring(i, i + 6) === '</div>') {
          depth--;
          if (found && depth === 0) {
            content = content.substring(0, cardsStart) + CONTACT_HERO_IMAGE + content.substring(i + 6);
            break;
          }
        }
        i++;
      }
    }
    // Also need position:relative on hero-inner for the badge
    content = content.replace('.ct-hero-cards{', '.ct-hero-visual{position:relative}\n    .ct-hero-cards{');
    // Hide image on mobile (keep text full width)
    if (!content.includes('.ct-hero-visual{display:none}')) {
      content = content.replace('.ct-hero-cards{display:none}', '.ct-hero-visual{display:none}');
    }
  }

  fs.writeFileSync(path.join(dir, f), content);
  console.log(`✅ ${f}`);
});

console.log('\n═══ All fixes applied ═══');
