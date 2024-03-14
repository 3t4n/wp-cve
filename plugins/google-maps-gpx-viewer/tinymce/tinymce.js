/*
tinymce.js, V 1.05, altm,23.04.2014 
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 init Multimap support
released under GNU General Public License
*/
	function init() {
		tinyMCEPopup.resizeToInnerSize();
	}
	
	function bodyloadGMap(){
		tinyMCEPopup.executeOnLoad('init();');
		jQuery('#map_tab').focus();
		// dialoge entries from selection
		var markedMap = tinyMCE.activeEditor.selection.getContent({format : 'text'});
		
		if(markedMap){
			// map tab 
			var res = markedMap.search(/maptype\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				// if(val) jQuery('#maptypetag').attr("value",val);
				if(val) jQuery("#maptypetag option[value="+val+"]").attr('selected',true);
			}
			res = markedMap.search(/z\s*=\s*"(\d+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				if(val) {
					jQuery('#mapZoom option').each(function(){
						jQuery(this).val(this.text);
					});
					jQuery("#mapZoom option[value="+val+"]").attr('selected',true);
				}
				// if(val) jQuery('#mapZoom').attr("value",val);
			}
			res = markedMap.search(/bike\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				if(val == 'yes') jQuery('#mapBike').attr('checked','checked');
			}
			res = markedMap.search(/traffic\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				if(val == 'yes') jQuery('#mapTraffic').attr('checked','checked');
			}
			res = markedMap.search(/pano\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				if(val == 'yes') jQuery('#mapPano').attr('checked','checked');
			}
			res = markedMap.search(/panotag\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var PanoTag = RegExp.$1;
				if(PanoTag) jQuery('#mapPanoTag').attr("value",PanoTag);
			}
			
			// style tab
			res = markedMap.search(/width:.*?(\w+%?)\s*;/i);
			if(res > -1){
				var val = RegExp.$1;
				if(!isNaN(parseInt(val))){
					if(val.search(/%+/) == -1)
						val = parseInt(val);
				}
				if(val) jQuery('#mapWidth').attr("value",val);
			}
			res = markedMap.search(/height:.*?(\w+%?)\s*;/i);
			if(res > -1){
				var val = RegExp.$1;
				if(!isNaN(parseInt(val))){
					if(val.search(/%+/) == -1)
						val = parseInt(val);
				}
				if(val) jQuery('#mapHeight').attr("value",val);
			}
			res = markedMap.search(/margin:.*?(\d+)\w+\s+(\d+)\w+\s+(\d+)\w+\s+(\d+).+\s*;/i);
			if(res > -1){
				var mT = RegExp.$1; var mR = RegExp.$2; var mB = RegExp.$3; var mL = RegExp.$4;
				if(mT) jQuery('#marginTop').attr("value",mT);
				if(mR) jQuery('#marginRight').attr("value",mR);
				if(mB) jQuery('#marginBottom').attr("value",mB);
				if(mL) jQuery('#marginLeft').attr("value",mL);
			}
			res = markedMap.search(/border:.*?(\d+)\w+\s+(\w+)\s+(\W?\w+)\s*;/i);
			if(res > -1){
				var bW = RegExp.$1; var bS = RegExp.$2; var bC = RegExp.$3;
				if(bW) jQuery('#borderSize').attr("value",bW);
				if(bS) jQuery('#borderStyle').attr("value",bS);
				if(bC) jQuery('#borderColor').attr("value",bC);
			}
			res = markedMap.search(/float:.*?([\w]+)\s*;/i);
			if(res > -1){
				var val = RegExp.$1; if(val) jQuery("#mapFloat option[value="+val+"]").attr('selected',true);
			}
			
			// map address lat/lon position
			res = markedMap.search(/address\s*=\s*"([\w ,\-ß-üÄ-ÜÂÀÅÃâàåãÄÇçÉÊÈËéêèëÓÔÒÕØóôòõøŠšÚÛÙúûùÝŸýÿŽž]+)\s*"/i);
			 
			if(res > -1){
				var addr = RegExp.$1;
				if(addr) jQuery('#mapAddress').attr("value",addr);
			}
			res = markedMap.search(/lat\s*=\s*"(\d+\.*\d*)\s*"/i);
			if(res > -1){
				val = RegExp.$1; if(val) jQuery('#mapLat').attr("value",val);
			}
			res = markedMap.search(/lon\s*=\s*"(\d+\.*\d*)\s*"/i);
			if(res > -1){
				val = RegExp.$1; if(val) jQuery('#mapLon').attr("value",val);
			}

			// marker tab
			res = markedMap.search(/marker\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				if(val == 'yes') {
					if(addr){
						jQuery('#mapMarkerAdr').click();
						toggleMarkerTab(document.getElementById('mapMarkerAdr'), '#address_tab');
					} else {
						jQuery('#mapMarkerPos').click();
						toggleMarkerTab(document.getElementById('mapMarkerPos'), '#position_tab');
					}
					res = markedMap.search(/infowindow\s*=\s*"([a-zA-ZåäöÅÄÖß0-9\s\/\\\:\.\+\*\$\-\(\)\?\']+)\s*"/i);
					if(res > -1){
						val = RegExp.$1; 
						if(val) jQuery('#mapInfoContent').attr("value",val);
					}
					
					res = markedMap.search(/markerimage\s*=\s*"([a-zA-ZåäöÅÄÖß0-9\s\/\/\.\-\?].+?)\s*"/i);
					if(res > -1){
						val = RegExp.$1;
						if(val) jQuery('#mapMarkerURL').attr("value",val);
					}
				}
			}
			
			// map file 
			res = markedMap.search(/kml\s*=\s*"(http:\/\/.+?\.kml)\s*"/i);
			if(res > -1){
				val = RegExp.$1;
				if(val) jQuery('#mapFile').attr("value",val);
			}
			res = markedMap.search(/gpx\s*=\s*"(http:\/\/.+?\.gpx)\s*"/i);
			if(res > -1){
				val = RegExp.$1;
				if(val) jQuery('#mapFile').attr("value",val);
			}
			res = markedMap.search(/download\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				val = RegExp.$1;
				if(val) jQuery('#mapDownload').click();
			}
			res = markedMap.search(/elevation\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				val = RegExp.$1;
				if(val)	jQuery('#mapElevation').click();
			}
			res = markedMap.search(/mtoggle\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				val = RegExp.$1;
				if(val)	jQuery('#mapMarkerToggle').click();
			}
			
			// fusion tables
			res = markedMap.search(/fusion\s*=\s*"(.+)"/i);
			if(res > -1){
				val = RegExp.$1;
				var fusion = val.split(';');
				if(fusion[0]) jQuery('#FusionTab').attr("value",fusion[0]);
				if(fusion[1]) jQuery('#FusionSelect').attr("value",fusion[1]);
				if(fusion[2]) jQuery('#FusionWhere').attr("value",fusion[2]);
			}
			
			var res = markedMap.search(/poi_db\s*=\s*"(\w+)\s*"/i);
			if(res > -1){
				var val = RegExp.$1;
				if(val){
					jQuery('#poi_db_access').attr("value",val);
					jQuery('#poi_db_switch').click();
				}
			}			
		}
	}

	function insertGMap() {
		// style tab
		var mapWidth = jQuery('#mapWidth').val();
		var mapHeight = jQuery('#mapHeight').val();
		var mapFloat = jQuery('#mapFloat').val();
		var borderSize = jQuery('#borderSize').val();

		var marginTop = jQuery('#marginTop').val();
		var marginRight = jQuery('#marginRight').val();
		var marginBottom = jQuery('#marginBottom').val();
		var marginLeft = jQuery('#marginLeft').val();
		var borderSize = jQuery('#borderSize').val();
		var borderStyle = jQuery('#borderStyle').val();
		var borderColor = jQuery('#borderColor').val();
		
		var tagtext = '[map style="';
		if(mapWidth){
			tagtext += 'width: ';
			if(isNaN(Number(mapWidth)))
				tagtext += mapWidth + '; ';
			else
				tagtext += mapWidth + 'px; ';
		}
		if(mapHeight){
			if(isNaN(Number(mapHeight)))
				tagtext += ' height:' + mapHeight + '; ';
			else
				tagtext += ' height:' + mapHeight + 'px; ';
		}
		if (mapFloat != '') 
			tagtext += ' float:' + mapFloat + ';';
		if (marginTop != '' || marginRight != '' || marginBottom != '' || marginLeft != '' ) {
			tagtext += ' margin:';
			if (marginTop != '')
				tagtext += marginTop + 'px ';
			if (marginRight != '')
				tagtext += marginRight + 'px ';
			if (marginBottom != '')
				tagtext += marginBottom + 'px ';
			if (marginLeft != '')
				tagtext += marginLeft + 'px';
			tagtext += ';';
		}
		tagtext += ' border: ' + borderSize + 'px ' + ' ' + borderStyle + ' ' + borderColor + ';';
		tagtext += '"';
		// address tab
		var mapAddress = jQuery('#mapAddress').val();
		if (mapAddress != '') 
			tagtext += ' address="' + mapAddress + '"';
		// marker tab
		var marker_is = false;
		var marker = jQuery('.mapMarker');
		marker.each(function() {
			if(this.checked)
				marker_is = true;
		});	
		if(marker_is){
			tagtext += ' marker="yes"';
			var mapInfoContent = jQuery('#mapInfoContent').val();
			if(mapInfoContent)
				tagtext += ' infowindow="' + mapInfoContent + '"';
			var mapMarkerURL = jQuery('#mapMarkerURL').val();
			if(mapMarkerURL)
				tagtext += ' markerimage="' + mapMarkerURL + '"';
		}
		// position tab
		var mapLat = jQuery('#mapLat').val();
		var mapLon = jQuery('#mapLon').val();
		if(mapLat && mapLon){ // && !mapAddress
			tagtext += ' lat="' + mapLat + '"';
			tagtext += ' lon="' + mapLon + '"';
		}
		
		// maptype tab
		var maptypetag = jQuery('#maptypetag').val();
		if(maptypetag){
			tagtext += ' maptype="' + maptypetag + '"';
		}
		var mapZoom = jQuery('#mapZoom').val();
		if(mapZoom){
			tagtext += ' z="' + mapZoom + '"';
		}
		if(jQuery('#mapBike').prop('checked')){
			tagtext += ' bike="yes"';
		}
		if(jQuery('#mapTraffic').prop('checked')){
			tagtext += ' traffic="yes"';
		}
		if(jQuery('#mapPano').prop('checked')){
			tagtext += ' pano="yes"';
		}
		var mapPanoTag = jQuery('#mapPanoTag').val();
		if(mapPanoTag){
			tagtext += ' panotag="' + mapPanoTag + '"';
		}
		
		// GPX/KML file tab
		var mapFile = jQuery('#mapFile').val();
		if(mapFile){
			if(mapFile.substr(mapFile.length - 3, 3).toLowerCase() == 'gpx')
				tagtext += ' gpx="' + mapFile + '"';
			else
				tagtext += ' kml="' + mapFile + '"';	// kmz too
				
			var mapElevation = jQuery('#mapElevation').prop('checked');
			var mapelevationProfile = jQuery('#mapelevationProfile').val();
			if((mapElevation && mapelevationProfile == ''))
				tagtext += ' elevation="yes"';	
			else if (!mapElevation && mapelevationProfile == '1')
				tagtext += ' elevation="no"';	
				
			var mapDownload = jQuery('#mapDownload').prop('checked');
			var mapdownloadLink = jQuery('#mapdownloadLink').val();
			if((mapDownload && mapdownloadLink == ''))
				tagtext += ' download="yes"';	
			else if (!mapDownload && mapdownloadLink == '1')
				tagtext += ' download="no"';	
		}
		if(jQuery('#mapMarkerToggle').prop('checked')){
			tagtext += ' mtoggle="yes"';
		}
		
		var FusionTab = jQuery('#FusionTab').val();
		if(FusionTab){
			tagtext += ' fusion="' + FusionTab ;
			var FusionSelect = jQuery('#FusionSelect').val();
			if(FusionSelect){
				tagtext += ';' + FusionSelect;
				var FusionWhere = jQuery('#FusionWhere').val();
				if(FusionWhere)
					tagtext += ';' + FusionWhere;
			}
			tagtext += '"';
		}
		
		if(jQuery('#poi_db_switch').prop('checked')){
			var poi_db_access = jQuery('#poi_db_access').val();
			tagtext += ' poi_db="' + poi_db_access + '"';
		}	
		
		tagtext += ']';

		if(window.tinyMCE) {
			if (window.tinyMCE.majorVersion >= "4") {
				window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
			} else {
				window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
			}
			tinyMCEPopup.editor.execCommand('mceRepaint');
			tinyMCEPopup.close();
		}
		return;
	}
