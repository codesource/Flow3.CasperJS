/*global _ */

(function () {

    'use strict';

    module.exports = (function (casper) {

        function getHeader(response, name) {
            return _.find(response.headers, function (item) {
                return item.name === name;
            });
        }

        return {

            responseStatus: function (response, expectedStatus) {
                casper.test.assert(_.has(response, 'status'), 'Response has status');
                casper.test.assertEquals(response.status, expectedStatus, 'Response has status ' + expectedStatus);
            },

            responseIsJson: function (response) {

                casper.test.assert(_.has(response, 'headers'), 'Response has headers');

                var contentType = getHeader(response, 'Content-Type');

                casper.test.assert(_.has(contentType, 'value'), 'Response has content type');
                casper.test.assertEquals(contentType.value, 'application/json', 'Content type is JSON');
            }
        };

    }(casper));

}());
