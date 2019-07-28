var scrape = require('html-metadata');
var request = require('request');
const helpers = require('./helpers.js');
var userAgents = require('./userAgents.json');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];

    scrape({
        url: url,
        jar: request.jar(), // Cookie jar
        headers: {
            'User-Agent': helpers.getRandomUserAgent(userAgents)
        }
    }, function (error, metadata) {
        console.log(JSON.stringify(metadata));
    });

})();
