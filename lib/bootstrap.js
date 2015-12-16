/*global casper */
/*jslint node */

var libdir = casper.cli.get("libdir");

var _ = require('underscore');

var utils = require('utils');

var asserts = require(libdir + '/assertions');
var options = require(libdir + '/../options');

casper.test.on("fail", function () {

    'use strict';

    // This is a hack to avoid the PhantomJS bug that displays "Unsafe JavaScript attempt to access frame"
    // See https://github.com/ariya/phantomjs/issues/12697
    setTimeout(function exit() {
        casper.exit(99);
    }, 0);
});
