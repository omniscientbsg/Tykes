const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');

  // 1. Force header logo to 60px — catch ALL variants
  content = content.replace(/\.header-logo img\{height:\d+px/g, '.header-logo img{height:60px');

  // 2. Remove drawer logo entirely
  content = content.replace(/<div class="drawer-logo"><img[^>]*><\/div>\n?/g, '');
  // Remove drawer-logo CSS margin that creates empty space
  content = content.replace(/\.drawer-logo\{margin-bottom:24px\}/g, '');
  content = content.replace(/\.drawer-logo img\{height:\d+px\}/g, '');

  // 3. Replace mobile sub-menu items with colorful dots
  // About Us sub-menu
  content = content.replace(
    /<div class="mob-sub" id="sub-about-dd">\s*<a href="tykes-about\.html">About Us<\/a>\s*<a href="tykes-philosophy\.html">Our Philosophy<\/a>\s*<a href="tykes-leadership\.html">Our Leadership<\/a>\s*<a href="tykes-spaces\.html">Our Learning Spaces<\/a>\s*<a href="tykes-awards\.html">Awards &amp; Recognition<\/a>\s*<\/div>/g,
    `<div class="mob-sub" id="sub-about-dd">
        <a href="tykes-about.html"><span class="dot" style="background:#7C3AED;"></span> About Us</a>
        <a href="tykes-philosophy.html"><span class="dot" style="background:#F97316;"></span> Our Philosophy</a>
        <a href="tykes-leadership.html"><span class="dot" style="background:#EC4899;"></span> Our Leadership</a>
        <a href="tykes-spaces.html"><span class="dot" style="background:#14B8A6;"></span> Our Learning Spaces</a>
        <a href="tykes-awards.html"><span class="dot" style="background:#F59E0B;"></span> Awards &amp; Recognition</a>
      </div>`
  );

  // Curriculum sub-menu
  content = content.replace(
    /<div class="mob-sub" id="sub-curriculum-dd">\s*<a href="tykes-curriculum\.html">Our Curriculum<\/a>\s*<a href="tykes-difference\.html">The Tykes Difference<\/a>\s*<a href="tykes-day\.html">A Day @ Tykes<\/a>\s*<a href="tykes-commitment\.html">Our Commitment<\/a>\s*<\/div>/g,
    `<div class="mob-sub" id="sub-curriculum-dd">
        <a href="tykes-curriculum.html"><span class="dot" style="background:#7C3AED;"></span> Our Curriculum</a>
        <a href="tykes-difference.html"><span class="dot" style="background:#F97316;"></span> The Tykes Difference</a>
        <a href="tykes-day.html"><span class="dot" style="background:#22C55E;"></span> A Day @ Tykes</a>
        <a href="tykes-commitment.html"><span class="dot" style="background:#14B8A6;"></span> Our Commitment</a>
      </div>`
  );

  // Programs sub-menu
  content = content.replace(
    /<div class="mob-sub" id="sub-programs-dd">\s*<a href="tykes-programmes\.html">Our Programmes<\/a>\s*<a href="tykes-programmes\.html#playgroup">Play Group<\/a>\s*<a href="tykes-programmes\.html#nursery">Nursery<\/a>\s*<a href="tykes-programmes\.html#junior">Junior KG<\/a>\s*<a href="tykes-programmes\.html#senior">Senior KG<\/a>\s*<a href="tykes-programmes\.html#daycare">Daycare<\/a>\s*<\/div>/g,
    `<div class="mob-sub" id="sub-programs-dd">
        <a href="tykes-programmes.html"><span class="dot" style="background:#7C3AED;"></span> Our Programmes</a>
        <a href="tykes-programmes.html#playgroup"><span class="dot" style="background:#A78BFA;"></span> Play Group</a>
        <a href="tykes-programmes.html#nursery"><span class="dot" style="background:#F97316;"></span> Nursery</a>
        <a href="tykes-programmes.html#junior"><span class="dot" style="background:#0EA5E9;"></span> Junior KG</a>
        <a href="tykes-programmes.html#senior"><span class="dot" style="background:#22C55E;"></span> Senior KG</a>
        <a href="tykes-programmes.html#daycare"><span class="dot" style="background:#F59E0B;"></span> Daycare</a>
      </div>`
  );

  // Franchise sub-menu
  content = content.replace(
    /<div class="mob-sub" id="sub-franchise-dd">\s*<a href="tykes-franchise-main\.html">Franchise Overview<\/a>\s*<a href="tykes-why-partner\.html">Why Partner With Us<\/a>\s*<a href="tykes-how-we-support\.html">How We Support You<\/a>\s*<a href="tykes-franchise-application\.html">Franchise Application<\/a>\s*<\/div>/g,
    `<div class="mob-sub" id="sub-franchise-dd">
        <a href="tykes-franchise-main.html"><span class="dot" style="background:#7C3AED;"></span> Franchise Overview</a>
        <a href="tykes-why-partner.html"><span class="dot" style="background:#14B8A6;"></span> Why Partner With Us</a>
        <a href="tykes-how-we-support.html"><span class="dot" style="background:#F59E0B;"></span> How We Support You</a>
        <a href="tykes-franchise-application.html"><span class="dot" style="background:#EC4899;"></span> Franchise Application</a>
      </div>`
  );

  // Contact sub-menu
  content = content.replace(
    /<div class="mob-sub" id="sub-contact-dd">\s*<a href="tykes-contact\.html">Get in Touch<\/a>\s*<a href="tykes-centers-list\.html">Our Centers<\/a>\s*<\/div>/g,
    `<div class="mob-sub" id="sub-contact-dd">
        <a href="tykes-contact.html"><span class="dot" style="background:#7C3AED;"></span> Get in Touch</a>
        <a href="tykes-centers-list.html"><span class="dot" style="background:#14B8A6;"></span> Our Centers</a>
      </div>`
  );

  // 4. Update mob-sub CSS: remove the ::before pseudo dot (we now use inline dots)
  content = content.replace(
    /\.mob-sub a::before\{content:'';width:6px;height:6px;border-radius:50%;background:var\(--pl\);flex-shrink:0\}/g,
    ''
  );

  // 5. Add .dot styling inside mob-sub if not present
  if (content.includes('.mob-sub') && !content.includes('.mob-sub a .dot')) {
    content = content.replace(
      /\.mob-sub a:hover\{color:var\(--p\);background:var\(--pxl\)\}/g,
      '.mob-sub a .dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;display:inline-block}\n    .mob-sub a:hover{color:var(--p);background:var(--pxl)}'
    );
  }

  // 6. Reduce top padding of drawer-panel since logo is removed
  content = content.replace(
    /\.drawer-panel\{[^}]*padding:28px 24px/g,
    (match) => match.replace('padding:28px 24px', 'padding:16px 24px')
  );

  fs.writeFileSync(path.join(dir, f), content);
  console.log(`✅ ${f}`);
});

console.log('\n═══ Done: 60px logo, no sidebar logo, colorful dots in mobile menu ═══');
