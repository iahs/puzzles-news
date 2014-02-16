var newsApp = angular.module('newsApp', ['ui.router', 'postCtrl', 'postService']);

newsApp.config(function($stateProvider, $urlRouterProvider) {
    //
    // For any unmatched url, redirect to home page
    $urlRouterProvider.otherwise("posts");
    //
    // Now set up the states
    $stateProvider
        .state('posts', {
            url: "/posts",
            templateUrl: "partials/posts.html",
            controller: 'PostController'
        })
        .state('posts.list', {
            url: "/posts/list",
            templateUrl: "partials/posts.list.html",
            controller: 'PostController'
        })
        .state('state2', {
            url: "/state2",
            templateUrl: "partials/state2.html"
        })
});