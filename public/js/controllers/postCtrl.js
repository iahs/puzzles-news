angular.module('postCtrl', [])

    .controller('PostController', function($scope, $http, $state, Post) {

        // Object to hold data for the new post form
        $scope.postData = {};

        // Get all the posts
        Post.infiniteLoader(0, 15)
            .success(function(data) {
                $scope.posts = data;
            });


        // Load more posts
        $scope.infiniteLoadMore = function() {

            // Find the oldest post in the scope
            var minId = Number.POSITIVE_INFINITY;

            for (var i=$scope.posts.length-1; i>=0; i--) {
                var tmp = $scope.posts[i];
                if (tmp.id < minId) minId = tmp.id;
            }

            // And load posts older than that. 15 at the time
            Post.infiniteLoader(minId, 15)
                .success(function(data) {
                    for (var i=0; i<data.length; i++) {
                        $scope.posts.push(data[i]);
                    }
                    
                });
        };

        // Create a new post
        $scope.submitPost = function() {

            Post.save($scope.postData)
                .success(function(data) {
                    Post.get()
                        .success(function(getData) {
                            $scope.posts = getData;
                            $state.go('posts.list');
                        });
                })
                .error(function(data) {
                    console.log(data);
                });
        };

        // Delete a post
        $scope.deletePost = function(id) {

            Post.destroy(id)
                .success(function(data) {
                    Post.get()
                        .success(function(getData) {
                            $scope.posts = getData;
                        });
                });
        };

    });

