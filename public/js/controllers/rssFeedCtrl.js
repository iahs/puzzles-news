angular.module('rssFeedCtrl', [])
    .controller('RssFeedController', function($scope, $http, $state, RssFeed) {

        RssFeed.get().success(function (response) {
            $scope.feeds = response['data'];
        });

        // Create a new rss feed
        $scope.createFeed = function () {
            RssFeed.save($scope.newFeed)
                .success(function (response) {
                    RssFeed.get()
                        .success(function(getData) {
                            $state.go('posts.list');
                        });
                }).error(function (response) {
                    $rootScope.error = response['errors'];
                });
        };

        // Delete a feed
        $scope.deleteFeed = function (id) {
            RssFeed.destroy(id)
                .success(function (data) {
                    RssFeed.get()
                        .success(function (response) {
                            $scope.feeds = response['data'];
                        });
                });
        };

    })
