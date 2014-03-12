angular.module('rssFeedCtrl', [])
    .controller('RssFeedController', function($scope, $http, $state, RssFeed) {

        // Create a new rss feed
        $scope.createFeed = function() {
            console.log("creating feed")
            RssFeed.save($scope.newFeed)
                .success(function(response) {
                    RssFeed.get()
                        .success(function(getData) {
                            $state.go('posts.list');
                        });
                })
                .error(function(response) {
                    console.log(response);
                });
        };

        // Delete a feed
        $scope.deletePost = function(id) {
            RssFeed.destroy(id)
                .success(function(data) {
                    RssFeed.get()
                        .success(function(getResponse) {
                            $scope.posts = getResponse.data;
                        });
                });
        };

    })
