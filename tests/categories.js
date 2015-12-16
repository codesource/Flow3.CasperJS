/*global casper */

casper.test.begin('API category - List', function suite(test) {

    var content;

    casper.start();

    casper.thenOpen(options.baseUrl + '/api/categories', function (response) {
        asserts.responseStatus(response, 200);
        asserts.responseIsJson(response);
    });

    casper.then(function () {
        content = JSON.parse(this.getPageContent());
        test.assert(_.isArray(content), 'Response content is array');
    });

    casper.then(function () {
    });

    casper.run(function () {
        this.test.done();
    });

});

casper.test.begin('API category - Create', function (test) {

    casper.start();

    casper.thenOpen(options.baseUrl + '/api/categories', {
        method: 'POST'
    }, function (response) {
        asserts.responseStatus(response, 200);
        asserts.responseIsJson(response);
    });

    casper.run(function () {
        this.test.done();
    });

});

