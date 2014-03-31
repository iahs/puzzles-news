angular.module('menuCtrl', [])
    .controller('MenuController', function($scope, Auth, $rootScope) {
        // Bind the auth object with current user data to the scope
        Auth.getAuth().then(function (auth) {
            $scope.auth = auth;
            $scope.logout = Auth.logout;
        });

        // tell postCtrl to clear query when the user clicks "List posts"
        $scope.clearQuery = function() {
            $rootScope.$broadcast('clearQuery');
        }
    });
