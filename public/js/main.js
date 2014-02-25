var newsApp = angular.module('newsApp', ['ui.router', 'postCtrl','userCtrl', 'menuCtrl', 'postService', 'authService']);

newsApp.config(function($stateProvider, $urlRouterProvider, $httpProvider) {
    //
    // For any unmatched url, redirect to home page
    $urlRouterProvider.otherwise("posts");

    // Now set up the states
    $stateProvider
        .state('posts', {
            abstract: true,
            templateUrl: "partials/posts.html"
        })
        .state('posts.list', {
            url: "/posts",
            templateUrl: "partials/posts.list.html",
            controller: 'PostController'
        })
        .state('posts.new', {
            url: "/posts/new",
            templateUrl: "partials/posts.new.html",
            controller: 'PostController'
        })
        .state('user', {
            abstract: true,
            templateUrl: "partials/user.html"
        })
        .state('user.login', {
            url: "/login",
            controller:"UserController",
            templateUrl: "partials/user.login.html"
        })
        .state('user.signup', {
            url: "/signup",
            controller:"UserController",
            templateUrl: "partials/user.signup.html"
        })
});

/*
 * Catch 401 error messages from the server,
 * and check for API codes indicating that the user is
 * unauthenticated or unauthorized to view the page.
 *
 */
newsApp.config(function ($httpProvider) {

    var interceptor = function ($q, $rootScope, $location) {
        return {
            response: function (response) {
                //Will only be called for HTTP up to 300
                return response;
            },
            responseError: function (rejection) {

                switch (rejection.status) {
                    case 401:
                        if (rejection.config.url!=='#/login')
                        {
                            $rootScope.$broadcast('auth:loginRequired');
                            $location.path('login');
                        }
                        break;
                    case 403:
                        $rootScope.$broadcast('auth:forbidden');
                        break;
                    case 404:
                        $rootScope
                            .$broadcast('page:notFound');
                        break;
                    case 500:
                        $rootScope
                            .$broadcast('server:error');
                        break;
                }
                return $q.reject(rejection);
            }
        }
    }
    $httpProvider.interceptors.push(interceptor);
});

