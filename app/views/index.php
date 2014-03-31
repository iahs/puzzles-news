<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example with a side menu that hides on mobile, just like the Pure website.">

    <title>Harvard News Aggregator - Team Puzzles</title>

    <link rel="stylesheet" href="bower_components/pure/pure-min.css">
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="css/side-menu.css">
        <link rel="stylesheet" href="css/side-panel-twitter.css">
        <link rel="stylesheet" href="css/main.css">
    <!--<![endif]-->
</head>

<body ng-app="newsApp">
    <div id="layout">
        <!-- Menu toggle -->
        <a href="#menu" id="menuLink" class="menu-link">
            <!-- Hamburger icon -->
            <span></span>
        </a>
        <div id="menu" ng-controller="MenuController">
            <div class="pure-menu pure-menu-open">
                <a class="pure-menu-heading" href="#" ng-click="clearQuery()">Puzzles</a>
                <li><a ui-sref="posts.list" ng-click="clearQuery()">List posts</a></li>
                <li><a ui-sref="posts.popular">Popular posts</a></li>
                <li ng-hide="auth.user"><a ui-sref="user.login">Login</a></li>
                <li ng-show="auth.isEditor"><a ui-sref="feeds.list">Manage RSS</a></li>
                <li ng-show="auth.user"><a ui-sref="user.edit">Edit account</a></li>
                <p ng-show="auth.user" style="text-align: center">
                    <img src="http://www.gravatar.com/avatar/{{ auth.user.gravatar }}" alt="Gravatar" title="Edit your profile picture at gravatar.com" />
                    <br/>
                    You are signed in as {{ auth.user.first_name || auth.user.cs50fullname }}
                </p>
                <li ng-show="auth.user"><a href="#" ng-click="logout()">Logout</a></li>
            </div>
        </div>


        <div class="pure-g">
            <div id="main" ui-view class="pure-u-3-4">

            </div>

            <div id="sidebar" ng-controller="TweetController" class="pure-u-1-4 pure-hidden-phone">


                <div class="side-panel-twitter">

                    <a class="twitter-timeline" href="https://twitter.com/taylorswift13" data-widget-id="438017394917638144">Tweets by @taylorswift13</a>
                    <script> 

                        !function(d,s,id){
                            var js,fjs=d.getElementsByTagName(s)[0];

                            if(!d.getElementById(id)){
                                js=d.createElement(s);js.id=id;
                                js.src="https://platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js,fjs);
                            }

                            console.log("ran");

                        }(document,"script","twitter-wjs"); 
                    </script>
                </div>

                <twitter-feed></twitter-feed>

            </div>
        </div>

    </div>


    <script src="js/ui.js"></script>
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/angular-ui-router/release/angular-ui-router.js"></script>

    <script src="js/main.js"></script>
    <script src="js/filters.js"></script>
    <script src="js/angularSlideables.js"></script>
    <script src="js/services/postService.js"></script>
    <script src="js/services/authService.js"></script>
    <script src="js/services/tweetService.js"></script>
    <script src="js/services/rssFeedService.js"></script>
    <script src="js/controllers/twitter.js"></script>
    <script src="js/controllers/postCtrl.js"></script>
    <script src="js/controllers/userCtrl.js"></script>
    <script src="js/controllers/menuCtrl.js"></script>
    <script src="js/controllers/tweetCtrl.js"></script>
    <script src="js/controllers/rssFeedCtrl.js"></script>


</body>
</html>
