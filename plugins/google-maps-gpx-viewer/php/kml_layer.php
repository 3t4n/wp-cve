<?php
/*
kml_layer.php, V 1.05, altm, 19.08.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License 
*/
			$kmlfile = htmlspecialchars_decode($attr['kml']);		
			$retval .= '
			' . $map_id . '.g_seCookie = false; // no cookie
			' . $map_id . '.g_showCnt++;
			var kml_' . $map_id . ' = new google.maps.KmlLayer("' . $kmlfile . '");
			kml_' . $map_id . '.preserveViewport = true;
			kml_' . $map_id . '.setMap(' . $map_id . ');
			google.maps.event.addListenerOnce(kml_' . $map_id . ', "defaultviewport_changed", function() {
				var status = kml_' . $map_id . '.getStatus();
				var bounds = kml_' . $map_id . '.getDefaultViewport();
				' . $map_id . '.gpx_kml = kml_' . $map_id . '; // save for toggle
				fitViewport(' . $map_id . ', bounds);
			});	
			';
			if($elevationProfile == "true" || $downloadLink == "true" ) {
				$retval .= '
				getKmlPath(' . $map_id . ', "' . $kmlfile . '");	';				
			}?>