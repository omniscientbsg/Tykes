const puppeteer = require('puppeteer');
const fs = require('fs');

const testUrls = [
    'https://kidzonia.in/navi-mumbai/preschool-vashi/',
    'https://kidzonia.in/preschool-aundh/',
    'https://kidzonia.in/navi-mumbai/preschool-kamothe-sector-19/'
];

async function run() {
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();

    for (const url of testUrls) {
        console.log(`\nScraping: ${url}`);
        try {
            await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
            
            const data = await page.evaluate(() => {
                let headName = '';
                let headImg = '';
                let headText = '';
                
                // Find all headings that might indicate Center Head section
                const headings = Array.from(document.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title'));
                let targetHeading = null;
                for (const h of headings) {
                    const txt = h.innerText.toLowerCase();
                    if (txt.includes('center head') || txt.includes('our team')) {
                        targetHeading = h;
                        break;
                    }
                }
                
                if (targetHeading) {
                    // Find the section containing this heading
                    let section = targetHeading.parentElement;
                    while (section && section.tagName !== 'SECTION' && !section.className.includes('elementor-section')) {
                        section = section.parentElement;
                    }
                    
                    if (section) {
                        // Extract images
                        const imgs = Array.from(section.querySelectorAll('img')).filter(img => 
                            !img.src.includes('logo') && !img.src.includes('icon') && !img.src.includes('bg')
                        );
                        if (imgs.length > 0) headImg = imgs[imgs.length - 1].src;
                        
                        // Extract Bio
                        const paragraphs = Array.from(section.querySelectorAll('p'));
                        if (paragraphs.length > 0) {
                            // Longest paragraph is usually the bio
                            const bioP = paragraphs.reduce((a, b) => a.innerText.length > b.innerText.length ? a : b);
                            headText = bioP.innerText.trim();
                        }
                        
                        // Extract Name
                        // Typically it's a small heading (h3, h4, h5) inside the section, but NOT the targetHeading itself
                        const subheadings = Array.from(section.querySelectorAll('h3, h4, h5, h6, .elementor-heading-title')).filter(h => h !== targetHeading);
                        for (const sh of subheadings) {
                            const txt = sh.innerText.trim();
                            // Names are typically 2-3 words
                            if (txt.split(' ').length <= 4 && txt.length > 3 && !txt.toLowerCase().includes('testimonial')) {
                                headName = txt;
                                break;
                            }
                        }
                    }
                }

                // If no iframe, return empty
                let mapIframe = '';
                const iframes = Array.from(document.querySelectorAll('iframe'));
                for (const iframe of iframes) {
                    if (iframe.src.includes('google.com/maps')) {
                        mapIframe = iframe.src;
                        break;
                    }
                }

                return { headName, headImg, headText, mapIframe };
            });

            console.log(JSON.stringify(data, null, 2));
        } catch (e) {
            console.error(`Failed: ${e.message}`);
        }
    }

    await browser.close();
}
run();
