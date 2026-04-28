const fs = require('fs');
const path = require('path');

const templatesDir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(templatesDir).filter(f => f.endsWith('.html'));

files.forEach(file => {
  const filePath = path.join(templatesDir, file);
  let html = fs.readFileSync(filePath, 'utf8');
  let original = html;

  html = html.replace(/href="tykes-programmes\.html#playgroup"/g, 'href="tykes-playgroup.html"');
  html = html.replace(/href="tykes-programmes\.html#nursery"/g, 'href="tykes-nursery.html"');
  html = html.replace(/href="tykes-programmes\.html#junior"/g, 'href="tykes-junior-kg.html"');
  html = html.replace(/href="tykes-programmes\.html#senior"/g, 'href="tykes-senior-kg.html"');
  html = html.replace(/href="tykes-programmes\.html#daycare"/g, 'href="tykes-premium-daycare.html"');

  if (original !== html) {
    fs.writeFileSync(filePath, html);
    console.log(`Fixed submenu links in ${file}`);
  }
});
