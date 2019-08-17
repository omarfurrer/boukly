const request = require('request');
const helpers = require('./helpers.js');
var userAgents = require('./userAgents.json');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];

    var result = {
        error: false,
        errorDetails: {
            response: null,
        },
        isAvailable: true,
        code: null,
        message: '',
    };
    request(url, {
        json: true,
        timeout: 50000,
        jar: request.jar(), // Cookie jar
        headers: {
            'User-Agent': helpers.getRandomUserAgent(userAgents)
        },
    }, (err, response, body) => {
        if (err) {
            result.error = true;
            result.errorDetails.response = err;
        } else {
            result.code = response.statusCode;
            result.message = response.statusMessage;
            if (result.code < 200 || result.code > 299) {
                result.isAvailable = false;
            }
        }
        console.log(JSON.stringify(result));
    });

})();
