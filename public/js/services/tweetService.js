angular.module('tweetService', [])
    .factory('Tweet', function($http) {
        var postUrl = '/api/tweets/';
        return {
            // get the tweets
            get: function() {
                return $http.get(postUrl);
            }
        };
    });
