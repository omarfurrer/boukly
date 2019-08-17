const isPorn = require('is-porn');
const helpers = require('./helpers.js');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];
    const name = await helpers.extractRootDomain(url);

    var result = {
        error: false,
        errorDetails: {
            response: null,
            body: null,
        },
        isAdult: false
    };

    isPorn(name, function (error, status) {
        if (error) {
            result.error = true;
            result.errorDetails = error;
            console.log(JSON.stringify(result));
        } else {
            result.isAdult = status;
        }
        console.log(JSON.stringify(result));
    });

})();
