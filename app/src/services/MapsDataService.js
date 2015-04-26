module = angular.module('kf2App');

module.factory('MapsDataService', ['BackEndRequestService', function (BackEndRequestService) {


    var service = {
      getMapsList: function () {
        var getMapsListPromise = function () {
          BackEndRequestService.getMapsList()
                  .then(function (success) {
                   
                    service.maps = success;

                  }, function (error) {

                  });
        };

        return getMapsListPromise();

        BackEndRequestService.getMapsList();
      },
      maps: []
    };


    return service;
  }]);