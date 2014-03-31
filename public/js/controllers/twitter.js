angular.module('twitterFeed', []).
    directive('twitterFeed', ['$window', '$q', function ($window, $q) {
        return {
            restrict: 'E',
            link: function (scope, element, attrs) {
                !function(d,s,id){
                    var js,fjs=d.getElementsByTagName(s)[0];
                    js=d.createElement(s);js.id=id;
                    js.src="https://platform.twitter.com/widgets.js";
                    fjs.parentNode.insertBefore(js,fjs);
                }(document,"script","twitter-wjs");
            }
        };
    }]);
