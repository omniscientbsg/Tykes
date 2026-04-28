const puppeteer = require('puppeteer');

async function test() {
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();
    await page.goto('https://kidzonia.in/navi-mumbai/preschool-vashi/', { waitUntil: 'networkidle2' });

    const data = await page.evaluate(() => {
        const imgs = Array.from(document.querySelectorAll('img')).map(img => ({
            src: img.src,
            dataSrc: img.getAttribute('data-src'),
            dataLazySrc: img.getAttribute('data-lazy-src'),
            alt: img.alt,
            class: img.className
        }));
        
        // Find text near 'Nidhi'
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
        const texts = [];
        let node;
        while (node = walker.nextNode()) {
            if (node.nodeValue.includes('Nidhi')) {
                let parent = node.parentElement;
                texts.push({
                    text: node.nodeValue.trim(),
                    tag: parent.tagName,
                    class: parent.className
                });
            }
        }
        return { imgs, texts };
    });

    console.log(JSON.stringify(data, null, 2));
    await browser.close();
}
test();
