/*global utils */

/**
 * Attach callbacks to various casper events to facilitate debuging.
 */

(function (casper, utils) {
    'use strict';

    casper.on('page.error', function(message, trace) {
        utils.dump('================ PAGE.ERROR');
        utils.dump(message);
        utils.dump(trace);
    });

    casper.on('page.resource.requested', function(request) {
        utils.dump('---------------- PAGE.RESOURCE.REQUESTED: ' + request.url);
    });

    casper.on('page.resource.received', function(response) {
        utils.dump('---------------- PAGE.RESOURCE.RECEIVED: ' + response.url);
    });

    casper.on('resource.error', function() {
        utils.dump('================ RESOURCE.ERROR');
        utils.dump(JSON.stringify(arguments));
    });

    casper.on('resource.requested', function(request) {
        utils.dump('---------------- RESOURCE.REQUESTED: ' + request.url);
    });

    casper.on('resource.received', function(response) {
        utils.dump('---------------- RESOURCE.RECEIVED: ' + response.url);
    });

    casper.on('remote.message', function(message) {
        utils.dump('---------------- REMOTE.MESSAGE: ' + message);
    });

}(casper, utils));
