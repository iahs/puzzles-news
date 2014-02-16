angular.module('postService', [])
    .factory('Post', function($http) {
        var postUrl = '/api/posts/';
        return {
            get: function() {
                return $http.get(postUrl);
            },

            infiniteLoader: function(pNewest, pLimit) {
                return $http({
                    method: 'GET',
                    url: '/api/posts/infinite',
                    params: {
                        newest: pNewest,
                        limit: pLimit
                    }
                });
            },

            save: function(postData) {
                return $http({
                    method: 'POST',
                    url: postUrl,
                    data: postData
                });
            },

            destroy: function(id) {
                return $http.delete(postUrl + id);
            }
        }

    });