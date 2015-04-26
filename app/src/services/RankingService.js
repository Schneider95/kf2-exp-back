module = angular.module('kf2App');


module.factory('RankingService', ['BackEndRequestService', function (BackEndRequestService) {

    var service = {
      nbPlayerToLoad: 60,
      statsRetrieved: [],
      statToRetrieve: '',
      changeStat: function (statToRetrieve) {
        service.statsRetrieved = [];
        service.statToRetrieve = statToRetrieve;
        service.getRankingByStat();
      },
      getRankingByStat: function () {
        BackEndRequestService
                .getRankingByStat(service.statToRetrieve, service.statsRetrieved.length, service.nbPlayerToLoad)
                .then(
                        function (success) {
                          angular.forEach(success.data, function (stat) {
                            service.statsRetrieved.push(stat);
                          });
                        },
                        function (error) {

                        }
                );
      }
    }

    return service;
  }]);