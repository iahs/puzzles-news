angular.module('postCtrl', [])
    .controller('PostController', function($scope, $http, $state, Post, $window) {
        // CONFIG: max post length
        $scope.maxPostLength = 320;

        // Object to hold data for the new post form
        $scope.postData = {};

        // Tag stuff
        $scope.queryTags = [];

        $scope.addQueryTag = function (tag) {
            var tag = $scope.tags.splice($scope.tags.indexOf(tag),1)[0];
            $scope.queryTags.push(tag);
            $scope.search();
        };

        $scope.removeQueryTag = function (tag) {
            var tag = $scope.queryTags.splice($scope.queryTags.indexOf(tag),1)[0];
            $scope.tags.push(tag);
            $scope.search();
        };

        var infiniteLoading = false;

        if($state.is('posts.popular'))
        {
            // Get popular posts
            Post.getPopular()
                .success(function(response) {
                    $scope.posts = response.data;
                    var pos = 0;
                    for (var i=0; i<$scope.posts.length; i++) {
                        $scope.posts[i].pos = pos++;
                    }
                });
        }
        else
        {
            // Shuffle tag list here so order is the same within but not across loads
            var shuffle = function(o) {
                for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
                    return o;
            };

            // Load tag list
            $http.get('/api/tags').success(function (response) {
                $scope.tags = shuffle(response['data']);
            });

            // Get all the posts
            Post.infiniteLoader(0, 15, '', '')
                .success(function(response) {
                    $scope.posts = response.data;
                    var pos = 0;
                    for (var i=0; i<$scope.posts.length; i++) {
                        $scope.posts[i].pos = pos++;
                    }
                    // Bind for infinite scrolling
                    $(window).scroll(function () {
                        if ($(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
                          $scope.infiniteLoadMore();
                        }
                    });
                    // Add clear button (X) to search fields
                    function tog(v){return v?'addClass':'removeClass';}
                    $(document).on('input', '.clearable', function(){
                        $(this)[tog(this.value)]('x');
                    }).on('mousemove', '.x', function( e ){
                        $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');
                    }).on('click', '.onX', function(){
                        $(this).removeClass('x onX').val('').trigger('change');
                    });
                });
        }

        // Load more posts
        $scope.infiniteLoadMore = function() {
            // Return if already in the process of loading more posts
            if (infiniteLoading) return;
            infiniteLoading = true;

            // Find the oldest post in the scope
            var minCreated = Number.POSITIVE_INFINITY;

            if (! $scope.posts) return;
            for (var i=$scope.posts.length-1; i>=0; i--) {
                var tmp = $scope.posts[i];
                if (tmp.time_posted < minCreated) minCreated = tmp.time_posted;
            }
            // And load posts older than that. 15 at the time
            Post.infiniteLoader(minCreated, 15, $scope.search.query, $scope.tagIds)
                .success(function(response) {
                    var pos = $scope.posts.length;
                    for (var i=0; i<response.data.length; i++) {
                        response.data[i].pos = pos++;
                        $scope.posts.push(response.data[i]);
                    }
                    infiniteLoading = false;
                });
        };

        // Delete a post
        $scope.deletePost = function(id) {
            Post.destroy(id)
                .success(function(data) {
                    Post.get()
                        .success(function(getResponse) {
                            $scope.posts = getResponse.data;
                        });
                });
        };

        // Expand post
        $scope.expandPost = function(id,pos) {
            $scope.posts[pos].expanded = true;
            Post.click(id);
        };

        // Search for posts
        $scope.search = function() {
            var tagIds = [];
            for(var i=0; i<$scope.queryTags.length; i++) {
                tagIds.push($scope.queryTags[i]['id']);
            };

            $scope.tagIds = tagIds.join(',');

            Post.infiniteLoader(0, 15, $scope.search.query, $scope.tagIds)
                .success(function(response) {
                    $scope.posts = response.data && response.data.length>0 ? response.data : [{title: "No matches", body: ""}];
                    var pos = 0;
                    for (var i=0; i<$scope.posts.length; i++) {
                        $scope.posts[i].pos = pos++;
                    }
                });
        };

        // Clear query and tag selection
        var clearQuery = function() {
            $scope.search.query = '';
            $.merge($scope.tags, $scope.queryTags);
            $scope.queryTags = [];
        }

        // Show all possts with tag when user clicks on tag rectangle
        $scope.showTag = function(tag) {
            clearQuery();
            $scope.addQueryTag(tag);
        }

        // Clear query when user clicks "List posts" in menubar
        $scope.$on('clearQuery', function(){
            clearQuery();
            $scope.search();
        });
    })
