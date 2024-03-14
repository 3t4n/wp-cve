<?php
/*
init_maps.php, V 1.03, altm, 20.09.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
		//full size z-index & distance unit
		if(get_option('gmapv3_disableDefaultUI')) $gmap_disableDefaultUI = "true"; else $gmap_disableDefaultUI = "false";
		if(get_option('gmapv3_zoomControl')) $gmap_zoomControl = "true"; else $gmap_zoomControl = "false";

		$retval .= '
		var fszIndex = ' . get_option('gmap_v3_gpx_fszIndex') . ';
		var distanceUnit = "' . get_option('gmap_v3_gpx_distanceUnit') . '";
		var gmapv3_disableDefaultUI = ' . $gmap_disableDefaultUI . ';
		var gmapv3_zoomControl = ' . $gmap_zoomControl . ';

		';
		// check if we have a size button
		if (strpos($_SERVER['HTTP_USER_AGENT'], "Chrome"))
		$retval .= '
		var scrollToEle = "body";';
		else
		$retval .= '
		var scrollToEle = "html";';
		if (get_option('gmap_v3_gpx_mapSizeBtn'))
		$retval .= '
		var mapSizeButton = true;';
		else
		$retval .= '
		var mapSizeButton = false;';
		// get selected maptyes from db and push them into a global js-array
		$maptypes = get_option('gmap_v3_gpx_maptypes');
		if (is_array($maptypes)){
			foreach($maptypes as $map => $obj) {
				$copy = addslashes($obj[2]);
				if(stristr($copy, 'google')) {
					$copy = "";
				}
				$visible = "true";
				if( $obj[1] == 0)
					$visible = "false";
		$retval .= '
		var mapobj = { 
			name: "' . $obj[0] . '",
			wms: "' . $obj[3] . '",
			minzoom: ' . $obj[5] . ',
			maxzoom: ' . $obj[6] . ',
			url: "' . $obj[4] . '",
			copy:"' . $copy . '",
			visible:' . $visible . '
		};
		mapTypesArr.push(mapobj);';
			}
		}
		$retval .= '
		var msg_00 = "'.__("click to full size","google-maps-gpx-viewer").'";
		var msg_01 = "'.__("IE 8 or higher is needed / switch of compatibility mode","google-maps-gpx-viewer").'";
		var msg_03 = "'.__("Distance","google-maps-gpx-viewer").'";
		var msg_04 = "'.__("Height","google-maps-gpx-viewer").'";
		var msg_05 = "'.__("Download","google-maps-gpx-viewer").'";
		var pluri = "' . WP_PLUGIN_URL."/".GPX_GM_PLUGIN. '/";
		var ieX = false;
		if (window.navigator.appName == "Microsoft Internet Explorer") {
			var err = ieX = true;
			if (document.documentMode > 7) err = false;
			if(err){
				//alert(msg_01);
			}
		}
		';

?>