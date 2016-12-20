var ppMigrationApp = angular.module('ppMigrationApp', ['ngMessages', 'ngAnimate']);

ppMigrationApp.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
});

ppMigrationApp.controller('pageController', ['$scope', function($scope) {
    $scope.units = 1;
    $scope.hasErrors = function(errors) {
        return !angular.equals({}, errors);
    }
}]);

angular.element(document).ready(function () {
    var appDiv = document.getElementById('page');
    angular.bootstrap(angular.element(appDiv), ['ppMigrationApp', 'ngMessages']);
});






