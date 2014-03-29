

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
    .factory('Auth', function($http, $rootScope, $state, $q) {
        var authUrl = '/session/';

        /*
         * The auth object is responsible for holding the current user information. The user information has to
         * be attached to an object because we want to pass by reference, but update the user object itself on
         * queries to the server.
         */
        var auth = {user: null};

        // Listen for the http interceptor
        $rootScope.$on('auth:loginRequired', function () {
            auth.user = null;
            $state.go('user.login');
        })

        // Listen for the http interceptor
        $rootScope.$on('auth:forbidden', function () {
            $state.go('posts.list');
        })

        /*
         * The getAuth function queries the server for current session data
         * @return promise for auth object
         */
        var getAuth = function () {
            var deferred = $q.defer();
            if (! auth.user) {
                // Just query the server when necessary
                $http.get(authUrl + 'show').success(function (response) {
                    auth.user = response['data'];

                    auth.isAdmin = auth.user['role'] > 2;
                    auth.isEditor = auth.user['role'] > 1;

                    deferred.resolve(auth);
                });
            } else {
                deferred.resolve(auth);
            }
            return deferred.promise;
        };

        return {
            getAuth: getAuth,
            signup: function (newUser) {
                return $http({
                    url: '/api/users',
                    method: 'POST',
                    data: {
                        data: newUser
                    }
                }).success(function (response) {
                    auth.user = response['data'];
                    $rootScope.$broadcast('auth:login', response['data']); // A new user is automatically signed in
                    $state.go('posts.list');
                }).error(function (response) {
                    $rootScope.error = response['errors'];
                });
            },
            updateProfile: function (user) {
                return $http({
                    url: '/api/users/' + user.id,
                    method: 'PUT',
                    data: {
                        data: user
                    }
                });
            },

            login: function(credentials) {
                return $http({
                    url: '/session/apilogin',
                    method: 'POST',
                    data: {
                        data: credentials
                    }
                }).success(function (response) {
                    auth.user = response['data'];
                    $rootScope.$broadcast('auth:login', response['data']);
                    $state.go('posts.list');
                }).error(function (response) {
                    $rootScope.error = response['errors'];
                });
            },

            logout: function () {
                $http.get(authUrl + "destroy")
                    .success(function (responde) {
                        auth.user = null;
                        $rootScope.$broadcast('auth:logout', {})
                    });
            }
        };
    });
