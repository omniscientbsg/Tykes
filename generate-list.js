const fs = require('fs');
const path = require('path');

const dataPath = path.join(__dirname, 'centers_data.json');
const outputPath = path.join(__dirname, 'Tykes Html Templates', 'tykes-centers-list.html');

const centersData = JSON.parse(fs.readFileSync(dataPath, 'utf8'));

// Group by city
const grouped = {};
centersData.forEach(center => {
    if (!grouped[center.city]) grouped[center.city] = [];
    grouped[center.city].push(center);
});

let html = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tykes Early Years – Find a Center Near You</title>
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --p: #8257bd; --gold: #fdbc02; --orange: #fc8738; --teal: #05a28d;
      --bg-lav: #F9F7FF; --txt: #1E1B4B; --muted: #6B7280; --white: #fff;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Poppins', sans-serif; background: var(--bg-lav); color: var(--txt); }
    .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
    
    header { background: var(--white); padding: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
    .logo { font-family: 'Fredoka', sans-serif; font-size: 1.8rem; color: var(--p); font-weight: 700; text-decoration: none; }
    
    .hero { padding: 60px 0; text-align: center; }
    .hero h1 { font-family: 'Fredoka', sans-serif; font-size: 3rem; color: var(--txt); margin-bottom: 15px; }
    .hero p { font-size: 1.1rem; color: var(--muted); max-width: 600px; margin: 0 auto; }
    
    .map-container { width: 100%; height: 400px; border-radius: 20px; overflow: hidden; margin-bottom: 60px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    
    .city-section { margin-bottom: 50px; }
    .city-title { font-family: 'Fredoka', sans-serif; font-size: 2rem; color: var(--teal); margin-bottom: 30px; border-bottom: 2px solid var(--teal); display: inline-block; padding-bottom: 5px; }
    
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    .card { background: var(--white); border-radius: 16px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 5px solid var(--gold); }
    .card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    .card h3 { font-family: 'Fredoka', sans-serif; font-size: 1.4rem; color: var(--p); margin-bottom: 10px; }
    .card p { font-size: 0.9rem; color: var(--muted); margin-bottom: 15px; line-height: 1.5; }
    .card a { display: inline-block; padding: 10px 20px; background: var(--orange); color: var(--white); border-radius: 30px; text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: background 0.3s ease; }
    .card a:hover { background: #e56d1f; }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <a href="#" class="logo">Tykes Early Years</a>
    </div>
  </header>

  <section class="hero container">
    <h1>Find a Center Near You</h1>
    <p>We are expanding rapidly across India to bring high-quality, structured early childhood education to your neighborhood.</p>
  </section>

  <div class="container map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3770.7921931657876!2d73.00762391489728!3d19.07283628708992!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c13aa7b123d9%3A0x6b80155b9a89d714!2sVashi%2C%20Navi%20Mumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1689163102492!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>

  <div class="container">
`;

for (const [city, centers] of Object.entries(grouped)) {
    html += `    <div class="city-section">\n`;
    html += `      <h2 class="city-title">${city}</h2>\n`;
    html += `      <div class="grid">\n`;
    
    centers.forEach(center => {
        const fileName = `../Centers/tykes-center-${center.area.toLowerCase().replace(/\s+/g, '-')}.html`;
        html += `        <div class="card">\n`;
        html += `          <h3>Tykes ${center.area}</h3>\n`;
        html += `          <p><strong>📍 Address:</strong> ${center.address.substring(0, 60)}...</p>\n`;
        html += `          <a href="${fileName}">View Center Details &rarr;</a>\n`;
        html += `        </div>\n`;
    });
    
    html += `      </div>\n`;
    html += `    </div>\n`;
}

html += `  </div>
</body>
</html>`;

fs.writeFileSync(outputPath, html);
console.log('Successfully generated tykes-centers-list.html');
