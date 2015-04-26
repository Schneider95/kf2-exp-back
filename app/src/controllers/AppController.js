module.controller('AppController', ['$mdSidenav', '$mdBottomSheet', 'PlayersDataService', '$q', '$location',
  function ($mdSidenav, $mdBottomSheet, PlayersDataService, $q, $location) {
    var self = this;

    self.selected = null;
    self.menuElements = [
      {
        name: 'Home',
        icon: 'svg-home',
        link: '/'
      },{
        name: 'Add player',
        icon: 'svg-person-add',
        link: '/addPlayer'
      },{
        name: 'Stats page',
        icon: 'svg-assessment',
        link: '/stats/' + playersIdsString()+'aa'
      },{
        name: 'Map',
        icon: 'svg-map',
        link: '/map'
      },{
        name: 'Ranking',
        icon: 'svg-ranking',
        link: '/ranking'
      },{
        name: 'Contact',
        icon: 'svg-mail',
        link: '/contact'
      }
    ];
    self.toggleList = toggleElementsList;
    self.go = go;
    self.goToStatsPage = goToStatsPage;

    function playersIdsString() {
      return PlayersDataService.playersIdsString;
    }
    

    function toggleElementsList() {
      var pending = $mdBottomSheet.hide() || $q.when(true);

      pending.then(function () {
        $mdSidenav('left').toggle();
      });
    }

    function go(path) {
      self.toggleList();
      $location.path(path);
    }
    
    function goToStatsPage() {
      self.toggleList();
      $location.path('/stats/' + PlayersDataService.playersIdsString);
    }
  }
]);