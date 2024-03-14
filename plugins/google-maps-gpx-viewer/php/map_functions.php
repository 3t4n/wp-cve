<?php
/*
map_functions.php, V 1.30, altm, 18.10.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
	
	// widgets
	// Opt. POI Editor
	define('WIDGET_ROOT', PLUGIN_ROOT . '/widgets');
	require_once(WIDGET_ROOT . '/poi/gMap_pois.php');
	require_once(WIDGET_ROOT . '/search/gMap_search.php');
	require_once(WIDGET_ROOT . '/upload/gMap_gpxload.php');
	// Update GPX File to DB on "save post"
	if(file_exists ( PLUGIN_ROOT . '/php/gpx_database.php')){
		require_once(PLUGIN_ROOT . '/php/gpx_database.php');
		// Opt. RESTful API
		if(get_option('gmapv3_restful')){
			if(file_exists ( PLUGIN_ROOT . '/php/gpx_restful.php')){
				require_once(PLUGIN_ROOT . '/php/gpx_restful.php');
			}
		}
	}
	// add version
	add_action('wp_head', 'gmaps_header',999999);	

	function gmaps_header(){
		add_shortcode('map', 'gmapv3');
		echo '<meta name="gm-gpx-v" content="'.GMAPX_VERSION.'" />
		';
		echo '<script type="text/javascript">jQuery.noConflict();</script>
		';
	}
	global $default_maptypes;
	/* init */
	add_action('init', 'gmaps_v3_init');
	function gmaps_v3_init() {
		global $default_maptypes;
		load_plugin_textdomain(GPX_GM_PLUGIN, false, GPX_GM_PLUGIN.'/lang');
		wp_enqueue_script( 'jquery' );
		$gm_version = get_option('gmap_v3_gpx_version');
		if($gm_version < 1.12){
			// upgrading if needed
			delete_option('gmap_v3_gpx_maptypes');
		}
		update_option('gmap_v3_gpx_version', GMAPX_VERSION);

		$default_maptypes = array(
			0	=> array("OSM", 1, __('<a href="http://www.openstreetmap.org" target="_blank">Open Street Map</a>', GPX_GM_PLUGIN), OSM, 'http://tile.openstreetmap.org/' , 18, 0),
			1	=> array("OSM Cycle", 1, __('<a href="http://creativecommons.org/licenses/by-sa/2.0/">Cycle OSM</a>', GPX_GM_PLUGIN), OSM, 'http://b.tile.opencyclemap.org/cycle/' , 18, 0),
			2	=> array("Relief", 1, __('<a href="http://www.maps-for-free.com/html/about.html" target="_blank">maps-for-free</a>', GPX_GM_PLUGIN), "", "", 18, 0),
			3	=> array("Demis", 1, __('WMS demo by Demis', GPX_GM_PLUGIN), WMS, 'http://www2.demis.nl/wms/wms.ashx?Service=WMS&WMS=BlueMarble&Version=1.1.0&Request=GetMap&Layers=Earth Image,Borders,Coastlines&Format=image/jpeg', 13, 1),
			4	=> array("ROADMAP", 1, __('Google roadmap', GPX_GM_PLUGIN), "", "", 13, 10),
			5	=> array("SATELLITE", 1, __('Google satellite', GPX_GM_PLUGIN),"" , "", 13, 10),
			6	=> array("HYBRID", 1, __('Google satellite with roadmap', GPX_GM_PLUGIN), "", "", 13, 10),
			7	=> array("TERRAIN", 1, __('Google topographical map', GPX_GM_PLUGIN), "", "", 13, 10)
			);
		add_option('gmap_v3_gpx_maptypes', $default_maptypes);
		add_option('gmap_v3_gpx_mapSizeBtn', true);
		add_option('gmap_v3_gpx_defMaptype', "TERRAIN");
		add_option('gmap_v3_gpx_fszIndex', 1);
		add_option('gmap_v3_gpx_elevationProfile', 1);
		add_option('gmap_v3_gpx_downloadLink', 1);
	}

	/* admin options page */
	add_action('admin_menu', 'google_maps_gpx_viewer_options_page');
	function google_maps_gpx_viewer_options_page() {
		add_options_page('Google Maps Options', 'Google Maps', 'administrator', GPX_GM_PLUGIN.'/php/options.php');
	}
	
	/*
	* Enable buttons in tinymce.
	* This function should be called by an action.
	*
	*/
	add_action('admin_init', 'gmap_add_buttons');
	function gmap_add_buttons() {
		// Don't bother doing this stuff if the current user lacks permissions
		if( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) 
			return;

		// Add only in Rich Editor mode
		if( get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', 'add_gmap_script');
			add_filter('mce_buttons', 'add_gmap_button');
		}
	}

	/*
	* Function to answer the MCE ajax call.
	* This function should be called by an action.
	*/
	add_action('wp_ajax_gmap_tinymce', 'gmap_tinymce');
	function gmap_tinymce() {
		// check for rights
		if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
			die(__("You are not logged in!"));

		require_once(PLUGIN_ROOT . '/tinymce/mce_win.php');
		die();
	}
	// add file upload dialog
	add_action('wp_ajax_gmap_tinymce_upload', 'gmap_tinymce_upload');
	function gmap_tinymce_upload() {
		// check for rights
		if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
			die(__("You are not logged in!"));
		
		require_once(PLUGIN_ROOT . '/tinymce/uploader.php');
		die();
	}
	// add map editor  dialog
	add_action('wp_ajax_gmap_tinymce_editor', 'gmap_tinymce_editor');
	function gmap_tinymce_editor() {
		// check for rights
		if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') ) 
			die(__("You are not logged in!"));
		
		require_once(PLUGIN_ROOT . '/editor/editor.php');
		die();
	}
	/*
	* Function to add the button to the bar.
	*/
	function add_gmap_button($buttons) {
		array_push($buttons, 'GmapGpx');
		return $buttons;
	}

	/*
	* Function to set the script which should answer when the user press the button.
	*/
	function add_gmap_script($plugins) {
		$dir_name = '/wp-content/plugins/'.GPX_GM_PLUGIN;
		$url = get_bloginfo('wpurl');
		$pluginURL = $url.$dir_name.'/tinymce/editor_plugin.js';
		$plugins['GmapGpx'] = $pluginURL;
		return $plugins;
	}


	/* PO DB metabox */

	add_action( 'add_meta_boxes', 'gmap_gpx_add_custom_box' );
	add_action( 'save_post', 'gmap_gpx_save_postdata' );

	function gmap_gpx_add_custom_box() {
		$screens = array( 'post', 'page' );
		foreach ($screens as $screen) {
			add_meta_box(
				'gmap_gpx_poidb_sectionid',
				__( 'Google Maps POI Widget', GPX_GM_PLUGIN ),
				'gmap_gpx_inner_custom_box',
				$screen,
				'side'
			);
		}
	}

	function gmap_gpx_inner_custom_box( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'gmap_gpx_noncename' );
		// checkbox
		$value = get_post_meta( $post->ID, 'gmap_gpx_map_switch', true ); ?>
		
		<p>
		<input type="checkbox" id="gmap_gpx_map_switch" name="gmap_gpx_map_switch" <?php if( $value == true ) { echo 'checked="checked"'; }?> />
		<label for="gmap_gpx_map_switch">
		<?php _e("Enable POI Map Widget", GPX_GM_PLUGIN );?>
		</label>
		</p>
		
		<p>
		<label for="gmap_gpx_map_title">
		<?php _e("Title", GPX_GM_PLUGIN );?> :
		</label>

		<?php 	$value = get_post_meta( $post->ID, 'gmap_gpx_map_title', true );	?>
		<input type="text" id="gmap_gpx_map_title" name="gmap_gpx_map_title" value="<?php echo esc_attr( $value ) ?>" size="25" />
		</p>
		
		<p>
		<label for="gmap_gpx_map_height">
		<?php _e("Height", GPX_GM_PLUGIN );?>
		</label>
		<?php 	
		$value = get_post_meta( $post->ID, 'gmap_gpx_map_height', true );	
		if($value == "") $value = "250";
		?>
		<input type="text" id="gmap_gpx_map_height" name="gmap_gpx_map_height" value="<?php echo esc_attr( $value ) ?>" size="5" />
		</p>

		<p>
		<?php 	
		$poidb_access_key = get_post_meta( $post->ID, 'poidb_access_key', true );	
		?>
		<label for="poidb_access_key"><?php _e( 'User must be at least', GPX_GM_PLUGIN ); ?></label> 
		<select class="widefat" id="poidb_access_key"  name="poidb_access_key" size="1">
		<?php
		echo '<option value="1"'; if ($poidb_access_key == "1") echo ' selected="selected"'; echo'>' .__( 'administrator', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="2"'; if ($poidb_access_key == "2") echo ' selected="selected"'; echo'>' .__( 'editor', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="3"'; if ($poidb_access_key == "3") echo ' selected="selected"'; echo'>' .__( 'author', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="4"'; if ($poidb_access_key == "4") echo ' selected="selected"'; echo'>' .__( 'contributor', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="5"'; if ($poidb_access_key == "5") echo ' selected="selected"'; echo'>' .__( 'subscriber', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="6"'; if ($poidb_access_key == "6") echo ' selected="selected"'; echo'>' .__( 'everybody', GPX_GM_PLUGIN ) . '</option>';
		?>
		</select>
		</p>	

		<p><a href="http://www.atlsoft.de/poi-database/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></p>
		<?php
	}

	function gmap_gpx_save_postdata( $post_id ) {
		if ( 'page' == $_REQUEST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
				return;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
		}
		if ( ! isset( $_POST['gmap_gpx_noncename'] ) || ! wp_verify_nonce( $_POST['gmap_gpx_noncename'], plugin_basename( __FILE__ ) ) )
			return;
		// $post_ID = $_POST['post_ID'];
		if(isset($_POST['gmap_gpx_map_switch'])){
			
			$mydata =  $_POST['gmap_gpx_map_switch'];
			add_post_meta($post_id, 'gmap_gpx_map_switch', $mydata, true) ||
			update_post_meta($post_id, 'gmap_gpx_map_switch', $mydata);
			
			if(isset($_POST['gmap_gpx_map_title'])){
				$mydata =  sanitize_text_field( $_POST['gmap_gpx_map_title']);
				add_post_meta($post_id, 'gmap_gpx_map_title', $mydata, true) ||
				update_post_meta($post_id, 'gmap_gpx_map_title', $mydata);
			}

			if(isset($_POST['gmap_gpx_map_height'])){
				$mydata =  sanitize_text_field( $_POST['gmap_gpx_map_height']);
				add_post_meta($post_id, 'gmap_gpx_map_height', $mydata, true) ||
				update_post_meta($post_id, 'gmap_gpx_map_height', $mydata);
			}


			if(isset($_POST['poidb_access_key'])){
				$mydata =  $_POST['poidb_access_key'];
				add_post_meta($post_id, 'poidb_access_key', $mydata, true) ||
				update_post_meta($post_id, 'poidb_access_key', $mydata);
			}	
		} else {
			delete_post_meta($post_id, 'gmap_gpx_map_switch');
			delete_post_meta($post_id, 'gmap_gpx_map_title');
			delete_post_meta($post_id, 'gmap_gpx_map_height');
			delete_post_meta($post_id, 'poidb_access_key');
		}
			
	}
?>