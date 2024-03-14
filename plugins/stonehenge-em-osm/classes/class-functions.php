<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists('Stonehenge_EM_OSM_Functions') ) :
Class Stonehenge_EM_OSM_Functions {

	#===============================================
	public function define_options( $sections = array() ) {
		$sections[] = array(
			'id' 		=> 'osm',
			'label'		=> __wp('Settings'),
			'fields' 	=> array(
				array(
					'id' 		=> 'intro',
					'label' 	=> '',
					'type'		=> 'info',
					'default' 	=> __('This plugin completely replaces the built-in Google Maps with open source OpenStreetMap.', $this->text) .'<br>'.
__('It uses OpenCage for geocoding.', $this->text),
				),
				array(
					'id' 		=> 'api',
					'label' 	=> __('OpenCage API Key', $this->text),
					'type' 		=> 'text',
					'required' 	=> true,
					'size'		=> 'regular-text',
					'after' 	=> sprintf( '<a href=%s target="_blank">%s</a>', 'https://opencagedata.com/pricing', '<button type="button" class="button-secondary">'. __('Get your free key', $this->text) .'</button>'),
					'helper' 	=> __('A free OpenCage API Key has a daily limit of 2500 calls per day.', $this->text) .'<br>'. __('This plugin only calls the OpenCage API when you click the "Search Address" button.', $this->text),
				),
				array(
					'id' 		=> 'zoom',
					'label' 	=> __('Default Zoom Level', $this->text),
					'type' 		=> 'number',
					'required'	=> true,
					'min' 		=> '0',
					'max' 		=> '19',
					'default'	=> '15',
					'helper' 	=> __('Enter a number between 1 and 19. (Default is 15)', $this->text),
				),
				array(
					'id' 		=> 'showlevel',
					'label' 	=> __('Show Zoom Level', $this->text),
					'type' 		=> 'toggle',
					'default' 	=> 'no',
					'required' 	=> true,
				),
				array(
					'id' 		=> 'zoomcontrols',
					'label' 	=> __('Show Zoom Controls', $this->text),
					'type' 		=> 'toggle',
					'default' 	=> 'no',
					'required' 	=> true,
				),
				array(
					'id' 		=> 'fullscreen',
					'label' 	=> __('Show Fullscreen', $this->text),
					'type' 		=> 'toggle',
					'default' 	=> 'no',
					'required' 	=> true,
				),
				array(
					'id' 		=> 'scale',
					'label' 	=> __('Show Scale', $this->text),
					'type' 		=> 'toggle',
					'default' 	=> 'no',
					'required' 	=> true,
				),
				array(
					'id' 		=> 'marker',
					'label'		=> __('Default Marker Color', $this->text),
					'required' 	=> true,
					'type' 		=> 'select',
					'choices' 	=> $this->marker_color_options(),
					'helper' 	=> sprintf( __('The default marker color will be used as a fallback, if not set in the <a href=%s>%s</a>.', $this->text), admin_url('edit.php?post_type=location'), __('Edit Location Page', $this->text) ) .'<br>' . sprintf( __('You can target this output using the <code>%s</code> filter.', $this->text), 'em_osm_default_marker' ),
				),
				array(
					'id' 		=> 'type',
					'label'		=> __('Default Map Style', $this->text),
					'type' 		=> 'select',
					'choices' 	=> $this->map_type_options(),
					'helper' 	=> sprintf( __('This map style will be shown on %s and %s. It will also be used as a fallback for single location maps using %s.', $this->text), '<code>[locations_map]</code>', '<code>[events_map]</code>', '<code>#_LOCATIONMAP</code>') .'<br>' . sprintf( __('You can target this output using the <code>%s</code> filter.', $this->text), 'em_osm_default_tiles' ),
					'required' 	=> true,
				),
				array(
					'id' 		=> 'per_location',
					'label' 	=> __('Enable per Location', $this->text),
					'type' 		=> 'toggle',
					'default' 	=> 'no',
					'helper' 	=> sprintf( __('If set to "Yes", you can set the marker color and map style per location in the <a href=%s>Edit Location Page</a> or when creating a new location in the <a href=%s>Edit Event Page</a>.', $this->text), admin_url('edit.php?post_type=location'), admin_url('edit.php?post_type=event') )  .'<br>' . sprintf( __('If set to "Yes", you can target this output using the %s and %s filters.', $this->text), '<code>em_osm_location_tiles</code>', '<code>em_osm_location_marker</code>' ),
					'required' 	=> true,
				),
				array(
					'id' 		=> 'per_admin',
					'label' 	=> __('Back-End only', $this->text),
					'type' 		=> 'toggle',
					'default'	=> 'yes',
					'helper' 	=> __('If set to "Yes", the marker color and map style options will <u>not</u> be shown in front-end submission forms.', $this->text),
				),
				array(
					'id' 		=> 'delete',
					'label' 	=> __('Delete Data', $this->text),
					'type' 		=> 'toggle',
					'required'	=> true,
					'helper' 	=> __('Automatically delete all data from your database when you uninstall this plugin?', $this->text),
					'default' 	=> 'yes',
				),
			),
		);
		return $sections;
	}


	#===============================================
	public function get_default_maptiles() {
		$options 	= $this->plugin['options'];
		$maptiles	= $options['type'] ?? '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		$maptiles	= apply_filters( 'em_osm_default_tiles', $maptiles );
		return $maptiles;
	}


	#===============================================
	public function get_default_marker() {
		$options 	= $this->plugin['options'];
		$marker 	= $options['marker'] ?? 'blue';
		$marker 	= sanitize_text_field( $marker );
		$marker 	= apply_filters( 'em_osm_default_marker', $marker );

		if( is_array($marker) ) {
			$marker = wp_parse_args( $marker, $this->icon_defaults );
			$marker = array_map( 'sanitize_text_field', $marker );
			$marker = implode( '|', $marker );
		}
		return $marker;
	}


	#===============================================
	public function get_location_tiles( $EM_Location ) {

		$maptiles = $this->default_tiles;
		if( $this->per_location() ) {
			$meta 		= get_post_meta( $EM_Location->post_id, '_location_map_type', true);
			$maptiles	= isset($meta) && !empty($meta) ? $meta : $this->default_tiles;
			$maptiles	= apply_filters( 'em_osm_location_tiles' , $maptiles, (int) @$EM_Location->location_id );
		}
		return $maptiles;
	}


	#===============================================
	public function get_location_marker( $EM_Location ) {
		$marker = $this->default_marker;
		if( $this->per_location() ) {
			$meta 		= get_post_meta( $EM_Location->post_id, '_location_marker_color', true);
			$marker		= isset($meta) && !empty($meta) ? $meta : $this->default_marker;
			$marker 	= sanitize_text_field( $marker );
			$marker		= apply_filters('em_osm_location_marker', $marker, (int) @$EM_Location->location_id);

			if( is_array($marker) ) {
				$marker = wp_parse_args( $marker, $this->icon_defaults );
				$marker = array_map( 'sanitize_text_field', $marker );
				$marker = implode( '|', $marker );
			}
		}

		// Since 4.2.0. loading FrontAwesome conditionally.
		// Do this outside "per location" to allow default marker filter.
		if( strpos($marker, '|') !== false ) {
			$this->load_fontawesome();
		}

		return $marker;
	}


	#===============================================
	public function per_location() {
		$options = $this->plugin['options'];
		$per_location = isset($options['per_location']) && ($options['per_location'] != 'no') ? true : false;
		return $per_location;
	}


	#===============================================
	public function admin_only() {
		$options 	= $this->plugin['options'];
		$admin_only = isset($options['per_admin']) && ($options['per_admin'] != 'no') ? true : false;
		$is_admin 	= (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false) ? true : false;

		if( $this->per_location() ) {
			if( $admin_only ) {
				$show_this = $is_admin ? true : false;
				return $show_this;
			}
			$show_this = true;
			return $show_this;
		}
		return false;
	}


	#===============================================
	public function replace_em_templates($located, $template_name, $load, $the_args) {
		// Edit Event.
		if( $template_name === 'forms/event/location.php') {
			$located = stonehenge()->get_template( 'edit-event.php', $this->plugin['base'] );
		}
		// Edit Location.
		if( $template_name === 'forms/location/where.php') {
			$located = stonehenge()->get_template( 'edit-location.php', $this->plugin['base'] );
		}
		return $located;
	}


	#===============================================
	public function show_hidden_fields( $EM_Location ) {
		$filter_map 	= $this->get_location_tiles( $EM_Location );
		$filter_marker 	= $this->get_location_marker( $EM_Location );

		ob_start();
		?>
		<div id="osm-location-info" style="display:none;">
<input type="text" size="10" id="location-id" name="location_id" value="<?php echo esc_attr($EM_Location->location_id, ENT_QUOTES); ?>" readonly>
<input type="text" size="10" id="location-latitude" name="location_latitude" value="<?php echo esc_attr($EM_Location->location_latitude, ENT_QUOTES); ?>" readonly>
<input type="text" size="10" id="location-longitude" name="location_longitude" value="<?php echo esc_attr($EM_Location->location_longitude, ENT_QUOTES); ?>" readonly>
<input type="text" size="10" id="location-marker" value="<?php echo esc_attr($filter_marker, ENT_QUOTES); ?>" readonly>
<input type="text" size="10" id="location-map" value="<?php echo esc_attr($filter_map, ENT_QUOTES); ?>" readonly>
	</div>
		<?php
		$fields = ob_get_clean();
		return $fields;
	}


	#===============================================
	public function marker_color_options() {
		$choices 	= array(
			'blue' 		=> __('Blue', $this->text),
			'red' 		=> __('Red', $this->text),
			'green' 	=> __('Green', $this->text),
			'orange' 	=> __('Orange', $this->text),
			'yellow' 	=> __('Yellow', $this->text),
			'violet' 	=> __('Purple', $this->text),
			'grey' 		=> __('Grey', $this->text),
			'black' 	=> __('Black', $this->text),
		);
		return $choices;
	}


	#===============================================
	public function map_type_options() {
		$choices 	= array(
			'//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png' 	=> 'OpenStreetMap',
			'//{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png' => 'OpenStreetMap HOT',
			'//server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}' => 'ArcGIS WorldMap',
			'//server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}' => 'ArcGIS TopoMap',
			'//server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}' => 'ArcGIS World Imagery',
			'//{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png' => 'Hydda (max zoom = 18)',
			'//maps.heigit.org/openmapsurfer/tiles/roads/webmercator/{z}/{x}/{y}.png' => 'OpenMapSurfer Roads',
			'//maps.wikimedia.org/osm-intl/{z}/{x}/{y}{r}.png' => 'Wikimedia',
			'//stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.png' => 'Stamen Toner',
			'//stamen-tiles-{s}.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}.png' => 'Stamen Toner Lite',
			'//stamen-tiles-{s}.a.ssl.fastly.net/terrain/{z}/{x}/{y}.jpg' => 'Stamen Terrain',
			'//stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg' => 'Stamen Watercolor',
		);
		return $choices;
	}


	#===============================================
	public function show_per_location_select_dropdowns( $EM_Location ) {
		$filter_map 	= $this->get_location_tiles( $EM_Location );
		$filter_marker 	= $this->get_location_marker( $EM_Location );

		if( $this->admin_only() ) {
			$this->text 		= $this->plugin['text'];
			$fields 	= array(
				'marker' => array(
					'id' 		=> 'location_marker_color',
					'label' 	=> __('Marker Color', $this->text),
					'choices'	=> $this->marker_color_options(),
				),
				'maptype' => array(
					'id' 		=> 'location_map_type',
					'label' 	=> __('Map Type', $this->text),
					'choices'	=> $this->map_type_options(),
				),
			);
			foreach( $fields as $field ) {
				$saved 		= get_post_meta( $EM_Location->post_id, "_{$field['id']}", true );
				$filtered 	= $field['id'] != 'location_marker_color' ? $filter_map : $filter_marker;
				?>
				<tr class="osm-<?php echo esc_attr($field['id'], ENT_QUOTES); ?>">
					<th><?php echo esc_html($field['label']); ?>:</th>
					<td><select name="_<?php echo esc_attr($field['id'], ENT_QUOTES); ?>" id="<?php echo esc_attr($field['id'], ENT_QUOTES); ?>">
						<option value="" selected>- <?php echo esc_attr__('Default'); ?> -</option>
						<?php
						foreach( $field['choices'] as $choice => $value ) {
							$selected = $checked = '';
							if( array_key_exists($filtered, $field['choices']) ) {
								$selected = ($saved != $choice) ? '' : ' selected';
							} else {
								$checked = 'selected';
							}
							echo "<option value='{$choice}' {$selected}>{$value}</option>";
						}
						echo "<option {$checked} disabled>" . __('Filter Applied', $this->text) ."</option>";
						?>
					</select></td>
				</tr>
				<?php
			}
		}
		return;
	}


	#===============================================
	public function save_maps_per_location( $count, $EM_Location ) {
		global $wpdb;
		$table 			= EM_LOCATIONS_TABLE;
		$post_id 		= $EM_Location->post_id;

		if( isset($_POST['_location_marker_color']) ) {
			$marker = sanitize_text_field( $_POST['_location_marker_color'] );
			update_post_meta( $post_id, '_location_marker_color', $marker);
			$wpdb->query( "UPDATE `{$table}` SET `location_marker` = '{$marker}' WHERE `post_id` = '{$post_id}'" );

		}
		if( isset($_POST['_location_map_type']) ) {
			$map = sanitize_text_field( $_POST['_location_map_type'] );
			update_post_meta( $post_id, '_location_map_type', $map);
			$wpdb->query( "UPDATE {$table} SET `location_map_type` = '{$map}' WHERE `post_id` = '{$post_id}'" );
		}
		return $count;
	}


	#===============================================
	public function show_search_tip() {
		$this->text = $this->plugin['text'];
		$options 	= $this->plugin['options'];

		if( !isset($options['api']) || empty($options['api']) ) {
			$return = '<p style="color:#e14d43; font-weight: bold;">'. sprintf( __('Please enter your OpenCage API Key in your <a href=%s>Plugin Settings</a>.', $this->text), $this->plugin['url'] ) .'</p>';

		}
		else {
			$button 	= '<button type="button" class="button button-secondary" onClick="apiSearch()">'. esc_html__('Search Address', $this->text) .'</button>';
			$expl 		= esc_html__('Hint', $this->text);
			$tooltip 	= esc_html__('If your location cannot be found, search for one nearby.', $this->text) .'<br>'. esc_html__('After the marker has been set, you can drag the marker to the preferred position and manually change the address details in the location form fields.', $this->text);
			$return 	= sprintf( '<div id="osm-search-tip" class="description"><span>%s %s</span></div>%s',
				esc_attr__('The more details you provide, the more accurate the search result will be.', $this->text),
				'<span class="osm-tooltip">('. $expl .')<span class="osm-tooltiptext">'. $tooltip .'</span></span>', $button
			);
		}
		return $return;
	}

} // End class.
endif;

