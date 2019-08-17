const request = require('request');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];

    var result = {
        error: false,
        errorDetails: {
            response: null,
            body: null,
        },
        isAvailable: true,
        code: null,
        message: '',
    };

    request(url, {
        json: true,
        timeout: 50000
    }, (err, response, body) => {
        if (err) {
            result.error = true;
            result.errorDetails.response = response;
            result.errorDetails.body = body;
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
