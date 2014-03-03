/**
 * Truncate Filter
 * @Param text
 * @Param length, default is 10
 * @Param end, default is "..."
 * @return string
 * from http://jsfiddle.net/tUyyx/
 */

angular.module('newsAppFilters', [])
    .filter('truncate', function () {
        return function (text, length, end) {
            if (isNaN(length))
                length = 10;

            if (end === undefined)
                end = "...";

            if (text.length <= length || text.length - end.length <= length) {
                return text;
            }
            else {
                return String(text).substring(0, text.lastIndexOf(' ',length-end.length)) + end;
            }

        };
    })
    .filter('unescape', function () {
        return function (text) {
            return text.replace(/&[rlds]{2}quo;/g, '"').replace(/&nbsp;/g,' ').replace(/&hellip;/g,' ').replace(/&[a-z]{1,5};/g,'');
        };
    });

/**
 * Usage
 *
 * var myText = "This is an example.";
 *
 * {{myText|Truncate}}
 * {{myText|Truncate:5}}
 * {{myText|Truncate:25:" ->"}}
 * Output
 * "This is..."
 * "Th..."
 * "This is an e ->"
 *
 */