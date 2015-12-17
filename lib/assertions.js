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

            /**
             * Assert the response has a given HTTP status code.
             * @param response The casper response
             * @param expectedStatus The expected status code
             */
            responseStatus: function (response, expectedStatus) {
                casper.test.assert(_.has(response, 'status'), 'Response has status');
                casper.test.assertEquals(response.status, expectedStatus, 'Response status is ' + expectedStatus);
            },

            /**
             * Assert the response content type is the one expected.
             * @param response The casper response
             * @param expectedContentType The expected content type
             */
            responseContentTypeIs: function (response, expectedContentType) {

                casper.test.assert(_.has(response, 'headers'), 'Response has headers');

                var contentType = getHeader(response, 'Content-Type');

                casper.test.assert(_.has(contentType, 'value'), 'Response has content type');
                casper.test.assertEquals(
                    contentType.value,
                    expectedContentType, 'Content type is "' + expectedContentType + '"'
                );
            },

            /**
             * Assert the content type of the response is json.
             * @param response The casper response
             */
            responseIsJson: function (response) {
                this.responseContentTypeIs(response, 'application/json');
            },

            /**
             * Assert the response is JSON and successful (HTTP status 200).
             * @param response The casper response
             */
            responseIsSuccessfulJson: function (response) {
                this.responseStatus(response, 200);
                this.responseIsJson(response);
            },

            /**
             * Assert the response is JSON but unsuccessful (HTTP status 400).
             * @param response The casper response
             * @param expectedStatus The expected HTTP status (default: 400)
             */
            responseIsUnsuccessfulJson: function (response, expectedStatus) {
                expectedStatus = expectedStatus || 400;
                this.responseStatus(response, expectedStatus);
                this.responseIsJson(response);
            },

            /**
             * Assert the response content is a Json error, i.e. it contains an "error" object field.
             * @param data
             */
            responseIsAnError: function (data) {
                casper.test.assert(_.isObject(data), 'Response content is an object');
                casper.test.assert(_.has(data, 'errors'), 'Response data has an "error" field');
                casper.test.assert(_.isObject(data.errors), 'Response data.errors is an object');
            },

            /**
             * Assert an object has a field with given name and value.
             * @param obj The object to test
             * @param fieldName The name of the field expected to exist
             * @param expectedValue The expected value for that field
             */
            objectHasFieldWithValue: function (obj, fieldName, expectedValue) {
                casper.test.assert(_.isObject(obj), 'Parameter is an object');
                casper.test.assert(_.has(obj, fieldName), 'Object has a "' + fieldName + '" field');

                if (expectedValue) {
                    casper.test.assertEquals(
                        expectedValue,
                        obj[fieldName],
                        'Object "' + fieldName+ '" field value is correct'
                    );
                }
            }

        };

    }(casper));

}());
