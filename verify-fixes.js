const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));
let issues = 0;

files.forEach(f => {
  const content = fs.readFileSync(path.join(dir, f), 'utf8');
  
  // Check for PHP
  if (content.includes('<?php')) {
    console.log(`❌ PHP found in ${f}`);
    issues++;
  }
  
  // Check for tykes.school links (except image src and mailto/tel)
  const schoolLinks = content.match(/href="https:\/\/tykes\.school[^"]*"/g);
  if (schoolLinks) {
    schoolLinks.forEach(l => {
      console.log(`❌ External link in ${f}: ${l}`);
      issues++;
    });
  }

  // Check header logo
  if (content.includes('header-logo') && content.includes('White-Logo') && content.includes('class="header-logo"')) {
    // Check if the header logo is the white one
    const headerMatch = content.match(/class="header-logo">\s*<img src="([^"]*)"/);
    if (headerMatch && headerMatch[1].includes('White-Logo')) {
      console.log(`❌ White logo in header of ${f}`);
      issues++;
    }
  }

  // Check drawer logo
  const drawerMatch = content.match(/class="drawer-logo">\s*<img src="([^"]*)"/);
  if (drawerMatch && drawerMatch[1].includes('White-Logo')) {
    console.log(`❌ White logo in drawer of ${f}`);
    issues++;
  }

  // Check submenu bridge
  if (content.includes('.nav-dropdown') && !content.includes('.nav-dropdown::before')) {
    console.log(`❌ Missing submenu bridge in ${f}`);
    issues++;
  }

  // Check for missing footer
  if (!content.includes('site-footer')) {
    console.log(`❌ Missing footer in ${f}`);
    issues++;
  }

  // Check for missing nav
  if (!content.includes('main-nav')) {
    console.log(`❌ Missing main nav in ${f}`);
    issues++;
  }
});

if (issues === 0) {
  console.log('✅ All checks passed! No issues found.');
} else {
  console.log(`\n⚠️  Found ${issues} issues.`);
}
