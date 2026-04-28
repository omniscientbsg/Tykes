const https = require('https');

https.get('https://tykes.school/tykes-franchise/', (resp) => {
  let data = '';
  resp.on('data', (chunk) => {
    data += chunk;
  });
  resp.on('end', () => {
    const fs = require('fs');
    fs.writeFileSync('franchise-scrape.html', data);
    console.log('Done');
  });
}).on("error", (err) => {
  console.log("Error: " + err.message);
});
