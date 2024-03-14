<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if(!class_exists('Stonehenge_EM_OSM_Maps') ) :
class Stonehenge_EM_OSM_Maps extends Stonehenge_EM_OSM_Customize {


	#===============================================
	public function replace_location_map_placeholder( $replacement, $input, $placeholder ) {
		// $input is used so both $EM_Location and $EM_Event can be processed.
		if( $placeholder === '#_LOCATIONMAP' ) {
			$replacement = ($input->location_id) ? $this->single_map($input->location_id) : '';
		}
		return $replacement;
	}


	#===============================================
	public function map_settings() {
		$text 			= $this->plugin['text'];
		$options 		= $this->plugin['options'];
		$showZoom 		= isset($options['showlevel']) && $options['showlevel'] != 'no' ? 'true' : 'false';
		$zoomControls	= isset($options['zoomcontrols']) && $options['zoomcontrols'] != 'no' ? 'true' : 'false';
		$showFullscreen	= isset($options['fullscreen']) && $options['fullscreen'] != 'no' ? 'yes' : 'no';
		$showScale 		= isset($options['scale']) && $options['scale'] != 'no' ? 'yes' : 'no';

		ob_start();
		?><script>
		// Check for Mobile
		if(L.Browser.mobile) {
			var mobileDrag = false;
			var mobileZoom = false;
		}
		else {
			var mobileDrag = true;
			var mobileZoom = true;
		}

		// Set general options.
		var pluginUrl 		= '<?php echo plugins_url('assets/images/marker-icon-2x-', __DIR__); ?>',
			shadowUrl		= '<?php echo plugins_url('assets/images/marker-shadow.png', __DIR__); ?>',
			zoomLevel 		= <?php echo (int) $options['zoom']; ?>,
			zoomButtons 	= <?php echo esc_attr($zoomControls); ?>,
			mapOptions 		= {
				zoom: zoomLevel,
				zoomSnap: 0.25,
				zoomControl: zoomButtons,
				zoomDisplayControl: <?php echo $showZoom; ?>,
				scrollWheelZoom: mobileZoom,
				dragging: mobileDrag,
			},
			mapIcon 		= new L.Icon({});
			LeafIcon 		= L.Icon.extend({
				options: {
				    iconSize: [25, 41],
				    iconAnchor: [12, 41],
				    popupAnchor: [1, -40],
				    shadowSize: [41, 41],
				    shadowUrl: '<?php echo plugins_url('assets/images/marker-shadow.png', __DIR__); ?>',
				}
			}),
			showFullscreen 	= '<?php echo esc_attr($showFullscreen); ?>',
			showScale 		= '<?php echo esc_attr($showScale); ?>';
		</script><?php
		$output = ob_get_clean();
		$output = stonehenge()->minify_js($output);
		return $output;
	}


