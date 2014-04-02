angular.module('tweeterCtrl', [])
    .controller('TweeterController', function($scope, Tweeter) {
		$scope.tweeters = [];
        Tweeter.get()
            .success(function(response) {
                $scope.tweeters = response.data;
            });
    });