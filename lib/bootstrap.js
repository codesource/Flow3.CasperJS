/*jslint unused: false, globalstrict: true, strict: true */

'use strict';

var libdir = casper.cli.get('libdir');

var _ = require('underscore');

var utils = require('utils');

var asserts = require(libdir + '/assertions');
var options = require(libdir + '/../options');

//var successes = [];
//var failures = [];
//
//casper.test.on("success", function(success) {
//    successes.push(success);
//});
//
//casper.test.on('fail', function (failure) {
//    failures.push(failure);
//});

casper.test.done();