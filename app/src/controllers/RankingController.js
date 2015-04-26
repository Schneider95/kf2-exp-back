module.controller('RankingController', ['BackEndRequestService', 'StatsDataService', 'RankingService', 'PlayersDataService', '$location', function (BackEndRequestService, StatsDataService, RankingService, PlayersDataService, $location) {

  var self = this;

  self.selectedStat = '';

  self.toppings = [
    { category: 'meat', name: 'Pepperoni' },
    { category: 'meat', name: 'Sausage' },
    { category: 'meat', name: 'Ground Beef' },
    { category: 'meat', name: 'Bacon' },
    { category: 'meat', name: 'Pepperoni' },
    { category: 'meat', name: 'Sausage' },
    { category: 'meat', name: 'Ground Beef' },
    { category: 'meat', name: 'Bacon' },
    { category: 'meat', name: 'Pepperoni' },
    { category: 'meat', name: 'Sausage' },
    { category: 'meat', name: 'Ground Beef' },
    { category: 'meat', name: 'Bacon' }
  ];

  self.getStatsList = function() {
    console.log(StatsDataService.stats);
    return StatsDataService.stats;
  };

  self.changeStat = function() {
    RankingService.changeStat(self.selectedStat);
  };

  self.getRankingByStat = function() {
    RankingService.getRankingByStat();
  };

  self.getStatsRetrieved = function() {
    return RankingService.statsRetrieved;
  };

  self.addPlayerToStatPage = function(id) {
    PlayersDataService.addPlayer(id);
    $location.path( "/stats/"+ PlayersDataService.playersIdsString);
  };  
  
  StatsDataService.getStatsList(); 
}]);