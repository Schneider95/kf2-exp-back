module = angular.module('kf2App');

module.factory('StatsDataService', ['BackEndRequestService', function (BackEndRequestService) {

    var service = {
      getStatsList: function () {
        var getStatsListPromise = function () {
          BackEndRequestService.getStatsList()
                  .then(function (success) {
                    service.stats = success;
                  }, function (error) {
                    
                  });
        };

        return getStatsListPromise();
      },
      stats: []
    };

    return service;
  }]);