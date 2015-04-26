module = angular.module('kf2App');

module.factory('BackEndUrlService', [function() {
  return {
    getBaseUrl: function() {        
      return document.location.origin + document.location.pathname;
    }
  }
}]);