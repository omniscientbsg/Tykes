const fs = require('fs');
const path = require('path');
const dir = 'Tykes Html Templates';
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

const svgs = {
  fb: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>`,
  ig: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>`,
  yt: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>`
};

files.forEach(f => {
  let content = fs.readFileSync(path.join(dir, f), 'utf8');
  let original = content;

  // 1. Social icons in footer
  content = content.replace(/>📸<\/a>/g, `>${svgs.ig}</a>`);
  content = content.replace(/>📘<\/a>/g, `>${svgs.fb}</a>`);
  content = content.replace(/>▶<\/a>/g, `>${svgs.yt}</a>`);

  // 2. A Day @ Tykes -> A Day @ Kidzonia
  content = content.replace(/A Day @ Tykes/gi, 'A Day @ Kidzonia');

  // 3. Tykes to Kidzonia in centers list
  if (f === 'tykes-centers-list.html') {
    content = content.replace(/<h3>Tykes /g, '<h3>Kidzonia ');
  }

  // 4. Ages
  content = content.replace(/1\.5\s*[-–]\s*2\.5\s*y(ea)?rs/gi, '2 - 3 Years');
  content = content.replace(/2\.5\s*[-–]\s*3\.5\s*y(ea)?rs/gi, '3 - 4 Years');
  content = content.replace(/3\.5\s*[-–]\s*4\.5\s*y(ea)?rs/gi, '4 - 5 Years');
  content = content.replace(/4\.5\s*[-–]\s*5\.5\s*y(ea)?rs/gi, '5 - 6 Years');
  content = content.replace(/5\.5\s*[-–]\s*6\.5\s*y(ea)?rs/gi, '6 - 7 Years');

  if (content !== original) {
    fs.writeFileSync(path.join(dir, f), content, 'utf8');
    console.log(`Updated ${f}`);
  }
});
