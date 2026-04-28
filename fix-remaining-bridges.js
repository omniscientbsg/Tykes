const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

const BRIDGE_CSS = '.nav-dropdown::before { content: ""; position: absolute; top: -15px; left: 0; right: 0; height: 15px; background: transparent; }';

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');
  let changed = false;

  // Fix submenu bridge — try various patterns
  if (content.includes('.nav-dropdown') && !content.includes('.nav-dropdown::before')) {
    // Try inserting before .nav-item:hover .nav-dropdown
    if (content.includes('.nav-item:hover .nav-dropdown')) {
      content = content.replace(/\.nav-item:hover \.nav-dropdown\s*\{/, BRIDGE_CSS + '\n    .nav-item:hover .nav-dropdown {');
      changed = true;
    }
  }

  // Fix centers-list.html — add the standard nav if missing
  if (f === 'tykes-centers-list.html' && !content.includes('main-nav')) {
    // This page likely has a simpler structure. Let me check and inject if needed.
    console.log(`Note: ${f} needs manual nav check`);
  }

  if (changed) {
    fs.writeFileSync(path.join(dir, f), content);
    console.log(`Patched: ${f}`);
  }
});
