const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');
  let regex = /href="https:\/\/tykes\.school([^"]*)"/g;
  let matches = content.match(regex);
  if (matches) {
    matches.forEach(m => {
      if (!m.includes('corporate-daycare')) {
        console.log(f, m);
      }
    });
  }
});
