const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs');

async function scrapeLocations() {
    try {
        const { data } = await axios.get('https://kidzonia.in/');
        const $ = cheerio.load(data);
        
        const links = new Set();
        $('a').each((i, el) => {
            let href = $(el).attr('href');
            if (href && href.startsWith('/')) {
                href = 'https://kidzonia.in' + href;
            }
            if (href && href.includes('kidzonia.in') && href.includes('preschool-')) {
                links.add(href);
            }
        });
        
        console.log(`Found ${links.size} location links.`);
        
        const centersData = [];
        let i = 1;
        for (const link of links) {
            console.log(`Scraping [${i}/${links.size}]: ${link}`);
            try {
                const { data: pageData } = await axios.get(link);
                const $page = cheerio.load(pageData);
                
                const centerName = $page('h1').text().trim().replace(/Kidzonia/g, 'Tykes');
                const centerCityMatch = link.match(/kidzonia\.in\/(?:[^\/]+\/)?preschool-([^\/]+)/);
                const centerAreaRaw = centerCityMatch ? centerCityMatch[1].replace(/-/g, ' ') : 'Unknown Area';
                const centerArea = centerAreaRaw.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');

                const address = $page('.elementor-icon-list-text').filter((j, el) => {
                    const txt = $(el).text().toLowerCase();
                    return txt.includes('sector') || txt.includes('road') || txt.includes('nagar') || txt.includes('plot');
                }).first().text().trim() || 'Address not found';
                
                let phone = $page('a[href^="tel:"]').first().text().trim();
                if (!phone) phone = '+91 84001 58500';
                
                let email = $page('a[href^="mailto:"]').first().text().trim();
                if (!email) email = 'admissions@tykes.school';

                let city = 'Mumbai';
                if (link.includes('navi-mumbai')) city = 'Navi Mumbai';
                else if (link.includes('pune') || link.includes('aundh') || link.includes('hinjewadi') || link.includes('wakad')) city = 'Pune';
                else if (link.includes('hyderabad') || link.includes('serilingampally') || link.includes('ameenpur')) city = 'Hyderabad';

                centersData.push({
                    name: centerName || `Tykes ${centerArea}`,
                    area: centerArea,
                    city: city,
                    url: link,
                    address,
                    phone,
                    email
                });
            } catch (err) {
                console.log(`Failed to scrape ${link}: ${err.message}`);
            }
            i++;
        }
        
        fs.writeFileSync('centers_data.json', JSON.stringify(centersData, null, 2));
        console.log('Successfully saved to centers_data.json');
    } catch (error) {
        console.error('Error scraping:', error);
    }
}

scrapeLocations();
