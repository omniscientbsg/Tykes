const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const dataPath = path.join(__dirname, 'centers_data.json');
let centersData = JSON.parse(fs.readFileSync(dataPath, 'utf8'));

async function scrapeAll() {
    console.log("Launching Puppeteer for all 32 centers...");
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();
    
    // Disable unnecessary resources to speed up
    await page.setRequestInterception(true);
    page.on('request', (req) => {
        if(['stylesheet', 'font', 'media'].includes(req.resourceType())) {
            req.abort();
        } else {
            req.continue();
        }
    });

    for (let i = 0; i < centersData.length; i++) {
        let center = centersData[i];
        console.log(`[${i+1}/${centersData.length}] Scraping: ${center.url}`);
        
        try {
            await page.goto(center.url, { waitUntil: 'domcontentloaded', timeout: 30000 });
            
            const scraped = await page.evaluate(() => {
                let headName = '';
                let headImg = '';
                let headText = '';
                let address = '';
                let phone = '';
                let email = '';
                let mapIframe = '';

                // Extract all text content
                const allText = document.body.innerText;
                
                // Extract Phone and Email using Regex
                const phoneMatch = allText.match(/(?:\+91|0)?[ -]?\d{4,5}[ -]?\d{5,6}/);
                if (phoneMatch) phone = phoneMatch[0].trim();
                
                const emailMatch = allText.match(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/);
                if (emailMatch) email = emailMatch[0].trim();

                // Find Address
                const lists = Array.from(document.querySelectorAll('li, .elementor-icon-list-text, p'));
                const addressNode = lists.find(el => {
                    const txt = el.innerText.toLowerCase();
                    return (txt.includes('sector') || txt.includes('road') || txt.includes('plot') || txt.includes('mumbai') || txt.includes('pune') || txt.includes('nagar')) && txt.length > 15 && txt.length < 150 && !txt.includes('kidzonia');
                });
                if (addressNode) address = addressNode.innerText.trim();

                // Find Center Head details
                const paragraphs = Array.from(document.querySelectorAll('p, div.elementor-widget-container'));
                let targetBio = null;
                for (const p of paragraphs) {
                    const txt = p.innerText.toLowerCase();
                    if (txt.includes('center head') || txt.includes('our center is led by') || txt.includes('guidance of')) {
                        if (txt.length > 50 && txt.length < 1000) {
                            targetBio = p;
                            break;
                        }
                    }
                }

                if (targetBio) {
                    headText = targetBio.innerText.trim();
                    
                    // Try to find the section to scope our search
                    let section = targetBio.parentElement;
                    while (section && section.tagName !== 'SECTION' && !section.className.includes('elementor-section')) {
                        section = section.parentElement;
                    }
                    
                    if (section) {
                        const imgs = Array.from(section.querySelectorAll('img')).filter(img => 
                            !img.src.includes('logo') && !img.src.includes('icon') && !img.src.includes('bg')
                        );
                        if (imgs.length > 0) {
                            const img = imgs[imgs.length - 1];
                            headImg = img.getAttribute('data-src') || img.getAttribute('data-lazy-src') || img.src;
                        }

                        const subheadings = Array.from(section.querySelectorAll('h1, h2, h3, h4, h5, h6, .elementor-heading-title'));
                        for (const sh of subheadings) {
                            const txt = sh.innerText.trim();
                            if (txt.split(' ').length <= 4 && txt.length > 3 && !txt.toLowerCase().includes('testimonial') && !txt.toLowerCase().includes('center head') && !txt.toLowerCase().includes('team')) {
                                headName = txt;
                                break;
                            }
                        }
                    }
                }

                return { headName, headImg, headText, address, phone, email };
            });

            // If we found new data, update the center object
            if (scraped.headName && scraped.headName.length < 30) center.headName = scraped.headName;
            if (scraped.headImg) center.headImg = scraped.headImg;
            if (scraped.headText) center.headText = scraped.headText;
            if (scraped.address && scraped.address.length > 10) center.address = scraped.address.replace(/\n/g, ', ');
            if (scraped.phone) center.phone = scraped.phone;
            if (scraped.email) center.email = scraped.email;

        } catch (e) {
            console.error(`Error on ${center.url}: ${e.message}`);
        }
    }

    fs.writeFileSync(dataPath, JSON.stringify(centersData, null, 2));
    console.log("Successfully updated centers_data.json");
    await browser.close();
}

scrapeAll();
