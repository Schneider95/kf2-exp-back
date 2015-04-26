
var partialsPath = document.location.origin + '/partials/';

module.controller('DialogCtrl', ['$scope', '$mdDialog', function ($scope, $mdDialog) {
  $scope.close = function() {
    $mdDialog.hide();
  };
}]);

module.controller('RightCtrl', ['$scope', '$mdSidenav', '$log', '$mdDialog', function($scope,  $mdSidenav, $log, $mdDialog ) {

  $scope.showAdvanced = function(ev) {
    $mdDialog.show({
      controller: 'DialogCtrl',
      templateUrl: partialsPath + 'dialog.html',
      targetEvent: ev
    })
    .then(function(answer) {
      $scope.alert = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.alert = 'You cancelled the dialog.';
    });
  };
}]); 

module.controller('ToolbarCtrl', ['$scope', '$mdSidenav', function($scope,  $mdSidenav) {
  $scope.toggleSidenav = function() {
    $mdSidenav('left').toggle();
  };
}]);
