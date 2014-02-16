angular.module('menuCtrl', [])
    .controller('MenuController', function($scope, Auth) {
        // Bind the auth object with current user data to the scope
        Auth.getAuth().then(function (auth) {
            $scope.auth = auth;
        });
    });



