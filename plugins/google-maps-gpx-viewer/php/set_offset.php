<?php
/*
set_offset.php, V 1.01, altm, 12.07.2012
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
		if ($attr["x"] != 0 || $attr["y"] != 0){
			$retval .= '
			' . $map_id . '.g_offsX = ' . $attr["x"] . ';
			' . $map_id . '.g_offsY = ' . $attr["y"] . ';
			';
		}
?>