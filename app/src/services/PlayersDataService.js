
module = angular.module('kf2App');
module.factory('PlayersDataService', ['SecondsBeforeUpdatePlayer', 'BackEndRequestService', 'BackEndUrlService', function (SecondsBeforeUpdatePlayer, BackEndRequestService, BackEndUrlService) {

    var service = {
      addPlayer: function (id) {

        service.players[id] = {
          steam_name: 'Player number ' + id,
          update_state: 'Not retrieved for the moment.',
          updating: false
        };
        service.rebuildPlayerIdsString();
      },
      checkPlayersFromUrl: function (playersIdsFromUrlString) {

        if (playersIdsFromUrlString !== undefined) {

          var playersIdsFromUrlArray = playersIdsFromUrlString.split(",");
          for (var i = 0; i < playersIdsFromUrlArray.length; i++) {

            if (isNaN(playersIdsFromUrlArray[i])) {
              return 'IDs provided in URL must be separated by a comma.';
            }
          }

          return true;
        } else {

          return 'No player selected for display. Search existing player with the top search bar, or add new player with the link in the left menu.';
        }
      },
      getReadableTimePlayed: function (totalMinutes) {

        if (isNaN(totalMinutes)) {
          return '';
        }

        return Math.floor(totalMinutes / 60) + 'h' + totalMinutes % 60 + 'm';
      },
      loadPlayersFromUrl: function (playersIdsFromUrlString) {

        var playersIdsFromUrlArray = playersIdsFromUrlString.split(",");
        var playerIdToRemove = [];
        // for each players in array, check if it exist in url 
        angular.forEach(service.players, function (player, playerId) {

          // if a player in array dont exist in URL
          if (playersIdsFromUrlArray.indexOf(playerId) === -1) {
            // add this player to this the array of player to remove
            playerIdToRemove.push(playerId);
          }
        });
        angular.forEach(playerIdToRemove, function (playerId, key) {
          service.removePlayer(playerId);
        });
        // for each players in url, check if it exist in array 
        angular.forEach(playersIdsFromUrlArray, function (playerId) {
          // if a player in url dont exist in array
          if (service.players[playerId] === undefined) {
            // add this player to the array
            service.addPlayer(playerId);
          }
        });
      },
      players: {},
      playersIdsString: '',
      playersIdsArray: [],
      removePlayer: function (id) {

        delete service.players[id];
        service.rebuildPlayerIdsString();
      },
      registerPlayer: function (profileUrl) {

        service.registeringPlayer.state = true;
        var steamId;
        var checkIfPlayerExistInDatabasePromise = function () {
          BackEndRequestService.checkIfPlayerExistInDatabase(profileUrl)
                  .then(function (success) {
                    if (isNaN(success)) {
                      service.registeringPlayer.message = 'Player not found in database. Check if it exist on Steam...';
                      return checkIfPlayerExistOnSteamPromise();
                    } else {
                      service.addPlayer(success);
                      service.registeringPlayer.message = 'This player is already registered in database. It has been added to the stats page. Check it by clicking on the link in left menu.';
                      service.registeringPlayer.state = false;
                    }
                  }, function (error) {
                    service.registeringPlayer.message = error;
                    service.registeringPlayer.state = false;
                  });
        };
        var checkIfPlayerExistOnSteamPromise = function () {
          BackEndRequestService.checkIfPlayerExistOnSteam(profileUrl).then(
                  function (success) {

                    if (isNaN(success)) {
                      service.registeringPlayer.message = success;
                      service.registeringPlayer.state = false;
                    } else {
                      steamId = success;
                      service.registeringPlayer.message = 'Player found on Steam with ID ' + steamId + '. Check if the player have a public profile...';
                      return checkIfPlayerHasAPublicProfilePromise(steamId);
                    }
                  }, function (error) {
            service.registeringPlayer.message = error;
            service.registeringPlayer.state = false;
          });
        };
        var checkIfPlayerHasAPublicProfilePromise = function () {
          BackEndRequestService.getProfileDataFromSteamAPI(steamId).then(
                  function (success) {
                    if (success.communityvisibilitystate === 3) {
                      service.registeringPlayer.message = 'Player have a public profile. Checking if a have Killing Floor...';
                      return checkIfPlayerHaveGamePromise();
                    } else {
                      service.registeringPlayer.message = 'Player don\'t have a public profile. Unable to retrieve stats.';
                      service.registeringPlayer.state = false;
                    }
                  }, function (error) {
            service.registeringPlayer.message = error;
            service.registeringPlayer.state = false;
          });
        };
        var checkIfPlayerHaveGamePromise = function () {
          BackEndRequestService.checkIfPlayerHaveGame(steamId).then(
                  function (success) {
                    if (success) {
                      service.registeringPlayer.message = 'Player have Killing Floor. Retrieve profile data...';
                      return updateProfileDataPromise();
                    }

                    service.registeringPlayer.message = success.data;
                  }, function (error) {
            service.registeringPlayer.message = error;
            service.registeringPlayer.state = false;
          });
        };
        var updateProfileDataPromise = function () {

          BackEndRequestService.updateProfileDataWithSteamAPI(steamId)
                  .then(function (success) {
                    service.addPlayer(success);
                    service.registeringPlayer.message = 'Player profile data retrieved. Check it on stats page by clicking on the link in the left menu.';
                    service.registeringPlayer.state = false;
                  }, function (error) {
                    service.registeringPlayer.message = error;
                    service.registeringPlayer.state = false;
                  });
        };
        if (/^http:\/\/steamcommunity.com\/(profiles\/[0-9]*|id\/[a-zA-Z0-9-_]*)[\/]?$/.test(profileUrl)) {
          return checkIfPlayerExistInDatabasePromise();
        } else {
          service.registeringPlayer.message = 'The URL provided has NOT the correct form.';
          service.registeringPlayer.state = false;
        }
      },
      registeringPlayer: {
        state: false,
        message: ''
      },
      rebuildPlayerIdsString: function () {

        service.playersIdsArray = [];
        angular.forEach(service.players, function (player, playerId) {
          service.playersIdsArray.push(playerId);
        });
        service.playersIdsString = service.playersIdsArray.join(',');
      },
      refactorStatsAchievemementsArray: function (id) {

        var statsArray = [];
        var achievementsArray = [];
        angular.forEach(service.players[id].player_stats, function (player_stat) {
          statsArray[player_stat.stat.stat_name] = player_stat.value;
        });
        angular.forEach(service.players[id].player_achievements, function (player_achievement) {
          achievementsArray[player_achievement.achievement.achievement_name] = player_achievement.value;
        });
        service.players[id].player_stats = statsArray;
        service.players[id].player_achievements = achievementsArray;
        service.setMedicLevel(id);
        service.setSharpShooterLevel(id);
        service.setSupportLevel(id);
        service.setCommandoLevel(id);
        service.setBerzerkLevel(id);
        service.setFirebugLevel(id);
        service.setDemolitionsLevel(id);
      },
      setBerzerkLevel: function (id) {

        if (service.players[id].player_stats['MeleeDamage'] >= 11000000) {
          service.players[id].berzerk_level = '6+';
        } else if (service.players[id].player_stats['MeleeDamage'] >= 5500000) {
          service.players[id].berzerk_level = '6';
        } else if (service.players[id].player_stats['MeleeDamage'] >= 3500000) {
          service.players[id].berzerk_level = '5';
        } else if (service.players[id].player_stats['MeleeDamage'] >= 1500000) {
          service.players[id].berzerk_level = '4';
        } else if (service.players[id].player_stats['MeleeDamage'] >= 500000) {
          service.players[id].berzerk_level = '3';
        } else if (service.players[id].player_stats['MeleeDamage'] >= 100000) {
          service.players[id].berzerk_level = '2';
        } else if (service.players[id].player_stats['MeleeDamage'] >= 25000) {
          service.players[id].berzerk_level = '1';
        } else if (service.players[id].player_stats['MeleeDamage'] < 25000) {
          service.players[id].berzerk_level = '0';
        } else {
          service.players[id].berzerk_level = '0';
        }
      },
      setCommandoLevel: function (id) {

        if (service.players[id].player_stats['1_1'] < 1000) {
           service.players[id].commando_level = '0';
        } else if (service.players[id].player_stats['1_1'] < 1000 && service.players[id].player_stats['1_1'] < 262789 ) {
           service.players[id].commando_level = '1';
        } else if (service.players[id].player_stats['1_1'] >= 262789 && service.players[id].player_stats['1_1'] < 525175  ) {
           service.players[id].commando_level = '2';
        } else if (service.players[id].player_stats['1_1'] >= 525175  && service.players[id].player_stats['1_1'] < 700000  ) {
           service.players[id].commando_level = '3';
        } else if (service.players[id].player_stats['1_1'] >= 700000  && service.players[id].player_stats['1_1'] < 1049348  ) {
           service.players[id].commando_level = '4';
        } else if (service.players[id].player_stats['1_1'] >= 1049348 && service.players[id].player_stats['1_1'] < 1573783  ) {
           service.players[id].commando_level = '5';
        } else if (service.players[id].player_stats['1_1'] >= 1573783 && service.players[id].player_stats['1_1'] < 1836106 ) {
           service.players[id].commando_level = '6';
        } else if (service.players[id].player_stats['1_1'] >= 1836106 && service.players[id].player_stats['1_1'] < 2100661 ) {
           service.players[id].commando_level = '7';
        } else if (service.players[id].player_stats['1_1'] >= 2100661 && service.players[id].player_stats['1_1'] < 2360352 ) {
           service.players[id].commando_level = '8';
        } else if (service.players[id].player_stats['1_1'] >= 2360352 && service.players[id].player_stats['1_1'] < 2500000 ) {
           service.players[id].commando_level = '9';
        } else if (service.players[id].player_stats['1_1'] >= 2500000 && service.players[id].player_stats['1_1'] < 2884121 ) {
           service.players[id].commando_level = '10';
        } else if (service.players[id].player_stats['1_1'] >= 2884121 && service.players[id].player_stats['1_1'] < 3146041 ) {
           service.players[id].medic_level = '11';
        } else if (service.players[id].player_stats['1_1'] >= 3146041 && service.players[id].player_stats['1_1'] < 3400000 ) {
           service.players[id].commando_level = '12';
        } else if (service.players[id].player_stats['1_1'] >= 3400000 && service.players[id].player_stats['1_1'] < 3700000 ) {
           service.players[id].commando_level = '13';
        } else if (service.players[id].player_stats['1_1'] >= 3700000 && service.players[id].player_stats['1_1'] < 3900000 ) {
           service.players[id].commando_level = '14';
        } else if (service.players[id].player_stats['1_1'] >= 3900000 && service.players[id].player_stats['1_1'] < 4100000 ) {
           service.players[id].commando_level = '15';
        } else if (service.players[id].player_stats['1_1'] >= 4000000 && service.players[id].player_stats['1_1'] < 4457534 ) {
           service.players[id].commando_level = '16';
        } else if (service.players[id].player_stats['1_1'] >= 4457534 && service.players[id].player_stats['1_1'] < 1000000000 ) {
           service.players[id].commando_level = '17';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '18';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '19';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '20';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '21';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '22';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '23';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '24';
        } else if (service.players[id].player_stats['1_1'] >= 1000000000) {
           service.players[id].commando_level = '25';
        }
      },
      setDemolitionsLevel: function (id) {

        if (service.players[id].player_stats['ExplosivesDamage'] >= 11000000) {
          service.players[id].demolitions_level = '6+';
        } else if (service.players[id].player_stats['ExplosivesDamage'] >= 5500000) {
          service.players[id].demolitions_level = '6';
        } else if (service.players[id].player_stats['ExplosivesDamage'] >= 3500000) {
          service.players[id].demolitions_level = '5';
        } else if (service.players[id].player_stats['ExplosivesDamage'] >= 1500000) {
          service.players[id].demolitions_level = '4';
        } else if (service.players[id].player_stats['ExplosivesDamage'] >= 500000) {
          service.players[id].demolitions_level = '3';
        } else if (service.players[id].player_stats['ExplosivesDamage'] >= 100000) {
          service.players[id].demolitions_level = '2';
        } else if (service.players[id].player_stats['ExplosivesDamage'] >= 25000) {
          service.players[id].demolitions_level = '1';
        } else if (service.players[id].player_stats['ExplosivesDamage'] < 25000) {
          service.players[id].demolitions_level = '0';
        } else {
          service.players[id].demolitions_level = '0';
        }
      },
      setFirebugLevel: function (id) {

        if (service.players[id].player_stats['FlameThrowerDamage'] >= 11000000) {
          service.players[id].firebug_level = '6+';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] >= 5500000) {
          service.players[id].firebug_level = '6';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] >= 3500000) {
          service.players[id].firebug_level = '5';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] >= 1500000) {
          service.players[id].firebug_level = '4';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] >= 500000) {
          service.players[id].firebug_level = '3';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] >= 100000) {
          service.players[id].firebug_level = '2';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] >= 25000) {
          service.players[id].firebug_level = '1';
        } else if (service.players[id].player_stats['FlameThrowerDamage'] < 25000) {
          service.players[id].firebug_level = '0';
        } else {
          service.players[id].firebug_level = '0';
        }
      },
      setMedicLevel: function (id) {
        
        if (service.players[id].player_stats['1_40'] < 1000) {
           service.players[id].medic_level = '0';
        } else if (service.players[id].player_stats['1_40'] < 1000 && service.players[id].player_stats['1_40'] >= 1000) {
           service.players[id].medic_level = '1';
        } else if (service.players[id].player_stats['1_40'] >= 1000 && service.players[id].player_stats['1_40'] < 2000 ) {
           service.players[id].medic_level = '2';
        } else if (service.players[id].player_stats['1_40'] >= 2000 && service.players[id].player_stats['1_40'] < 4000 ) {
           service.players[id].medic_level = '3';
        } else if (service.players[id].player_stats['1_40'] >= 4000 && service.players[id].player_stats['1_40'] < 5000 ) {
           service.players[id].medic_level = '4';
        } else if (service.players[id].player_stats['1_40'] >= 5000 && service.players[id].player_stats['1_40'] < 6000 ) {
           service.players[id].medic_level = '5';
        } else if (service.players[id].player_stats['1_40'] >= 6000 && service.players[id].player_stats['1_40'] < 7000 ) {
           service.players[id].medic_level = '6';
        } else if (service.players[id].player_stats['1_40'] >= 7000 && service.players[id].player_stats['1_40'] < 8000 ) {
           service.players[id].medic_level = '7';
        } else if (service.players[id].player_stats['1_40'] >= 8000 && service.players[id].player_stats['1_40'] < 9000 ) {
           service.players[id].medic_level = '8';
        } else if (service.players[id].player_stats['1_40'] >= 2360169 && service.players[id].player_stats['1_40'] < 2500000 ) {
           service.players[id].medic_level = '9';
        } else if (service.players[id].player_stats['1_40'] >= 2500000 && service.players[id].player_stats['1_40'] < 2884121 ) {
           service.players[id].medic_level = '10';
        } else if (service.players[id].player_stats['1_40'] >= 2884121 && service.players[id].player_stats['1_40'] < 3146041 ) {
           service.players[id].medic_level = '11';
        } else if (service.players[id].player_stats['1_40'] >= 3146041 && service.players[id].player_stats['1_40'] < 3400000 ) {
           service.players[id].medic_level = '12';
        } else if (service.players[id].player_stats['1_40'] >= 3400000 && service.players[id].player_stats['1_40'] < 3700000 ) {
           service.players[id].medic_level = '13';
        } else if (service.players[id].player_stats['1_40'] >= 3700000 && service.players[id].player_stats['1_40'] < 3900000 ) {
           service.players[id].medic_level = '14';
        } else if (service.players[id].player_stats['1_40'] >= 3900000 && service.players[id].player_stats['1_40'] < 4196712 ) {
           service.players[id].medic_level = '15';
        } else if (service.players[id].player_stats['1_40'] >= 4196712 && service.players[id].player_stats['1_40'] < 4457534 ) {
           service.players[id].medic_level = '16';
        } else if (service.players[id].player_stats['1_40'] >= 4300000 && service.players[id].player_stats['1_40'] < 4470850 ) {
           service.players[id].medic_level = '17';
        } else if (service.players[id].player_stats['1_40'] >= 4470850) {
           service.players[id].medic_level = '18';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '19';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '20';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '21';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '22';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '23';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '24';
        } else if (service.players[id].player_stats['1_40'] >= 1000000000) {
           service.players[id].medic_level = '25';
        }
      },
      setSupportLevel: function (id) {
        
        if (service.players[id].player_stats['1_20'] < 1000) {
           service.players[id].support_level = '0';
        } else if (service.players[id].player_stats['1_20'] >= 1000 && service.players[id].player_stats['1_20'] < 2000 ) {
           service.players[id].support_level = '1';
        } else if (service.players[id].player_stats['1_20'] >= 1000 && service.players[id].player_stats['1_20'] < 2000 ) {
           service.players[id].support_level = '2';
        } else if (service.players[id].player_stats['1_20'] >= 2000 && service.players[id].player_stats['1_20'] < 4000 ) {
           service.players[id].support_level = '3';
        } else if (service.players[id].player_stats['1_20'] >= 4000 && service.players[id].player_stats['1_20'] < 5000 ) {
           service.players[id].support_level = '4';
        } else if (service.players[id].player_stats['1_20'] >= 5000 && service.players[id].player_stats['1_20'] < 6000 ) {
           service.players[id].support_level = '5';
        } else if (service.players[id].player_stats['1_20'] >= 6000 && service.players[id].player_stats['1_20'] < 7000 ) {
           service.players[id].support_level = '6';
        } else if (service.players[id].player_stats['1_20'] >= 7000 && service.players[id].player_stats['1_20'] < 8000 ) {
           service.players[id].support_level = '7';
        } else if (service.players[id].player_stats['1_20'] >= 8000 && service.players[id].player_stats['1_20'] < 9000 ) {
           service.players[id].support_level = '8';
        } else if (service.players[id].player_stats['1_20'] >= 2360169 && service.players[id].player_stats['1_20'] < 2500000 ) {
           service.players[id].support_level = '9';
        } else if (service.players[id].player_stats['1_20'] >= 2500000 && service.players[id].player_stats['1_20'] < 2884121 ) {
           service.players[id].support_level = '10';
        } else if (service.players[id].player_stats['1_20'] >= 2884121 && service.players[id].player_stats['1_20'] < 3146041 ) {
           service.players[id].support_level = '11';
        } else if (service.players[id].player_stats['1_20'] >= 3146041 && service.players[id].player_stats['1_20'] < 3410594 ) {
           service.players[id].support_level = '12';
        } else if (service.players[id].player_stats['1_20'] >= 3410594 && service.players[id].player_stats['1_20'] < 3675657 ) {
           service.players[id].support_level = '13';
        } else if (service.players[id].player_stats['1_20'] >= 3675657 && service.players[id].player_stats['1_20'] < 3900000 ) {
           service.players[id].support_level = '14';
        } else if (service.players[id].player_stats['1_20'] >= 3900000 && service.players[id].player_stats['1_20'] < 4000000 ) {
           service.players[id].support_level = '15';
        } else if (service.players[id].player_stats['1_20'] >= 4000000 && service.players[id].player_stats['1_20'] < 4457534 ) {
           service.players[id].support_level = '16';
        } else if (service.players[id].player_stats['1_20'] >= 4457534 && service.players[id].player_stats['1_20'] < 4734307 ) {
           service.players[id].support_level = '17';
        } else if (service.players[id].player_stats['1_20'] >= 4734307) {
           service.players[id].support_level = '18';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '19';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '20';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '21';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '22';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '23';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '24';
        } else if (service.players[id].player_stats['1_20'] >= 1000000000) {
           service.players[id].support_level = '25';
        }
        
        
      },
      setSharpShooterLevel: function (id) {

        if (service.players[id].player_stats['HeadshotKills'] >= 17000) {
          service.players[id].sharpshooter_level = '6+';
        } else if (service.players[id].player_stats['HeadshotKills'] >= 8500) {
          service.players[id].sharpshooter_level = '6';
        } else if (service.players[id].player_stats['HeadshotKills'] >= 5500) {
          service.players[id].sharpshooter_level = '5';
        } else if (service.players[id].player_stats['HeadshotKills'] >= 2500) {
          service.players[id].sharpshooter_level = '4';
        } else if (service.players[id].player_stats['HeadshotKills'] >= 700) {
          service.players[id].sharpshooter_level = '3';
        } else if (service.players[id].player_stats['HeadshotKills'] >= 100) {
          service.players[id].sharpshooter_level = '2';
        } else if (service.players[id].player_stats['HeadshotKills'] >= 30) {
          service.players[id].sharpshooter_level = '1';
        } else if (service.players[id].player_stats['HeadshotKills'] < 30) {
          service.players[id].sharpshooter_level = '0';
        } else {
          service.players[id].sharpshooter_level = '0';
        }
      },
      
      updatePlayer: function (id) {

        service.players[id].updating = true;
        service.players[id].update_state = 'Check if player need to be updated...';
        var getPlayerByIdPromise = function () {
          BackEndRequestService.getPlayerById(id)
                  .then(function (success) {

                    service.players[id] = success;
                    service.refactorStatsAchievemementsArray(id);
                    
                    var lastUpdateDate = new Date(success.last_update_time);
                    var lastUpdateTime = lastUpdateDate.getTime() / 1000;
                    var actualTime = new Date().getTime() / 1000;

                    if (lastUpdateTime + SecondsBeforeUpdatePlayer < actualTime)
                    {
                      service.players[id].updating = true;
                      service.players[id].update_state = 'Retrieve player profile data from Steam...';
                      return getProfileDataPromise(success.steam_id);
                    } else {
                      service.players[id].updating = false;
                      service.players[id].update_state = 'Player updated.';
                    }
                  }, function (error) {

                    service.players[id].updating = false;
                    service.players[id].update_state = error;
                  });
        };
        var getProfileDataPromise = function (steamId) {
          BackEndRequestService.getProfileDataFromSteamAPI(steamId).then(
                  function (success) {
                    if (success.communityvisibilitystate === 3) {
                      return updateProfileDataPromise();
                    } else {
                      service.players[id].updating = false;
                      service.players[id].update_state = 'Unfortunately, the player profile is now private.';
                    }
                  }, function (error) {
            service.players[id].updating = false;
            service.players[id].update_state = 'Failed to retrieve player profile data from Steam.';
          }
          );
        };
        var updateProfileDataPromise = function () {

          BackEndRequestService.updateProfileDataWithSteamAPI(service.players[id].steam_id)
                  .then(function (success) {

                    service.players[id].update_state = 'Retrieving player stats data...';
                    return updateStatsDataPromise();
                  }, function (error) {
                    service.players[id].updating = false;
                    service.players[id].update_state = 'Failed to retrieve player profile data from Steam.';
                  });
        };
        var updateStatsDataPromise = function () {
          BackEndRequestService.updateStatsDataWithSteamAPI(service.players[id].steam_id)
                  .then(function (success) {

                    service.players[id].update_state = 'Retrieving player stats data...';
                    return updateTimePlayedPromise();
                  }, function (error) {

                    service.players[id].updating = false;
                    service.players[id].update_state = error;
                  });
        };
        var updateTimePlayedPromise = function () {
          BackEndRequestService.updateTimePlayedWithSteamAPI(service.players[id].steam_id)
                  .then(function (success) {

                    service.players[id].update_state = 'Retrieving player from database...';
                    return getPlayerByIdPromise();
                  }, function (error) {

                    service.players[id].updating = false;
                    service.players[id].update_state = error;
                  });
        };
        return getPlayerByIdPromise();
      },
      updatePlayers: function () {

        angular.forEach(service.players, function (player, playerId) {
          service.updatePlayer(playerId);
        });
      }
    };
    return service;
  }]);
