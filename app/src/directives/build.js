module.directive('build', ['PartialsPath', 
  function(partialsPath) {
    return {
      scope: { buildData: '=' },
      templateUrl: partialsPath + 'build.html',
    };
  }
]);