(function () {

    'use strict';

    // This is a hack to avoid the PhantomJS bug that displays "Unsafe JavaScript attempt to access frame"
    // See https://github.com/ariya/phantomjs/issues/12697
    setTimeout(function exit() {
        casper.test.done();
        casper.exit(0);
    }, 0);

}());
