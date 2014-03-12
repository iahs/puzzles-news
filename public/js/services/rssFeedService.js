angular.module('rssFeedService', [])
    .factory('RssFeed', function($http) {
        var postUrl = '/api/rssfeeds/';
        return {
            get: function() {
                return $http.get(postUrl);
            },
            save: function(postData) {
                return $http({
                    method: 'POST',
                    url: postUrl,
                    data: {data: postData}
                });
            },
            destroy: function(id) {
                return $http.delete(postUrl + id);
            }
        }
    });
