module.controller('AddPlayerController', ['BackEndRequestService', 'PlayersDataService', function (BackEndRequestService, PlayersDataService) {

    var self = this;

    self.playerURL = '';

    self.submit = function () {
      PlayersDataService.registerPlayer(self.playerURL);
    };

    self.registeringPlayerState = function () {
      return PlayersDataService.registeringPlayer.state;
    };

    self.registeringPlayerMessage = function () {
      return PlayersDataService.registeringPlayer.message;
    };
  }]);