const isPorn = require('is-porn');
const helpers = require('./helpers.js');

(async () => {

    const args = process.argv.splice(2);
    const url = args[0];
    const name = await helpers.extractRootDomain(url);

    isPorn(name, function (error, status) {
        console.log(JSON.stringify(status));
    });

})();
