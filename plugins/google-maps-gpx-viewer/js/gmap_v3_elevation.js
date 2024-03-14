/*
gmap_v3_elevation.js, V 1.08, altm, 14.05.2013
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 gpx overlay
released under GNU General Public License
*/ 
	function showDownload(map){
		var mapId = map.getDiv().id;
		var divmap = "#" + mapId;
		var divDistW = jQuery(divmap).width()//; - divChartW;
		var distId = "dist_" + mapId + "";
		jQuery('<div id="'+distId+'" class="gmv3_download gm_add_'+mapId+'" style="display:none;"><a href="'+map['uri']+'" target="_blank">'+msg_05+'</a></div>').insertAfter(divmap);
		var divH = 35;
		jQuery('#'+distId).height(divH);
		jQuery('#holder_'+mapId).height(divH + jQuery(divmap).height());
		jQuery('#'+distId).slideToggle("slow");
	}
	
	function showElevation(map, path){
		// Load the Visualization API and the columnchart package.
		// Create a PathElevationRequest object using this array.
		// Limit path to max. 256 coords
		var totalLength = 0.0;
		for (var i = 0; i < path.length; i++) {
			if( i > 0 ){
				totalLength += calcDistance(path[i], path[i-1]);
			}
		}
		var startPos = path[0];
		var endPos = path[path.length-1];
		if(path.length > 100){
			while(path.length > 200){
				var i = path.length-1;
				for (path.length-1; i > 0; i = i-2) {
					path.splice(i,1);
				}
			}
		}
		path[path.length-1] = endPos;
		// count our diagramms
		var mapUri = map['uri'];
		// Ask for 100 samples along that path 
		var pathRequest = {
		  'path': path,
		  'samples': 100
		}
		// Create an ElevationService.
		var elevator = new google.maps.ElevationService();
		// Initiate the path request.
		elevator.getElevationAlongPath(pathRequest, 
			// Takes an array of ElevationResult objects, draws the path on the map
			// and plots the elevation profile on a Visualization API ColumnChart.
			function plotElevation(results, status) {
				if (status == google.maps.ElevationStatus.OK) {
					var elevations = results;
					if (map['ovls'] != undefined)
						map['ovls'] ++;
					else 
						map['ovls'] = 0;
					var mapId = map.getDiv().id;
					var divmap = "#" + mapId;
					var divChartW = Math.round(jQuery(divmap).width()*0.85);
					var divDistW = jQuery(divmap).width() - divChartW;
					var divH = (jQuery(divmap).height() < jQuery(divmap).width()) ? Math.round(jQuery(divmap).height()/3) : Math.round(jQuery(divmap).width()/3);
					if (divH < 100) divH = 100;
					var chartId = "elevation_chart_" + mapId + "_" + map['ovls'];
					var distId = "dist_" + mapId + "_" + map['ovls'];
					jQuery('#holder_'+mapId).height(divH + jQuery('#holder_'+mapId).height());
					var fz = divH/180*100;
					if (fz > 100) fz = 100;
					jQuery('<div class="gm_add_'+mapId+'" id="'+chartId+'" style="width:'+divChartW+'px; height:'+divH+'px; display:none;"></div>').insertAfter(divmap);	
					jQuery('<div class="gmv3_elevation gm_add_'+mapId+'" id="'+distId+'" style="width:'+divDistW+'px; height:'+divH+'px;font-size:'+fz+'%;" overflow:hidden;></div>').insertAfter(divmap);	
					// var chart = new google.visualization.AreaChart(document.getElementById(chartId));
					var chart = new google.visualization.LineChart(document.getElementById(chartId));

					// Extract the data from which to populate the chart.
					// Because the samples are equidistant, the 'Sample'
					// column here does double duty as distance along the
					// X axis.
					var data = new google.visualization.DataTable();
					data.addColumn('string', 'Sample');
					data.addColumn('number', '');
					var deltaHeight = 0;
					var lSeg = totalLength/results.length;
					for (var i = 0; i < results.length; i++) {
						if( i > 0 ){
							var diff = elevations[i].elevation - elevations[i-1].elevation;
							if(diff > 0)
								deltaHeight += diff;
						}
						if(distanceUnit == 'miles')
							data.addRow([(Math.round(lSeg * i * 10 * 0.62137)/10).toString(), Math.round((elevations[i].elevation * 3.281 * 10)/10)]);
						else
							data.addRow([(Math.round(lSeg * i * 10)/10).toString(), Math.round(elevations[i].elevation)]);						
					}
					var x = Math.round((divH-28)/8);
					jQuery('#'+distId).append('<div style="margin:28px 0px 0px 0px;">'+msg_03+'</div>');
					if(distanceUnit == 'miles')
						jQuery('#'+distId).append('<div style="margin:0px;">'+Math.round(totalLength * 0.62137)+' miles</div>');
					else
						jQuery('#'+distId).append('<div style="margin:0px;">'+Math.round(totalLength)+' km</div>');
					
					jQuery('#'+distId).append('<div style="margin:'+x+'px 0px 0px 0px;">'+msg_04+'</div>');
					if(distanceUnit == 'miles')
						jQuery('#'+distId).append('<div style="margin:0px;">'+Math.round(deltaHeight * 3.281)+' ft</div>');
					else
						jQuery('#'+distId).append('<div style="margin:0px;">'+Math.round(deltaHeight)+' m</div>');
					
					if(map['download'])
						jQuery('#'+distId).append('<div style="margin:'+x+'px 0px 0px 0px;"><a href="'+mapUri+'" target="_blank">'+msg_05+'</a></div>');
					//
					// Draw the chart using the data within its DIV. 
					// and fade out our elevation profile
					jQuery('#'+chartId).slideDown(500);
					jQuery('#'+chartId).css("position","relative");
					var hUntis = '(m)';
					var lUntis = '(km)';
					if(distanceUnit == 'miles'){
						hUntis = '(ft)';
						lUntis = '(miles)';
					}
					chart.draw(data, {
						chartArea:{left:50,top:22,width:"89%",height:"50%"},
						backgroundColor:'transparent',
						tooltipTextStyle:{fontSize: 10},
						width: divChartW,
						height: divH,
						legend: 'none',
						//enableInteractivity:false, // peak level tooltip
						focusTarget:'category',
						//isStacked:true, // start from 0
						hAxis:{slantedText:false,maxAlternation:1},
						titleX: lUntis,
						titleY: hUntis
					});
					image = new google.maps.MarkerImage(pluri + 'img/ele.png');
					var ele_marker = new google.maps.Marker({
						map: map, 
						icon: image,
						zIndex : 1000,
						position: startPos
					});
					ele_marker.setVisible(false);
					
					google.visualization.events.addListener(chart, 'onmouseover', 
						function handler(click) {
							var loc = elevations[click.row].location;
							ele_marker.setPosition(loc);
							ele_marker.setVisible(true);
						}
					);
 					google.visualization.events.addListener(chart, 'onmouseout', 
						function handler(click) {
							ele_marker.setVisible(false);
						}
					);
				}
			}  
		);
	}

	function getKmlPath(map, uri){
		jQuery.ajax({
			type: "GET",
			url: uri,
			dataType: "text",
			success: function(data) { 
				var xml = jQuery(data);
				var doc = xml.children("Document");
				var kPath = [];

				if (doc.length > 0){
					var coordinates = jQuery(doc).find("LineString").children("coordinates");
					coordinates.each(function(){
						var coords = jQuery(this).text();
						if (coords.length > 0){
							var points = [];
							coord = coords.replace(/\s/g,' ');
							var coordStrings = coord.split( ' ' );
							for ( i = 0; i < coordStrings.length; i++) {
								vals =  coordStrings[i].replace(/[^0-9,.-]/g,'');
								vals = vals.split(',')
								if(!isNaN(vals[0]) && !isNaN(vals[1])){
									loc = new google.maps.LatLng(vals[1], vals[0]);
									points.push(loc); 
								}
							}
							kPath = kPath.concat(points);
						}
					});
					var coords = doc.find('gx\\:Track').find('gx\\:coord');
					if (coords.length > 0){
						var points = [];
						coords.each(function(){
							var coords = jQuery(this).text();
								coord = coords.replace(/\s/g,' ');
								var coordStrings = coord.split( ' ' );
									if(!isNaN(coordStrings[0]) && !isNaN(coordStrings[1])){
										loc = new google.maps.LatLng(coordStrings[1], coordStrings[0]);
										points.push(loc); 
									}
						});
						kPath = kPath.concat(points);
					}
					
				}
				if(map['elevation'] && kPath.length > 1){
					map['uri'] = uri;
					showElevation(map, kPath);
				} else if (map['download']){
					map['uri'] = uri;
					showDownload(map);
				}
			},
			complete:function (jqXHR, textStatus){
				/* enable for error check in loading gpx*/
				if(textStatus == "error"){
					//alert('Error: ' + jqXHR.responseText);
					if (map['download']){
						map['uri'] = uri;
						showDownload(map);
					}
				}
			}    
		});	
	}	

	/**/
	function calcDistance(p1, p2){
		// var dist = 0;
		var R = 6371; // km
		var toRad = Math.PI / 180;
		var dLat = (p2.lat()-p1.lat()) * toRad;
		var dLon = (p2.lng()-p1.lng()) * toRad;
		var lat1 = p1.lat() * toRad;
		var lat2 = p2.lat() * toRad;

		var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
		var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
		return (R * c);		// return dist;
	}
