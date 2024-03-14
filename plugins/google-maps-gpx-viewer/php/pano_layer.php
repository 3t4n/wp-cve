<?php
/*
pano_layer.php, V 1.01, altm, 14.09.2012
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/

			$retval .= '
				var ' . $map_id . '_panoramioLayer = new google.maps.panoramio.PanoramioLayer(';
			if($attr['panotag'] != ''){
				$val = $attr['panotag'];
				if((int)$val)
					$retval .= '{userId:"' . $val . '"}';
				else
					$retval .= '{tag:"' . $val . '"}';
			}
			$retval .= ');
				
				' . $map_id . '_panoramioLayer.setMap(' . $map_id . ');
			';
?>