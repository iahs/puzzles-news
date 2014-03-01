angular.module('postCtrl', [])
    .controller('PostController', function($scope, $http, $state, Post, $window) {
        // CONFIG: max post length
        $scope.maxPostLength = 320;

        // Object to hold data for the new post form
        $scope.postData = {};
        var infiniteLoading = false;

        // Get all the posts
        Post.infiniteLoader(0, 15)
            .success(function(response) {
                $scope.posts = response.data;
                var pos = 0;
                for (var i=0; i<$scope.posts.length; i++) {
                    $scope.posts[i].pos = pos++;
                }
                $(window).scroll(function () {
                    if ($(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
                      $scope.infiniteLoadMore();
                    }
                });
            });

        // Load more posts
        $scope.infiniteLoadMore = function() {
            // Return if already in the process of loading more posts
            if (infiniteLoading) return;
            infiniteLoading = true;

            // Find the oldest post in the scope
            var minId = Number.POSITIVE_INFINITY;

            if (! $scope.posts) return;
            for (var i=$scope.posts.length-1; i>=0; i--) {
                var tmp = $scope.posts[i];
                if (tmp.id < minId) minId = tmp.id;
            }
            // And load posts older than that. 15 at the time
            Post.infiniteLoader(minId, 15)
                .success(function(response) {
                    var pos = $scope.posts.length;
                    for (var i=0; i<response.data.length; i++) {
                        response.data[i].pos = pos++;
                        console.log(pos)
                        $scope.posts.push(response.data[i]);
                    }
                    infiniteLoading = false;
                });
        };

        // Create a new post
        $scope.submitPost = function() {
            Post.save($scope.postData)
                .success(function(response) {
                    Post.get()
                        .success(function(getData) {
                            $scope.posts = getData;
                            $state.go('posts.list');
                        });
                })
                .error(function(response) {
                    console.log(response);
                });
        };

        // Delete a post
        $scope.deletePost = function(id) {
            Post.destroy(id)
                .success(function(data) {
                    Post.get()
                        .success(function(getResponse) {
                            $scope.posts = getResponse.data;
                        });
                });
        };

        // Expand post
        $scope.expandPost = function(pos) {
            $scope.posts[$scope.posts[pos].pos].expanded = true;
            console.log('expanded post')
            //TODO: track expand
        };
    })
/**
 * Truncate Filter
 * @Param text
 * @Param length, default is 10
 * @Param end, default is "..."
 * @return string
 * from http://jsfiddle.net/tUyyx/
 */
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
