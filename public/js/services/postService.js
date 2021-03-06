angular.module('postService', [])
    .factory('Post', function($http) {
        var postUrl = '/api/posts/';
        return {
            get: function() {
                return $http.get(postUrl);
            },

            infiniteLoader: function(pOldest, pLimit, query, tags) {
                return $http({
                    method: 'GET',
                    url: postUrl + 'infinite',
                    params: {
                        oldest: pOldest,
                        limit: pLimit,
                        query: query,
                         tags: tags
                    }
                });
            },

            getPopular: function() {
                return $http({
                    method: 'GET',
                    url: postUrl + 'popular',
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
            },

            click: function(id) {
                return $http.post(postUrl + 'click',
                $.param({id: id}),
                {
                    headers:
                    {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    }
                });
            }
        }

    });
