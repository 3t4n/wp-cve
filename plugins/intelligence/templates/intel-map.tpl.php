<?php

/**
 * @file
 * Default theme implementation to present a picture configured for the
 * user's account.
 *
 * Available variables:
 * - $user_picture: Image set by the user or the site's default. Will be linked
 *   depending on the viewer's permission to view the user's profile page.
 * - $account: Array of account information. Potentially unsafe. Be sure to
 *   check_plain() before use.
 *
 * @see template_preprocess_user_picture()
 *
 * @ingroup themeable
 */
?>
<?php if (!empty($script_src)) { print '<script src="' . $script_src .'"></script>'; } ?>
<script>
	var intel_map_init = intel_map_init || [];

	intel_map_init.push( function () {
		var myLatlng = new google.maps.LatLng(<?php print $locations[0]['lat']; ?>, <?php print $locations[0]['lon']; ?>);
		var mapOptions = {
			<?php if (isset($map_options['zoom'])) { print  'zoom: ' . $map_options['zoom'] . ",\n"; } ?>
			<?php if (isset($map_options['center'])) {
				print  'center: new google.maps.LatLng(' . $map_options['center']['lat'] . ', ' .  $map_options['center']['lon'] . "),\n";
			}
			else {
				print  'center: new google.maps.LatLng(' . $locations[0]['lat'] . ', ' .  $locations[0]['lon'] . "),\n";
			}
			?>
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		var map = new google.maps.Map(document.getElementById('map-canvas-<?php print $map_index ; ?>'),
				mapOptions);

		<?php if (isset($locations[0]['info'])) {
			print "var info = '" . str_replace("'", "'", $locations[0]['info']) . "';\n";
		} ?>

		var circleOptions = {
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 1,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			map: map,
			center: new google.maps.LatLng(<?php print $map_options['center']['lat']; ?>, <?php print $map_options['center']['lon']; ?>),
			radius: 50000
		};
		var locCircle = new google.maps.Circle(circleOptions);
	});

	//if (!window['_intel_googleapi_map_init']) {
		window['_intel_googleapi_map_init'] = function () {
			for (var i = 0; i < intel_map_init.length; i++) {
				google.maps.event.addDomListener(window, "load", intel_map_init[i]());
			}
		}
	//}
</script>
<div class="google-map"><div id="map-canvas-<?php print $map_index ; ?>" class="map-canvas"></div></div>

