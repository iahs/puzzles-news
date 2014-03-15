angular.module('rssFeedCtrl', [])
    .controller('RssFeedController', function($scope, $http, $state, RssFeed) {

        RssFeed.get().success(function (response) {
            $scope.feeds = response['data'];
        });

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
        $scope.deleteFeed = function(id) {
            console.log("deleting" + id)
            RssFeed.destroy(id)
                .success(function(data) {
                    RssFeed.get()
                        .success(function(response) {
                            $scope.posts = response['data'];
                        });
                });
        };

    })
