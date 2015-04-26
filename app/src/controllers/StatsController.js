module.controller('StatsController', ['$routeParams', 'BackEndRequestService', 'AchievementsDataService', 'PlayersDataService', 'MapsDataService', 'StatsDataService','BuildsList', function ($routeParams, BackEndRequestService, AchievementsDataService, PlayersDataService, MapsDataService, StatsDataService, BuildsList) {

    var self = this;
    self.achievements = [];
    self.stats = [];
    self.maps = [];

    self.getAchievementsList = function () {
      return AchievementsDataService.achievements;
    };

    self.getPlayers = function () {
      return PlayersDataService.players;
    };

    self.getNbPlayers = function () {
      return Object.keys(self.getPlayers()).length;
    };

    self.removePlayer = function (id) {
      return PlayersDataService.removePlayer(id);
    };
    
    self.getStatsList = function () {
      return StatsDataService.stats;
    };
    
    self.getMapsList = function () {
      return MapsDataService.maps;
    };
    
    self.getNumberOfAchievementsMaps = function () {      
      return Object.keys(MapsDataService.maps).length * 4;
    };
    
    self.getNumberOfMaps = function () {      
      return Object.keys(MapsDataService.maps).length;
    };
    
    self.getNumberOfAchievements = function () {
      return AchievementsDataService.achievements.length;
    };

    self.getBuildsList = function() {
      return BuildsList;
    };

    // check if params in URL is correctly constrcuted
    var ids = PlayersDataService.checkPlayersFromUrl($routeParams.ids);

    if (ids === true) {
      // Parameter correct, update players
      self.errorBoolean = false;
      PlayersDataService.loadPlayersFromUrl($routeParams.ids);
      PlayersDataService.updatePlayers();
      StatsDataService.getStatsList(); 
      MapsDataService.getMapsList(); 
      AchievementsDataService.getAchievementsList(); 
    } else {
      // An error occured, bad URL parameter
      self.errorMessage = ids;
      self.errorBoolean = true;
    }
    
  }]);