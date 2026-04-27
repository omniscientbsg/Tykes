const fs = require('fs');
const path = require('path');

const templatePath = path.join(__dirname, 'Tykes Html Templates', 'tykes-center-template.html');
const dataPath = path.join(__dirname, 'centers_data.json');
const knownHeadsPath = path.join(__dirname, 'known-heads.json');
const outputDir = path.join(__dirname, 'Centers');

if (!fs.existsSync(outputDir)) {
    fs.mkdirSync(outputDir);
}

const template = fs.readFileSync(templatePath, 'utf8');
const centersData = JSON.parse(fs.readFileSync(dataPath, 'utf8'));
const knownHeads = JSON.parse(fs.readFileSync(knownHeadsPath, 'utf8'));

centersData.forEach(center => {
    let content = template;
    
    // Replace all basic placeholders using clean area names instead of long SEO titles
    const cleanCenterName = `Tykes ${center.area}`;
    content = content.replace(/\[Center Name, City\]/g, `${cleanCenterName}, ${center.city}`);
    content = content.replace(/\[Center Name\]/g, cleanCenterName);
    content = content.replace(/\[City\]/g, center.city);
    content = content.replace(/\[Location\]/g, center.area);
    content = content.replace(/\[Area\]/g, center.area);
    content = content.replace(/\[Center Location\]/g, `${center.area}, ${center.city}`);
    
    // Contact Info
    let address = center.address && center.address !== 'Address not found' ? center.address : `Tykes Preschool, ${center.area}, ${center.city}`;
    let phone = center.phone || '+91 84001 58500';
    let email = center.email || 'admissions@tykes.school';

    content = content.replace(/\[Full Address of the Center\]/g, address);
    content = content.replace(/\[Center Phone Number\]/g, phone);
    content = content.replace(/\[Center Email Address\]/g, email);
    
    // Map Info (Dynamic Google Maps Embed based on Area)
    const mapQuery = encodeURIComponent(`Tykes Preschool ${center.area} ${center.city}`);
    const mapEmbed = `<iframe src="https://maps.google.com/maps?q=${mapQuery}&t=m&z=14&output=embed&iwloc=near" width="100%" height="100%" style="border:0; border-radius: 20px;" allowfullscreen="" loading="lazy"></iframe>`;
    content = content.replace(/<span.*?\[Google Maps Embed\].*?<\/span>/g, mapEmbed);

    // Center Head Information
    let headData = null;
    
    // Check known overrides first
    const knownKeys = Object.keys(knownHeads);
    for (let k of knownKeys) {
        if (center.area.toLowerCase().includes(k.toLowerCase()) || center.name.toLowerCase().includes(k.toLowerCase())) {
            headData = knownHeads[k];
            break;
        }
    }
    
    // If no override, use scraped data if available
    if (!headData && (center.headName || center.headText)) {
        headData = {
            headName: center.headName || 'Center Head',
            headImg: center.headImg || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80',
            headText: center.headText || `Our center in ${center.area} is led by a dedicated Center Head committed to providing exceptional early childhood education.`
        };
    }

    if (headData) {
        // Inject data
        content = content.replace(/\[Name of Center Head\]/g, headData.headName);
        content = content.replace(/\[Quote from the center head about their passion for early childhood education and commitment to the children\.\]/g, headData.headText);
        content = content.replace('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80', headData.headImg);
        content = content.replace(/\[X\]\+/g, '10');
    } else {
        // Center Head section is roughly lines 801-812 in template. 
        // We will remove the section block entirely if no data exists so we don't show placeholders.
        // It's wrapped in an HTML comment `<!-- ─── CENTER HEAD ─── -->` followed by a `<section>` ending in `</section>`
        const headSectionRegex = /<!-- ─── CENTER HEAD ─── -->[\s\S]*?<\/section>/;
        content = content.replace(headSectionRegex, '<!-- ─── CENTER HEAD MISSING ─── -->');
    }

    // Fix Relative Links for local viewing
    // Any href="tykes-*.html" becomes href="../Tykes Html Templates/tykes-*.html"
    content = content.replace(/href="tykes-([^"]*\.html[^"]*)"/g, 'href="../Tykes Html Templates/tykes-$1"');
    
    const fileName = `tykes-center-${center.area.toLowerCase().replace(/\s+/g, '-')}.html`;
    const outputPath = path.join(outputDir, fileName);
    
    fs.writeFileSync(outputPath, content);
    console.log(`Generated: ${fileName} - Has Head: ${!!headData}`);
});

console.log(`\nSuccessfully generated ${centersData.length} center pages.`);
