var apastronApp = angular.module('apastronNG', ['ngRoute']);
	apastronJson = 'http://apastron.loc/wp-json/',
	api = {};
	supportsHistoryApi = !!(window.history && history.pushState);

// This to avoid conflicts with the twigs syntax {{}} as it happens to be the same in angular, hence the need to change it to {[]} instead.

apastronApp.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[');
    $interpolateProvider.endSymbol(']}');
});

if(supportsHistoryApi){
    apastronApp.config(function($locationProvider){
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false,
            rewriteLinks: false
        });
    });
}

apastronApp.controller('apastronHeader', ['$scope', '$http', '$routeParams', '$sce', 

	function($scope, $http, $routeParams, $sce) {

	    // JSON content location
	    api.query = 'http://apastron.loc/wp-json/wp/v2/acf/options';

	    // add content to the scope

		$http.get('/wp-json/wp/v2/acf/options')
			.then(function (success){
				$scope.test = success.data;
			},function (error){
				$scope.test = 'nee';
		});
	}

]);