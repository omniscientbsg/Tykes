const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

const NEW_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/01-Tykes-with-Kidzonia-Enterprise-scaled.png';
const KIDZONIA_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png';

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');

  // 1. Replace header logo with new compact logo
  content = content.replace(/(<a[^>]*class="header-logo"[^>]*>\s*<img src=")[^"]*(")/g, `$1${NEW_LOGO}$2`);

  // 2. Replace drawer logo with new compact logo
  content = content.replace(/<div class="drawer-logo"><img src="[^"]*"/g, `<div class="drawer-logo"><img src="${NEW_LOGO}"`);

  // 3. Make logo slightly larger (52px instead of 46px)
  content = content.replace(/\.header-logo img\{height:46px/g, '.header-logo img{height:52px');

  // 4. Make drawer logo larger too (44px instead of 38px)
  content = content.replace(/\.drawer-logo img\{height:38px/g, '.drawer-logo img{height:44px');

  // 5. Fix header layout: use proper flexbox with equal edge padding
  // Replace old header-inner style with balanced version
  content = content.replace(
    /\.header-inner\{display:flex;align-items:center;justify-content:space-between;position:relative;padding:18px 30px;/g,
    '.header-inner{display:flex;align-items:center;justify-content:space-between;position:relative;padding:18px 40px;'
  );

  // 6. Ensure nav is truly centered using absolute positioning (already done) 
  // but make sure scrolled state also has equal padding
  content = content.replace(
    /\.site-header\.scrolled \.header-inner\{[^}]*padding:10px 30px/g,
    (match) => match.replace('padding:10px 30px', 'padding:10px 40px')
  );

  fs.writeFileSync(path.join(dir, f), content);
  console.log(`✅ ${f}`);
});

console.log('\n═══ Logo & header layout updated ═══');
