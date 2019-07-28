const request = require('request');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];

    var result = {
        error: false,
        code: null,
        message: '',
    };

    request(url, {json: true}, (err, response, body) => {
        if (err) {
            result.error = true;
        } else {
            result.code = response.statusCode;
            result.message = response.statusMessage;
            if (result.code < 200 || result.code > 299) {
                result.error = true;
            }
        }
        console.log(JSON.stringify(result));
    });

})();
