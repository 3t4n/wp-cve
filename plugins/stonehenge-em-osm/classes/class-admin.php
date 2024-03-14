<?php
// Exit if accessed directly
if( !defined('ABSPATH') ) exit;

if( !class_exists('Stonehenge_EM_OSM_Admin') ) :
Class Stonehenge_EM_OSM_Admin extends Stonehenge_EM_OSM_Functions {


	#===============================================
	public function restore() {
		$EM_folder 	= WP_PLUGIN_DIR .'/events-manager/';
		$OSM_folder = WP_PLUGIN_DIR .'/stonehenge-em-osm/originals/';

		// Restore events-manager.js
		if( file_exists( $EM_folder.'includes/js/_events-manager.js') ) {
			wp_delete_file( $EM_folder.'includes/js/_events-manager.js' );
			wp_delete_file( $EM_folder.'includes/js/events-manager.js' );
			copy( $OSM_folder.'events-manager.js', $EM_folder.'includes/classes/events-manager.js');
		}

		// Restore em-actions.php
		if( file_exists( $EM_folder.'_em-actions.php') ) {
			wp_delete_file( $EM_folder.'_em-actions.php');
			wp_delete_file( $EM_folder.'em-actions.php');
			copy( $OSM_folder.'em-actions.php', $EM_folder.'em-actions.php');
		}

		// Remove old template files. No longer needed since version 4.0.0.
		$theme_event 	= get_stylesheet_directory().'/plugins/events-manager/forms/event/';
		$theme_location	= get_stylesheet_directory().'/plugins/events-manager/forms/location/';

		// Do check by back-up file to prevent user templates to be deleted after this update.
		if( file_exists($theme_event.'location-1.8.5.php') ) {
			if( file_exists($theme_event.'location.php') ) {
				wp_delete_file($theme_event.'location.php');
			}
			wp_delete_file($theme_event.'location-1.8.5.php');
		}

		if( file_exists($theme_location.'where-1.8.5.php') ) {
			if( file_exists($theme_location.'where.php') ) {
				wp_delete_file($theme_location.'where.php');
			}
			wp_delete_file($theme_location.'where-1.8.5.php');
		}
		return;
	}


	#===============================================
	public function disable_google() {
		if( '0' != get_option('dbem_gmap_is_active') ) {
			update_option( 'dbem_gmap_is_active', '0' );
		}

		// Disable GEO Search.
		if( '0' != get_option('dbem_search_form_geo') ) {
			update_option( 'dbem_search_form_geo', '0');
		}

		$disabled = '<em>'. sprintf( __('This option is not available when using %s.', $this->plugin['text']), $this->plugin['short'] ) .'</em>';

		?><script>
		jQuery(document).ready(function() {
			jQuery('#dbem_search_form_geo_row td, #dbem_gmap_is_active_row td, #dbem_search_form_geo_units_row td').html('<?php echo $disabled; ?>');
			jQuery('#em-opt-google-maps p').html('');
			jQuery('#dbem_search_form_geo_units_row th').html('');
			jQuery('#dbem_search_form_geo_units_label_row').hide();
			jQuery('#dbem_search_form_geo_distance_options_row').hide();
		});
		</script><?php
		return;
	}


	#===============================================
	public function alter_locations_table() {
		global $wpdb;
		$table 	= EM_LOCATIONS_TABLE;
		$column = $wpdb->query("SHOW COLUMNS FROM `{$table}` LIKE 'location_marker'");
		if( !$column ) {
			$wpdb->query("ALTER TABLE {$table} ADD `location_marker` VARCHAR( 25 ) AFTER `location_private`");
			$wpdb->query("ALTER TABLE {$table} ADD `location_map_type` VARCHAR( 255 ) AFTER `location_marker`");
		}
		return;
	}


	#===============================================
	public function replace_ajax_search() {
		global $wpdb;
		$suggestions = array();
		if( is_user_logged_in() || ( get_option('dbem_events_anonymous_submissions') && user_can(get_option('dbem_events_anonymous_user'), 'read_others_locations') ) ) {
			$location_cond = (is_user_logged_in() && !current_user_can('read_others_locations')) ? "AND location_owner=".get_current_user_id() : '';
			if( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') ) {
				if( !user_can(get_option('dbem_events_anonymous_user'),'read_private_locations') ) {
					$location_cond = " AND location_private=0";
				}
			}
			elseif( is_user_logged_in() && !current_user_can('read_private_locations') ) {
			    $location_cond = " AND location_private=0";
			}
			elseif( !is_user_logged_in() ) {
				$location_cond = " AND location_private=0";
			}

			if( EM_MS_GLOBAL && !get_site_option('dbem_ms_mainblog_locations') ) {
				$location_cond .= " AND blog_id=". absint(get_current_blog_id());
			}

			$location_cond = apply_filters('em_actions_locations_search_cond', $location_cond);
			$term = (isset($_REQUEST['term'])) ? '%'.$wpdb->esc_like(wp_unslash($_REQUEST['term'])).'%' : '%'.$wpdb->esc_like(wp_unslash($_REQUEST['q'])).'%';
			$sql = $wpdb->prepare("SELECT
					location_id AS `id`,
					Concat( location_name )  AS `label`,
					location_name AS `value`,
					location_address AS `address`,
					location_town AS `town`,
					location_state AS `state`,
					location_region AS `region`,
					location_postcode AS `postcode`,
					location_country AS `country`,
					location_latitude AS `latitude`,
					location_longitude AS `longitude`,
					location_marker AS `marker`,
					location_map_type AS `maptype`,
					post_id AS `post_id`
				FROM ". EM_LOCATIONS_TABLE ."
				WHERE ( `location_name` LIKE %s ) AND location_status=1 $location_cond LIMIT 10", $term);
			$locations = $wpdb->get_results($sql);
		}

		foreach( $locations as $location ) {
			$location->maptype 	= $location->maptype 	? $location->maptype 	: $this->get_location_tiles( $location );
			$location->marker	= $location->marker 	? $location->marker		: $this->get_location_marker( $location );
		}

		$response = json_encode($locations);
		echo $response;
		exit();
	}


	#===============================================
	public function create_admin_notices() {
		$options = $this->plugin['options'];
		if( !isset($options['api']) || empty($options['api']) ) {
			$message = '<div class="notice notice-error"><p>';
			$message .= sprintf( __('Please enter your OpenCage API Key in your <a href=%s>Plugin Settings</a>.', $this->plugin['text']), $this->plugin['url'] );
			$message .= '</p></div>';
			echo $message;
		}
		return;
	}

} // End class.
endif;