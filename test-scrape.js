const axios = require('axios');
const fs = require('fs');

async function download() {
    try {
        const { data } = await axios.get('https://kidzonia.in/navi-mumbai/preschool-vashi/');
        fs.writeFileSync('vashi-test.html', data);
        console.log(`Saved vashi-test.html. Length: ${data.length}`);
    } catch (e) {
        console.error(e.message);
    }
}
download();
