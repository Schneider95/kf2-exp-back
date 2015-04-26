var module = angular.module('kf2App', ['ngMaterial', 'ngRoute']);

var origin = document.location.origin;
var folder = document.location.pathname.split('/')[1];
var partialsPath;

if (folder.length === 0) {
  partialsPath = origin + '/app/src/partials/';
} else {
  partialsPath = origin + '/' + folder + '/app/src/partials/';
}

module.constant('PartialsPath', partialsPath);
module.constant('SecondsBeforeUpdatePlayer', 1000);

module.config(function($routeProvider) {
  $routeProvider
  .when('/', {
    templateUrl: partialsPath + 'home.html',
    controller: 'HomeController as ctrl'
  })
  .when('/addPlayer', {
    templateUrl: partialsPath + 'addPlayer.html',
    controller: 'AddPlayerController as ctrl'
  })
  .when('/ranking', {
    templateUrl: partialsPath + 'ranking.html',
    controller: 'RankingController as ctrl'
  })
  .when('/stats/:ids', {
    templateUrl: partialsPath + 'stats.html',
    controller: 'StatsController as ctrl'
  })
  .when('/stats/', {
    templateUrl: partialsPath + 'stats.html',
    controller: 'StatsController as ctrl'
  })
  .when('/map', {
    templateUrl: partialsPath + 'map.html',
    controller: 'MapController as ctrl'
  })
  .when('/contact', {
    templateUrl: partialsPath + 'contact.html',
    controller: 'ContactController as ctrl'
  })
  .otherwise({
    redirectTo: '/'
  });
});

module.config(function($mdThemingProvider, $mdIconProvider){
  
  $mdThemingProvider.theme('default')
    .primaryPalette('grey')
    .accentPalette('red');
  
  $mdIconProvider
    .icon("svg-menu", "./bower_components/material-design-icons/navigation/svg/production/ic_menu_48px.svg", 48)
    .icon("svg-home", "./bower_components/material-design-icons/action/svg/production/ic_home_48px.svg", 48)
    .icon("svg-person-add", "./bower_components/material-design-icons/social/svg/production/ic_person_add_48px.svg", 48)
    .icon("svg-assessment", "./bower_components/material-design-icons/action/svg/production/ic_assessment_48px.svg", 48)
    .icon("svg-map", "./bower_components/material-design-icons/maps/svg/production/ic_map_48px.svg", 48)
    .icon("svg-ranking", "./bower_components/material-design-icons/toggle/svg/production/ic_star_24px.svg", 48)
    .icon("svg-mail", "./bower_components/material-design-icons/content/svg/production/ic_mail_48px.svg", 48)
});
