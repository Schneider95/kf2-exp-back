module.controller('HomeController', ['BackEndRequestService', 'PlayersDataService', '$q', '$log', '$location', function (BackEndRequestService, PlayersDataService, $q, $log, $location) {

    var self = this;

    function querySearch(query) {
      return self.players.filter(createFilterFor(query));
    }

    function loadPlayers() {
      BackEndRequestService.getPlayersList().then(
              function (success) {
                self.players = success.data;
              },
              function (error) {

              }
      );
    }

    function loadLastUpdatedPlayers() {
      BackEndRequestService.getLastUpdatedPlayers().then(
              function (success) {
                self.loadLastUpdatedPlayers = success.data;
              },
              function (error) {

              }
      );
    }

    function searchTextChange(text) {
      //$log.info('searchText ' + text);
    }
    function selectedItemChange(item) {
      PlayersDataService.addPlayer(item.playerId);
      $location.path("/stats/" + PlayersDataService.playersIdsString);
    }

    function createFilterFor(query) {
      var lowercaseQuery = angular.lowercase(query);
      return function filterFn(player) {
        return (player.steamNameLowercase.indexOf(lowercaseQuery) !== -1);
      };
    }
    
    function addPlayerToStatPage(id) {
      PlayersDataService.addPlayer(id);
      $location.path("/stats/" + PlayersDataService.playersIdsString);
    };
    
    
    loadPlayers();
    loadLastUpdatedPlayers();

    self.selectedItem = null;
    self.searchText = null;
    self.querySearch = querySearch;
    self.simulateQuery = false;
    self.isDisabled = false;
    self.selectedItemChange = selectedItemChange;
    self.searchTextChange = searchTextChange;
    self.addPlayerToStatPage = addPlayerToStatPage;
    
    
    
  }]);