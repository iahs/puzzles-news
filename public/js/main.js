var newsApp = angular.module('newsApp', ['ui.router', 'postCtrl', 'postService']);

newsApp.config(function($stateProvider, $urlRouterProvider) {
    //
    // For any unmatched url, redirect to home page
    $urlRouterProvider.otherwise("posts");
    //
    // Now set up the states
    $stateProvider
        .state('posts', {
            templateUrl: "partials/posts.html",
            controller: 'PostController'
        })
        .state('posts.list', {
            url: "/posts",
            templateUrl: "partials/posts.list.html",
        })
        .state('posts.new', {
            url: "/posts/new",
            templateUrl: "partials/posts.new.html",
        })
});