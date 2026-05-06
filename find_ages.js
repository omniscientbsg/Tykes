const fs = require('fs');
const path = require('path');
const dir = 'Tykes Html Templates';
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

const matches = new Set();
files.forEach(f => {
  const content = fs.readFileSync(path.join(dir, f), 'utf8');
  const found = content.match(/\d(\.\d)?\s*[-–]\s*\d(\.\d)?\s*(Yrs|Years)/gi);
  if(found) found.forEach(m => matches.add(m));
});
console.log(Array.from(matches).join('\n'));
