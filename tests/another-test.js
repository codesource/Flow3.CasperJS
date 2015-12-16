/*global casper */

casper.test.begin('Another test', function suite(test) {

    casper.start();

    casper.then(function () {
        console.log('hello');
    });

    casper.run(function () {
        this.test.done();
    });

});
