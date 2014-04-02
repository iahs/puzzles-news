angular.module('tweeterService', [])
    .factory('Tweeter', function($http) {
        var postUrl = '/api/tweeters/';
        return {
            // get the tweeters
            get: function() {
                return $http.get(postUrl);
            }
        };
    });