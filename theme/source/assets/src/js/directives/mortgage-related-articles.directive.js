var searchArticleMiniTemplateUrl = '/app/themes/boi-ppg/views/html/mortgage-related-articles.html',
    directiveRestriction = 'E';


ppMigrationApp.directive('mortgageRelatedArticles', ['$http', '$cookies', 'ajaxRequestService', 'ppMigCookieService', function($http, $cookies, ajaxRequestService, ppMigCookieService) {

    var directive = {};
    directive.restrict = directiveRestriction;
    directive.transclude = true;
    directive.replace = false;
    directive.templateUrl = searchArticleMiniTemplateUrl;
    directive.scope = {
        id:"@",
        mortgageSegment:'=',
        mortgageStage:'=?',
        currentArticleId:'=?',
        numberArticles:'=?'
    };

    directive.link = function(scope, element, attrs){
        console.log('scope.mortgageSegment: '+scope.mortgageSegment);
        var url = '/wp-json/boi-api/v1/mortgages/articles/related/' + scope.mortgageSegment + '/' + (angular.isDefined(scope.mortgageStage) ? scope.mortgageStage : 'na') + '/' + (angular.isDefined(scope.numberArticles) ? scope.numberArticles : 3) +'/' + (angular.isDefined(scope.currentArticleId) ? scope.currentArticleId : 0) + '/';
        ajaxRequestService.async(url).then(function(data) {
            console.log('scope.relatedArticles: '+scope.relatedArticles)
            scope.relatedArticles = data;
        });
    };
    return directive;
}]);
