const fs = require('fs');
const path = require('path');

const templatesDir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(templatesDir).filter(f => f.endsWith('.html'));

files.forEach(file => {
  const filePath = path.join(templatesDir, file);
  let html = fs.readFileSync(filePath, 'utf8');
  let original = html;

  // Fix IntersectionObserver thresholds to rootMargin: '50px', threshold: 0
  html = html.replace(/\{threshold:\s*0\.\d+\}/g, "{ rootMargin: '50px 0px 0px 0px', threshold: 0 }");
  html = html.replace(/\{\s*threshold:\s*0\.\d+\s*\}/g, "{ rootMargin: '50px 0px 0px 0px', threshold: 0 }");

  // Fix stagger bug where absolute index `i` is used for delay
  // e.g. el.style.transition='opacity 0.5s ease '+(i*0.07)+'s, transform 0.5s ease '+(i*0.07)+'s';
  html = html.replace(/el\.style\.transition\s*=\s*'opacity[^']+'\+\(i\*0\.\d+\)\+'s, transform[^']+'\+\(i\*0\.\d+\)\+'s';/g, 
                      "el.style.transition='opacity 0.6s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1)';");
  
  html = html.replace(/el\.style\.transition\s*=\s*'opacity[^']+'\s*\+\s*\(i\s*\*\s*0\.\d+\)\s*\+\s*'s,\s*transform[^']+'\s*\+\s*\(i\s*\*\s*0\.\d+\)\s*\+\s*'s';/g, 
                      "el.style.transition='opacity 0.6s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.6s cubic-bezier(0.2, 0.8, 0.2, 1)';");

  if (original !== html) {
    fs.writeFileSync(filePath, html);
    console.log(`Fixed animations in ${file}`);
  }
});
