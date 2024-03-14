<?php
/*
load_jsapi.php, V 1.06, altm, 20.09.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
	if ($instance_gmap_gpx == 0){
?>
<!-- start google maps gpx plugin api loader -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php

	$params = "sensor=false&libraries=places,panoramio"// sensor=true

	?>
<script type="text/javascript">google.load("maps", "3", {other_params:"<?php echo $params;?>"});</script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_size.js"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_gpx_overlay.js"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_wms_overlay.js"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_init.js"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>editor/gmap_v3_edit.js"></script>
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>editor/editor.css" type="text/css" />
<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>css/gmap_v3.css" type="text/css" />
<!-- end google maps gpx plugin api loader -->
<?php
	}
	if($attr['fusion'] != '' && !$gpx_gmap_visual) {
		$gpx_gmap_visual = true;
?>
<script type="text/javascript" id="script">google.load('visualization', '1');</script>
<?php
	}
	if(($attr['elevation'] != 'no' ||  $attr['download'] != 'no')  && !$gpx_gmap_elevation) {
		$gpx_gmap_elevation = true;
?>
<script type="text/javascript" id="script">google.load('visualization', '1', {packages: ['corechart']});</script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_elevation.js"></script>
<?php
	}

	// auto map id
	$map_id = sprintf('map_%d', $instance_gmap_gpx);
	$instance_gmap_gpx++;
?>