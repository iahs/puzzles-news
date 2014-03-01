angular.module('tweetCtrl', [])
    .controller('TweetController', function($scope) {

        $scope.tweets = [
            {content: "Tweet 1"},
            {content: "Tweet 2"}
        ];

    });

