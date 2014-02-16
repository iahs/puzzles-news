angular.module('postCtrl', [])

    .controller('PostController', function($scope, $http, $state, Post) {

        // Object to hold data for the new post form
        $scope.postData = {};

        // Show the loading icon
        $scope.loading = true;

        // Get all the posts
        Post.get()
            .success(function(data) {
                $scope.posts = data;
                $scope.loading = false;
            });

        // Create a new post
        $scope.submitPost = function() {
            $scope.loading = true;

            Post.save($scope.postData)
                .success(function(data) {

                    Post.get()
                        .success(function(getData) {
                            console.log('success');
                            $scope.posts = getData;
                            $scope.loading = false;
                            $state.go('posts.list');
                        });


                })
                .error(function(data) {
                    // Not sure why this is necessary
                    console.log(data);
                });
        };

        // Delete a post
        $scope.deletePost = function(id) {
            $scope.loading = true;

            Post.destroy(id)
                .success(function(data) {
                    Post.get()
                        .success(function(getData) {
                            $scope.posts = getData;
                            $scope.loading = false;
                        });
                });

        };

    });