	#===============================================
	public function admin_map( $EM_Location ) {
		$text 		= $this->plugin['text'];
		$options 	= $this->plugin['options'];
		$locale 	= strtolower(substr( get_bloginfo ( 'language' ), 0, 2 ));
		$balloon 	= !empty($EM_Location->location_id) ? $EM_Location->output("<strong>#_LOCATIONNAME</strong><br>#_LOCATIONADDRESS, #_LOCATIONTOWN") : ucwords( esc_html__('no default location', 'events-manager'));

		if( empty($options['api']) ) {
			return __('OpenCage API key is missing. Please enter it in your EM - OSM settings.', $text);
		}

		ob_start();
		?>
		<div id="em-osm-admin-map-container" class="osm-location-map-container">
			<link rel="stylesheet" href="<?php echo plugins_url('assets/public-em-osm.min.css', __DIR__); ?>">
			<script src="<?php echo plugins_url('assets/public-em-osm.min.js', __DIR__); ?>"></script>
			<div id="em-osm-map" style="height:300px; width:400px; max-width: 95%;"></div>
			<?php echo $this->map_settings(); ?>
			<script>
				var balloon 	= '<?php echo html_entity_decode( esc_js($balloon, ENT_QUOTES) ); ?>',
					Lat 		= jQuery('#location-latitude').val(),
					Lng			= jQuery('#location-longitude').val(),
					mapUrl	 	= jQuery('#location-map').val();
					thisMarker 	= jQuery('#location-marker').val(),
					map 		= L.map('em-osm-map', mapOptions );
				if( mapUrl.indexOf("stamen") >= 0 ) { setMaxZoom = 18; setNatZoom = 16; }
				else { setMaxZoom = 20; setNatZoom = 18; }

				map.setView([Lat, Lng]);

				L.tileLayer( mapUrl, {
					attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\">OpenStreetMap</a>',
					reuseTiles: true,
					detectRetina: true,
					minZoom: 1,
					maxZoom: L.Browser.retina ? setMaxZoom : setMaxZoom - 1,
					maxNativeZoom: L.Browser.retina ? setNatZoom : setNatZoom + 1
				}).addTo(map);

				if( !jQuery('#location-id').val() ) {
					map.setView([Lat, Lng], 1);
				}
				if( thisMarker.indexOf("|") >= 0 ) {
					customMarker = thisMarker.split('|');
					thisIcon = L.ExtraMarkers.icon({
						shape: customMarker[0],
						markerColor: customMarker[1],
						icon: customMarker[2],
						iconColor: customMarker[3],
						prefix: customMarker[4]
					});
				} else { thisIcon = new LeafIcon({iconUrl: pluginUrl + thisMarker + '.png', shadowUrl: '<?php echo plugins_url('assets/images/marker-shadow.png', __DIR__); ?>',
}); }

				marker = L.marker([Lat, Lng], {icon: thisIcon}).addTo(map).bindPopup(balloon).openPopup();

				if( showFullscreen == 'yes' ) 	{ map.addControl(new L.Control.Fullscreen({ position: 'topright', })); }
				if( showScale == 'yes' ) 		{ L.control.scale().addTo(map); }
				setTimeout(function(){ map.invalidateSize()}, 400);

			</script>
		</div>
		<?php
		$output = ob_get_clean();
		$output = stonehenge()->minify_js($output);
		return $output;
	}


	#===============================================
	public function single_map( $location_id ) {
		global $EM_Event, $EM_Location;
		$options 		= $this->plugin['options'];
		$EM_Location 	= new EM_Location( $location_id );
		$post_id 		= $EM_Location->post_id;

		// Set Map Size.
		$width 			= get_option('dbem_map_default_width') ?? '400px';
		$width 			= preg_match('/(px)|%/', $width) ? $width : $width.'px';
		$height 		= get_option('dbem_map_default_height') ?? '300px';
		$height 		= preg_match('/(px)|%/', $height) ? $height:$height.'px';

		// Get the right information of the correct Object.
		if( is_object( $EM_Event ) ) {
			$id 			= 'L' . $EM_Event->location_id . 'E' . $EM_Event->event_id;
			$balloon 		= trim(preg_replace('/\s\s+/', '<br>', get_option('dbem_location_baloon_format')));
			$balloon 		= $EM_Event->output($balloon);
		} else {
			$id 			= 'L' . $EM_Location->location_id;
			$balloon 		= trim(preg_replace('/\s\s+/', '<br>', get_option('dbem_map_text_format')));
			$balloon 		= $EM_Location->output($balloon);
		}
		$balloon 		= addslashes($balloon);
		$latitude		= $EM_Location->output("#_LOCATIONLATITUDE");
		$longitude		= $EM_Location->output("#_LOCATIONLONGITUDE");
		$tiles			= $this->get_location_tiles( $EM_Location );
		$marker 		= $this->get_location_marker( $EM_Location ) ;

		// Start output.
		ob_start();
		?>
		<div id="em-osm-single-map-container-<?php echo esc_attr($id); ?>" class="em-osm-container">
			<link rel="stylesheet" href="<?php echo plugins_url('assets/public-em-osm.min.css', __DIR__); ?>">
			<script src="<?php echo plugins_url('assets/public-em-osm.min.js', __DIR__); ?>"></script>
			<div id="map<?php echo esc_attr($id); ?>" class="em-osm-map" style="width: <?php echo esc_attr($width); ?>; height: <?php echo esc_attr($height); ?>;"></div>
			<?php echo $this->map_settings(); ?>
			<script>
				var	Lat 		= <?php echo esc_attr($latitude); ?>,
					Lng 		= <?php echo esc_attr($longitude);?>,
					thisMarker 	= '<?php echo $marker; ?>',
					thisMapTile = '<?php echo esc_attr($tiles); ?>',
					thisMap 	= L.map('map<?php echo esc_attr($id); ?>', mapOptions );

				if( thisMapTile.indexOf("stamen") >= 0 ) { setMaxZoom = 18; setNatZoom = 16; }
				else { setMaxZoom = 20; setNatZoom = 18; }

				thisMap.setView([Lat, Lng]);

				L.tileLayer( thisMapTile, {
					attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\">OpenStreetMap</a>',
					reuseTiles: true,
					detectRetina: true,
					minZoom: 1,
					maxZoom: L.Browser.retina ? setMaxZoom : setMaxZoom - 1,
					maxNativeZoom: L.Browser.retina ? setNatZoom : setNatZoom + 1
					}).addTo(thisMap);

				if( thisMarker.indexOf("|") >= 0 ) {
					customMarker = thisMarker.split('|');
					thisIcon = L.ExtraMarkers.icon({
						shape: customMarker[0],
						markerColor: customMarker[1],
						icon: customMarker[2],
						iconColor: customMarker[3],
						prefix: customMarker[4]
					});
				} else { thisIcon = new LeafIcon({iconUrl: pluginUrl + thisMarker + '.png', shadowUrl: '<?php echo plugins_url('assets/images/marker-shadow.png', __DIR__); ?>',
}); }

				marker = L.marker([Lat, Lng], {icon: thisIcon}).addTo(thisMap).bindPopup('<?php echo $balloon; ?>').openPopup().dragging.disable();

				if( showFullscreen == 'yes' ) 	{ thisMap.addControl(new L.Control.Fullscreen({ position: 'topright', })); }
				if( showScale == 'yes' ) 		{ L.control.scale().addTo(thisMap); }

				setTimeout(function(){ thisMap.invalidateSize()}, 400);
			</script>
		</div>
		<?php
		$output = ob_get_clean();
		$output = stonehenge()->minify_js($output);
		return $output;
	}


