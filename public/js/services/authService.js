

/**
 * The Authentication service is responsible for logging users in and out,
 * and to retrieve the current session status from the server
 *
 * Auth events that are broadcasted on Auth events from here and the Auth http interceptor in the config
 *
 * auth:login - the user has logged in
 * auth:logout - the user has out out
 * auth:loginRequired - the user has tried to access an api that requires login
 * auth:forbidden - the user (signed in) has tried to access and api without sufficient priviledges
 *
 */
angular.module('authService', [])
    .factory('Auth', function($http, $rootScope, $state) {
        var authUrl = '/session/';

        return {
            serverStatus: function() {
                console.log('checking user status');
                return $http.get(authUrl + 'show');
            },
            signup: function (newUser) {
                return $http({
                    url: '/api/users',
                    method: 'POST',
                    data: {
                        data: newUser
                    }
                }).success(function (response) {
                    $rootScope.$broadcast('auth:login', response['data']); // A new user is automatically signed in
                    $state.go('posts.list');
                }).error(function (response) {
                    console.log("Signup failed");
                });
            },
            // Not setting session cookie
            login: function(credentials) {
                return $http({
                    url: '/session/apilogin',
                    method: 'POST',
                    data: {
                        data: credentials
                    }
                }).success(function (response) {
                    $rootScope.$broadcast('auth:login', response['data']);
                    $state.go('posts.list');

                }).error(function (response) {
                    console.log("Login failed");
                });
            },

            logout: function () {
                console.log("Logging out");
                $http.get(authUrl + "destroy")
                    .success(function (responde) {
                        $rootScope.$broadcast('auth:logout', {})
                    });
            }
        };
    });