angular.module('userCtrl', [])
    .controller('UserController', function($scope, $http, $state, Auth) {
        Auth.getAuth().then(function (auth) {
            $scope.auth = auth;
        });

        $scope.credentials = {};

        $scope.newUser = {};

        $scope.signup = function () {
            Auth.signup($scope.newUser);
        }

        $scope.login = function () {
            Auth.login($scope.credentials);
        };

        $scope.logout = function() {
            Auth.logout();
        }

        $scope.updateProfile = function() {
            Auth.updateProfile($scope.auth.user);
        }
    });
