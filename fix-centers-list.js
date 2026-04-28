const fs = require('fs');
const path = require('path');

const KIDZONIA_LOGO = 'https://tykes.school/wp-content/uploads/2026/03/Tykes-Kidzonia-Logo.png';

// Read the about page to extract the full header CSS + structure
const aboutPage = fs.readFileSync(path.join(__dirname, 'Tykes Html Templates', 'tykes-about.html'), 'utf8');

// Extract CSS between <style> and </style> for header/nav/drawer/footer
const cssMatch = aboutPage.match(/<style>([\s\S]*?)<\/style>/);
const fullCSS = cssMatch ? cssMatch[1] : '';

// Extract only the header-related CSS rules we need
const headerCSSRules = [];
const lines = fullCSS.split('\n');
let capturing = false;
let braceCount = 0;
let currentRule = '';

for (const line of lines) {
  const trimmed = line.trim();
  // Check if this line starts a rule we want
  if (!capturing && (
    trimmed.startsWith('.site-header') || trimmed.startsWith('.header-') ||
    trimmed.startsWith('.main-nav') || trimmed.startsWith('.nav-item') ||
    trimmed.startsWith('.nav-dropdown') || trimmed.startsWith('.btn-enroll') ||
    trimmed.startsWith('.ham-btn') || trimmed.startsWith('.mobile-drawer') ||
    trimmed.startsWith('.drawer-') || trimmed.startsWith('.mob-link') ||
    trimmed.startsWith('.mob-sub') || trimmed.startsWith('.mob-enroll') ||
    trimmed.startsWith('.mob-chevron') || trimmed.startsWith('.site-footer') ||
    trimmed.startsWith('.footer-') || trimmed.startsWith('.social-btn') ||
    trimmed.startsWith('.social-links')
  )) {
    capturing = true;
    currentRule = line + '\n';
    braceCount = (line.match(/{/g) || []).length - (line.match(/}/g) || []).length;
    if (braceCount <= 0) {
      headerCSSRules.push(currentRule);
      capturing = false;
      currentRule = '';
    }
    continue;
  }
  if (capturing) {
    currentRule += line + '\n';
    braceCount += (line.match(/{/g) || []).length - (line.match(/}/g) || []).length;
    if (braceCount <= 0) {
      headerCSSRules.push(currentRule);
      capturing = false;
      currentRule = '';
    }
  }
}

// Extract header HTML block
const headerStart = aboutPage.indexOf('<header class="site-header"');
const headerEnd = aboutPage.indexOf('</header>');
const headerHTML = aboutPage.substring(headerStart, headerEnd + '</header>'.length);

// Extract mobile drawer HTML block  
const drawerStart = aboutPage.indexOf('<div class="mobile-drawer"');
let depth = 0, i = drawerStart, found = false;
let drawerEnd = drawerStart;
while (i < aboutPage.length) {
  if (aboutPage.substring(i, i + 4) === '<div') { depth++; found = true; }
  if (aboutPage.substring(i, i + 6) === '</div>') {
    depth--;
    if (found && depth === 0) { drawerEnd = i + 6; break; }
  }
  i++;
}
const drawerHTML = aboutPage.substring(drawerStart, drawerEnd);

// Extract footer HTML
const footerStart = aboutPage.indexOf('<footer class="site-footer">');
const footerEnd = aboutPage.indexOf('</footer>');
const footerHTML = aboutPage.substring(footerStart, footerEnd + '</footer>'.length);

// Extract JS block
const jsStart = aboutPage.indexOf('<script>');
const jsEnd = aboutPage.indexOf('</script>', jsStart);
const jsBlock = aboutPage.substring(jsStart, jsEnd + '</script>'.length);

// Now rebuild centers-list.html
const centersFile = path.join(__dirname, 'Tykes Html Templates', 'tykes-centers-list.html');
let content = fs.readFileSync(centersFile, 'utf8');

