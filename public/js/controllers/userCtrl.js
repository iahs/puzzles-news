angular.module('userCtrl', [])
    .controller('UserController', function($scope, $http, $state, Auth) {
        $scope.credentials = {};

        $scope.status = {};

        $scope.newUser = {};

        Auth.serverStatus()
            .success(function(response) {
                $scope.status = response;
            });

        $scope.signup = function () {
            Auth.signup($scope.newUser);
        }

        $scope.login = function () {
            Auth.login($scope.credentials);
            Auth.serverStatus()
                .success(function(response) {
                    $scope.status = response;
                });

        };

        $scope.logout = function() {
            Auth.logout();
        }
    });

