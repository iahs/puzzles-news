angular.module('searchCtrl', [])
    .controller('SearchController', function ($scope, $http) {


        $scope.search = function() {
            $http({
                url: '/api/posts/search',
                method: "GET",
                params: {query: $scope.search.query}
            }).success(function (response) {
                $scope.searchResults = response.data && response.data.length>0 ? response.data : [{title: "No matches"}];
            })
        };

    })