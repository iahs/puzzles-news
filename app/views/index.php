<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A layout example with a side menu that hides on mobile, just like the Pure website.">

    <title>PureCSS</title>

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
        <!-- Menu toggle -->'
        <a href="#menu" id="menuLink" class="menu-link">
            <!-- Hamburger icon -->
            <span></span>
        </a>
        <div id="menu">
            <div class="pure-menu pure-menu-open">
                <a class="pure-menu-heading" href="#">Puzzles</a>
                <li><a ui-sref="posts.list">List posts</a></li>
                <li><a ui-sref="posts.new">Create post</a></li>
                <li><a ui-sref="twitterfeed">Twitter</a></li>
            </div>
        </div>

        <div id="main" ui-view>
            <div class = "container">
                <div class = "side-panel-twitter">

                </div>

        </div>
    </div>

    <script src="js/ui.js"></script>
    <script src="bower_components/angular/angular.js"></script>
    <script src="bower_components/angular-ui-router/release/angular-ui-router.js"></script>

    <script src="js/main.js"></script>
    <script src="js/services/postService.js"></script>
    <script src="js/controllers/postCtrl.js"></script>
    <script src="js/controllers/twitter.js"></script>

</body>
</html>
