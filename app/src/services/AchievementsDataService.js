module = angular.module('kf2App');

module.factory('AchievementsDataService', ['BackEndRequestService', function (BackEndRequestService) {


    var service = {
      getAchievementsList: function () {
        var getAchievementsListPromise = function () {
          BackEndRequestService.getAchievementsList()
                  .then(function (success) {
                   
                    service.achievements = success;

                  }, function (error) {
                    console.log(error);
                  });
        };

        return getAchievementsListPromise();
      },
      achievements: []
    };


    return service;
  }]);