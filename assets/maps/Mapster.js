(function (window, google){

	var Mapster = (function(){
		function Mapster(element, opts){
			this.gMap = new google.maps.Map(element, opts);

			google.maps.event.addListener(this.gMap, "rightclick", function(event) {
				var lat = event.latLng.lat();
				var lng = event.latLng.lng();
			    // populate yor box/field with lat, lng
			    window.prompt("Copy to clipboard: Ctrl+C, Enter", lat + " " + lng);
			});

			
		}
		Mapster.prototype = {
			_on: function(opts){
				var self = this;
				google.maps.event.addListener(opts.obj, opts.event, function(e){
					opts.callback.call(self, e);
				});
			},
			addMarker: function(opts){
				var marker;
				opts.position = {
					lat: opts.lat,
					lng: opts.lng
				}

				marker = this._createMarker(opts);
				if(opts.event){
					this._on({
						obj: marker,
						event : opts.event.name,
						callback: opts.event.callback
					});
				}
				if(opts.content){
					this._on({
						obj: marker,
						event: 'click',
						callback: function(){
							var infoWindow = new google.maps.InfoWindow({
								content: opts.content
							});
							infoWindow.open(this.gMap, marker);
						}
					});
				}
				return marker;
			},
			addRoad: function(opts){
				// var lineSymbol = {
			 //    	path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			 //    	scale: 3,
			 //  	};

				if(opts.idGenerator == 1 || opts.idGenerator == 9){
					var istrokeColor = '#e74c3c'; // merah
				} else if(opts.idGenerator == 2 || opts.idGenerator == 10){
					var istrokeColor = '#3498db'; // biru
				} else if(opts.idGenerator == 3 || opts.idGenerator == 11){
					var istrokeColor = '#FFA500'; // orange
				} else if(opts.idGenerator == 4 || opts.idGenerator == 12){
					var istrokeColor = '#3ce7a2'; // ijo biru
				} else if(opts.idGenerator == 5 || opts.idGenerator == 13){
					var istrokeColor = '#db3498'; // ungu
				} else if(opts.idGenerator == 6 || opts.idGenerator == 14){
					var istrokeColor = '#98db34';
				} else if(opts.idGenerator == 7 || opts.idGenerator == 15){
					var istrokeColor = '#37dcff';
				} else if(opts.idGenerator == 8 || opts.idGenerator == 16){
					var istrokeColor = '#ff748c';
				} else if(opts.idGenerator == 99){
					var istrokeColor = '#7f7f7f ';
				} else{
					var istrokeColor = '#3498db';
					// var iicons = [{
					// 	icon: lineSymbol,
					// 	offset: '100%'
				 //    }];
				};

				var flightPath = new google.maps.Polyline({
					path: opts.coordinates,
					geodesic: true,
					strokeOpacity: opts.strokeOpacity,
					strokeWeight: opts.strokeWeight,
					strokeColor: istrokeColor,
					//icons: iicons
				});
				google.maps.event.addListener(flightPath, "rightclick", function(event) {
					var lat = event.latLng.lat();
					var lng = event.latLng.lng();
				    // populate yor box/field with lat, lng
				    alert(lat + " " + lng);
				});
				flightPath.setMap(this.gMap);
				return flightPath;
			},
			addPolygon: function(opts){
				if(opts.idGenerator == 1){
					var istrokeColor = '#ff0000';
				} else if(opts.idGenerator == 2){
					var istrokeColor = '#005aff';
				} else if(opts.idGenerator == 3){
					var istrokeColor = '#ffff00';
				} else if(opts.idGenerator == 4){
					var istrokeColor = '#000000';
				}
				else{
					var istrokeColor = '#00ff00';
					// var iicons = [{
					// 	icon: lineSymbol,
					// 	offset: '100%'
				 //    }];
				};

				var minimumBoundingRectangle = new google.maps.Polygon({
					paths: opts.paths,
					strokeColor: istrokeColor,
					strokeOpacity: opts.strokeOpacity,
					strokeWeight: opts.strokeWeight,
					fillColor: istrokeColor,
					fillOpacity: 0.25
				});
				google.maps.event.addListener(minimumBoundingRectangle, "rightclick", function(event) {
					var lat = event.latLng.lat();
					var lng = event.latLng.lng();
				    // populate yor box/field with lat, lng
				    alert(lat + " " + lng);
				});
				minimumBoundingRectangle.setMap(this.gMap);
				return minimumBoundingRectangle;
			},
			_createMarker: function(opts){
				opts.map = this.gMap;
				return new google.maps.Marker(opts);
			}
		};
		return Mapster;
	}());

Mapster.create = function(element, opts){
	return new Mapster(element, opts);
};

window.Mapster = Mapster;
}(window, google));