	#===============================================
	public function locations_map( $args ) {
		// Create fallback to prevent errors.
		if(empty($args)) {
			$args = array();
		}

		if( isset($args['country']) ) {
			$args['country'] = strtoupper($args['country']);
		}

		// Start fetching Locations from the Database.
		$EM_Locations 	= EM_Locations::get( $args, $count = false );
		$location_ids 	= array();
		foreach( $EM_Locations as $EM_Location ) {
			$location_ids[] = $EM_Location->location_id;
		}

		// Clean the array.
		$location_ids 	= array_unique( $location_ids );

		if( count( (array) $location_ids) === 0 ) {
			echo '<p><em>'. __em('No Locations Found') .'.</em></p>';
			return;
		}

		$map = $this->multiple_map( $location_ids, $args );
		return $map;
	}


	#===============================================
	public function events_map( $args ) {
		// Create fallback to prevent errors.
		if(empty($args)) {
			$args = array();
		}

		if( isset($args['country']) ) {
			$args['country'] = strtoupper($args['country']);
		}

		$EM_Events 		= EM_Events::get( $args, $count = false );
		$location_ids 	= array();
		foreach( $EM_Events as $EM_Event ) {
			// Filter out Events without  location.
			if( 0 != (int) $EM_Event->location_id ) {
				$location_ids[] = $EM_Event->location_id;
			}
		}

		// Clean the array.
		$location_ids 	= array_unique( $location_ids );

		// Is there anything to process?
		if( count( (array) $location_ids) === 0 ) {
			echo '<p><em>'. __em('No Events Found') .'.</em></p>';
			return;
		}

		$map = $this->multiple_map( $location_ids, $args );
		return $map;
	}


