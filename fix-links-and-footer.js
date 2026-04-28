const fs = require('fs');
const path = require('path');
const dir = path.join(__dirname, 'Tykes Html Templates');

// 1. Get the footer block from tykes-spaces.html
const spacesContent = fs.readFileSync(path.join(dir, 'tykes-spaces.html'), 'utf8');
const footerRegex = /(<!-- FOOTER.*?-->[\s\S]*?<footer class="site-footer">[\s\S]*?<\/footer>)/;
const footerMatch = spacesContent.match(footerRegex);
if (!footerMatch) {
    console.error("Could not find footer in tykes-spaces.html");
    process.exit(1);
}
let footerBlock = footerMatch[1];

// Also update links inside the footerBlock so they are absolute or correct
const globalLinksMap = {
    'https://tykes.school/tykes-programmes/#playgroup': 'tykes-programmes.html#playgroup',
    'https://tykes.school/tykes-programmes/#nursery': 'tykes-programmes.html#nursery',
    'https://tykes.school/tykes-programmes/#junior': 'tykes-programmes.html#junior',
    'https://tykes.school/tykes-programmes/#senior': 'tykes-programmes.html#senior',
    'https://tykes.school/tykes-programmes/#daycare': 'tykes-programmes.html#daycare',
    'https://tykes.school/corporate-daycare/': 'https://tykes.school/corporate-daycare/',
    'https://tykes.school/': 'tykes-homepage.html',
    'tykes-about.html': 'tykes-about.html',
    'tykes-philosophy.html': 'tykes-philosophy.html',
    'tykes-leadership.html': 'tykes-leadership.html',
    'tykes-spaces.html': 'tykes-spaces.html',
    'tykes-awards.html': 'tykes-awards.html',
    '#spaces': 'tykes-spaces.html#spaces',
    '#playgroup': 'tykes-programmes.html#playgroup',
    '#nursery': 'tykes-programmes.html#nursery',
    '#daycare': 'tykes-programmes.html#daycare'
};

const navItemsRegex = /<a href="([^"]*)".*?>([^<]*)<\/a>/g;
// We'll run custom replacement on the fly per file.

const files = fs.readdirSync(dir).filter(f => f.endsWith('.html'));

files.forEach(f => {
    let content = fs.readFileSync(path.join(dir, f), 'utf8');

    // 1. INJECT FOOTER IF MISSING
    if (!content.includes('<footer class="site-footer">')) {
        // Insert right before <!-- POPUP --> or </body>
        if (content.includes('<!-- POPUP')) {
            content = content.replace(/(<!-- POPUP[\s\S]*)/, footerBlock + '\n\n$1');
        } else {
            content = content.replace(/<\/body>/, footerBlock + '\n</body>');
        }
        console.log(`Injected footer into ${f}`);
    } else {
        // Replace existing footer with standard block
        content = content.replace(/(<!-- FOOTER.*?-->[\s\S]*?<footer class="site-footer">[\s\S]*?<\/footer>)/, footerBlock);
        console.log(`Updated footer in ${f}`);
    }

    // 2. HEADER LOGO UPDATE
    content = content.replace(/src="[^"]*Tykes-without-Kidzonia-Enterprise[^"]*"/, 'src="https://tykes.school/wp-content/uploads/2026/03/Tykes-White-Logo-e1774873322197.png"');
    content = content.replace(/src="[^"]*Assets-for-website-14[^"]*"/, 'src="https://tykes.school/wp-content/uploads/2026/03/Tykes-White-Logo-e1774873322197.png"');

    // 3. HEADER NAV AND FOOTER LINK FIXES
    // Replace hardcoded https://tykes.school/tykes-programmes/#... to local
    content = content.replace(/href="https:\/\/tykes\.school\/tykes-programmes\/([^"]*)"/g, 'href="tykes-programmes.html$1"');
    content = content.replace(/href="https:\/\/tykes\.school\/?"/g, 'href="tykes-homepage.html"');
    
    // Replace `#` links in the header Navigation Dropdowns
    const navReplacement = content.replace(/<div class="nav-item">([\s\S]*?)<\/div>/g, (match, inner) => {
        if (inner.includes('About Us')) {
            inner = inner.replace(/href="#"(.*?)>([^<]*)Our Philosophy/g, 'href="tykes-philosophy.html"$1>$2Our Philosophy');
            inner = inner.replace(/href="#"(.*?)>([^<]*)Our Leadership/g, 'href="tykes-leadership.html"$1>$2Our Leadership');
            inner = inner.replace(/href="#"(.*?)>([^<]*)Our Learning Spaces/g, 'href="tykes-spaces.html"$1>$2Our Learning Spaces');
            inner = inner.replace(/href="#"(.*?)>([^<]*)Awards & Recognition/g, 'href="tykes-awards.html"$1>$2Awards & Recognition');
            inner = inner.replace(/href="#"(.*?)>About Us/g, 'href="tykes-about.html"$1>About Us');
        }
        if (inner.includes('Franchise')) {
            inner = inner.replace(/href="#"(.*?)>([^<]*)Why Partner With Us/g, 'href="tykes-why-partner.html"$1>$2Why Partner With Us');
        }
        if (inner.includes('Contact Us')) {
            inner = inner.replace(/href="#"(.*?)>([^<]*)Our Centers/g, 'href="tykes-centers-list.html"$1>$2Our Centers');
        }
        return `<div class="nav-item">${inner}</div>`;
    });
    content = navReplacement;

    // 4. BUTTON FIXES
    // "Learn More" in Junior KG
    if (f === 'tykes-programmes.html') {
        content = content.replace(/onclick="tykesOpenPopup\(\)"(.*?>Learn More)/, `onclick="document.getElementById('junior').scrollIntoView({behavior:'smooth'})"$1`);
    }

    // "Learn More" in Why Tykes Carousel (card-1, card-2, etc)
    content = content.replace(/<a href="#" class="why-card card-1">/g, '<a href="tykes-about.html" class="why-card card-1">');
    content = content.replace(/<a href="#" class="why-card card-2">/g, '<a href="tykes-spaces.html#safety" class="why-card card-2">');
    content = content.replace(/<a href="#" class="why-card card-3">/g, '<a href="tykes-spaces.html" class="why-card card-3">');
    content = content.replace(/<a href="#" class="why-card card-4">/g, '<a href="tykes-about.html" class="why-card card-4">');
    content = content.replace(/<a href="#" class="why-card card-5">/g, '<a href="tykes-about.html" class="why-card card-5">');
    content = content.replace(/<a href="#" class="why-card card-6">/g, '<a href="tykes-about.html" class="why-card card-6">');

    // 5. Drawer links fix (mobile menu)
    content = content.replace(/<a href="#playgroup">Play Group/g, '<a href="tykes-programmes.html#playgroup">Play Group');
    content = content.replace(/<a href="#nursery">Nursery/g, '<a href="tykes-programmes.html#nursery">Nursery');
    content = content.replace(/<a href="#daycare">Daycare/g, '<a href="tykes-programmes.html#daycare">Daycare');

    fs.writeFileSync(path.join(dir, f), content);
});

console.log("All files updated successfully for Phase 1.");
