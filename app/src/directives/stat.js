module.directive('stat', ['PartialsPath', 
  function(partialsPath) {
    return {
      templateUrl: partialsPath + 'stat.html'
    };
  }
]);