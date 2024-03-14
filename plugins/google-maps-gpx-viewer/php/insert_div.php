<?php
/*
insert_div.php, V 1.02, altm, 17.05.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
	$parts = explode(";", $attr['style']);
	$partsLength = count($parts);
	$mapStyle = "";
	$mapCore =  "";
	for($i = 0; $i < $partsLength; $i++){
		$attrs = explode(":", $parts[$i]);
		$sel = strtolower( trim($attrs[0]));
		if($sel == 'border' || $sel == 'border-radius' || $sel == 'box-shadow'){
			$mapCore .= $parts[$i] . ";";
			continue;
		} else if ($sel != '')
			$mapStyle .= $parts[$i]. ";";
			
		if($sel == 'width'){
			if (strpos($parts[$i], "%") === false) {
				$mapCore .= $parts[$i] . ";";
			} else {
				$mapCore .= "$sel:100%;";
			}
		}
		else if($sel == 'height')
				$mapCore .= $parts[$i] . ";";
	}
		
	// the map object
	$retval = '
	<div class="gm_gpx_body" id="holder_' . $map_id . '" style="' . $mapStyle . '">
	<div class="google_map_holder" id="' . $map_id . '" style="' . $mapCore . '"></div></div>';
?>