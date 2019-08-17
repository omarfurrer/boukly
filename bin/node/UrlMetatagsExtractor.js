var scrape = require('html-metadata');
var request = require('request');
const helpers = require('./helpers.js');
var userAgents = require('./userAgents.json');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];

    var result = {
        error: false,
        errorDetails: {
            response: null,
            body: null,
        },
        metaTags: null
    };

    scrape({
        url: url,
        jar: request.jar(), // Cookie jar
        headers: {
            'User-Agent': helpers.getRandomUserAgent(userAgents)
        },
        timeout: 50000
    }, function (error, metadata) {
        if (error) {
            result.error = true;
            result.errorDetails = error;
        } else {
            result.metaTags = metadata;
        }
        console.log(JSON.stringify(result));
    });

})();
