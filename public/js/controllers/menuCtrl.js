angular.module('menuCtrl', [])
    .controller('MenuController', function($scope, Auth) {
        // TODO: Should move all this to a directive
        // Get the user status when the controller loads
        Auth.serverStatus().success(function (response) {
            $scope.user = response['data'];
        });

        // Then listen for any events indicating a change in status
        $scope.$on('auth:logout', function (event, data) {
            $scope.user = {message: "Not logged in"}
        })
        $scope.$on('auth:login', function (event, data) {
            $scope.user = data;
        })
        $scope.$on('auth:loginRequired', function (event, data) {
            $scope.user = {message: "Not logged in"}
        })
    });



