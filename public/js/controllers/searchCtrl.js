angular.module('searchCtrl', [])
    .controller('SearchController', function ($scope, $http, Post) {

        // CONFIG: max post length
        $scope.maxPostLength = 320;

        $scope.queryTags = [];

        $http.get('/api/tags').success(function (response) {
            $scope.tags = response['data'];
        });

        // TODO: make it work
        $scope.addQueryTag = function (tag) {
            var tag = $scope.tags.splice($scope.tags.indexOf(tag),1)[0];
            $scope.queryTags.push(tag);
        };

        $scope.removeQueryTag = function (tag) {
            var tag = $scope.queryTags.splice($scope.queryTags.indexOf(tag),1)[0];
            $scope.tags.push(tag);
        };

        $scope.expandPost = function(id,pos) {
            $scope.searchResults[pos].expanded = true;
            Post.click(id);
        };

        $scope.search = function() {

            var tagIds = [];
            for(var i=0; i<$scope.queryTags.length; i++) {
                tagIds.push($scope.queryTags[i]['id']);
            };

            tagIds = tagIds.join(',');

            $http({
                url: '/api/posts/search',
                method: "GET",
                params: {query: $scope.search.query,
                         tags: tagIds}
            }).success(function (response) {
                $scope.searchResults = response.data && response.data.length>0 ? response.data : [{title: "No matches"}];
            })
        };

    })