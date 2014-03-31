angular.module('tweetCtrl', [])
    .controller('TweetController', function($scope, Tweet) {
		$scope.tweets = [];
        Tweet.get()
            .success(function(response) {
                $scope.tweets = response.data;
            });
    });
