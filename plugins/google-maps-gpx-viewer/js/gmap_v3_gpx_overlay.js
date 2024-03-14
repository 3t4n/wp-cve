/*
gmap_v3_gpx_overlay.js, V 1.13, altm, 14.11.2014
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 gpx overlay
released under GNU General Public License
*/
	function showMarkers(map, icon){
		icon.visible = !icon.visible;
		for(var i = 0; i < map.markers.length; i++){
			if (map.markers[i].icon.url == icon.url){
				map.markers[i].setVisible(icon.visible);
			}
		}
	}
	function markerUI(map){
		var icons = getIconsId(map);
		var mapId = map.getDiv().id;
		var divmap = "#" + mapId;
		var div_id = "makerL_" + mapId + "";
		jQuery('<div id='+div_id+' class="gmv3_mrk" style="display:none;"></div>').insertAfter(divmap);
		
		for (var i = 0; i < icons.length; i++){
			if(!icons[i].url)
				continue;
			var name = icons[i].url.split('/');
			name = name[name.length-1];
			name = name.split('.');
			name = name[0];
			var id = "document.getElementById('wptm_"+mapId+'_'+i+"').click(); return;";
			var text = '<div id="micon_'+mapId+'_'+i+'" class="gmv3_mrktoggle" alt="'+name+'" title="'+name+'" onclick="'+id+'">';
			text    += '<img class="gmv3_dlg_img" src="'+icons[i].url+'" alt="'+name+'" title="'+name+'">';
			text    += '<input style="display:none;" id="wptm_'+mapId+'_'+i+'" onclick="makerWinToggle('+mapId+', this.value, this.checked); return;" type="checkbox" value="'+i+'"></div>';
			jQuery(text).appendTo('#'+div_id);

			makerWinToggle(map, i);
		}
		jQuery('#'+div_id).css("display","block");
		jQuery('#'+div_id).css("overflow","auto");
		var height = jQuery('#'+div_id).height();
		jQuery('#holder_'+mapId).height(jQuery('#holder_'+mapId).height() + height);
		jQuery('#holder_'+mapId).css("height","auto");
	}
	
	function makerWinToggle(map, val){
		var icons = getIconsId(map);
		showMarkers(map, icons[val]);
		if(icons[val].visible)
			jQuery('#micon_'+map.getDiv().id+'_'+val).addClass("gmv3_mrktoggle_active");
		else
			jQuery('#micon_'+map.getDiv().id+'_'+val).removeClass("gmv3_mrktoggle_active");
	}
	
	var idTog = 0;
	function sMoff(map){
		var icons = getIconsId(map);
		if(idTog >= icons.length){
			idTog = 0;
		}
		showMarkers(map, icons[idTog]);
		idTog++;		
	}
	// get the gpx-track icon ids
	function getIconsId(map){
		
		var iconIds = new Array();
		for (var i = 0; i < map.markers.length; i++){
		    var icon = [];
			icon.url = map.markers[i].icon.url;
			icon.visible = map.markers[i].getVisible();
			var l = iconIds.length;
			var solo = true;
			for (var n = 0; n < l; n++){
				if (iconIds[n].url == icon.url){
					solo = false;
				}
			}
			if (solo)
				iconIds.push(icon);
		}
		return iconIds
	}

	function setMarker(map, pos, trk){	
		var title = trk.children("name").html();
		var description = trk.children("desc").html();
		var link = trk.children("link").attr("href");
		var linkdesc = trk.children("link").next("text").html();
		var contentString = "";
		if (title)
			contentString = '<div class="gmv3_marker"><div class="gmv3_markerHeader">' + title + '</div>';
		if(description)
			contentString += '<div class="gmv3_markerText">' + description + '</div>';
		if(link){
			contentString += '<div><a class="gmv3_markerLink" target="_blank" href="' + link + '">'
			if(linkdesc)
				contentString += linkdesc;
			 else
				contentString += 'more...';
			contentString += '</a></div>'	
		}
		if(description)
			contentString += '</div>';
		if(title)
			contentString += '</div>';

		var image;
		var shadow;
		var shape;
		var sym;
		var symNo = -1;
		var trkSym = false;
		sym = trk.children("sym").attr('src');
		if (sym && sym.search(/http.+/i) != -1 ){
			image = new google.maps.MarkerImage(sym);
			symNo = parseInt(trk.children("sym").html());
		}
		else {
			if (trk.context.nodeName.toUpperCase() == "TRK" || trk.context.nodeName.toUpperCase() == "RTE"){
				trkSym = true;
				if(escape(sym) >= 0 && escape(sym) < 16){
					sym = 'img/trz_'+sym+'.png';
					image = new google.maps.MarkerImage(pluri + sym,
						new google.maps.Size(26, 28),
						new google.maps.Point(0,0),
						new google.maps.Point(7, 28));
					shadow = new google.maps.MarkerImage(pluri + 'img/trz_shw.png',
						new google.maps.Size(40, 28),
						new google.maps.Point(0,0),
						new google.maps.Point(7, 28));
				}
				else {
					if(contentString){	// removed since V 2.0
						image = {  
								strokeWeight: 1,
								strokeColor: '#000000',
								strokeOpacity: 1.0,
								scale: map.polies[map.aktPoly].strokeWeight*1.5,
								fillColor: map.polies[map.aktPoly].strokeColor,
								//fillOpacity: map.polies[map.aktPoly].strokeOpacity,
								path: google.maps.SymbolPath.CIRCLE
						};
					} else
						return;
				}
					
			} else { //wpt marker
				sym = trk.children("sym").html();
				symNo = parseInt(sym);
				if(escape(sym) >= 0 && escape(sym) < 28 && symNo != NaN ){
					sym = 'img/sym_'+sym+'.png';
				} else 
					sym = 'img/sym_1.png';
				image = new google.maps.MarkerImage(pluri + sym,
					new google.maps.Size(20, 32),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 32));
				shadow = new google.maps.MarkerImage(pluri + 'img/sym_shw.png',
					new google.maps.Size(37, 32),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 32));
			}
		}
		var marker = new google.maps.Marker({
			map: map, 
			icon: image,
			shadow: shadow,
			position: pos,
			// symbol: symNo,
			title: title,
			content: description
		});
		marker.infowindow = new google.maps.InfoWindow({
			content: contentString
		});
		marker.event = google.maps.event.addListener(marker, 'click', function() {
			if(map.infoWin)
				map.infoWin.infowindow.close();
			marker.infowindow.open(map ,marker);
			map.infoWin = marker;
		});
		if(!trkSym)
			map.markers.push(marker);
	}
	
	function showGPX(map, uri){
		jQuery.ajax({
			type: "GET",
			url: uri,
			dataType: "text",
			success: function(data) { 
				var gPath = [];
				var xml = jQuery(data);
				var bounds = new google.maps.LatLngBounds ();
				// look for tracks
				var trk = xml.children("trk");
				if (trk.length > 0){
					trk.each(function(){
						// track attributes & content
						var name = jQuery(this).children("name").html();
						var color = jQuery(this).attr('color');
						if(color == undefined) 
							color = "#cc3322";
						var width = jQuery(this).attr('width');
						if(width == undefined) 
							width = "3";
						var opacity = jQuery(this).attr('opacity');
						if(opacity == undefined) 
							opacity = "0.7";
						var trkseg = jQuery(this).children("trkseg");
						if (trkseg.length > 0){
							var actTrk = jQuery(this);
							var position = 0;
							var first = true;
							trkseg.each(function() {
								var trkpt = jQuery(this).children("trkpt");
								if (trkpt.length > 0){
									var points = [];
									points.length = 0;
									trkpt.each(function() {
										var lat = jQuery(this).attr("lat");
										var lon = jQuery(this).attr("lon");
										var p = new google.maps.LatLng(lat, lon);
										if(first){ 
											position = new google.maps.LatLng(lat, lon);
											first = false;
										}
										points.push(p);
										bounds.extend(p);
									});
									if(map['elevation']){
										gPath = gPath.concat(points);
									}
									var polyOptions = {
										strokeColor: color,
										strokeOpacity: opacity,
										strokeWeight: width,
										path: points
									}
									// Polyline array
									map.polies.push(new google.maps.Polyline(polyOptions));
									map.aktPoly = map.polies.length-1;
									var poly = map.polies[map.aktPoly];
									poly.g_name = name;
									poly.index = map.aktPoly;
									poly.setMap(map);

								}
							});
						}
						// Marker if set
						setMarker(map, position, actTrk);
					});
				}
				// look for routes
				var rte = xml.children("rte");
				if (rte.length > 0){
					rte.each(function(){
						var actTrk = jQuery(this);
						var position = 0;
						var first = true;
						// track attributes
						var color = jQuery(this).attr('color');
						if(color == undefined) 
							color = "#cc3322";
						var width = jQuery(this).attr('width');
						if(width == undefined) 
							width = "3";
						var opacity = jQuery(this).attr('opacity');
						if(opacity == undefined) 
							opacity = "0.7";

						var rtept = jQuery(this).children("rtept");
						if (rtept.length > 0){
							var points = [];
							points.length = 0;
							rtept.each(function() {
								var lat = jQuery(this).attr("lat");
								var lon = jQuery(this).attr("lon");
								var p = new google.maps.LatLng(lat, lon);
								if(first){ 
									position = new google.maps.LatLng(lat, lon);
									first = false;
								}
								points.push(p);
								bounds.extend(p);
							});
							if(map['elevation']){
								gPath = gPath.concat(points);
							}
							var polyOptions = {
								strokeColor: color,
								strokeOpacity: opacity,
								strokeWeight: width,
								path: points
							}
							// Polyline array
							map.polies.push(new google.maps.Polyline(polyOptions));
							map.aktPoly = map.polies.length-1;
							var poly = map.polies[map.aktPoly];
							poly.index = map.aktPoly;
							poly.setMap(map);

						}
						// Marker if set
						setMarker(map, position, actTrk);
					});
				}
				// look for waypoints
				// fit bounds to track
				var wpt = xml.children("wpt");
				if (wpt.length > 0){
					wpt.each(function() {
						var lat = jQuery(this).attr("lat");
						var lon = jQuery(this).attr("lon");
						var p = new google.maps.LatLng(lat, lon);
						var position = new google.maps.LatLng(lat, lon);
						bounds.extend(p);
						setMarker(map, position, jQuery(this));
					});
				}
				fitViewport(map, bounds);					
				google.maps.event.addListener(map, 'click', function() {
					if(map.infoWin)
						map.infoWin.infowindow.close();
				});
				if(map.map_menu){ // maybe we want to edit
					map.map_menu.gpxLoad();
				}
				if(map['elevation'] && gPath.length > 1){
					map['uri'] = uri;
					showElevation(map, gPath);
				} else if (map['download']){
					map['uri'] = uri;
					showDownload(map);
				}
			},
			complete:function (jqXHR, textStatus){
				/* enable for error check in loading gpx*/ 
				// if(textStatus != "success")
					// alert('Error: ' + jqXHR.responseText + ' + ' + textStatus);				
			}    
		});	
	}
