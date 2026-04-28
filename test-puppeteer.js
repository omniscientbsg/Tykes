const puppeteer = require('puppeteer');
const fs = require('fs');

async function scrape() {
    console.log("Starting Puppeteer...");
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    console.log("Navigating to page...");
    await page.goto('https://kidzonia.in/navi-mumbai/preschool-vashi/', { waitUntil: 'networkidle2' });
    
    const html = await page.content();
    fs.writeFileSync('vashi-puppeteer.html', html);
    
    // Look for Center Head
    const centerHead = await page.evaluate(() => {
        const textNodes = [];
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
        let node;
        while (node = walker.nextNode()) {
            if (node.nodeValue.includes('Nidhi')) {
                textNodes.push(node.nodeValue);
            }
        }
        
        let mapSrc = '';
        const iframe = document.querySelector('iframe');
        if (iframe) mapSrc = iframe.src;
        
        return { textNodes, mapSrc };
    });
    
    console.log("Puppeteer Results:", centerHead);
    await browser.close();
}
scrape();
