module.directive('divMap', ['BackEndRequestService', 'WorldMapService', function(BackEndRequestService, WorldMapService) {

  var link = function (scope, elem, attrs) {
    
    var mapOptions;
    var latitude = attrs.latitude;
    var longitude = attrs.longitude;
    var map;
    var markers = [];

    latitude = latitude && parseFloat(latitude, 10) || 42.892427;
    longitude = longitude && parseFloat(longitude, 10) || -8.743023;

    mapOptions = {
      zoom: 2,
      center: new google.maps.LatLng(latitude, longitude)
    };

    map = new google.maps.Map(elem[0], mapOptions);

    if (map === void 0) {
        map = new google.maps.Map(element[0], mapOptions);
    }

    BackEndRequestService.getCitiesWithPlayers().then(
      function(success) {
        angular.forEach(success.data, function(city) {
          var markerOptions = {
            position: new google.maps.LatLng(city.cityLat, city.cityLon),
            map: map,
            title: city.cityName,
            cityId: city.id
          };
          var marker = new google.maps.Marker(markerOptions);

          google.maps.event.addListener(marker, 'click', function() {
            BackEndRequestService.getPlayersByCity(this.cityId).then(
              function(success) {
                WorldMapService.cityClicked = success.data.cityName;
                WorldMapService.playersFromCityClicked = success.data.players;
              }, 
              function (error) {
              }
            );
          });
        
          markers.push(marker); // add marker to array
        });  
        
        var mc = new MarkerClusterer(map, markers);
      },
      function(error) {
        
      }
    );
  }; 
    
  return {
 restrict: 'AE',

    link: link
  };
}]);