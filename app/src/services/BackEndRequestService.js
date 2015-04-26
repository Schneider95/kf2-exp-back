module = angular.module('kf2App');

module.factory('BackEndRequestService', ['$http', '$q', 'BackEndUrl', function ($http, $q, BackEndUrl) {

    return {
      checkIfPlayerExistInDatabase: function (profileUrl) {

        return $http.get(BackEndUrl + 'checkIfPlayerExistInDatabase?profileUrl=' + profileUrl)
                .then(function (response) {
                  return response.data;
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      checkIfPlayerExistOnSteam: function (profileUrl) {

        return $http.get(BackEndUrl + 'checkIfPlayerExistOnSteam?profileUrl=' + profileUrl)
                .then(function (response) {
                  return response.data;
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      checkIfPlayerHaveGame: function (steamId) {

        return $http.get(BackEndUrl + 'checkIfPlayerHaveGame?steamId=' + steamId)
                .then(function (response) {
                  return response.data;
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      getAchievementsList: function () {
        return $http.get(BackEndUrl + 'getAchievementsList')
                .then(function (response) {
                  if (typeof response.data === 'object') {
                            return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      getCitiesWithPlayers: function () {
        return $http.get(BackEndUrl + 'getCitiesWithPlayers');
      },
      getLastUpdatedPlayers: function () {

        return $http.get(BackEndUrl + 'getLastUpdatedPlayers');
      },
      getMapsList: function () {

        return $http.get(BackEndUrl + 'getMapsList')
                .then(function (response) {
                  if (typeof response.data === 'object') {
                            return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      getPlayersByCity: function (cityId) {

        return $http.get(BackEndUrl + 'getPlayersByCity?cityId=' + cityId);
      },
      getPlayersList: function () {

        return $http.get(BackEndUrl + 'getPlayersList');
      },
      getProfileDataFromSteamAPI: function (steamId) {

        return $http.get(BackEndUrl + 'getProfileData?steamId=' + steamId)
                .then(function (response) {
                  if (typeof response.data === 'object') {
                    return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      getPlayerById: function (id) {

        return $http.get(BackEndUrl + 'getPlayer/' + id)
                .then(function (response) {
                  if (typeof response.data === 'object') {
                    return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      getRankingByStat: function (statName, nbPlayerLoaded, nbPlayerToLoad) {
        return $http.get(BackEndUrl + 'getStatsRanking?statName=' + statName + '&nbPlayerLoaded=' + nbPlayerLoaded + '&nbPlayerToLoad=' + nbPlayerToLoad);
      },
      
      getStatsList: function () {

        return $http.get(BackEndUrl + 'getStatsList')
                .then(function (response) {
                  if (typeof response.data === 'object') {
                            return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      updateProfileDataWithSteamAPI: function (steamId) {

        return $http.get(BackEndUrl + 'updateProfileData?steamId=' + steamId)
                .then(function (response) {
                  if (isNaN(response.data) === false) {
                    return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      updateStatsDataWithSteamAPI: function (steamId) {

        return $http.get(BackEndUrl + 'updateStatsData?steamId=' + steamId)
                .then(function (response) {
                  if (response.data === true) {
                    return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      },
      updateTimePlayedWithSteamAPI: function (steamId) {
        return $http.get(BackEndUrl + 'updateTimePlayed?steamId=' + steamId)
                .then(function (response) {
                  if (response.data === true) {
                    return response.data;
                  } else {
                    return $q.reject(response.data);
                  }
                }, function (response) {
                  return $q.reject(response.data);
                });
      }
    }
  }]);