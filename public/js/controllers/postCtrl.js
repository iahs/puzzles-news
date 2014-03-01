angular.module('postCtrl', [])
    .controller('PostController', function($scope, $http, $state, Post, $window) {
        // Object to hold data for the new post form
        $scope.postData = {};
        var infiniteLoading = false;

        // Get all the posts
        Post.infiniteLoader(0, 15)
            .success(function(response) {
                $scope.posts = response.data;
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
                    for (var i=0; i<response.data.length; i++) {
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
    })
