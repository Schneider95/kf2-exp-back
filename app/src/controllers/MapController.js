module.controller('MapController', ['$location', 'WorldMapService', 'PlayersDataService', function($location, WorldMapService, PlayersDataService) {
    
  var self = this;
  
  self.cityClicked = function() {
    return WorldMapService.cityClicked;
  };

  self.playersFromCityClicked = function() {
    return WorldMapService.playersFromCityClicked;
  };

  self.addPlayerToStatPage = function(id) {
    PlayersDataService.addPlayer(id);
    $location.path("/stats/" + PlayersDataService.playersIdsString);
  };
}]);