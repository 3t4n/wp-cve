<?php
/**
* Plugin Name: GForms Export Entries
* Plugin URI: http://OptiReto.com/
* Description: Export Gravity Forms entries from selected dates to an excel spreadsheet.
* Author: OptiReto.com
* Contributors: theverylastperson
* Author URI: http://OptiReto.com
* Version: 1.9.3
* License: GPL2
* Code Monkey: Jay Chuck Mailen
* Bitbucket Plugin URI: https://bitbucket.org/Optimized-Marketing/gforms-export-entries/
* Bitbucket Branch:     master  
*/

if ( class_exists( 'GFForms' ) ) {

	define( 'GFEE_VER', '1.9.3' );

	$plugin_path = plugins_url() . '/gforms-export-entries/';
	define( 'GFEE_PATH', $plugin_path );
	
	require_once( 'admin/admin-page-class.php' );
	require_once( 'includes/excelwriter.inc.php' );
	require_once( 'includes/gfee_handler.php' );
	require_once( 'includes/generate_export.php' );
	require_once( 'includes/schedule.php' );
	
	add_action( 'export_gfee_entries', 'maybe_export_gf_entries_schedule', 10, 1 );
	add_action( 'gfee_cleaning_days', 'maybe_gfee_cleaning_days_schedule', 10, 1 );
	
	function gfee_write_log( $log )  {
		if ( true === WP_DEBUG ) {
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
	
	function gfee_clean_title( $form_title ) {
		$form_title = trim( $form_title );
		$form_title = str_replace( '[', '{', $form_title );
		$form_title = str_replace( ']', '}', $form_title );
		
		$form_title = str_replace( "'", "", $form_title );
		$form_title = str_replace( '  ', ' ', $form_title );
		
		$form_title = wp_strip_all_tags( $form_title );
		return $form_title;
	}
	
	add_filter( 'gform_export_menu', 'gfee_export_menu', 10, 1 );
	function gfee_export_menu( $settings ) {
		$settings[] = array(
			'name' => 'export_entries',
			'label' => 'Advanced Export Entries'
		);
		return $settings;
	}
	
	add_action( "gform_export_page_export_entries", 'gfee_export_entries_page' );
	function gfee_export_entries_page() {
		$x = '<h3>' . __( 'Please wait while you are redirected to the Advanced Export Entries Page.', '' ) . '</h3>';
		$x .= '<style>body { cursor: wait; }</style>';
		$x .= '<script>window.location = "/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries"</script>';
		echo $x;
	}
	
	/**
	 * Run activation
	 *
	 * Set default options
	 *
	 */
	function gfee_activate() {
		//= convert old settings to new settings
	
	}
	register_activation_hook( __FILE__, 'gfee_activate' );
	
	/**
	 * Run deactivation
	 *
	 */
	function gfee_deactivate() {
		
	}
	register_deactivation_hook( __FILE__, 'gfee_deactivate' );
	
	/**
	 * Custom logging function to capture items outside addon class
	*/
	function gfee_log( $message ) {
		if ( class_exists( 'GFLogging' ) ) {
			GFLogging::include_logger();
			GFLogging::log_message( 'gforms-export-entries', $message, KLogger::DEBUG );
		}
	}
	
} //= End check to see if Gravity Forms is active

/**
 * If Gravity Forms is deactivated then deactivate this PlugIn
 */
function gfee_deactivate_init() {
	if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
		$path = plugin_dir_path( __FILE__ ) . 'gforms-export-entries.php';
		deactivate_plugins( $path );
	}
}
add_action( 'admin_init', 'gfee_deactivate_init' );

function gfee_configuration_alert( $form, $is_new ) {
	if ( ! $is_new && rgget( 'isnew' ) == 1 ) {
		echo "<script type='text/javascript'>
				var action = confirm( 'Would you like to setup this form for Export Entries?' );
				if ( action === true ) {
					var url = '/wp-admin/admin.php?page=gf_settings&subview=gforms-export-entries';
					var win = window.open( url, '_blank' );
					win.focus();
				}
		</script>";
	}
	return $form;
}
add_action( 'gform_after_save_form', 'gfee_configuration_alert', 10, 2 );

/**
 * Update old settings to new settings
 * This function should be called whenever an export is run
 * Also runs when admin page is loaded
 */
function gfee_update_settings() {
    $has_updated = get_option( 'gfee_has_updated_1_7_3', false );
    if ( $has_updated ) {
        //= This site has already had it's settings updated so stop here and return
        return false;
    }

    //= Get our old settings
    $old_settings = get_option( 'gfee_settings', array() );

    //= Create an array to store our settings in - pre-populate it with the old settings
    $settings = $old_settings;

    //= Get all of the forms from Gravity Forms
    $forms = GFAPI::get_forms();

    //= Do exports exist? If not then bail
    if ( ! isset( $old_settings['exports'] ) ) {
        return false;
    }
    
	//= create array to store form names and ids for later lookup
	$form_ids = array();
	$field_ids = array();
    //= Loop each form and add it to our array
	foreach( $forms as $form ) {
		//= add form title and id to $form_ids array using our clean format
		$form_ids[ gfee_clean_title( $form['title'] ) ] = $form['id'];
    }
    
    //= Loop each export
    foreach( $old_settings['exports'] as $export=>$export_settings ) {
        //= Do the form settings exist?
        if ( ! isset( $export_settings['forms'] ) ) {
            //= No forms have been setup previously so we can quit here
            return false;
        }
        //= Make sure our new settings array has a blank set of form settings, since that's what we'll be changing
        $settings['exports'][ $export ]['forms'] = array();
        //= Loop each form
        foreach( $export_settings['forms'] as $form_name=>$form_settings ) {
            //= Get the Gravity Forms form id from form name in the array we created above
            if ( isset( $form_ids[ $form_name ] ) ) {
                $form_id = $form_ids[ $form_name ];
            } else {
                //= If the form doesn't exist then just punt and make it a zero
                $form_id = 0;
            }
            //= Add this form's settings to our new settings array
            $settings['exports'][ $export ]['forms'][ $form_id ] = $form_settings;
        }
    }

    //= Save the setting that tells us the update has been done
    update_option( 'gfee_has_updated_1_7_3', true, false );
    update_option( 'gfee_settings_old', $old_settings, false );
    update_option( 'gfee_settings', $settings, false );
    $x = '';
    $x .= '<h1 class="gfee_alert">' . __( 'Settings have been updated to version 1.7.3 - please reload page', 'gforms-export-entries' ) . '</h1>';
    
    return $x;
}
?>