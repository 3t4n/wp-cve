/*
gmap_v3_init.js, V 1.28, altm, 22.11.2013 
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 init Multimap support
released under GNU General Public License
*/
	var maps = new Array();
	var map_cookie = "mapinfo_";  
	var expiers = 7;    
	var mapTypesArr = new Array();	
	
	function set_cookie(map, check) {
        maptype = 0;
		for (var i=0;i<map.mapTypeControlOptions.mapTypeIds.length;i++) {
			if (map.getMapTypeId() == map.mapTypeControlOptions.mapTypeIds[i]) {
				maptype = i;
				if(map.g_mapzoom == undefined)
					map.g_mapzoom = map.getZoom();
				if (map.g_mapzoom != map.getZoom() && check){
					map.g_mapzoom = map.getZoom();
				}
			}
		}
 		if(map.g_seCookie){
			var mc = map_cookie + map.getDiv().id;
			var cookietext = mc + "="+map.getCenter().lat()+"|"+map.getCenter().lng()+"|"+map.getZoom()+"|"+maptype;
			if (expiers) {
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiers);
				cookietext += ";expires="+exdate.toGMTString();
			}
			document.cookie=cookietext;   
		}			
   }
	
	function post_init(map){
 		if(map.g_seCookie){
			var have_nocookie = true;
			if (document.cookie.length>0) {
				var mc = map_cookie + map.getDiv().id;

				cookieStart = document.cookie.indexOf(mc + "=");
				if (cookieStart!=-1) {
					cookieStart += mc.length+1; 
					cookieEnd=document.cookie.indexOf(";",cookieStart);
					if (cookieEnd==-1) {
						cookieEnd=document.cookie.length;
					}
					cookietext = document.cookie.substring(cookieStart,cookieEnd);
					vals = cookietext.split("|");
					startPoint = new google.maps.LatLng(parseFloat(vals[0]), parseFloat(vals[1]));
					zoomlevel = parseInt(vals[2]);
					maptype = parseInt(vals[3]);
					have_nocookie = false;
					if(map.mapTypeControlOptions.mapTypeIds[maptype])
						map.setMapTypeId(map.mapTypeControlOptions.mapTypeIds[maptype]);
					map.setCenter(startPoint);
					map.setZoom(zoomlevel);
				} 
			}
			if (have_nocookie) {
				// entire world, center europe
				startPoint = new google.maps.LatLng(30.0, 10.0);
				zoomlevel = 1;
				map.setCenter(startPoint);
				map.setZoom(zoomlevel);
			}
		} else {
			if(map.g_showCnt <= 0){
				if( map.g_latlon) 
					map.setCenter(map.g_latlon);
				if(map.g_zoom)
					map.setZoom(map.g_zoom);
				else
					map.setZoom(13);
				if(map.g_offsX || map.g_offsY)
					map.panBy(map.g_offsX,map.g_offsY);
			}
		}
		// cook it
		google.maps.event.addListener(map, "maptypeid_changed", function(){
			maptype_changed(map);
			set_cookie(map,true);
		});
		google.maps.event.addListener(map, "zoom_changed", function(){set_cookie(map,true);});
		google.maps.event.addListener(map, "center_changed", function(){set_cookie(map,false);});
		maptype_changed(map);

		var gm_map_visible = jQuery('#'+map.getDiv().id).parents(':hidden');
		if(gm_map_visible.length){
			jQuery('#'+map.getDiv().id).appear(function() {
				wakeMap(map);
			});
		}
	}
	
	function maptype_changed(map){
		var numSel = map.getMapTypeId().toLowerCase();
		for (var i=0;i< mapTypesArr.length;i++) {
			if(mapTypesArr[i].name.toLowerCase()==numSel){
				var chosen = mapTypesArr[i].copy;
				toggleCopy(map, chosen);
				break;
			}
		}
	}
	
	function init_map(maptype, mapInst, access){
		var mTypes = new Array();
		var mapOptions;
		var mapTypeControlOptions;
		var have_maptype = false;
		var maps = 0;
		for (var i = 0; i < mapTypesArr.length; i++){
			if(mapTypesArr[i].visible || access){
				maps++;
				switch (mapTypesArr[i].name) {
				  case "ROADMAP":
					mTypes.push(google.maps.MapTypeId.ROADMAP);
					break;
				  case "SATELLITE":
					mTypes.push(google.maps.MapTypeId.SATELLITE);
					break;
				  case "HYBRID":
					mTypes.push(google.maps.MapTypeId.HYBRID);
					break;
				  case "TERRAIN":
					mTypes.push(google.maps.MapTypeId.TERRAIN);
					break;
				  default:
					mTypes.push(mapTypesArr[i].name);
					break;
				}		
			}
		}
		if(maps > 1){
			mapTypeControlOptions = {mapTypeIds: mTypes, style:google.maps.MapTypeControlStyle.DROPDOWN_MENU};
			mapOptions = {
				mapTypeControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
				},
				mapTypeControlOptions:mapTypeControlOptions
			};
		}
		else{
			mapTypeControlOptions = {mapTypeIds: mTypes};
			mapOptions = {
				mapTypeControl: false,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.SMALL
				},
				mapTypeControlOptions:mapTypeControlOptions
			};
		}
		if(!gmapv3_disableDefaultUI)
			mapOptions.disableDefaultUI = true;
		if(!gmapv3_zoomControl)
			mapOptions.zoomControl = false;
		var newmap = new google.maps.Map(document.getElementById('' + mapInst + ''));
		
		newmap.mapTypes.set("Relief", reliefMapType);
		// web map service
		for (var i = 0; i < mapTypesArr.length; i++){
			// open street map
			if(mapTypesArr[i].wms == 'osm')
				newmap.mapTypes.set(mapTypesArr[i].name,  OSMMapType(mapTypesArr[i].name, mapTypesArr[i].url, mapTypesArr[i].minzoom,  mapTypesArr[i].maxzoom));
			//check if WMS public
			if(mapTypesArr[i].wms == 'wms') 
				newmap.mapTypes.set(mapTypesArr[i].name, WebMapService(newmap, mapTypesArr[i].name, mapTypesArr[i].url, mapTypesArr[i].minzoom,  mapTypesArr[i].maxzoom));
			//check if OSGeo
			if(mapTypesArr[i].wms == 'osgeo') 
				newmap.mapTypes.set(mapTypesArr[i].name, OSGeoMapType(mapTypesArr[i].name, mapTypesArr[i].url, mapTypesArr[i].minzoom,  mapTypesArr[i].maxzoom));
			//check if WMS admin access only
			if(mapTypesArr[i].wms == 'wms_a' && access) 
				newmap.mapTypes.set(mapTypesArr[i].name, WebMapService(newmap, mapTypesArr[i].name, mapTypesArr[i].url, mapTypesArr[i].minzoom,  mapTypesArr[i].maxzoom));
			if(mapTypesArr[i].name.toLowerCase() == maptype.toLowerCase())
				have_maptype = true;		
		}
		newmap.setOptions(mapOptions);
		//preselect maptype
		if(!have_maptype && mTypes.length > 0)
			maptype=mTypes[0];
		if(maptype == 'TERRAIN' || maptype == 'ROADMAP' || maptype == 'HYBRID' || maptype == 'SATELLITE' ) 
		{
			newmap.setMapTypeId(maptype.toLowerCase());
		} else {
			newmap.setMapTypeId(maptype);
		}
		// map size button
		if (mapSizeButton){
			var sizeControlDiv = document.createElement('div');
			var sizeControl = new SizeControl(sizeControlDiv, newmap);
			sizeControlDiv.index = 0;
			newmap.controls[google.maps.ControlPosition.TOP_RIGHT].push(sizeControlDiv);
		}
		return newmap;
	}
	
	function load_map(map, lat, lon , zoom ){
		// bounding box for resize
		map.bbox = new google.maps.LatLngBounds();
		map.g_showCnt = 0;

		if(lat && lon){
			map.g_latlon = new google.maps.LatLng(lat, lon);
		} else {
			// entire world, center europe
			map.g_seCookie = true; // set cookie
		}
		if(zoom != ''){
			map.g_zoom = Number(zoom);
		}

		map.polies = new Array();
		map.markers = new Array();	
		if(typeof(tinymce) != 'undefined'){
			map.mrk_eddi = new MkrEddi(map);
			map.trk_eddi = new TrkEddi(map);
			map.map_menu = new RcMenu(map);
			map.gpx_overlay = new google.maps.OverlayView();
			map.gpx_overlay.draw = function() {};
			map.gpx_overlay.setMap(map);
		}	
	}
	
	// fits everything into one viewport
	function fitViewport(map, bounds){	
		if(bounds){
			if(!map["bbox"].isEmpty()){
				bounds.union(map["bbox"]);
			}
			map["bbox"] = bounds;
		}
		map.g_showCnt--;
		if(map.g_showCnt <= 0){
			if(!map["bbox"].isEmpty()){
				if(map.g_latlon){
					map["bbox"].extend(map.g_latlon);
					google.maps.event.addListenerOnce(map, "tilesloaded", function(){map.panTo(map.g_latlon);});
				} 
				map.fitBounds(map["bbox"]);

			} else {
				if(map.g_latlon){
			 		map.setCenter(map.g_latlon);
				} else {
					map.setCenter(new google.maps.LatLng(30.0, 10.0));
				}
				if(!map.g_zoom){
					map.g_zoom = 1;
				}
			}
			if(map.g_zoom){
				map.setZoom(map.g_zoom);
				google.maps.event.addListenerOnce(map, "tilesloaded", function(){map.setZoom(map.g_zoom);});
			}
			if(map.g_offsX || map.g_offsY){
				map.panBy(map.g_offsX,map.g_offsY);
			}
			if(map.g_mToggle && map.markers.length)
				markerUI(map);
			// additional functionality in Thems template?
			if(typeof searchPlacesOnLoad == 'function')
				searchPlacesOnLoad(map);
		}
	}
	function gotoGeoLocation(map){
		var address = document.getElementById('goto').value;
		var geocoder = new google.maps.Geocoder();/* */
		geocoder.geocode( { "address": address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK){
				map.setCenter(results[0].geometry.location);
				if(results[0].geometry.bounds){
					map["bbox"] = results[0].geometry.bounds;
					map.fitBounds(map["bbox"]);
				} else {
					map.setZoom(21);
				}
			} else {
				alert(status);
			}
		});
	}
	function toggleKML(map){
		if(map.gpx_kml){
			if(map.gpx_kml.getMap())
				map.gpx_kml.setMap(null);
			else
				map.gpx_kml.setMap(map);
		}
	}