// Extract the body content between old header and footer
const bodyContent = content.match(/<section class="hero[\s\S]*?(?=<footer|<\/body)/);
const mainContent = bodyContent ? bodyContent[0] : '';

// Build new page
const newPage = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tykes Early Years – Find a Center Near You</title>
  <meta name="description" content="Find a Tykes Early Years center near you. Browse our locations across Navi Mumbai, Pune, Mumbai, and Hyderabad.">
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --p:#8257bd; --pd:#6d46a8; --pl:#a884e3; --pxl:#f3edff;
      --gold:#fdbc02; --goldd:#e0a800; --orange:#fc8738;
      --pink:#dd5b9d; --coral:#e44a4b; --teal:#05a28d;
      --sky:#0EA5E9; --green:#22C55E;
      --white:#fff; --txt:#1E1B4B; --muted:#6B7280;
      --bg-lav:#F9F7FF; --bg-cream:#FFFBF0;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    body{font-family:'Poppins',sans-serif;background:var(--bg-lav);color:var(--txt);overflow-x:hidden}
    img{max-width:100%;display:block} a{text-decoration:none}
    .container{max-width:1280px;margin:0 auto;padding:0 24px}
    .h-fredoka{font-family:'Fredoka',cursive;font-weight:700}
    .b-poppins{font-family:'Poppins',sans-serif}
    @keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}

    /* HEADER */
    .site-header{position:fixed;top:0;left:0;width:100%;z-index:9000;transition:all .4s}
    .site-header.scrolled .header-inner{background:rgba(255,255,255,.97);backdrop-filter:blur(20px);box-shadow:0 4px 30px rgba(124,58,237,.12);padding:10px 30px}
    .header-inner{display:flex;align-items:center;justify-content:space-between;position:relative;padding:18px 30px;background:rgba(255,255,255,.85);backdrop-filter:blur(16px);border-bottom:1px solid rgba(124,58,237,.08);transition:all .4s}
    .header-logo{display:flex;align-items:center;gap:10px;flex-shrink:0}
    .header-logo img{height:46px;width:auto;display:block}
    .main-nav{display:flex;align-items:center;gap:4px;position:absolute;left:50%;transform:translateX(-50%)}
    .nav-item{position:relative}
    .nav-item>a{display:flex;align-items:center;gap:5px;padding:8px 14px;border-radius:50px;font-weight:700;font-size:.92rem;color:var(--txt);transition:.25s;white-space:nowrap}
    .nav-item>a:hover{color:var(--p);background:var(--pxl)}
    .nav-item>a .chevron{display:inline-block;width:14px;height:14px;fill:none;stroke:currentColor;stroke-width:2.5;transition:transform .3s}
    .nav-item:hover>a .chevron{transform:rotate(180deg)}
    .nav-dropdown{position:absolute;top:calc(100% + 8px);left:50%;transform:translateX(-50%) translateY(8px);background:white;border-radius:20px;box-shadow:0 20px 60px rgba(124,58,237,.18);border:1px solid rgba(124,58,237,.1);padding:12px;min-width:200px;opacity:0;pointer-events:none;visibility:hidden;transition:opacity .25s,transform .25s}
    .nav-dropdown::before{content:"";position:absolute;top:-15px;left:0;right:0;height:15px;background:transparent}
    .nav-item:hover .nav-dropdown{opacity:1;pointer-events:all;visibility:visible;transform:translateX(-50%) translateY(0)}
    .nav-dropdown a{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;font-size:.9rem;font-weight:600;color:var(--txt);transition:.2s;white-space:nowrap}
    .nav-dropdown a:hover{background:var(--pxl);color:var(--p)}
    .nav-dropdown a .dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
    .header-cta{display:flex;align-items:center;gap:10px;flex-shrink:0}
    .btn-enroll{background:linear-gradient(135deg,var(--p),var(--pd));color:white;padding:10px 22px;border-radius:50px;font-weight:800;font-size:.9rem;border:none;cursor:pointer;font-family:inherit;transition:.3s;box-shadow:0 6px 20px rgba(124,58,237,.35)}
    .btn-enroll:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(124,58,237,.45)}
    .ham-btn{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:6px;z-index:9100}
    .ham-btn span{display:block;width:24px;height:2.5px;background:var(--p);border-radius:4px;transition:.35s}
    .ham-btn.open span:nth-child(1){transform:translateY(7.5px) rotate(45deg)}
    .ham-btn.open span:nth-child(2){opacity:0;transform:scaleX(0)}
    .ham-btn.open span:nth-child(3){transform:translateY(-7.5px) rotate(-45deg)}
    .mobile-drawer{display:none;position:fixed;inset:0;z-index:9050;background:rgba(30,27,75,.55);backdrop-filter:blur(6px);opacity:0;transition:opacity .35s}
    .mobile-drawer.open{opacity:1;pointer-events:all}
    .drawer-panel{position:absolute;top:0;right:-340px;width:320px;height:100%;background:white;box-shadow:-20px 0 50px rgba(0,0,0,.18);padding:28px 24px;overflow-y:auto;transition:right .4s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;gap:4px}
    .mobile-drawer.open .drawer-panel{right:0}
    .drawer-logo{margin-bottom:24px}
    .drawer-logo img{height:38px}
    .mob-link{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-radius:14px;font-weight:700;font-size:1rem;color:var(--txt);cursor:pointer;transition:.2s;border:none;background:none;width:100%;text-align:left;font-family:inherit}
    .mob-link:hover{background:var(--pxl);color:var(--p)}
    .mob-link .mob-chevron{transition:transform .3s;font-size:1.1rem;color:var(--muted)}
    .mob-link.has-sub.active .mob-chevron{transform:rotate(90deg);color:var(--p)}
    .mob-sub{padding-left:16px;display:none;flex-direction:column;gap:2px}
    .mob-sub.open{display:flex}
    .mob-sub a{padding:10px 14px;border-radius:10px;font-size:.9rem;font-weight:600;color:var(--muted);display:flex;align-items:center;gap:8px}
    .mob-sub a::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--pl);flex-shrink:0}
    .mob-sub a:hover{color:var(--p);background:var(--pxl)}
    .mob-enroll-btn{margin-top:20px;background:linear-gradient(135deg,var(--p),var(--pd));color:white;width:100%;padding:14px;border-radius:50px;border:none;font-weight:800;font-size:1rem;cursor:pointer;font-family:inherit}

    /* PAGE CONTENT */
    .centers-hero{padding:120px 0 60px;text-align:center}
    .centers-hero h1{font-family:'Fredoka',cursive;font-size:clamp(2rem,5vw,3rem);color:var(--txt);margin-bottom:15px}
    .centers-hero p{font-size:1.1rem;color:var(--muted);max-width:600px;margin:0 auto}
    .map-container{width:100%;height:400px;border-radius:20px;overflow:hidden;margin-bottom:60px;box-shadow:0 10px 30px rgba(0,0,0,.1)}
    .city-section{margin-bottom:50px}
    .city-title{font-family:'Fredoka',cursive;font-size:2rem;color:var(--teal);margin-bottom:30px;border-bottom:2px solid var(--teal);display:inline-block;padding-bottom:5px}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:30px}
    .card{background:var(--white);border-radius:16px;padding:25px;box-shadow:0 5px 20px rgba(0,0,0,.05);transition:transform .3s,box-shadow .3s;border-top:5px solid var(--gold)}
    .card:hover{transform:translateY(-5px);box-shadow:0 15px 30px rgba(0,0,0,.1)}
    .card h3{font-family:'Fredoka',cursive;font-size:1.4rem;color:var(--p);margin-bottom:10px}
    .card p{font-size:.9rem;color:var(--muted);margin-bottom:15px;line-height:1.5}
    .card a{display:inline-block;padding:10px 20px;background:var(--orange);color:var(--white);border-radius:30px;text-decoration:none;font-weight:500;font-size:.9rem;transition:background .3s}
    .card a:hover{background:#e56d1f}

    /* FOOTER */
    .site-footer{background:linear-gradient(135deg,var(--p),var(--pd));color:rgba(255,255,255,.65);position:relative;overflow:hidden}
    .site-footer::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--gold),var(--orange),var(--pink),var(--p))}
    .footer-top{padding:80px 0 60px}
    .footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:50px}
    .footer-brand img{height:52px;width:auto;margin-bottom:20px;display:block}
    .footer-brand p{line-height:1.7;font-size:.9rem;margin-bottom:24px}
    .social-links{display:flex;gap:10px}
    .social-btn{width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;font-size:1rem;color:rgba(255,255,255,.7);transition:.25s}
    .social-btn:hover{background:rgba(255,255,255,.18);color:white;transform:translateY(-2px)}
    .footer-col h5{color:white;font-family:'Fredoka',cursive;font-size:1.15rem;font-weight:700;margin-bottom:20px}
    .footer-col ul{list-style:none;display:flex;flex-direction:column;gap:10px}
    .footer-col ul a{color:rgba(255,255,255,.6);transition:.2s;font-size:.9rem}
    .footer-col ul a:hover{color:var(--gold);padding-left:4px}
    .footer-contact-item{display:flex;align-items:flex-start;gap:10px;color:rgba(255,255,255,.65);font-size:.9rem;margin-bottom:12px}
    .footer-contact-item a{color:rgba(255,255,255,.65);transition:color .2s}
    .footer-contact-item a:hover{color:var(--gold)}
    .footer-bottom{border-top:1px solid rgba(255,255,255,.07);padding:24px 0;text-align:center;font-size:.84rem;color:rgba(255,255,255,.35)}
    .footer-bottom span{color:var(--gold)}

    @media(max-width:991px){
      .main-nav{display:none} .ham-btn{display:flex}
      .mobile-drawer{display:block;pointer-events:none}
      .footer-grid{grid-template-columns:1fr 1fr}
    }
    @media(max-width:640px){
      .footer-grid{grid-template-columns:1fr}
      .grid{grid-template-columns:1fr}
    }
  </style>
</head>
<body>

${headerHTML}

${drawerHTML}

<section class="centers-hero container">
  <h1>Find a Center Near You</h1>
  <p>We are expanding rapidly across India to bring high-quality, structured early childhood education to your neighborhood.</p>
</section>

<div class="container map-container">
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3770.7921931657876!2d73.00762391489728!3d19.07283628708992!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c13aa7b123d9%3A0x6b80155b9a89d714!2sVashi%2C%20Navi%20Mumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1689163102492!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
`;

// Extract center cards from old content
const cardsSection = content.match(/<div class="container">\s*<div class="city-section[\s\S]*?(?=<!-- ─── FOOTER|<footer)/);
const cardsHTML = cardsSection ? cardsSection[0] : '';

const endPage = `
${footerHTML}

${jsBlock}
</body>
</html>`;

fs.writeFileSync(path.join(__dirname, 'Tykes Html Templates', 'tykes-centers-list.html'), newPage + cardsHTML + '\n' + endPage);
console.log('✅ Centers list page rebuilt with full header/footer');