	#===============================================
	public function multiple_map( $location_ids, $args ) {
		$options 		= $this->plugin['options'];

		// Set Map Size.
		$width 			= isset($args['width']) && !empty($args['width']) ? $args['width'] :get_option('dbem_map_default_width');
		$width 			= preg_match('/(px)|%/', $width) ? $width : $width.'px';
		$height 		= isset($args['height']) && !empty($args['height']) ? $args['height'] : get_option('dbem_map_default_height');
		$height 		= preg_match('/(px)|%/', $height) ? $height : $height.'px';
		$padding 		= isset($args['padding']) ? (int) $args['padding'] : 10;

		// Create an unique ID to allow multiple maps per page.
		$id 			= rand(1,100);

		// Always use default MapTile for Multiple Locations Maps.
		$this_tiles		= $this->default_tiles;

		// Process result to prepare for the map.
		$marker 		= array();
		$lats 			= array();
		$lngs 			= array();

		foreach( $location_ids as $location_id ) {
			$EM_Location 	= new EM_Location( $location_id );
			$balloon 		= trim(preg_replace('/\s\s+/', '<br>', get_option('dbem_map_text_format')));
			$balloon 		= addslashes( $EM_Location->output($balloon) );
			$latitude		= $EM_Location->output("#_LOCATIONLATITUDE");
			$longitude		= $EM_Location->output("#_LOCATIONLONGITUDE");
			$lats[]			= $EM_Location->output("#_LOCATIONLATITUDE");
			$lngs[]			= $EM_Location->output("#_LOCATIONLONGITUDE");
			$marker 		= $this->get_location_marker( $EM_Location );
			$markers[] 		= "[\"{$balloon}\", {$latitude}, {$longitude}, '{$marker}']";
		}
		$locations 	= implode(", ", $markers);
		$high_lat 	= max($lats);
		$high_lng 	= max($lngs);
		$low_lat 	= min($lats);
		$low_lng 	= min($lngs);
		$avg_lat 	= array_sum($lats)/count($lats);
		$avg_lng 	= array_sum($lngs)/count($lngs);

		$mapbounds 	= (count( (array) $location_ids) === 1) ? sprintf('setView([%1$s, %2$s], %3$s);', esc_attr($high_lat), esc_attr($high_lng), (int) $options['zoom']) : sprintf('fitBounds([[%1$s, %2$s], [%3$s, %4$s]], {padding: [%5$s, %5$s]});', esc_attr($high_lat), esc_attr($high_lng), esc_attr($low_lat), esc_attr($low_lng), $padding);

		// Start the output.
		ob_start();
		?>
		<div id="em-osm-locations-map-container-<?php echo $id; ?>" class="em-osm-container">
			<link rel="stylesheet" href="<?php echo plugins_url('assets/public-em-osm.min.css', __DIR__); ?>">
			<script src="<?php echo plugins_url('assets/public-em-osm.min.js', __DIR__); ?>"></script>
			<div id="em-osm-map-<?php echo $id; ?>" class="em-osm-map-multiple" style="width: <?php echo $width;?>; height: <?php echo $height; ?>;"></div>
			<?php echo $this->map_settings(); ?>
			<script>
				var locations 	= [<?php echo $locations; ?>],
					thisMapTile = '<?php echo esc_attr($this_tiles); ?>',
					thisMap 	= L.map('em-osm-map-<?php echo esc_attr($id); ?>', mapOptions );
					shadowUrl 	= '<?php echo plugins_url('assets/images/marker-shadow.png', __DIR__); ?>';


				if( thisMapTile.indexOf("stamen") >= 0 ) { setMaxZoom = 18; setNatZoom = 16; }
				else { setMaxZoom = 20; setNatZoom = 18; }

				thisMap.setView([<?php echo esc_attr($avg_lat); ?>, <?php echo esc_attr($avg_lng); ?>]);

				L.tileLayer( thisMapTile, {
					attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\">OpenStreetMap</a>',
					reuseTiles: true,
					detectRetina: true,
					minZoom: 1,
					maxZoom: L.Browser.retina ? setMaxZoom : setMaxZoom - 1,
					maxNativeZoom: L.Browser.retina ? setNatZoom : setNatZoom + 1
					}).addTo(thisMap);

				thisMap.<?php echo $mapbounds; ?>;

				for (var i = 0; i < locations.length; i++) {
					if( locations[i][3].indexOf("|") >= 0 ) {
						customMarker = locations[i][3].split('|');
							thisIcon = L.ExtraMarkers.icon({
								shape: customMarker[0],
								markerColor: customMarker[1],
								icon: customMarker[2],
								iconColor: customMarker[3],
								prefix: customMarker[4],
							});
						} else {
							thisIcon = new LeafIcon({iconUrl: pluginUrl + locations[i][3] + '.png', shadowUrl: shadowUrl});
						}
					var	marker = new L.marker([locations[i][1],locations[i][2]], {icon: thisIcon}).addTo(thisMap).bindPopup(locations[i][0]).dragging.disable();
				}

				function clickZoom(e) {
					thisMap.setView(e.target.getLatLng(),zoomLevel);
				}

				if( showFullscreen == 'yes' ) 	{ thisMap.addControl(new L.Control.Fullscreen({ position: 'topright' })); }
				if( showScale == 'yes' ) 		{ L.control.scale().addTo(thisMap); }

				setTimeout(function(){ thisMap.invalidateSize()}, 400);
			</script>
		</div>
		<?php
		$script = ob_get_clean();
		$script = stonehenge()->minify_js($script);
		return $script;
	}

} // End class.

endif;