const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');

  // 1. Logo height 60px in header
  content = content.replace(/\.header-logo img\{height:52px/g, '.header-logo img{height:60px');
  content = content.replace(/\.header-logo img\{height:46px/g, '.header-logo img{height:60px');

  // 2. Remove self-referencing links from desktop dropdowns
  // Remove "About Us" from inside the About Us dropdown
  content = content.replace(
    /<a href="tykes-about\.html"><span class="dot" style="background:#7C3AED;"><\/span> About Us<\/a>\n/g,
    ''
  );
  // Also handle \r\n line endings
  content = content.replace(
    /<a href="tykes-about\.html"><span class="dot" style="background:#7C3AED;"><\/span> About Us<\/a>\r\n/g,
    ''
  );

  // Remove "Get in Touch" self-ref from Contact dropdown (it's same as Contact Us parent)
  content = content.replace(
    /<a href="tykes-contact\.html"><span class="dot" style="background:#7C3AED;"><\/span> Get in Touch<\/a>\n/g,
    ''
  );
  content = content.replace(
    /<a href="tykes-contact\.html"><span class="dot" style="background:#7C3AED;"><\/span> Get in Touch<\/a>\r\n/g,
    ''
  );

  // 3. Fix nav overlap — increase breakpoint from 991px to 1200px
  // This makes the hamburger menu kick in earlier before overlap happens
  content = content.replace(
    /@media\(max-width:991px\)\{\s*\.main-nav\{display:none\}\s*\.ham-btn\{display:flex\}/g,
    '@media(max-width:1200px){.main-nav{display:none} .ham-btn{display:flex}'
  );

  // Also make nav font slightly smaller to help with fitting
  content = content.replace(
    /\.nav-item>a\{[^}]*font-size:\.92rem/g,
    (match) => match.replace('font-size:.92rem', 'font-size:.85rem')
  );

  // Reduce nav item padding slightly
  content = content.replace(
    /\.nav-item>a\{[^}]*padding:8px 14px/g,
    (match) => match.replace('padding:8px 14px', 'padding:8px 12px')
  );

  fs.writeFileSync(path.join(dir, f), content);
  console.log(`✅ ${f}`);
});

console.log('\n═══ Fixed: logo 60px, removed self-ref dropdowns, responsive breakpoint ═══');
