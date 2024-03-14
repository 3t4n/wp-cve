<?php
/*
adr_layer.php, V 1.05, altm, 31.1.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
			$retval .= '
			geocoder = new google.maps.Geocoder();
			var address = "' . $attr['address'] . '";
			' . $map_id . '.g_showCnt++;
			' . $map_id . '.g_seCookie = false; // no cookie
			geocoder.geocode( { "address": address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					var bounds = results[0].geometry.bounds;
					if(!bounds) { //try location
						bounds = new google.maps.LatLngBounds(results[0].geometry.location, results[0].geometry.location);
					}
					fitViewport(' . $map_id . ', bounds);
			';
			if ($attr['marker'] !='')
			{
				$markerPosition = 'results[0].geometry.location';
				require( dirname(__FILE__) . "/marker_layer.php");
			}
			$retval .= '
				} else {
					/* ' . $map_id . '.g_showCnt--; */
					fitViewport(' . $map_id . ', null);
					alert("'.__("Geocoder failed due: ","google-maps-gpx-viewer").'" + status);
				}	
			';
			$retval .= '
			});
			';
?>