const fs = require('fs');
const html = fs.readFileSync('franchise-scrape.html', 'utf8');

const start = html.indexOf('<section class="section" id="recognitions">');
let end = start;
let depth = 0, found = false;

for (let i = start; i < html.length; i++) {
  if (html.substring(i, i+4) === '<sec') {
    depth++;
    found = true;
  }
  if (html.substring(i, i+5) === '</sec') {
    depth--;
    if (found && depth === 0) {
      end = i + 10;
      break;
    }
  }
}

const section = html.substring(start, end);
fs.writeFileSync('awards.html', section);
console.log('Extracted awards');
