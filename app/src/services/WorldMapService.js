module = angular.module('kf2App');

module.factory('WorldMapService', [function() {
    
  var service = {  
    cityClicked: '',
    playersFromCityClicked: []
  };

  return service;
}]);