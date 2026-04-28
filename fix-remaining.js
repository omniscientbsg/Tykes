const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');

  // Fix all tykes.school links
  content = content.replace(/href="https:\/\/tykes\.school\/corporate-daycare\/?"/g, 'href="tykes-corporate-daycare.html"');
  content = content.replace(/href="https:\/\/tykes\.school\/?"/g, 'href="tykes-homepage.html"');
  content = content.replace(/href="https:\/\/tykes\.school\/tykes-programmes\/(#[^"]*)"/g, 'href="tykes-programmes.html$1"');

  // Fix header logo visibility by applying a dark filter to the white logo
  // Or switch back to the original colored logo? The user said: "Tykes header logo is not visible when it is visible on footer"
  // Let's add a CSS block to style the header logo image so it's visible. 
  // Wait, the header is white. I can just use filter: invert(1) brightness(0.2) sepia(1) hue-rotate(240deg) saturate(3); to make it dark purple.
  // Or better, let's just make the header background dark, OR change the logo src.
  // The user says: "Tykes header logo is not visible when it is visible on footer". This implies they expect it to be visible. Let's make it dark purple with a filter.
  // The simplest is just `filter: invert(1)` to make it black, but `invert(0.5) sepia(1) saturate(10) hue-rotate(240deg)` gives purple.
  // Actually, I'll just add `.header-logo img { filter: invert(1); }` but then on scroll if the header changes? The header is white.
  // Let's just swap the image src back to `Tykes-without-Kidzonia-Enterprise-04-scaled-e1774873351568.png` in the header! The footer can keep the white one!
  
  content = content.replace(/class="header-logo">\s*<img src="[^"]*Tykes-White-Logo-e1774873322197\.png"/g, 'class="header-logo">\n      <img src="https://tykes.school/wp-content/uploads/2026/03/Tykes-without-Kidzonia-Enterprise-04-scaled-e1774873351568.png"');
  
  // Fix Submenu Gap
  // The user says: "When I try to hover over a menu item and then try to move to a sub-menu the sub-menu disappears even before I can move to sub menu may be because of gap (adding a pseudo element might solve it)"
  // In the CSS we have: .nav-dropdown { position:absolute; top:calc(100% + 8px); ... }
  // We can change `top:calc(100% + 8px);` to `top: 100%;` and add `margin-top: 8px;` wait no, padding!
  // Adding `padding-top: 8px;` instead of `translateY(8px)`! 
  // Wait, if we just add a pseudo element bridging the gap:
  if (!content.includes('.nav-dropdown::before')) {
    content = content.replace('.nav-item:hover .nav-dropdown {', '.nav-dropdown::before { content: ""; position: absolute; top: -15px; left: 0; right: 0; height: 15px; background: transparent; }\n    .nav-item:hover .nav-dropdown {');
  }

  fs.writeFileSync(path.join(dir, f), content);
});
console.log("Fixes applied to all templates.");
