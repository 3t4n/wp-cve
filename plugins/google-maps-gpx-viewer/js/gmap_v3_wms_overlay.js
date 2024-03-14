/*
gmap_v3_init.js, V 1.26, altm, 01.10.2012 
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
GMap V3 WMS and OSM layer support
released under GNU General Public License
*/
 
 /* 
	Display Copyright if not a google map
 */
	function toggleCopy(map, message) {
		var control = map.controls[google.maps.ControlPosition.BOTTOM_RIGHT];
		if (control.getLength() > 0) 
			control.pop();
		if(message != ''){
			var outerdiv = document.createElement("div");
			outerdiv.style.fontSize = "11px";
			outerdiv.style.whiteSpace = "nowrap";
			outerdiv.style.padding = "2px";
			var copyright = document.createElement("span");
			copyright.style.color = "#fff";
			copyright.innerHTML = message + " - ";
			outerdiv.appendChild(copyright);
			control.push(outerdiv);
		}
	}
	//Define OSM maptye
 	function OSMMapType(name, map_url, max, min){ 
		var osmMapType = new google.maps.ImageMapType({
			getTileUrl: function (coord, zoom) {
				return map_url + zoom + "/" + coord.x + "/" + coord.y + ".png";
			},
			tileSize: new google.maps.Size(256, 256),
			isPng: true,
			alt: "OpenStreetMap",
			name: name,
			minZoom: min,
			maxZoom: max
		});
		return osmMapType;
	}

	//Define OSGeo maptye
 	function OSGeoMapType(name, map_url, max, min){ 
		var OSGeoMap = new google.maps.ImageMapType({
			getTileUrl: function (coord, zoom) {
				return map_url + "&zoom=" + zoom + "&x=" + coord.x + "&y=" + coord.y;
			},
			tileSize: new google.maps.Size(256, 256),
			isPng: true,
			alt: "OSGeoMap",
			name: name,
			minZoom: min,
			maxZoom: max
		});
		return OSGeoMap;
	}

	// Define Relief layer
	var reliefMapType = new google.maps.ImageMapType({
		getTileUrl: function(coord, zoom) {
			return "http://maps-for-free.com/layer/relief/z" + zoom + "/row" + coord.y + "/" + zoom + "_" + coord.x + "-" + coord.y + ".jpg"; },
			tileSize: new google.maps.Size(256, 256),
			isPng: false,
			minZoom: 0,
			maxZoom: 11,
			name: "Relief",
			alt: "maps-for-free.com"
	 });

	//Define custom WMS layer
	function WebMapService(map, wms, wms_url, max, min){ 
		var WMSObj = new google.maps.ImageMapType({
			getTileUrl: function (coord, zoom) {
			var proj = map.getProjection();
			var zf = Math.pow(2, zoom);
			// get Long Lat coordinates
			var top = proj.fromPointToLatLng(new google.maps.Point(coord.x * 256 / zf, coord.y * 256 / zf));
			var bot = proj.fromPointToLatLng(new google.maps.Point((coord.x + 1) * 256 / zf, (coord.y + 1) * 256 / zf));
			//create Bounding box string
			var bbox =     top.lng() + "," + bot.lat() + "," + bot.lng() + "," + top.lat();
			//create WMS URL
			var url = wms_url;					//WMS url 
				url += "&SRS=EPSG:4326";     	//WMS Proj. WGS84 
				url += "&BBOX=" + bbox;      	//WMS bounding box
				url += "&WIDTH=256";         	//WMS tile size google needs
				url += "&HEIGHT=256";
			return url;                 
			},
			tileSize: new google.maps.Size(256, 256),
			name: wms,
			maxZoom: max,
			minZoom: min,
			isPng: false
        });
		return WMSObj;
	}

	// Run a gviz query to get location information from the fusion table for a bounding box
	function getQueryBounds(layer) {
		// Select lat/long points from table
		var queryText = encodeURIComponent(layer.getQuery());
		var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
		var map = layer.getMap();
		var map_type = map.getMapTypeId();
		// Set the callback function
		query.send(
			// Run through the results of the gviz query to get a list of coordinates
			function getData(response) {
				map.setMapTypeId(map_type);
				if (response.isError()) {
					map.g_showCnt--;
					alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
					return;
				} 
				// Get the number of rows returned
				numRows = response.getDataTable().getNumberOfRows();
				if (numRows == 0){
					map.g_showCnt--;
					alert('Nothing mached query!');
					return;
				}
				var bounds = new google.maps.LatLngBounds ();
				var	haveBbox = false;
				// test what we got e.g. Location should be a pair of coords and not an address...
				var table = response.getDataTable()
				var test = response.getDataTable().getValue(0, 0);
				if (test.length == undefined){
					map.g_showCnt--;
					alert('Nothing mached query!');
					return;
				}
				var del = ",";
				var tester = test.split(del);
				// check for Location as lat-lon pair
				
				if (tester.length != 2){
					del = " ";
					tester = test.split(del); // try a space as delimiter
				}
				if(tester.length == 2 && !isNaN(tester[0]) && !isNaN(tester[1])){ 
					for(i = 0; i < numRows; i++) {
						vals = response.getDataTable().getValue(i, 0);
						vals = vals.split(del)
						loc = new google.maps.LatLng(vals[0], vals[1]);
						bounds.extend(loc);
					}
					haveBbox = true;
				}
				// check for geometry kml
				else if(test.search(/<coordinates>/i) != -1){
					for(j = 0; j < numRows; j++) {
						var coords = response.getDataTable().getValue(j, 0);
						if(coords == "")
							break;
						coords = coords.replace(/[^0-9,.-]/g,'');
						//var coordsText = jQuery( coords ).find( 'coordinates' ).text(); <-- not cross browser compatible
						var coordStrings = coords.split( ' ' );
						for ( i = 0; i < coordStrings.length; i++) {
							vals =  coordStrings[i].replace(/[^0-9,.-]/g,'');
							vals = vals.split(',')
							if(!isNaN(vals[0]) && !isNaN(vals[1])){
								loc = new google.maps.LatLng(vals[1], vals[0]);
								bounds.extend(loc);
								haveBbox = true;
							}
						}
					}
				}
				// lat/lon cols
				else if(!isNaN(test) && !isNaN(response.getDataTable().getValue(0,1))){ 
					for(i = 0; i < numRows; i++) {
						var lat = response.getDataTable().getValue(i,0);
						var lon = response.getDataTable().getValue(i,1);
						loc = new google.maps.LatLng(lat, lon);
						bounds.extend(loc);
					}
					haveBbox = true;
				// try data as an address field
				} else {
					var geocoder = new google.maps.Geocoder(); 
					var address = new Array();;
					for (var i = 0; i < numRows; i++) { 
						if(i > 10) break; // or we receive googles OVER_QUERY_LIMIT 
						address.push(response.getDataTable().getValue(i,0));
						(function(i) { 
							geocoder.geocode( {'address': address[i]}, function(results, status) {
								if (status == google.maps.GeocoderStatus.OK) {
									// Extending the bounds object with each LatLng 
									bounds.extend(results[0].geometry.location); 
									haveBbox = true;
								} else { 
								//alert("no success: " + status); 
								}
								if(i == (numRows - 1)){ 
								}
							});
						})(i);
					} 
				}				
				if(haveBbox){
					fitViewport(map, bounds);
				} else 
					map.g_showCnt--;
			}
		);
	}
