<?php
/*
Plugin Name: Nearby Map by Wabeo
Plugin URI: http://nearbymap.wabeo.fr
Description: Allow to build a map to show the activities, places and services around a given geographical point.
Version: 0.9.3
Author: Willy Bahuaud
Author URI: http://wabeo.fr
License: GPLv2
TextDomain: nbm
DomainPath: /l
*/

DEFINE( 'NBM_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );

/**
LOAD LEAFLET
* @uses nbm_load_leaflet FUNCTION to register scripts and load styles
* @uses nbm_load_ie_styles FUNCTION to print script only for ie
* @uses nbm_lang_init FUNCTION to load language files
*/
function nbm_load_leaflet() {
	wp_register_script( 'leaflet', NBM_PLUGIN_URL . '/leaflet/leaflet-src.js', false, '0.5.1', true );
	wp_register_script( 'leaflet-animated-marker', NBM_PLUGIN_URL . '/leaflet/AnimatedMarker.min.js', array('leaflet'), '0.5.1', true );
	wp_register_script( 'leaflet-script', NBM_PLUGIN_URL . '/j/leaflet-script.js', array('leaflet', 'leaflet-animated-marker', 'jquery' ), '1', true );

	wp_register_style( 'leaflet', NBM_PLUGIN_URL . '/leaflet/leaflet.css', false, '0.5.1', 'all');
	wp_enqueue_style( 'leaflet' );

	wp_register_style( 'maps-style', NBM_PLUGIN_URL . '/maps.css', array('leaflet'), '1', 'all');
	wp_enqueue_style( 'maps-style' );
}
add_action( 'wp_enqueue_scripts', 'nbm_load_leaflet', 10 );

function nbm_load_ie_styles() {
	echo '<!--[if lte IE 8]>
    <link rel="stylesheet" href="' . NBM_PLUGIN_URL . '/leaflet/leaflet.ie.css" />
<![endif]-->';
}
add_action( 'wp_head', 'nbm_load_ie_styles', 10 );

function nbm_lang_init() {
	load_plugin_textdomain( 'nbm', false, basename( dirname( __FILE__ ) ) . '/l/' );
}
add_action( 'init', 'nbm_lang_init' );

/**
CREATE PLACES
* @uses nbm_register_places FUNCTION to register the CPT

* @uses nbm_post_type FILTER HOOK to target an existing post type instead creating one
* @uses places_args FILTER HOOK to modify places property 
*/
function nbm_register_places() {
	if( apply_filters( 'nbm_post_type', 'places' ) == 'places' ) {
		$places_args = array(
			'label' => __( 'Places', 'nbm' ),
			'labels' => array(
				'name'          => __( 'Places', 'nbm' ),
				'singular_name' => __( 'Place', 'nbm' ),
				'all_items'		=> __( 'All places', 'nbm' ),
				'add_new'       => __( 'Add place', 'nbm' ),
				'add_new_item'  => __( 'Add a new place', 'nbm' ),
				'edit_item'     => __( 'Edit place', 'nbm' ),
				'new_item'      => __( 'Add a place', 'nbm' ),
				'view_item'     => __( 'View place', 'nbm' )
				),
			'public' 	=> true,
			'supports' 	=> array( 'title', 'editor', 'thumbnail' )
			);
		register_post_type( 'places', apply_filters( 'places_args', $places_args ) ); 
	}

	// ADD A SPECIFIC IMAGE SIZE
	add_image_size( 'nbm-size', 100, 100, true );
}
add_action( 'init', 'nbm_register_places' );

/**
CREATE METABOXES
* @uses nbm_create_metaboxes FUNCTION to register metaboxes

* @uses nbm_post_type FILTER HOOK to target an existing post type instead default
*/
function nbm_create_metaboxes() {
	add_meta_box('nbm_infos', __( 'Information' , 'nbm' ), 'nbm_infos', apply_filters( 'nbm_post_type', 'places' ), 'side', 'default' );
	add_meta_box('nbm_loca_places', __( 'Location' , 'nbm' ), 'nbm_loca_places', apply_filters ('nbm_post_type', 'places' ), 'side', 'default' );
	add_meta_box('nbm_loc_icon', __( 'Pictos' , 'nbm' ), 'nbm_loc_icon', apply_filters( 'nbm_post_type', 'places' ), 'normal', 'default' );
}
add_action("admin_init", "nbm_create_metaboxes");

/**
SAVES METABOXES
* @uses nbm_save_metaboxes FUNCTION to save metaboxes
*/
function nbm_save_metaboxes( $post_ID ) { 
	if( isset( $_POST[ 'nbm_address' ] ) ) {
		check_admin_referer( 'nbm_coords-save_' . $_POST[ 'post_ID' ], 'nbm_coords-nonce' );

		$address = $_POST[ 'nbm_address' ];
		update_post_meta( $post_ID, '_nbm_address', $address );	

		// manual coords ?
		if( isset( $_POST[ 'nbm_do_u_define_coords' ] ) ) {		
			update_post_meta( $post_ID, '_nbm_do_u_define_coords', 1 );	

			// sexagesimales ?
			if( ! empty( $_POST[ 'w_degres' ] ) && ! empty( $_POST[ 'w_minutes' ] ) && ! empty( $_POST[ 'w_secondes' ] ) && ! empty( $_POST[ 'n_degres' ] ) && ! empty( $_POST[ 'n_minutes' ] ) && ! empty( $_POST[ 'n_secondes' ] ) ) {
				
				// CACULATE LONG
				// DEGRES + or - ?
				if( $_POST[ 'w_degres' ] < 0 ) {
					$longitude = -1*( -1*( intval( $_POST[ 'w_degres' ] ) ) + ( intval( $_POST[ 'w_minutes' ] )/60 ) + ( floatval( $_POST[ 'w_secondes' ] )/3600 ) );
				} else {
					$longitude = -1*( intval( $_POST[ 'w_degres' ] ) + ( intval( $_POST[ 'w_minutes' ] )/60 ) + ( floatval( $_POST[ 'w_secondes' ] )/3600 ) );
				}
				
				// CALCULATE LAT
				$latitude = ( intval( $_POST[ 'n_degres' ] ) ) + ( intval( $_POST[ 'n_minutes' ] )/60 ) + ( floatval( $_POST[ 'n_secondes' ] )/3600 );

				$coords = array( 
					'lat'  => trim( $latitude ), 
					'long' => trim( $longitude ) 
					);
				update_post_meta( $post_ID, '_nbm_coords', $coords );

				$coords_sexa = array(
					'w_degres'   => $_POST[ 'w_degres' ],
					'w_minutes'  => $_POST[ 'w_minutes' ],
					'w_secondes' => $_POST[ 'w_secondes' ],
					'n_degres'   => $_POST[ 'n_degres' ],
					'n_minutes'  => $_POST[ 'n_minutes' ],
					'n_secondes' => $_POST[ 'n_secondes' ]
					);
				update_post_meta( $post_ID, '_nbm_coords_sexa', $coords_sexa );

			// decimales ?
			} else {
				$user_coords = explode( ',', trim( $_POST[ 'nbm_coords' ] ) );
				$coords = array( 
					'lat'  => trim( $user_coords[0] ), 
					'long' => trim( $user_coords[1] ) 
					);
				update_post_meta( $post_ID, '_nbm_coords', $coords );
			}
		// auto coords
		} else {
			update_post_meta( $post_ID, '_nbm_do_u_define_coords', 0 );
			$coords = nbm_get_coords( $address );

			// COORDS RETURN A RESULT
			if( $coords != '' )
				update_post_meta( $post_ID, '_nbm_coords', $coords );
		}
	}
	if( isset( $_POST[ 'nbm-colors' ], $_POST[ 'nbm-letters' ] ) ) {
		check_admin_referer( 'nbm_icon-save_' . $_POST[ 'post_ID' ], 'nbm_icon-nonce' );
		update_post_meta( $post_ID, '_nbm_icon', array( 
			'color'  => $_POST[ 'nbm-colors' ], 
			'letter' => $_POST[ 'nbm-letters' ] 
			) );
	}

	if( isset( $_POST[ 'nbm_tel' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		update_post_meta( $post_ID, '_nbm_tel', $_POST[ 'nbm_tel' ] );
	}
	if( isset( $_POST[ 'nbm_email' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		update_post_meta( $post_ID, '_nbm_email', is_email( $_POST[ 'nbm_email' ] ) );
	}
	if( isset( $_POST[ 'nbm_website' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		update_post_meta( $post_ID, '_nbm_website', esc_url( $_POST[ 'nbm_website' ] ) );
	}
	if( isset( $_POST[ 'nbm_hours' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		update_post_meta( $post_ID, '_nbm_hours', esc_textarea( $_POST[ 'nbm_hours' ] ) );
	}
	if( isset( $_POST[ 'nbm_type_of_place' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		update_post_meta( $post_ID, '_nbm_type_of_place', sanitize_html_class( $_POST[ 'nbm_type_of_place' ] ) );
	}
	if( isset( $_POST[ 'nbm_important' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		$prev = get_option( 'nbm_important' );
		if( $_POST[ 'nbm_important' ] == 'yes' ) {
			update_post_meta( $prev, '_nbm_important', 2);

			update_option( 'nbm_important', $post_ID );
			update_post_meta( $post_ID, '_nbm_important', 1);
		} elseif( $prev == $post_ID ) {
			update_option( 'nbm_important', false );
			update_post_meta( $post_ID, '_nbm_important', 2);
		} else {
			update_post_meta( $post_ID, '_nbm_important', 2);
		}
	}
	if( isset( $_POST[ 'nbm-size' ] ) ) {
		check_admin_referer( 'nbm_infos-save_' . $_POST[ 'post_ID' ], 'nbm_infos-nonce' );
		switch( $_POST[ 'nbm-size' ] ) {
			case 'large' :
				$size = 'large';
				break;
			case 'medium' :
				$size = 'medium';
				break;
			case 'small' :
				$size = 'small';
				break;
			default: '';
		}
		update_post_meta( $post_ID, '_nbm_size', $size );
	}
	
}
add_action( 'save_post', 'nbm_save_metaboxes' ); 

/**
FUNCTIONS FOR METABOXES
* @uses nbm_loca_places FUNCTION to enter infos about location
* @uses nbm_loc_icon FUNCTION to chose a pictogram
* @uses nbm_infos FUNCTION to center general information
*/
function nbm_loca_places($post) {
	$address             = get_post_meta( $post->ID, '_nbm_address', true );
	$coords              = get_post_meta( $post->ID, '_nbm_coords', true );
	$coords_sexa         = get_post_meta( $post->ID, '_nbm_coords_sexa', true );
	$do_u_define_coords  = get_post_meta( $post->ID, '_nbm_do_u_define_coords', true );

	wp_nonce_field( 'nbm_coords-save_'.$post->ID, 'nbm_coords-nonce' );

	//RETRIEVE ADDRESS & COORDS
	?>
	
	<textarea name="nbm_address" style="width:250px;"><?php echo $address; ?></textarea>

	<div id="coord_decimal">
		<input type="text" name="nbm_coords" style="width:250px;" value="<?php echo ( ( $coords ) ? $coords['lat'] : '' ) . ' , ' . ( ( $coords ) ? $coords['long'] : '' ); ?>" disabled="disabled" id="gps_coords" /><br/>
	</div>  
	<div id="coord_sexa" style="display:none;" >
		<input type="text" name="n_degres" size="3" value="<?php echo  ( ( $coords_sexa ) ? $coords_sexa['n_degres'] : '' ) ?>"/>
		<input type="text" name="n_minutes" size="3" value="<?php echo  ( ( $coords_sexa ) ? $coords_sexa['n_minutes'] : '' ) ?>"/>
		<input type="text" name="n_secondes" size="3" value="<?php echo  ( ( $coords_sexa ) ? $coords_sexa['n_secondes'] : '' ) ?>"/>
		N <br />
		<input type="text" name="w_degres" size="3" value="<?php echo  ( ( $coords_sexa ) ? $coords_sexa['w_degres'] : '' ) ?>"/>
		<input type="text" name="w_minutes" size="3" value="<?php echo  ( ( $coords_sexa ) ? $coords_sexa['w_minutes'] : '' ) ?>"/>
		<input type="text" name="w_secondes" size="3" value="<?php echo  ( ( $coords_sexa ) ? $coords_sexa['w_secondes'] : '' ) ?>"/>
		W <br />
	</div>
  
	<input type="checkbox" name="nbm_do_u_define_coords" <?php checked( $do_u_define_coords, 1 ) ?> value="1" id="nbm_do_u_define_coords">
	<label for="nbm_do_u_define_coords"><?php _e( 'Do you want to manually define GPS coords of the place ?', 'nbm' ); ?></label><br/>
  
	<div id="choix_type_coord" style="display:none">
		<input type="radio" name="type_coord" id="choix_coord_decimal" checked /><label for="choix_coord_decimal"><strong> <?php _e( 'Decimal coordinates', 'nbm' ); ?></strong></label>
		<br />
		<input type="radio" name="type_coord" id="choix_coord_sexagesimal" /><label for="choix_coord_sexagesimal"><strong> <?php _e( 'Sexagesimal coordinates', 'nbm' ); ?></strong>
		</label>
	</div>
  
	<script type="text/javascript">
		//SWITCH BETWEEN MANUAL & AUTO + SEXAGESIMAL & DECIMAL
		jQuery(document).ready(function($){
			var $gps_man = $( '#nbm_do_u_define_coords' );
			var $deci    = $( '#choix_coord_decimal' );
			var $sexa    = $( '#choix_coord_sexagesimal' );

			function test_manual_coords(){
				if( $gps_man.prop( "checked" ) == true ){
					$( '#gps_coords' ).prop( "disabled", false );
					$( '#choix_type_coord' ).show();
				}else{			
					$( '#gps_coords' ).prop( "disabled", true );
					$( '#choix_type_coord' ).hide();
				}
			}
			$gps_man.on( 'click', test_manual_coords );
			test_manual_coords();

			function show_deci(){
				$( '#coord_sexa' ).hide();
				$( '#coord_decimal' ).show();		
			}
			$deci.on( 'click', show_deci );

			function show_sexa(){
				$( '#coord_decimal' ).hide();
				$( '#coord_sexa' ).show();      		
			}
			$sexa.on( 'click', show_sexa );
		});
	</script>
	<?php
}

function nbm_loc_icon($post) {
	wp_enqueue_style('plugin_name-admin-ui-css',
                NBM_PLUGIN_URL . '/nbm-admin-style.css',
                false,
                '1.0',
                false);
	wp_nonce_field( 'nbm_icon-save_' . $post->ID, 'nbm_icon-nonce' );
	$data = get_post_meta($post->ID, '_nbm_icon', true );
	$size = get_post_meta($post->ID, '_nbm_size', true );

	//colors
	echo '<h4>' . __( 'Marker color', 'nbm' ) . ' :</h4>';
	$colors = nbm_return_icon_data( 'colors' );
	foreach( $colors as $k => $c )
		echo '<span class="nbm-color"><input id="' . $k . '" type="radio" name="nbm-colors" value="' . $k . '" '. ( ( isset( $data[ 'color' ] ) ) ? checked( $data[ 'color' ], $k, false ) : '' ) . '><label for="' . $k . '" style="background-color:' . $c . '"></label></span>';

	echo '<hr />';

	//colors
	echo '<h4>' . __( 'Marker size', 'nbm' ) . ' :</h4>';
	echo '<span><input id="nbm-size-large" type="radio" name="nbm-size" value="large" ' . ( ( isset( $size ) ) ? checked( $size, 'large', false ) : '' ) . '> <label for="nbm-size-large" >' . __('large', 'nbm' ) . '</label></span><br>';
	echo '<span><input id="nbm-size-medium" type="radio" name="nbm-size" value="medium" ' . ( ( isset( $size ) ) ? checked( $size, 'medium', false ) : '' ) . '> <label for="nbm-size-medium" >' . __('medium', 'nbm' ) . '</label></span><br>';
	echo '<span><input id="nbm-size-small" type="radio" name="nbm-size" value="small" ' . ( ( isset( $size ) ) ? checked( $size, 'small', false ) : '' ) . '> <label for="nbm-size-small" >' . __('small', 'nbm' ) . '</label></span><br>';

	echo '<hr />';

	//icons
	echo '<h4>' . __( 'Marker icon', 'nbm' ) . ' :</h4>';
	$icons = nbm_return_icon_data( 'letters' );
	foreach( $icons as $k => $i )
		echo '<span class="nbm-letter"><input id="' . $k . '" type="radio" name="nbm-letters" value="' . $k . '" '. ( ( isset( $data[ 'letter' ] ) ) ? checked( $data[ 'letter' ], $k, false ) : '' ) . '><label for="' . $k . '">' . "$i" . '</label></span>';
}

function nbm_infos($post) {
	$tel = get_post_meta( $post->ID, '_nbm_tel', true );
	$email = get_post_meta( $post->ID, '_nbm_email', true );
	$website = get_post_meta( $post->ID, '_nbm_website', true );
	$hours = get_post_meta( $post->ID, '_nbm_hours', true );
	$type_of_place = get_post_meta( $post->ID, '_nbm_type_of_place', true );
	$important = get_option( 'nbm_important' );

	wp_nonce_field( 'nbm_infos-save_' . $post->ID, 'nbm_infos-nonce' );

	echo '<label for="nbm_tel">' . __( 'Tel', 'nbm' ) . ' :</label> <input type="tel" name="nbm_tel" id="nbm_tel" value="' . esc_attr( $tel ) . '"><br/>';
	echo '<label for="nbm_email">' . __( 'E-mail', 'nbm' ) . ' :</label> <input type="email" name="nbm_email" id="nbm_email" value="' . esc_attr( $email ) . '"><br/>';
	echo '<label for="nbm_website">' . __( 'Website', 'nbm' ) . ' :</label> <input type="url" name="nbm_website" id="nbm_website" value="' . esc_attr( $website ) . '"><br/><br/>';
	echo '<label for="nbm_type_of_place">' . __( 'Type of place', 'nbm' ) . ':</label> <select id="nbm_type_of_place" name="nbm_type_of_place">';
		echo '<option value="Place">' . __( 'just a place...', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'AdministrativeArea', false ) . ' value="AdministrativeArea">' . __( 'administrative area', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'CivicStructure', false ) . ' value="CivicStructure">' . __( 'civic structure', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'Landform', false ) . ' value="Landform">' . __( 'landform', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'LandmarksOrHistoricalBuildings', false ) . ' value="LandmarksOrHistoricalBuildings">' . __( 'historical landmarks/building', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'LocalBusiness', false ) . ' value="LocalBusiness">' . __( 'local business', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'Residence', false ) . ' value="Residence">' . __( 'residence', 'nbm' ) . '</option>';
		echo '<option ' . selected( $type_of_place, 'TouristAttraction', false ) . ' value="TouristAttraction">' . __( 'tourist attraction', 'nbm' ) . '</option>';
	echo '</select><br/>';
	echo '<label for="nbm_hours">' . __( 'Opening hours', 'nbm' ) . ' :</label><br/><textarea name="nbm_hours" id="nbm_hours" style="width:100%;">' . esc_textarea( $hours ) . '</textarea>';
	echo '<hr>';
	echo '<label><b>' . __( 'Is there the central place ?', 'nbm' ) . '</b></label><br/><input type="radio" name="nbm_important" value="yes" id="is_important" ' . ( ( $important == $post->ID ) ? ' checked="checked"' : '' ) . '> <label for="is_important">' . __( 'Yes it is', 'nbm' ) . '</label><br/><input type="radio" name="nbm_important" value="no" id="is_no_important"' . ( ( $important != $post->ID ) ? ' checked="checked"' : '' ) . '> <label for="is_no_important">' . __( 'No it isn\'t', 'nbm' ) . '</label>';
}

/**
RETRIEVE DATAS
* @uses nbm_get_coords FUNCTION to retrieve coords
* @uses get_distance FUNCTION to calculate distance betwenn places

* @uses nbm_try_to_find_with_openstreetmap FILTER HOOK to specify if you don't want to retrieve coordinates with openstreetmap (increase precision, but it's google...)
*/
function nbm_get_coords( $a ) {
	$map_url = 'http://nominatim.openstreetmap.org/search?format=json&q=' . urlencode( $a );
	$request = wp_remote_get( $map_url );
	$json    = wp_remote_retrieve_body( $request );

	$tryosm = apply_filters( 'nbm_try_to_find_with_openstreetmap', true );

	if( $json != '[]' && $tryosm ) {
		$json = json_decode( $json );

		$long = $json[0]->lon;
		$lat  = $json[0]->lat;

		return compact( 'lat', 'long' );
	} else { // f**k, there are no results with openstreetmap... let's try on google maps
		$map_url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode( $a ) . '&sensor=false';
		$request = wp_remote_get($map_url);
		$json    = wp_remote_retrieve_body($request);

		if(empty($json))
			return false;

		$json = json_decode($json);
		
		$status = $json->status;
		
		if ( $status == 'OK' ){
			$lat = $json->results[0]->geometry->location->lat;
			$long = $json->results[0]->geometry->location->lng;

			return compact( 'lat', 'long' );
		}else{
			return false;
		}
	}
}

function get_distance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000 ) {
	// convert from degrees to radians
	$latFrom = deg2rad( $latitudeFrom );
	$lonFrom = deg2rad( $longitudeFrom );
	$latTo   = deg2rad( $latitudeTo );
	$lonTo   = deg2rad( $longitudeTo );

	$lonDelta = $lonTo - $lonFrom;
	$a = pow( cos( $latTo ) * sin( $lonDelta ), 2) + pow( cos( $latFrom ) * sin( $latTo ) - sin( $latFrom ) * cos( $latTo ) * cos( $lonDelta ), 2);
	$b = sin( $latFrom ) * sin( $latTo ) + cos( $latFrom ) * cos( $latTo ) * cos( $lonDelta );

	$angle = atan2( sqrt( $a ), $b );
	return intVal( $angle * $earthRadius / 1000 );
}
/**
GET ROUTE
* @uses nbm_get_route FUNCTION to retrieve routes datas

* @uses cloudmate_key FILTER HOOK to change cloudmade key
*/
function nbm_get_route(){
	$start = $_POST['nbm-start'];
	$end   = $_POST['nbm-end'];
	$mode  = $_POST['nbm-trans'];
	switch( $mode ) {
		case 'car':
			$mode = 'car';
			break;
		case 'car/shortest':
			$mode = 'car/shortest';
			break;
		case 'bicycle':
			$mode = 'bicycle';
			break;
		case 'foot':
			$mode = 'foot';
			break;
		default:
			$mode = 'car';
			break;
	}

	if( $start == 'custom' )
		$start = implode( ',', nbm_get_coords( $_POST['nbm-address-start'] ) );
	if( $end == 'custom' ) 
		$end = implode( ',', nbm_get_coords( $_POST['nbm-address-end'] ) );

	$ch = curl_init();

	// set URL and other appropriate options
	$key = apply_filters( 'cloudmate_key', '4D7C045AF92D4BCA9199E50BD83B1A46' );
	curl_setopt($ch, CURLOPT_URL, "http://navigation.cloudmade.com/" . $key . "/api/latest/" . $start . "," . $end . "/" . $mode . ".js?lang=" . substr( get_locale(), 0, 2 ) );
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// grab URL and pass it to the browser
	$response = curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

	echo $response;
	exit;
}
add_action( "wp_ajax_nopriv_nbm_get_route", "nbm_get_route" );
add_action( "wp_ajax_nbm_get_route", "nbm_get_route" );

/**
SHORTCODE
* @uses nbm_render_map FUNCTION to render the general map
* @uses nbm_place_information FUNCTION to render place information

* @uses [maps] SHORTCODE for the general map
* @uses [place] SHORTCODE for map information

* @uses nbm_need_more FILTER HOOK to disallow the list of places
* @uses nbm_need_route FILTER HOOK to disallow route system
* @uses nbm_places_link FILTER HOOK to disallow links to single place
* @uses markers_querys FILTER HOOK to overide marker query
* @uses nbm_post_type FILTER HOOK to targeting the right post type (instead of place)
* @uses nbm_map FILTER HOOK to alter general map HTML
* @uses nbm_place_information FILTER HOOK to alter returned information for a single place
*/

//GENERAL MAP
function nbm_render_map( $atts ){

	//LOAD SCRIPTS
	wp_enqueue_script( 'leaflet' );
	wp_enqueue_script( 'leaflet-animated-marker' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'leaflet-script' );

	//INITIALIZE AN OUTPUT
	$output = '<div id="map"></div>';

	$more_info = apply_filters( 'nbm_need_more', true );
	$route = apply_filters( 'nbm_need_route', true );
	$places_link = apply_filters( 'nbm_places_link', true );


	if( $more_info ) {

		$letters   = nbm_return_icon_data( 'letters' );
		$important = get_option( 'nbm_important' );
		$ref       = get_post_meta( $important, '_nbm_coords', true );

		$points	   = array();

		$output .= '<div id="nbm-all-places" class="nbm-all-places">';

		$places = new WP_Query( apply_filters( 'markers_querys', array(
			'post_type'      => apply_filters( 'nbm_post_type', 'places' ),
			'posts_per_page' => -1,
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_nbm_important',
			'order'          => 'ASC'
			) ) );
		if( $places->have_posts() ) : while( $places->have_posts() ) : $places->the_post();

			//datas
			$icon = get_post_meta( $places->post->ID, '_nbm_icon', true );
			$icon[ 'letter' ] = $letters[ $icon[ 'letter' ] ];
			$img = ( $img = get_the_post_thumbnail( $places->post->ID, 'nbm-size', array( 'class' => 'nbm-info-pict' ) ) ) ? preg_replace('/<(.*)>/', '<$1 itemprop="image">', $img ) : '<div class="nbm-pict nbm-info-pict ' . $icon[ 'color' ] . '">' . $icon['letter'] . '</div>' ;
			$coords = get_post_meta( $places->post->ID, '_nbm_coords', true );

			$output .= '<div data-nbm="'.$places->post->ID.'" class="nbm-more ' . $icon[ 'color' ] . '" itemscope itemtype="http://schema.org/' . ( ( $type_of_place = get_post_meta( $places->post->ID, '_nbm_type_of_place', true ) ) ? sanitize_html_class( $type_of_place ) : 'Place' ) . '">';
			$output .= $img;
			$output .= ( $important != false && $important != $places->post->ID ) ? '<div class="nbm-distance">' . get_distance( $ref['lat'], $ref['long'], $coords['lat'], $coords['long'] ) . 'km</div>' : '';
			$output .= the_title( '<strong class="nbm-info-title" itemprop="name">', '</strong>', false );
			$output .= '<div class="nbm-infos-comp">';
				$output .= ( $tel = get_post_meta( $places->post->ID, '_nbm_tel', true ) ) ? '<div class="nbm-info-comp"><a href="tel:'.$tel.'" title="'.$tel.'" itemprop="telephone">'.$tel.'</a></div>' : '';
				$output .= ( $email = get_post_meta( $places->post->ID, '_nbm_email', true ) ) ? '<div class="nbm-info-comp"><a href="mailto:'.antispambot($email).'" title="'.antispambot($email).'">'.antispambot($email).'</a></div>' : '';
				$output .= ( $website = get_post_meta( $places->post->ID, '_nbm_website', true ) ) ? '<div class="nbm-info-comp"><a href="'.esc_url($website).'" target="_blank" title="'.esc_url($website).'">'.esc_url($website).'</a></div>' : '';
				$output .= '<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">';
					$output .= '<meta itemprop="latitude" content="' . $coords[ 'lat' ] . '" />';
	    			$output .= '<meta itemprop="longitude" content="' . $coords[ 'long' ] . '" />';
    			$output .= '</div>';
			$output .= '</div>';
			if( $places_link )
				$output .= '<a class="nbm-more-link" href="' . get_permalink() . '" itemprop="url">' . __( 'More information', 'nbm' ) . '</a>';
			$output .= '</div>';

			$points[the_title('','',false)] = $coords;
		endwhile; endif;
		$output .= '</div>';
	}

	if( $route ) {
		if( ! isset( $points ) ) {
			$points	= array();
			$places = new WP_Query( apply_filters( 'markers_querys', array(
				'post_type'      => apply_filters( 'nbm_post_type', 'places' ),
				'posts_per_page' => -1,
				'orderby'        => 'meta_value_num',
				'meta_key'       => '_nbm_important',
				'order'          => 'ASC'
				) ) );
			if( $places->have_posts() ) : while( $places->have_posts() ) : $places->the_post();
				$points[the_title('','',false)] = get_post_meta( $places->post->ID, '_nbm_coords', true );
			endwhile; endif;
		}
		array_filter( $points );

		// RENDER FORM
		$output .= '<div id="nbm-route" class="nbm-route"><form id="nbm-route-form" class="nbm-route-form">';

			//options

			//from
			$sel = '<div class="nbm-route-from nbm-route-point">';
			$sel .= '<select id="nbm-start" name="nbm-start" class="nbm-select">';
			$sel .= '<option value="">' . __( 'Starting point', 'nbm' ) . '</option>';
			foreach( $points as $k => $p )
				$sel .= '<option value="' . esc_attr( $p[ 'lat' ] . ',' . $p[ 'long' ] ) . '">' . esc_html( $k ) . '</option>';
			$sel .= '<option value="custom">' . __( 'Custom start', 'nbm' ) . '</option>';
			$sel .= '</select>';
			$sel .= '<input type="text" placeholder="' . __( 'address', 'nbm' ) . '" name="nbm-address-start" id="nbm-address-start" class="nbm-route-custom-point hidden">';
			$sel .= '</div><div class="nbm-sep">></div>';

			$output .= $sel;

			//to
			$sel2 = '<div class="nbm-route-to nbm-route-point">';
			$sel2 .= '<select id="nbm-end" name="nbm-end" class="nbm-select">';
			$sel2 .= '<option value="">' . __( 'Ending point', 'nbm' ) . '</option>';
			foreach( $points as $k => $p )
				$sel2 .= '<option value="' . esc_attr( $p[ 'lat' ] . ',' . $p[ 'long' ] ) . '">' . esc_html( $k ) . '</option>';
			$sel2 .= '<option value="custom">' . __( 'Custom destination', 'nbm' ) . '</option>';
			$sel2 .= '</select>';
			$sel2 .= '<input type="text" placeholder="' . __( 'address', 'nbm' ) . '" name="nbm-address-end" id="nbm-address-end" class="nbm-route-custom-point hidden">';
			$sel2 .= '</div>';

			$output .= $sel2;

			//options
			$output .= '<div class="nbm-reset-before-options"></div><button id="nbm-show-route-options" class="nbm-show-route-options">' . __( 'More options', 'nbm' ) . '</button>';
			$output .= '<div id="nbm-route-options" class="nbm-route-options hidden">';
			$output .= '<label><input type="radio" name="nbm-trans" class="nbm-trans" value="car" checked="checked"> ' . __( 'Car (fastest)', 'nbm' ) . '</label><br/>';
			$output .= '<label><input type="radio" name="nbm-trans" class="nbm-trans" value="car/shortest"> ' . __( 'Car (shortest)', 'nbm' ) . '</label><br/>';
			$output .= '<label><input type="radio" name="nbm-trans" class="nbm-trans" value="bicycle"> ' . __( 'Bicycle', 'nbm' ) . '</label><br/>';
			$output .= '<label><input type="radio" name="nbm-trans" class="nbm-trans" value="foot"> ' . __( 'Foot', 'nbm' ) . '</label>';
			$output .= '</div>';
			
			//submit
			$output .= '<input type="submit" id="nbm-route-submit" class="nbm-route-submit" value="' . __( 'Display route', 'nbm' ) . '">';

		$output .= '</form>';

		$output .= '<div id="route-details" class="route-details"></div>';
		$output .= '</div>';


		wp_localize_script( 'leaflet-script', 'routeDial', array(
			'to' => __( 'to', 'nbm' ),
			'in' => __( 'in', 'nbm' ),
			'and' => __( 'and', 'nbm' ),
			'transIcon' => array(
				'car' => NBM_PLUGIN_URL . '/i/pins/car.png',
				'bicycle' => NBM_PLUGIN_URL . '/i/pins/bicycle.png',
				'foot' => NBM_PLUGIN_URL . '/i/pins/foot.png'
				)
			) );
	}
	wp_reset_postdata();
	return apply_filters( 'nbm_map', $output );
}
add_shortcode( 'maps', 'nbm_render_map' );

//EACH ITEM
function nbm_place_information(){
	global $post;
	$output = '<div class="nbm-item-place" itemscope itemtype="http://schema.org/' . ( ( $type_of_place = get_post_meta( $post->ID, '_nbm_type_of_place', true ) ) ? sanitize_html_class( $type_of_place ) : 'Place' ) . '">';
		$output .= the_title( '<strong itemprop="name">', '</strong>', false );
		$output .= ( ( $hours = get_post_meta( $post->ID, '_nbm_hours', true) ) ? '<div itemprop="openingHoursSpecification" itemscope  itemtype="http://schema.org/OpeningHoursSpecification><span itemprop="opens">' . nl2br( $hours ) . '</span></div>' : '' );
		$output .= ( ( $address = get_post_meta( $post->ID, '_nbm_address', true) ) ? '<div itemprop="address">' . $address . '</div>' : '' );

		$coordsplace = get_post_meta( $post->ID, '_nbm_coords', true );

		$output .= '<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">';
			$output .= __( 'Coordinates', 'nbm' ) . ' : <a href="http://www.openstreetmap.org/?mlat=' . $coordsplace[ 'lat' ] . '&mlon=' . $coordsplace[ 'long' ] . '&zoom=14&layers=0B00FT" target="_blank">' . implode( ',', $coordsplace ) . '</a>';
			$output .= '<meta itemprop="latitude" content="' . $coordsplace[ 'lat' ] . '" />';
			$output .= '<meta itemprop="longitude" content="' . $coordsplace[ 'long' ] . '" />';
		$output .= '</div>';

		$ref = get_option( 'nbm_important' );
		if( $ref && $ref != $post->ID ) {
			$refname     = get_the_title( $ref );
			$coordsref   = get_post_meta( $ref, '_nbm_coords', true );
			$km          = get_distance( $coordsref[ 'lat' ], $coordsref[ 'long' ], $coordsplace[ 'lat' ], $coordsplace[ 'long' ] );

			$output .= sprintf( __( '<p><em>%s</em> is located %s km from <em>%s</em></p>', 'nbm'), the_title( '', '', false ), $km, $refname );
		}

		$output .= ( $tel = get_post_meta( $post->ID, '_nbm_tel', true ) ) ? '<div class="nbm-info-comp">' . __( 'Tel', 'nbm' ) . ' : <a href="tel:' . $tel . '" title="' . $tel . '" itemprop="telephone">' . $tel . '</a></div>' : '';
		$output .= ( $email = get_post_meta( $post->ID, '_nbm_email', true ) ) ? '<div class="nbm-info-comp">' . __( 'E-mail', 'nbm' ) . ' : <a href="mailto:' . antispambot( $email ) . '" title="' . antispambot( $email ) . '">' . antispambot( $email ) . '</a></div>' : '';
		$output .= ( $website = get_post_meta( $post->ID, '_nbm_website', true ) ) ? '<div class="nbm-info-comp">' . __( 'Website', 'nbm' ) . ' : <a href="' . esc_url( $website ) . '" target="_blank" title="' . esc_url( $website ) . '">' . esc_url( $website ) . '</a></div>' : '';

	$output .= '</div>';
	return apply_filters( 'nbm_place_information', $output );
}
add_shortcode( 'place', 'nbm_place_information' );


/**
LOAD DATAS INTO LEAFLET
* @uses nbm_deep_into_my_script FUNCTION to load datas vars (for leaflet)

* @uses markers_querys FILTER HOOK to overide marker query
* @uses nbm_post_type FILTER HOOK to targeting the right post type (instead of place)
* @uses cloudmate_key FILTER HOOK to change cloudmade key
* @uses maps_datas FILTER HOOK to modify send datas 
*/
function nbm_deep_into_my_script(){

	$markers = array();
	$icons 	 = array();
	$places = new WP_Query( apply_filters( 'markers_querys', array(
		'post_type' => apply_filters( 'nbm_post_type', 'places' ),
		'posts_per_page' => -1
		) ) );

	$important = get_option('nbm_important');
	if( $places->have_posts() ) : while( $places->have_posts() ) : $places->the_post();
		$icon = get_post_meta( $places->post->ID, '_nbm_icon', true );
		$l = nbm_return_icon_data( 'letters' );
		$icon[ 'letter' ] = $l[ $icon[ 'letter' ] ];
		$size = get_post_meta( $places->post->ID, '_nbm_size', true );

		$datas = array();
		if( $tel = get_post_meta( $places->post->ID, '_nbm_tel', true ) ) $datas[] = '<a href="tel:' . $tel . '" title="' . $tel . '">g</a>';
		if( $email = get_post_meta( $places->post->ID, '_nbm_email', true ) ) $datas[] = '<a href="mailto:' . antispambot( $email ) . '" title="' . antispambot( $email ) . '">X</a>';
		if( $website = get_post_meta( $places->post->ID, '_nbm_website', true ) ) $datas[] = '<a href="' . esc_url( $website ) . '" target="_blank" title="' . esc_url( $website ) . '">a</a>';
		$datas = join( '|', $datas );
		if( ! empty( $datas ) ) $datas = '<br><div class="marker-datas">' . $datas . '</div>';

		$markers[] = array(
			'id'     => $places->post->ID,
			'name'   => $places->post->post_title,
			'icon'   => $icon,
			'size'   => $size,
			'import' => ( ( $important == $places->post->ID ) ? true : false ),
			'coords' => get_post_meta( $places->post->ID, '_nbm_coords', true ),
			'datas'  => $datas
			);
	endwhile; endif;

	$key = apply_filters( 'cloudmade_key', '4D7C045AF92D4BCA9199E50BD83B1A46' );
	$style = apply_filters( 'cloudmade_style', '997' );
	$maps_datas = array(
		'tiles'       => 'http://{s}.tile.cloudmade.com/' . $key . '/' . $style . '/256/{z}/{x}/{y}.png',
		'attribution' => ' &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> / <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a> / © <a href="http://cloudmade.com">CloudMade</a>',
		'subdomains'  => 'abc',
		'markers'     => $markers,
		'iconProp'    => array(
			'iconSizeSmall'     => array( 32, 44 ),
			'iconAnchorSmall'   => array( 16, 44 ),
			'popupAnchorSmall'  => array( 0, -44 ),
			'iconSizeMedium'    => array( 48, 61 ),
			'iconAnchorMedium'  => array( 24, 61 ),
			'popupAnchorMedium' => array( 0, -61 ),
			'iconSizeLarge'     => array( 55, 71 ),
			'iconAnchorLarge'   => array( 27, 71 ),
			'popupAnchorLarge'  => array( 0, -71 ),
			'className'         => 'icon',
			)
		);
	wp_localize_script( 'leaflet-script', 'maps_datas', apply_filters( 'maps_datas', $maps_datas ) );
}
add_action( 'wp_enqueue_scripts', 'nbm_deep_into_my_script', 12 );

/**
ICONS
* @uses nbm_return_icon_data FUNCTION to generate vars with all icons color|pictos
*/
function nbm_return_icon_data( $what ) {
	if( $what == 'colors' ) {
		return array(
			'blue'   => '#8bc6dd',
			'brown'  => '#b27349',
			'green'  => '#87a85f',
			'grey'   => '#485a61',
			'orange' => '#e09749',
			'pink'   => '#dc5888',
			'purple' => '#6e5da5',
			'red'    => '#c15656',
			'yellow' => '#dab049'
			);
	}
	if( $what == 'letters' ) {
		return array(
			'camera-alt'          => 'b',
			'basket'              => 'c',
			'aboveground-rail'    => 'h',
			'airfield'            => 'i',
			'airport'             => 'j',
			'art-gallery'         => 'k',
			'bar'                 => 'l',
			'baseball'            => 'm',
			'basketball'          => 'n',
			'beer'                => 'o',
			'belowground-rail'    => 'p',
			'bicycle'             => 'q',
			'bus'                 => 'r',
			'cafe'                => 's',
			'campsite'            => 't',
			'cemetery'            => 'u',
			'cinema'              => 'v',
			'college'             => 'w',
			'commerical-building' => 'x',
			'credit-card'         => 'y',
			'cricket'             => 'z',
			'embassy'             => 'A',
			'fast-food'           => 'B',
			'ferry'               => 'C',
			'fire-station'        => 'D',
			'football'            => 'E',
			'fuel'                => 'F',
			'garden'              => 'G',
			'giraffe'             => 'H',
			'golf'                => 'I',
			'grocery-store'       => 'J',
			'harbor'              => 'K',
			'heliport'            => 'L',
			'hospital'            => 'M',
			'industrial-building' => 'N',
			'library'             => 'O',
			'lodging'             => 'P',
			'london-underground'  => 'Q',
			'minefield'           => 'R',
			'monument'            => 'S',
			'museum'              => 'T',
			'pharmacy'            => 'U',
			'pitch'               => 'V',
			'police'              => 'W',
			'post'                => 'X',
			'prison'              => 'Y',
			'rail'                => 'Z',
			'religious-christian' => '0',
			'religious-islam'     => '1',
			'religious-jewish'    => '2',
			'restaurant'          => '3',
			'roadblock'           => '4',
			'school'              => '5',
			'shop'                => '6',
			'skiing'              => '7',
			'soccer'              => '8',
			'swimming'            => '9',
			'tennis'              => '&',
			'theatre'             => 'é',
			'toilet'              => '"',
			'town-hall'           => '\'',
			'trash'               => '(',
			'tree-1'              => '-',
			'tree-2'              => 'è',
			'warehouse'           => '_',
			'beaker'              => 'd',
			'stethoscope'         => 'f',
			'coffee'              => 'e',
			'phone'               => 'g',
			'link'                => 'a'
			);
	}
}