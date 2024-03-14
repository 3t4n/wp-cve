<?php
/*
fusion_layer.php, V 1.01, altm, 11.07.2011
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
			$query = explode(";", html_entity_decode($attr['fusion']));
			$queryLength = count($query);
			for($i = 0; $i < $queryLength; $i++){
				$query[$i] = trim($query[$i], ' ');
			}
			$queryString = "SELECT ";
			if( $query[1] != "")
				$queryString .= $query[1] . " ";
			$queryString .= "FROM " . $query[0];
			if( $query[2] != "")
				$queryString .= ' WHERE ' . $query[2];
			if( $query[3] != "")
				$queryString .= ' ' . $query[3];
				
			$queryOption .= '{ select: "' . $query[1] . '", from: "' . $query[0] . '", where: "' . $query[2] . '" }';
 
			$retval .= '
				' . $map_id . '.g_seCookie = false; // no cookie
				var layer_' . $map_id . ' = new google.maps.FusionTablesLayer("' . $query[0] . '");
				// var layer_' . $map_id . ' = new google.maps.FusionTablesLayer(' . $queryOption . ');
				layer_' . $map_id . '.setQuery("' . $queryString . '");
				layer_' . $map_id . '.setMap(' . $map_id . ');
			';
			if($query[1] != "" || $query[2])
			$retval .= '
				' . $map_id . '.g_showCnt++;
				getQueryBounds(layer_' . $map_id . ');
			';
?>