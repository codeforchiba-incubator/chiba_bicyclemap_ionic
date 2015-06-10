angular.module('starter').controller('MapController',
  [ '$scope',
    '$cordovaGeolocation',
    '$stateParams',
    '$ionicModal',
    '$ionicPopup',
    function(
      $scope,
      $cordovaGeolocation,
      $stateParams,
      $ionicModal,
      $ionicPopup
      ) {
	  var routing = L.Routing.control({
		  router: L.Routing.osrm({
			  serviceUrl: 'http://router.project-osrm.org/viaroute'
		      }),
		  waypoints: [
			      L.latLng(35.622857, 140.103713),
			      L.latLng(35.619216, 140.117156),
			      L.latLng(35.600252, 140.098042)
			      ],
		  routeWhileDragging: true,
		  geocoder: L.Control.Geocoder.nominatim(),
		  lineOptions: {
		      styles: [
	  {color: 'black', opacity: 1, weight: 10},
	  {color: 'white', opacity: 1, weight: 10},
	  {color: 'red', opacity: 1, weight: 10}
			       ]
		  },
	      });

      /**
       * Once state loaded, get put map on scope.
       */
      $scope.$on("$stateChangeSuccess", function() {
        $scope.map = {
          defaults: {
            tileLayer: 'http://tile.openstreetmap.jp/{z}/{x}/{y}.png',
            maxZoom: 18,
            zoomControlPosition: 'topleft'
          },
	  center: {},
	  controls: {
		custom: [routing],
		scale: true
	  },
	  layers: {
		baselayers: {
		    osm: {
			name: 'Open Street Map Japan',
			url: 'http://tile.openstreetmap.jp/{z}/{x}/{y}.png',
			type: 'xyz'
		    },
		    std: {
			name: '国土地理院地図 標準地図',
			url: 'http://cyberjapandata-t1.gsi.go.jp/xyz/std/{z}/{x}/{y}.png',
			type: 'xyz'
		    },
		    pale: {
			name: '国土地理院地図 淡白地図',
			url: 'http://cyberjapandata-t1.gsi.go.jp/xyz/pale/{z}/{x}/{y}.png',
			type: 'xyz'
		    }
		},
		overlays: {
		    parking: {
			name:'駐輪場',
			type:'geoJSON',
			url:'http://codeforchiba.github.io/chiba_bicyclemap_ionic/www/data/parking.geojson',
			layerOptions: {
			    pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {
					icon: L.icon({
						iconUrl: "img/bicycle_parking.svg",
						iconSize: [30, 30]
					    }),
					title: feature.properties.name
				    });
			    },
			    onEachFeature: function (feature, layer) {
				if (feature.properties) {
				    var content = feature.properties.name + "<br>" + feature.properties.address + "<br>" + feature.properties.capacity + "台";
				    layer.on({
					    click: function (e) {
						$ionicPopup.alert({
							title: '駐車場情報',
							template: content
						    });
					    }
					});
				}
			    }
			}
		    },
		    bicycleShop: {
			name:'自転車屋',
			type:'geoJSON',
			url:'http://codeforchiba.github.io/chiba_bicyclemap_ionic/www/data/bicycle_shop_map.geojson',
			layerOptions: {
			    pointToLayer: function (feature, latlng) {
				return L.marker(latlng, {
					icon: L.icon({
						iconUrl: "img/bicycle-24.png",
						iconSize: new L.Point(30, 30),
						opacity: 1
					    }),
					title: feature.properties.name
				    });
			    },
			    onEachFeature: function (feature, layer) {
				if (feature.properties) {
				    var content = feature.properties.name;
				    layer.on({
					    click: function (e) {
						$ionicPopup.alert({
							title: '自転車屋',
							template: content
						});
					    }
					});
				}
			    }
			}
		    },
		    blueline: {
			name:'自転車走りやすい道路',
			type:'geoJSON',
			url:'http://codeforchiba.github.io/chiba_bicyclemap_ionic/www/data/11_chiba_city_bicyclemap_blueline.geojson',
			layerOptions: {
			    style: {
				color: "#0000ff",
				fill: false,
				weight: 3,
				opacity: 1,
				clickable: false
			    }
			}
		    },
		    greenline: {
			name:'普通の道路',
			type:'geoJSON',
			url:'http://codeforchiba.github.io/chiba_bicyclemap_ionic/www/data/12_chiba_city_bicyclemap_greenline.geojson',
			layerOptions: {
			    style: {
				color: "#00ff00",
				fill: false,
				weight: 3,
				opacity: 1,
				clickable: false
			    }
			}
		    },
		    orangeline: {
			name:'幅の広い歩道あり',
			type:'geoJSON',
			url:'http://codeforchiba.github.io/chiba_bicyclemap_ionic/www/data/15_chiba_city_bicyclemap_orangeline.geojson',
			layerOptions: {
			    style: {
				color: "#ff9900",
				fill: false,
				weight: 3,
				opacity: 1,
				clickable: false
			    }
			}
		    },
		    pinkline: {
			name:'幅の広い歩道なし',
			type:'geoJSON',
			url:'http://codeforchiba.github.io/chiba_bicyclemap_ionic/www/data/14_chiba_city_bicyclemap_pinkline.geojson',
			layerOptions: {
			    style: {
				color: "#ff00ff",
				fill: false,
				weight: 3,
				opacity: 1,
				clickable: false
			    }
			}
		    }

		},
		options: {
		    position: 'topleft'
		}
	  },
          markers : {},
          events: {
            map: {
		    enable: ['click','context'],
		    logic: 'emit'
            }
          }
        };
	
      });

      /**
       * Center map on user's current position
       */
      $scope.locate = function(){
        $cordovaGeolocation
          .getCurrentPosition()
          .then(function (position) {
            $scope.map.center.lat  = position.coords.latitude;
            $scope.map.center.lng = position.coords.longitude;
            $scope.map.center.zoom = 15;

            $scope.map.markers.now = {
              lat:position.coords.latitude,
              lng:position.coords.longitude,
              message: "現在地",
              focus: true,
              draggable: false
            };

          }, function(err) {
            // error
            console.log("Location error!");
            console.log(err);
          });
      };

      var hide = false;
      $scope.routeShowHide = function(){
	  if(hide) {
	      routing.show();
	      hide = false;
	  } else {
	      routing.hide();
	      hide = true;
	  }
      };

    }]);