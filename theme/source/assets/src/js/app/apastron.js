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

apastronApp.controller('apastronCont', ['$scope', '$http', '$routeParams', '$sce', '$timeout', 

	function($scope, $http, $routeParams, $sce, $timeout) {

	    // JSON content location
	    api.query = 'http://apastron.loc/wp-json/';

	    // add content to the scope

		$http.get('/wp-json/wp/v2/apastron')
			.then(function (success){
				$scope.test = success.data;
				$scope.slides = $scope.test.home_slides;
				$scope.onEnd = function(){
					$timeout(function(){
						$scope.mySiema = new Siema({
							selector: '.home-slider'
						});
					}, 1);
				};
			},function (error){
				$scope.test = 'nee';
		});
	}

]);

apastronApp.filter('htmlToPlaintext', function() {
	return function(text) {
		return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
	};
});