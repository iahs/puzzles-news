angular.module('postCtrl', [])
    .controller('PostController', function($scope, $http, $state, Post, $window) {
        // CONFIG: max post length
        $scope.maxPostLength = 320;

        // Tag stuff
        $scope.queryTags = [];

        $http.get('/api/tags').success(function (response) {
            $scope.tags = response['data'];
        });

        // TODO: make it work
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

        // Object to hold data for the new post form
        $scope.postData = {};
        var infiniteLoading = false;

        // Get all the posts
        Post.infiniteLoader(0, 15, '', '')
            .success(function(response) {
                $scope.posts = response.data;
                var pos = 0;
                for (var i=0; i<$scope.posts.length; i++) {
                    $scope.posts[i].pos = pos++;
                }
                $(window).scroll(function () {
                    if ($(window).scrollTop() >= $(document).height() - $(window).height() - 300) {
                      $scope.infiniteLoadMore();
                    }
                });
            });

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

        // Create a new post
        $scope.submitPost = function() {
            Post.save($scope.postData)
                .success(function(response) {
                    Post.get()
                        .success(function(getData) {
                            $scope.posts = getData;
                            $state.go('posts.list');
                        });
                })
                .error(function(response) {
                    console.log(response);
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
                    $scope.posts = response.data && response.data.length>0 ? response.data : [{title: "No matches"}];
                    var pos = 0;
                    for (var i=0; i<$scope.posts.length; i++) {
                        $scope.posts[i].pos = pos++;
                    }
                });
        };

        $scope.showTag = function(tag) {
            $scope.search.query = '';
            $scope.queryTags = [tag];
            $scope.search();
        }
    })
