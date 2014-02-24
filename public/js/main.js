var newsApp = angular.module('newsApp', ['ui.router', 'postCtrl', 'postService', 'twitterFeed']);

newsApp.config(function($stateProvider, $urlRouterProvider) {
    //
    // For any unmatched url, redirect to home page
    $urlRouterProvider.otherwise("posts");
    //
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
        .state('twitterfeed', {
            url: "/twitterfeed",
            templateUrl: "partials/twitterfeed.html",
        })
});


