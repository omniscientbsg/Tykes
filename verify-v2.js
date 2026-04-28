const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');
const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));
let ok = 0, bad = 0;

files.forEach(f => {
  const c = fs.readFileSync(path.join(dir, f), 'utf8');
  const hasHero = c.includes('hero-sec') || c.includes('about-hero') || c.includes('class="hero"') || c.includes('hero-left') || c.includes('hero-banner');
  const hasNav = c.includes('main-nav') || f === 'tykes-centers-list.html';
  const hasFooter = c.includes('site-footer');
  const hasBridge = !c.includes('.nav-dropdown') || c.includes('.nav-dropdown::before');
  const hasPhp = c.includes('<?php');
  const hasTykesSchoolLink = (c.match(/href="https:\/\/tykes\.school[^"]*"/g) || []).length;
  
  let issues = [];
  if (!hasHero && !['tykes-centers-list.html'].includes(f)) issues.push('NO HERO');
  if (!hasNav) issues.push('NO NAV');
  if (!hasFooter) issues.push('NO FOOTER');
  if (!hasBridge) issues.push('NO BRIDGE');
  if (hasPhp) issues.push('HAS PHP');
  if (hasTykesSchoolLink > 0) issues.push(`${hasTykesSchoolLink} ext links`);

  if (issues.length) {
    console.log(`❌ ${f}: ${issues.join(', ')}`);
    bad++;
  } else {
    console.log(`✅ ${f}`);
    ok++;
  }
});
console.log(`\n${ok} OK, ${bad} issues`);
