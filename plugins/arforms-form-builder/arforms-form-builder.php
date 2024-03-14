<?php
/**
 * Plugin Name: ARForms Form Builder
 * Description: Most Powerful Form Builder to create wide variety of forms within a minute
 * Version: 1.6.3
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Plugin URI: http://www.arformsplugin.com/
 * Author: Repute InfoSystems
 * Author URI: http://www.arformsplugin.com/
 * Text Domain: arforms-form-builder
 * Domain Path: /languages
 *
 * @package ARForms
 */

if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'MSIE' ) !== false ) ) {
	header( 'X-UA-Compatible: IE=edge,chrome=1' );
}

define( 'ARFLITE_FORMPATH', WP_PLUGIN_DIR . '/arforms-form-builder' );
define( 'ARFLITE_MODELS_PATH', ARFLITE_FORMPATH . '/core/models' );
define( 'ARFLITE_VIEWS_PATH', ARFLITE_FORMPATH . '/core/views' );
define( 'ARFLITE_HELPERS_PATH', ARFLITE_FORMPATH . '/core/helpers' );
define( 'ARFLITE_CONTROLLERS_PATH', ARFLITE_FORMPATH . '/core/controllers' );
define( 'ARF_CLASS_PATH', ARFLITE_FORMPATH . '/core/classes/');

if ( ! defined( 'FS_METHOD' ) ) {
	define( 'FS_METHOD', 'direct' );
}
define( 'ARFLITE_PLUGIN_BASE_FILE', plugin_basename( __FILE__ ) );

$arflitesiteurl = home_url();
if ( is_ssl() && ( ! preg_match( '/^https:\/\/.*\..*$/', $arflitesiteurl ) || ! preg_match( '/^https:\/\/.*\..*$/', WP_PLUGIN_URL ) ) ) {
	$arflitesiteurl = str_replace( 'http://', 'https://', $arflitesiteurl );
	define( 'ARFLITEURL', str_replace( 'http://', 'https://', WP_PLUGIN_URL . '/arforms-form-builder' ) );
} else {
	define( 'ARFLITEURL', WP_PLUGIN_URL . '/arforms-form-builder' );
}

if ( is_ssl() ) {
	define( 'ARFLITE_HOME_URL', home_url( '', 'https' ) );
} else {
	define( 'ARFLITE_HOME_URL', home_url() );
}


if ( ! defined( 'ARFLITE_FILEDRAG_SCRIPT_URL' ) ) {
	define( 'ARFLITE_FILEDRAG_SCRIPT_URL', plugins_url( '', __FILE__ ) );
}
if ( ! function_exists( 'WP_Filesystem' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}

WP_Filesystem();
global $wp_filesystem;

define( 'ARFLITESCRIPTURL', $arflitesiteurl . ( is_admin() ? '/wp-admin' : '' ) . '/?plugin=ARFormslite' );
define( 'ARFLITEIMAGESURL', ARFLITEURL . '/images' );

$wp_upload_dir       = wp_upload_dir();
$imageupload_dir     = $wp_upload_dir['basedir'] . '/arforms-form-builder/userfiles/';
$imageupload_dir_sub = $wp_upload_dir['basedir'] . '/arforms-form-builder/userfiles/thumbs/';
$import_preset_value = $wp_upload_dir['basedir'] . '/arforms-form-builder/import_preset_value/';

$arf_file     = '/index.php';
$file_content = '<?php /* silence is golden */';

if ( ! is_dir( $imageupload_dir ) ) {
	wp_mkdir_p( $imageupload_dir );
}

define( 'ARFLITE_UPLOAD_USERFILE_DIR', $imageupload_dir );

$arflite_imageuploadindex_file = $imageupload_dir . $arf_file;
if ( is_dir( $imageupload_dir ) && ! file_exists( $arflite_imageuploadindex_file ) ) {
	if ( '' == $file_content || ! $wp_filesystem->put_contents( $arflite_imageuploadindex_file, $file_content, 0775 ) ) {
		return false;
	}
}

if ( ! is_dir( $imageupload_dir_sub ) ) {
	wp_mkdir_p( $imageupload_dir_sub );
}
define( 'ARFLITE_UPLOAD_USERFILE_THUMB_DIR', $imageupload_dir_sub );

$arflite_imageupload_dir_sub_file = $imageupload_dir_sub . $arf_file;
if ( is_dir( $imageupload_dir_sub ) && ! file_exists( $arflite_imageupload_dir_sub_file ) ) {
	if ( '' == $file_content || ! $wp_filesystem->put_contents( $arflite_imageupload_dir_sub_file, $file_content, 0775 ) ) {
		return false;
	}
}

if ( ! is_dir( $import_preset_value ) ) {
	wp_mkdir_p( $import_preset_value );
}

define( 'ARFLITE_UPLOAD_PRESET_FILE_DIR', $import_preset_value );

$arflite_import_present_val = $import_preset_value . $arf_file;
if ( is_dir( $import_preset_value ) && ! file_exists( $arflite_import_present_val ) ) {
	if ( '' == $file_content || ! $wp_filesystem->put_contents( $arflite_import_present_val, $file_content, 0775 ) ) {
		return false;
	}
}

$arflite_css_dir        = $wp_upload_dir['basedir'] . '/arforms-form-builder/css';
$arflite_index_css_file = $arflite_css_dir . $arf_file;

if ( is_dir( $arflite_css_dir ) && ! file_exists( $arflite_index_css_file ) ) {
	if ( '' == $file_content || ! $wp_filesystem->put_contents( $arflite_index_css_file, $file_content, 0775 ) ) {
	}
}

$arflite_maincss_dir        = $wp_upload_dir['basedir'] . '/arforms-form-builder/maincss';
$arflite_index_maincss_file = $arflite_maincss_dir . $arf_file;

if ( is_dir( $arflite_maincss_dir ) && ! file_exists( $arflite_index_maincss_file ) ) {
	if ( '' == $file_content || ! $wp_filesystem->put_contents( $arflite_index_maincss_file, $file_content, 0775 ) ) {
	}
}

define( 'ARFLITE_UPLOAD_URL', $wp_upload_dir['baseurl'] . '/arforms-form-builder' );

define( 'ARFLITE_UPLOAD_DIR', $wp_upload_dir['basedir'] . '/arforms-form-builder' );

if ( ! defined( 'IS_WPMU' ) ) {
	global $wpmu_version;
	$is_wpmu = ( ( function_exists( 'is_multisite' ) && is_multisite() ) || $wpmu_version ) ? 1 : 0;
	define( 'IS_WPMU', $is_wpmu );
}

global $arflitedbversion, $arfliteadvanceerrcolor, $arflite_memory_limit, $arflitememorylimit, $arflite_jscss_version, $arflite_plugin_slug;
$arfliteversion        = '1.6.3';
$arflitedbversion      = '1.6.3';
$arflite_jscss_version = $arfliteversion . '.' . rand( 10, 100 );
$arflite_memory_limit  = 256;
$arflitememorylimit    = ini_get( 'memory_limit' );
$arflite_plugin_slug   = basename( dirname( __FILE__ ) );

if ( isset( $arflitememorylimit ) ) {
	if ( preg_match( '/^(\d+)(.)$/', $arflitememorylimit, $matches ) ) {
		if ( 'M' == $matches[2] ) {
			$arflitememorylimit = $matches[1] * 1024 * 1024;
		} elseif ( 'K' == $matches[2] ) {
			$arflitememorylimit = $matches[1] * 1024;
		}
	}
} else {
	$arflitememorylimit = 0;
}

global $arfliteajaxurl;
$arfliteajaxurl = admin_url( 'admin-ajax.php' );

$geoip_file = ARFLITE_MODELS_PATH . '/geoip/autoload.php';
if ( file_exists( $geoip_file ) ) {
	include $geoip_file;
}
use GeoIp2\Database\Reader;

if ( ! function_exists( 'is_plugin_active' ) ) {
	include ABSPATH . '/wp-admin/includes/plugin.php';
}

$pro_plugin = 'arforms/arforms.php';

/**
 * Uninstallation function for ARFormslite
 *
 * @package ARFormslite
 */
function arflitepluginUninstall() {
	global $wpdb, $arflitesettingcontroller, $ARFLiteMdlDb;

	if ( IS_WPMU ) {

		$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				switch_to_blog( $blog['blog_id'] );

				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_fields' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_forms' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entries' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entry_values' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_settings' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_debug_log_setting' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_entries' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_entry_values' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_forms' );				
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_fields' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entries_159_backup' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entry_values_159_backup' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_fields_159_backup' );
				$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_forms_159_backup' );

				$wpdb->query( 'DELETE FROM `' . $wpdb->options . '` WHERE  `option_name` LIKE `%arf_previewtabledata%`' );

				delete_option( '_transient_arflite_options' );
				delete_option( '_transient_arfalite_options' );
				delete_option( 'arfalite_css' );
				delete_option( '_transient_arfalite_css' );
				delete_option( 'arflite_options' );
				delete_option( 'arflite_db_version' );
				delete_option( 'arforms_current_tab' );
				delete_option( 'arflitedefaultar' );
				delete_option( 'arfalite_options' );
				delete_option( 'widget_arformslite_widget_form' );
				delete_option( 'arflite_plugin_activated' );
				delete_option( 'is_arflite_submit' );
				delete_option( 'arflite_update_token' );
				delete_option( 'arfliteformcolumnlist' );
				delete_option( 'arfliteIsSorted' );
				delete_option( 'arfliteSortOrder' );
				delete_option( 'arfliteSortId' );
				delete_option( 'arfliteSortInfo' );
				delete_option( 'arflite_form_entry_separator' );
				delete_option( 'arflite_previewoptions' );

				$prefix            = $wpdb->get_blog_prefix( $blog['blog_id'] );
				$pro_form_table    = $prefix . 'arf_forms';
				$is_pro_form_table = $wpdb->query( "SHOW TABLES LIKE '" . $pro_form_table . "' " ); //phpcs:ignore

				if ( $is_pro_form_table > 0 ) {
					$pro_field_table            = $prefix . 'arf_fields';
					$pro_ar_table               = $prefix . 'arf_ar';
					$pro_entries                = $prefix . 'arf_entries';
					$pro_entry_meta             = $prefix . 'arf_entry_values';
					$pro_incomplete_entries     = $prefix . 'arf_incomplete_formdata';
					$pro_incomplete_entry_metas = $prefix . 'arf_incomlete_form_values';
					$pro_popup_forms            = $prefix . 'arf_popup_forms';
					$pro_forms_views            = $prefix . 'arf_views';

					$all_lite_forms = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM `{$pro_form_table}` WHERE arf_is_lite_form = %d", 1 ) );//phpcs:ignore

					if ( ! empty( $all_lite_forms ) ) {
						foreach ( $all_lite_forms as $lite_form ) {
							$lite_form_id = $lite_form->id;

							/* Delete Tables */
							$wpdb->delete( $pro_form_table, array( 'id' => $lite_form_id ) );

							/* Get Entries */
							$all_lite_form_fields = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM `{$pro_entries}` WHERE form_id = %d", $lite_form_id ) );//phpcs:ignore

							if ( ! empty( $all_lite_form_fields ) ) {
								foreach ( $all_lite_form_fields as $pro_entry ) {
									$entry_id = $pro_entry->id;

									/* Delete Entry Values */
									$wpdb->delete( $pro_entry_meta, array( 'entry_id' => $entry_id ) );
								}
							}

							/* Delete Fields */
							$wpdb->delete( $pro_field_table, array( 'form_id' => $lite_form_id ) );

							/* Delete Entry */
							$wpdb->delete( $pro_entries, array( 'form_id' => $lite_form_id ) );

							/* Get Incomplete Entries */
							$all_pro_incomplete_entries = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM `{$pro_incomplete_entries}` WHERE form_id = %d", $lite_form_id ) );//phpcs:ignore

							if ( ! empty( $all_pro_incomplete_entries ) ) {
								foreach ( $all_pro_incomplete_entries as $pro_incomplete_entry ) {
									$inc_entry_id = $pro_incomplete_entry->id;

									/* Delete incomplete Entry Values */
									$wpdb->delete( $pro_incomplete_entry_metas, array( 'entry_id' => $inc_entry_id ) );
								}
							}

							/* Delete Incomplete Entry */
							$wpdb->delete( $pro_incomplete_entries, array( 'form_id' => $lite_form_id ) );

							/* Delete Popup Forms */
							$wpdb->delete( $pro_popup_forms, array( 'form_id' => $lite_form_id ) );

							/* Delete Analytics */
							$wpdb->delete( $pro_forms_views, array( 'form_id' => $lite_form_id ) );

						}
					}
				}
			}
			restore_current_blog();
		}
	} else {

		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_autoresponder' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_fields' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_forms' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entries' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entry_values' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_debug_log_setting' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_ar' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_views' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_settings' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_debug_log_setting' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_entries' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_entry_values' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_forms' );				
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arf_fields' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entries_159_backup' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_entry_values_159_backup' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_fields_159_backup' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'arflite_forms_159_backup' );

		delete_option( '_transient_arflite_options' );
		delete_option( '_transient_arfalite_options' );
		delete_option( 'arfalite_css' );
		delete_option( '_transient_arfalite_css' );
		delete_option( 'arflite_options' );
		delete_option( 'arflite_db_version' );
		delete_option( 'arforms_current_tab' );
		delete_option( 'arflitedefaultar' );
		delete_option( 'arfalite_options' );
		delete_option( 'widget_arformslite_widget_form' );
		delete_option( 'arflite_plugin_activated' );
		delete_option( 'is_arflite_submit' );
		delete_option( 'arflite_update_token' );
		delete_option( 'arfliteformcolumnlist' );
		delete_option( 'arfliteIsSorted' );
		delete_option( 'arfliteSortOrder' );
		delete_option( 'arfliteSortId' );
		delete_option( 'arfliteSortInfo' );
		delete_option( 'arflite_form_entry_separator' );
		delete_option( 'arflite_previewoptions' );

		delete_transient( 'arflite_addon_listing_dashboard_page' );
		delete_transient( 'arflite_sample_listing_page' );

		$wpdb->query( 'DELETE FROM `' . $wpdb->options . "` WHERE  `option_name` LIKE  '%arflite_previewtabledata%'" );

		$prefix = $wpdb->prefix;

		$pro_form_table    = $prefix . 'arf_forms';
		$is_pro_form_table = $wpdb->query( "SHOW TABLES LIKE '" . $pro_form_table . "' " );//phpcs:ignore

		if ( $is_pro_form_table > 0 ) {
			$pro_field_table            = $prefix . 'arf_fields';
			$pro_ar_table               = $prefix . 'arf_ar';
			$pro_entries                = $prefix . 'arf_entries';
			$pro_entry_meta             = $prefix . 'arf_entry_values';
			$pro_incomplete_entries     = $prefix . 'arf_incomplete_formdata';
			$pro_incomplete_entry_metas = $prefix . 'arf_incomlete_form_values';
			$pro_popup_forms            = $prefix . 'arf_popup_forms';
			$pro_forms_views            = $prefix . 'arf_views';

			$all_lite_forms = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM `{$pro_form_table}` WHERE arf_is_lite_form = %d", 1 ) );//phpcs:ignore

			if ( ! empty( $all_lite_forms ) ) {
				foreach ( $all_lite_forms as $lite_form ) {
					$lite_form_id = $lite_form->id;

					/* Delete Tables */
					$wpdb->delete( $pro_form_table, array( 'id' => $lite_form_id ) );

					/* Get Entries */
					$all_lite_form_fields = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM `{$pro_entries}` WHERE form_id = %d", $lite_form_id ) );//phpcs:ignore

					if ( ! empty( $all_lite_form_fields ) ) {
						foreach ( $all_lite_form_fields as $pro_entry ) {
							$entry_id = $pro_entry->id;

							/* Delete Entry Values */
							$wpdb->delete( $pro_entry_meta, array( 'entry_id' => $entry_id ) );
						}
					}

					/* Delete Fields */
					$wpdb->delete( $pro_field_table, array( 'form_id' => $lite_form_id ) );

					/* Delete Entry */
					$wpdb->delete( $pro_entries, array( 'form_id' => $lite_form_id ) );

					/* Get Incomplete Entries */
					$all_pro_incomplete_entries = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM `{$pro_incomplete_entries}` WHERE form_id = %d", $lite_form_id ) );//phpcs:ignore

					if ( ! empty( $all_pro_incomplete_entries ) ) {
						foreach ( $all_pro_incomplete_entries as $pro_incomplete_entry ) {
							$inc_entry_id = $pro_incomplete_entry->id;

							/* Delete incomplete Entry Values */
							$wpdb->delete( $pro_incomplete_entry_metas, array( 'entry_id' => $inc_entry_id ) );
						}
					}

					/* Delete Incomplete Entry */
					$wpdb->delete( $pro_incomplete_entries, array( 'form_id' => $lite_form_id ) );

					/* Delete Popup Forms */
					$wpdb->delete( $pro_popup_forms, array( 'form_id' => $lite_form_id ) );

					/* Delete Analytics */
					$wpdb->delete( $pro_forms_views, array( 'form_id' => $lite_form_id ) );

				}
			}
		}
	}
}

register_uninstall_hook( __FILE__, 'arflitepluginUninstall' );

require_once __DIR__.'/autoload.php';

/* if ( is_plugin_active( $pro_plugin ) ) {

	require_once ARFLITE_CONTROLLERS_PATH . '/arfliteprocontroller.php';

} else { */

	global $arflitesiteurl, $arflite_is_active_cornorstone;
	$arflite_is_active_cornorstone = false;

	global $arflite_glb_preset_data;
	$arflite_glb_preset_data = array();

	$cs_splugin = 'cornerstone/cornerstone.php';
	if ( is_plugin_active( $cs_splugin ) ) {
		$arflite_is_active_cornorstone = true;
	}

	if ( $arflite_is_active_cornorstone ) {
		define( 'ARFLITE_CSURL', ARFLITEURL . '/arformslite_cs' );
		define( 'ARFLITE_CSDIR', ARFLITE_FORMPATH . '/arformslite_cs' );
	}

	/* add class display-blck-cls with display block property*/
	define( 'ARFLITE_LOADER_ICON', '<div class="arf_loader_icon_wrapper display-blck-cls" id="{arf_id}"><div class="arf_loader_icon_box"><div class="arf-spinner arf-skeleton arf-grid-loader"></div></div></div>' );


	define( 'ARFLITE_PLUS_ICON', '<path fill-rule="evenodd" clip-rule="evenodd" fill="#3f74e7" d="M11.134,20.362c-5.521,0-9.996-4.476-9.996-9.996c0-5.521,4.476-9.997,9.996-9.997s9.996,4.476,9.996,9.997C21.13,15.887,16.654,20.362,11.134,20.362z M11.133,2.314c-4.446,0-8.051,3.604-8.051,8.051c0,4.447,3.604,8.052,8.051,8.052s8.052-3.604,8.052-8.052C19.185,5.919,15.579,2.314,11.133,2.314z M12.146,14.341h-2v-3h-3v-2h3V6.372h2v2.969h3v2h-3V14.341z"/>' );

	define( 'ARFLITE_MINUS_ICON', '<path fill-rule="evenodd" clip-rule="evenodd" fill="#3f74e7" d="M11.12,20.389c-5.521,0-9.996-4.476-9.996-9.996c0-5.521,4.476-9.997,9.996-9.997s9.996,4.476,9.996,9.997C21.116,15.913,16.64,20.389,11.12,20.389z M11.119,2.341c-4.446,0-8.051,3.604-8.051,8.051c0,4.447,3.604,8.052,8.051,8.052s8.052-3.604,8.052-8.052C19.17,5.945,15.565,2.341,11.119,2.341z M12.131,11.367h3v-2h-3h-2h-3v2h3H12.131z" />' );

	define( 'ARFLITE_CUSTOM_UNCHECKED_ICON', '<path id="arfcheckbox_unchecked" d="M15.643,17.617H3.499c-1.34,0-2.427-1.087-2.427-2.429V3.045  c0-1.341,1.087-2.428,2.427-2.428h12.144c1.342,0,2.429,1.087,2.429,2.428v12.143C18.072,16.53,16.984,17.617,15.643,17.617z   M16.182,2.477H2.961v13.221h13.221V2.477z" />' );

	define( 'ARFLITE_CUSTOM_CHECKED_ICON', '<path id="arfcheckbox_checked" d="M15.645,17.62H3.501c-1.34,0-2.427-1.087-2.427-2.429V3.048  c0-1.341,1.087-2.428,2.427-2.428h12.144c1.342,0,2.429,1.087,2.429,2.428v12.143C18.074,16.533,16.986,17.62,15.645,17.62z   M16.184,2.48H2.963v13.221h13.221V2.48z M5.851,7.15l2.716,2.717l5.145-5.145l1.718,1.717l-5.146,5.145l0.007,0.007l-1.717,1.717  l-0.007-0.008l-0.006,0.008l-1.718-1.717l0.007-0.007L4.134,8.868L5.851,7.15z" />' );

	define( 'ARFLITE_CUSTOM_UNCHECKED_ICON_EDITOR', '' );
	define( 'ARFLITE_CUSTOM_CHECKED_ICON_EDITOR', '<path fill="#353942" d="M7.698,13.386c-0.365,0-0.731-0.14-1.01-0.418L1.641,7.919c-0.558-0.558-0.558-1.462,0-2.02s1.461-0.558,2.019,0  l4.039,4.039l9.086-9.086c0.558-0.558,1.462-0.558,2.019,0c0.558,0.558,0.558,1.462,0,2.019L8.708,12.967  C8.429,13.246,8.063,13.386,7.698,13.386z"/>' );

	define( 'ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON', '<path id="arfradio" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#B3BBCB" d="M9.03,16.688c-4.418,0-8-3.583-8-8.001s3.582-8.001,8-8.001  s8,3.583,8,8.001S13.448,16.688,9.03,16.688z M9.029,2.887c-3.203,0-5.798,2.596-5.798,5.799s2.596,5.799,5.798,5.799  c3.203,0,5.8-2.596,5.8-5.799S12.232,2.887,9.029,2.887z"/>' );

	define( 'ARFLITE_CUSTOM_CHECKEDRADIO_ICON', '<path id="arfradio_checked" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#03A9F4" d="M9.03,16.688c-4.418,0-8-3.583-8-8.001s3.582-8.001,8-8.001  s8,3.583,8,8.001S13.448,16.688,9.03,16.688z M9.029,2.887c-3.203,0-5.798,2.596-5.798,5.799s2.596,5.799,5.798,5.799  c3.203,0,5.8-2.596,5.8-5.799S12.232,2.887,9.029,2.887z M9.03,12.117c-1.895,0-3.43-1.537-3.43-3.43c0-1.895,1.535-3.43,3.43-3.43  c1.894,0,3.43,1.535,3.43,3.43C12.46,10.58,10.924,12.117,9.03,12.117z"/>' );

	define(
		'ARFLITE_CUSTOM_REQUIRED_ICON',
		'<path d="M16.975,7.696l-0.732-2.717l-6.167,1.865l0.312-6.276H7.562l0.31,6.276L1.666,4.979L0.975,7.696L7.1,8.939l-3.69,5.574
		l2.327,1.555l3.218-5.734l3.259,5.734l2.286-1.555L10.85,8.939L16.975,7.696z" fill="#ffffff"/>'
	);

	define( 'ARFLITE_CUSTOM_MULTICOLUMN_ICON', "<path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#9EABC9' d='M9.489,8.85l0.023-2h6l-0.024,2H9.489z M9.489,2.85l0.023-2h6  l-0.024,2H9.489z M1.489,14.85l0.023-2h5.969l-0.023,2H1.489z M1.489,8.85l0.023-2h5.969l-0.023,2H1.489z M1.489,2.85l0.023-2h5.969  l-0.023,2H1.489z M15.512,12.85l-0.024,2H9.489l0.023-2H15.512z'/>" );

	define( 'ARFLITE_CUSTOM_CUSTOMCSS_ICON', "<path xmlns='http://www.w3.org/2000/svg' fill='#9EABC9' d='M5.451,7.921V4.386c0-0.469,0.207-0.912,0.584-1.248c0.376-0.335,0.873-0.521,1.397-0.521V0.85  c-2.18,0-3.962,1.591-3.962,3.536v2.651c0,0.488-0.444,0.884-0.991,0.884h-0.99V9.69h0.99c0.547,0,0.991,0.396,0.991,0.884v2.652  c0,1.944,1.782,3.535,3.962,3.535v-1.768c-0.524,0-1.021-0.185-1.397-0.521c-0.377-0.336-0.584-0.779-0.584-1.247V9.69  c0-0.488-0.443-0.885-0.99-0.885C5.007,8.806,5.451,8.41,5.451,7.921z M13.375,9.69v3.536c0,0.468-0.207,0.911-0.583,1.247  c-0.377,0.336-0.873,0.521-1.398,0.521v1.769c2.18,0,3.963-1.592,3.963-3.536v-2.652c0-0.488,0.443-0.884,0.99-0.884h0.991V7.921  h-0.991c-0.547,0-0.99-0.396-0.99-0.884V4.386c0-1.945-1.783-3.536-3.963-3.536v1.768c0.525,0,1.021,0.186,1.398,0.521  c0.376,0.336,0.583,0.778,0.583,1.247v3.536c0,0.487,0.444,0.884,0.991,0.884C13.82,8.806,13.375,9.202,13.375,9.69z'/>" );

	define(
		'ARFLITE_CUSTOM_FIELDOPTION_ICON',
		'<path fill="#ffffff" d="M17.947,15.47l-1.633-1.362c0.584-0.854,0.973-1.824,1.139-2.838l2.172,0.175
		c0.232-0.002,0.42-0.189,0.42-0.421l-0.008-1.995c0-0.232-0.188-0.419-0.42-0.419l-2.201,0.197
		c-0.193-1.006-0.604-1.958-1.201-2.787l1.662-1.425c0.078-0.078,0.121-0.185,0.121-0.297c0-0.111-0.045-0.219-0.123-0.296
		l-1.414-1.406c-0.164-0.163-0.432-0.162-0.594,0.002l-1.42,1.706c-0.826-0.561-1.762-0.94-2.74-1.111l0.174-2.22
		c0-0.232-0.189-0.42-0.422-0.419L9.467,0.561c-0.232,0.001-0.42,0.19-0.42,0.421l0.197,2.22C8.26,3.379,7.318,3.771,6.492,4.344
		l-1.42-1.672C4.906,2.508,4.641,2.509,4.479,2.673L3.072,4.089C2.994,4.168,2.949,4.275,2.951,4.386
		c0,0.111,0.045,0.218,0.123,0.297L4.74,6.078C4.156,6.907,3.756,7.856,3.57,8.854L1.463,8.671c-0.23,0.001-0.418,0.189-0.418,0.422
		l0.006,1.994c0.002,0.232,0.189,0.42,0.422,0.419l2.074-0.188c0.17,1.005,0.561,1.965,1.143,2.811L3.07,15.483
		c-0.164,0.165-0.164,0.432,0.002,0.595l1.412,1.405c0.08,0.078,0.188,0.123,0.299,0.122C4.893,17.604,5,17.56,5.078,17.481
		l1.338-1.596c0.855,0.609,1.836,1.019,2.869,1.198l-0.184,2.06c0,0.232,0.189,0.42,0.422,0.419l1.992-0.007
		c0.232,0,0.42-0.19,0.42-0.421l-0.188-2.06c1.023-0.184,1.996-0.597,2.844-1.204l1.355,1.611c0.16,0.156,0.438,0.156,0.594-0.002
		l1.406-1.415C18.111,15.899,18.109,15.633,17.947,15.47z M10.561,15.223c-2.852,0.01-5.17-2.295-5.178-5.146
		c-0.008-2.853,2.295-5.172,5.146-5.182c2.85-0.01,5.168,2.294,5.178,5.146C15.715,12.893,13.41,15.213,10.561,15.223z"/>'
	);

	define(
		'ARFLITE_CUSTOM_COL1_ICON',
		'<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M1.059,14.666v-2h17v2H1.059z M1.059,6.666h17v2h-17V6.666z M1.059,0.666h17v2h-17
	  V0.666z"/>'
	);

	define(
		'ARFLITE_CUSTOM_COL2_ICON',
		'<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M15.047,14.714v-2H27.03v2H15.047z M15.047,6.714H27.03v2H15.047V6.714z
	   M15.047,0.714H27.03v2H15.047V0.714z M1.031,12.714h12.015v2H1.031V12.714z M1.03,6.714h12.015v2H1.03V6.714z M1.03,0.714h12.015v2
	  H1.03V0.714z"/>'
	);

	define( 'ARFLITE_CUSTOM_COL3_ICON', '<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M18.07,14.615v-2h6.853v2H18.07z M18.069,6.615h6.853v2h-6.853V6.615zM18.069,0.615h6.853v2h-6.853V0.615z M9.497,12.615h6.853v2H9.497V12.615z M9.496,6.615h6.853v2H9.496V6.615z M9.496,0.615h6.853v2H9.496V0.615z M0.923,12.615h6.853v2H0.923V12.615z M0.922,6.615h6.853v2H0.922V6.615z M0.922,0.615h6.853v2H0.922V0.615z"/>' );

	define(
		'ARFLITE_CUSTOM_COL4_ICON',
		'<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M27.928,14.646v-2h6.995v2H27.928z M27.927,6.646h6.995v2h-6.995V6.646z
	   M27.927,0.646h6.995v2h-6.995V0.646z M18.927,12.646h6.995v2h-6.995V12.646z M18.926,6.646h6.995v2h-6.995V6.646z M18.926,0.646
	  h6.995v2h-6.995V0.646z M9.925,12.646h6.995v2H9.925V12.646z M9.924,6.646h6.995v2H9.924V6.646z M9.924,0.646h6.995v2H9.924V0.646z
	   M0.924,12.646h6.996v2H0.924V12.646z M0.923,6.646h6.996v2H0.923V6.646z M0.923,0.646h6.996v2H0.923V0.646z"/>'
	);

	define(
		'ARFLITE_CUSTOM_COL5_ICON',
		'<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M34.931,14.599v-2h6.056v2H34.931z M34.93,6.599h6.056v2H34.93V6.599z
	   M34.93,0.599h6.056v2H34.93V0.599z M26.445,12.599h6.057v2h-6.057V12.599z M26.444,6.599H32.5v2h-6.056V6.599z M26.444,0.599H32.5
	  v2h-6.056V0.599z M17.959,12.599h6.057v2h-6.057V12.599z M17.958,6.599h6.056v2h-6.056V6.599z M17.958,0.599h6.056v2h-6.056V0.599z
	   M9.474,12.599h6.057v2H9.474V12.599z M9.473,6.599h6.056v2H9.473V6.599z M9.473,0.599h6.056v2H9.473V0.599z M0.988,12.599h6.057v2
	  H0.988V12.599z M0.987,6.599h6.057v2H0.987V6.599z M0.987,0.599h6.057v2H0.987V0.599z"/>'
	);

	define(
		'ARFLITE_CUSTOM_COL6_ICON',
		'<path fill="#ffffff" fill-rule="evenodd" clip-rule="evenodd" d="M36.022,14.568v-2h4.996v2H36.022z M36.021,6.568h4.996v2h-4.996V6.568z
	   M36.021,0.568h4.996v2h-4.996V0.568z M29.021,12.568h4.996v2h-4.996V12.568z M29.021,6.568h4.996v2h-4.996V6.568z M29.021,0.568
	  h4.996v2h-4.996V0.568z M22.021,12.568h4.996v2h-4.996V12.568z M22.02,6.568h4.996v2H22.02V6.568z M22.02,0.568h4.996v2H22.02V0.568
	  z M15.021,12.568h4.996v2h-4.996V12.568z M15.02,6.568h4.996v2H15.02V6.568z M15.02,0.568h4.996v2H15.02V0.568z M8.02,12.568h4.996
	  v2H8.02V12.568z M8.019,6.568h4.996v2H8.019V6.568z M8.019,0.568h4.996v2H8.019V0.568z M1.019,12.568h4.997v2H1.019V12.568z
	   M1.018,6.568h4.997v2H1.018V6.568z M1.018,0.568h4.997v2H1.018V0.568z"/>'
	);

	define( 'ARFLITE_CUSTOM_DUPLICATE_ITEM', "<path xmlns='http://www.w3.org/2000/svg' fill='#ffffff' d='M9.465,0.85h-6.72c-0.691,0-1.257,0.565-1.257,1.256v8.733H3.47V2.827h5.995V0.85z M13.227,3.833H5.728  c-0.691,0-1.258,0.565-1.258,1.257v11.509c0,0.691,0.566,1.257,1.258,1.257h7.499c0.691,0,1.257-0.565,1.257-1.257V5.089  C14.484,4.398,13.918,3.833,13.227,3.833z M12.465,15.869H6.469V5.837h5.996V15.869z'/>" );

	define( 'ARFLITE_CUSTOM_DELETE_ICON', "<path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M16.939,5.845h-1.415V17.3c0,0.292-0.236,0.529-0.529,0.529H4.055  c-0.292,0-0.529-0.237-0.529-0.529V5.845H2.018c-0.292,0-0.529-0.739-0.529-1.031s0.237-0.982,0.529-0.982h2.509V1.379  c0-0.293,0.237-0.529,0.529-0.529h8.954c0.293,0,0.529,0.236,0.529,0.529v2.452h2.399c0.292,0,0.529,0.69,0.529,0.982  S17.231,5.845,16.939,5.845z M12.533,2.811H6.517v1.011h6.016V2.811z M13.541,5.845l-0.277-0.031L5.788,5.845H5.534v10.001h8.007  V5.845z M8.525,13.849H7.534v-6.08h0.991V13.849z M11.525,13.849h-0.991v-6.08h0.991V13.849z' />" );

	define( 'ARFLITE_CUSTOM_MOVE_ICON', "<path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#3f74e7' stroke='#3f74e7' d='M18.401,9.574l-3.092,3.092  c-0.06,0.061-0.139,0.091-0.218,0.091s-0.159-0.03-0.219-0.091c-0.121-0.121-0.121-0.316,0-0.438l2.563-2.564H11.69  c-0.171,0-0.309-0.139-0.309-0.31c0-0.17,0.138-0.309,0.309-0.309h5.746l-2.563-2.564c-0.121-0.121-0.121-0.316,0-0.438  c0.12-0.121,0.316-0.121,0.437,0l3.092,3.092c0.028,0.029,0.051,0.063,0.066,0.101c0.031,0.076,0.031,0.161,0,0.236  C18.452,9.51,18.429,9.544,18.401,9.574z M13.081,4.56c-0.079,0-0.158-0.03-0.218-0.091l-2.563-2.564v5.748  c0,0.171-0.139,0.31-0.31,0.31s-0.31-0.139-0.31-0.31V1.905L7.117,4.469C7.057,4.53,6.978,4.56,6.899,4.56S6.741,4.53,6.68,4.469  c-0.121-0.12-0.121-0.316,0-0.437L9.771,0.94c0.028-0.028,0.063-0.051,0.101-0.066c0.075-0.031,0.161-0.031,0.236,0  c0.038,0.016,0.072,0.038,0.101,0.066l3.091,3.093c0.121,0.12,0.121,0.316,0,0.437C13.239,4.53,13.161,4.56,13.081,4.56z   M2.543,9.045H8.29c0.171,0,0.309,0.139,0.309,0.309c0,0.171-0.138,0.31-0.309,0.31H2.543l2.563,2.564  c0.121,0.121,0.121,0.316,0,0.438c-0.06,0.061-0.139,0.091-0.218,0.091c-0.08,0-0.158-0.03-0.219-0.091L1.58,9.574  C1.55,9.544,1.528,9.51,1.512,9.472c-0.031-0.075-0.031-0.16,0-0.236C1.528,9.198,1.55,9.164,1.58,9.135L4.67,6.043  c0.12-0.121,0.316-0.121,0.437,0c0.121,0.121,0.121,0.316,0,0.438L2.543,9.045z M7.117,14.239l2.563,2.564v-5.747  c0-0.171,0.139-0.31,0.31-0.31s0.31,0.139,0.31,0.31v5.747l2.563-2.564c0.121-0.12,0.315-0.12,0.437,0  c0.121,0.121,0.121,0.316,0,0.438l-3.091,3.092c-0.028,0.029-0.063,0.052-0.101,0.067S10.03,17.86,9.99,17.86  s-0.08-0.009-0.118-0.024s-0.072-0.038-0.101-0.067L6.68,14.676c-0.121-0.121-0.121-0.316,0-0.438  C6.801,14.119,6.997,14.119,7.117,14.239z' />" );

	define( 'ARFLITE_CUSTOM_CLOSE_BUTTON', "<path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#333333' d='M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249  L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z' />" );

	define( 'ARFLITE_TOOLTIP_ICON', '<path xmlns="http://www.w3.org/2000/svg" d="M9.609,0.33c-4.714,0-8.5,3.786-8.5,8.5s3.786,8.5,8.5,8.5s8.5-3.786,8.5-8.5S14.323,0.33,9.609,0.33z   M10.381,13.467c0,0.23-0.154,0.387-0.387,0.387H9.222c-0.231,0-0.387-0.156-0.387-0.387v-0.772c0-0.231,0.155-0.388,0.387-0.388  h0.772c0.232,0,0.387,0.156,0.387,0.388V13.467z M11.425,10.028c-0.541,0.463-0.929,0.772-1.044,1.197  c-0.039,0.193-0.193,0.309-0.387,0.309H9.222c-0.231,0-0.426-0.193-0.387-0.425c0.155-1.12,0.966-1.738,1.623-2.279  c0.697-0.541,1.082-0.889,1.082-1.546c0-1.082-0.85-1.932-1.932-1.932s-1.933,0.85-1.933,1.932c0,0.078,0,0.154,0,0.232  c0.04,0.192-0.077,0.386-0.27,0.425L6.672,8.173C6.44,8.25,6.208,8.096,6.169,7.864C6.131,7.67,6.131,7.478,6.131,7.284  c0-1.932,1.545-3.478,3.478-3.478c1.932,0,3.477,1.546,3.477,3.478C13.085,8.714,12.16,9.448,11.425,10.028L11.425,10.028z" fill="#BEC5D5"/>' );

	define(
		'ARFLITE_CUSTOM_MOVING_ICON',
		'<path fill="#ffffff" d="M20.062,10.027l-3.563-3.563V8.84h-4.75V4.088h2.376l-3.563-3.562L6.999,4.088h2.375V8.84h-4.75V6.464
		l-3.563,3.563l3.563,3.563v-2.376h4.75v4.751H6.999l3.563,3.562l3.563-3.562h-2.376v-4.751h4.75v2.376L20.062,10.027z"/>'
	);

	define( 'ARFLITE_CUSTOM_RESET_ICON', '<path fill="#B4BACA" d="M83.803,13.197C74.896,5.009,63.023,0,50,0C22.43,0,0,22.43,0,50s22.43,50,50,50c13.763,0,26.243-5.59,35.293-14.618  l-9.895-9.895C68.883,81.979,59.902,86,50,86c-19.851,0-36-16.149-36-36s16.149-36,36-36c9.164,0,17.533,3.447,23.895,9.105L62,35  h20.713H96v-4.586V1L83.803,13.197z"/>' );

	define( 'ARFLITE_FIELD_EDIT_OPTION_ICON', '<path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M14.968,5.735l-0.223,0.22l-2.817-2.78l1.351-1.333l1.689,1.666   l3.599-3.552l1.351,1.333l-4.728,4.666L14.968,5.735z M0.923,8.951h9v3h-9V8.951z M0.923,1.951h9v3h-9V1.951z M14.968,10.507   l3.599-3.552l1.351,1.333l-4.728,4.666l-0.222-0.22l-0.223,0.22l-2.817-2.78l1.351-1.333L14.968,10.507z"/>' );

	define( 'ARFLITE_FIELD_HTML_RUNNING_TOTAL_ICON', '<path xmlns="http://www.w3.org/2000/svg" fill="#ffffff" d="M10.844,0.452H0.833v1.749L6.256,7.45l-5.423,5.249v1.749h10.011v-2.624H5.005L9.176,7.45L5.005,3.076 h5.839V0.452z"/>' );

	define( 'ARFLITE_FIELD_MULTICOLUMN_EXPAND_ICON', '<path xmlns="http://www.w3.org/2000/svg" fill="#ffffff" d="M8.88,8.166c0-0.269-0.096-0.538-0.287-0.742L2.549,0.977c-0.383-0.41-1.007-0.41-1.392,0   c-0.382,0.411-0.382,1.075,0,1.485l5.348,5.704L1.16,13.87c-0.385,0.409-0.385,1.075,0,1.485c0.383,0.411,1.007,0.411,1.39,0   l6.043-6.447C8.784,8.704,8.88,8.435,8.88,8.166z"/>' );

	define( 'ARFLITE_STAR_RATING_ICON', '<path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" d="M13.002-0.057l3.966,7.228l8.065,1.557l-5.615,6.024l1.019,8.19   l-7.436-3.505l-7.436,3.505l1.019-8.19L0.97,8.728l8.066-1.557L13.002-0.057"/>' );

	define( 'ARFLITE_LIFEBOUY_ICON', '<path fill="#FF5A5A" d="M10.079,0.623c-4.971,0-9,4.029-9,9s4.029,9,9,9s9-4.029,9-9C19.073,4.654,15.047,0.628,10.079,0.623z    M10.079,1.796c1.159-0.001,2.304,0.257,3.35,0.755l-2.133,2.132c-0.833-0.206-1.705-0.197-2.534,0.025L6.645,2.593   C7.713,2.068,8.888,1.795,10.079,1.796z M5.14,10.839l-2.132,2.133c-1.02-2.149-1.005-4.646,0.041-6.783l2.117,2.117   c-0.222,0.828-0.231,1.699-0.025,2.532V10.839z M10.079,17.449c-1.224,0.002-2.43-0.285-3.521-0.838l2.107-2.097   c0.893,0.26,1.841,0.27,2.739,0.027l2.109,2.11C12.444,17.177,11.269,17.45,10.079,17.449z M10.079,13.536   c-2.161,0-3.913-1.752-3.913-3.913s1.752-3.913,3.913-3.913s3.913,1.752,3.913,3.913S12.24,13.536,10.079,13.536z M17.905,9.623   c0.001,1.19-0.271,2.365-0.797,3.434l-2.116-2.117c0.242-0.898,0.232-1.846-0.027-2.739l2.103-2.1   C17.62,7.192,17.907,8.399,17.905,9.623z"/>' );

	define( 'ARFLITE_EDIT_ENTRY_ICON', '<path fill="#4786ff" d="M29.015,12.169l-0.808,0.809l0,0l-0.018,0.018l0,0l0,0 l-1.651,1.652l-2.478-2.479l1.669-1.669l0,0l0.809-0.808L29.015,12.169z M16.333,24.709h-2.336v-2.336L16.333,24.709z M18.02,16.669l-12,0.011v-1.979h12V16.669z M6.02,6.675h12v2.01h-12V6.675z M18.02,12.684h-12v-2.01h12V12.684z M25.711,15.474 l-8.433,8.435L14.8,21.431l6.203-6.204V2.699H2.995v23.972h18.008v-6.385L23,18.222v10.483H0.999V0.696H23v12.533l0.233-0.233 L25.711,15.474z"/>' );

	require_once ARFLITE_MODELS_PATH . '/arflitesettingmodel.php';
	require_once ARFLITE_MODELS_PATH . '/arflitestylemodel.php';
	require_once ARF_CLASS_PATH . '/class.arf_admin_notice.php';

	global $arfliteloadcss, $arflite_forms_loaded, $arflitecssloaded, $arflitesavedentries, $arflite_form_all_footer_js, $arflite_loaded_form_unique_id_array,$arflite_form_all_footer_css, $arflite_intval_keys;
	$arfliteloadcss                      = false;
	$arflitecssloaded                    = false;
	$arflite_forms_loaded                = array();
	$arflitesavedentries                 = array();
	$arflite_loaded_form_unique_id_array = array();
	$arflite_form_all_footer_js          = '';
	$arflite_form_all_footer_css         = '';
	$arflite_intval_keys                 = array( 'required', 'max', 'minlength', 'enable_arf_prefix', 'enable_arf_suffix', 'arf_enable_readonly', 'max_rows', 'phonetype', 'country_validation', 'clock', 'step', 'arf_show_min_current_date', 'arf_show_max_current_date', 'default_hour', 'default_minutes', 'round_total', 'round_total' );


	add_action( 'plugins_loaded', 'arf_arform_lite_load_textdomain' );

	/**
	 * Function to load text-domain
	 *
	 * @package ARFormslite
	 */
	function arf_arform_lite_load_textdomain() {
		load_plugin_textdomain( 'arforms-form-builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	require_once ARFLITE_HELPERS_PATH . '/arflitemainhelper.php';
	global $arflitemainhelper;
	$arflitemainhelper = new arflitemainhelper();

	require_once ARFLITE_MODELS_PATH . '/arfliteinstallermodel.php';
	require_once ARFLITE_MODELS_PATH . '/arflitefieldmodel.php';
	require_once ARFLITE_MODELS_PATH . '/arfliteformmodel.php';
	require_once ARFLITE_MODELS_PATH . '/arfliterecordmodel.php';
	require_once ARFLITE_MODELS_PATH . '/arfliterecordmeta.php';

	global $ARFLiteMdlDb;
	global $arflitefield;
	global $arfliteform;
	global $arflite_db_record;
	global $arfliterecordmeta;
	
	global $arflite_style_settings;
	global $arflitesettingmodel;
	
	$ARFLiteMdlDb        = new arfliteinstallermodel();
	$arflitefield        = new arflitefieldmodel();
	$arfliteform         = new arfliteformmodel();
	$arflite_db_record   = new arfliterecordmodel();
	$arfliterecordmeta   = new arfliterecordmeta();
	$arflitesettingmodel = new arflitesettingmodel();
	
	
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitemaincontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arfliteformcontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitespamfiltercontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arfliteelementcontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitefilecontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitegrowthplugincontroller.php';

	global $arflitemaincontroller;
	global $arfliteformcontroller;
	global $arflite_spam_filter_controller;
	global $arfliteelementcontroller;
	global $arfgrowthplugins;

	$arflitemaincontroller          = new arflitemaincontroller();
	$arfliteformcontroller          = new arfliteformcontroller();
	$arflite_spam_filter_controller = new arflitespamfiltercontroller();
	$arfliteelementcontroller       = new arfliteelementcontroller();
	$arfgrowthplugins    			= new arf_growth_plugin();

	require_once ARFLITE_HELPERS_PATH . '/arfliterecordhelper.php';
	require_once ARFLITE_HELPERS_PATH . '/arfliteformhelper.php';
	require_once ARFLITE_MODELS_PATH . '/arflitenotifymodel.php';

	global $arflitenotifymodel;
	$arflitenotifymodel = new arflitenotifymodel();

	require_once ARFLITE_CONTROLLERS_PATH . '/arfliterecordcontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitefieldcontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitesettingcontroller.php';
	require_once ARFLITE_CONTROLLERS_PATH . '/arflitesamplecontroller.php';

	global $arfliterecordcontroller;
	global $arflitefieldcontroller;
	global $arflitesettingcontroller;
	global $arflitesamplecontroller;

	$arfliterecordcontroller  = new arfliterecordcontroller();
	$arflitefieldcontroller   = new arflitefieldcontroller();
	$arflitesettingcontroller = new arflitesettingcontroller();
	$arflitesamplecontroller  = new arflitesamplecontroller();

	require_once ARFLITE_HELPERS_PATH . '/arflitefieldhelper.php';
	global $arflitefieldhelper;
	global $arfliterecordhelper;
	global $arfliteformhelper;
	$arflitefieldhelper  = new arflitefieldhelper();
	$arfliterecordhelper = new arfliterecordhelper();
	$arfliteformhelper   = new arfliteformhelper();

	global $arflitereadonly;
	$arflitereadonly = false;

	global $arfliteshowfields, $arflitedatepickerloaded;
	global $arflitetimepickerloaded, $arfliteinputmasks;

	$arfliteshowfields       = array();
	$arflitedatepickerloaded = array();
	$arflitetimepickerloaded = array();
	$arfliteinputmasks       = array();

	global $arflitepagesize;
	$arflitepagesize = 20;

	global $arflitesidebar_width;
	$arflitesidebar_width = '';

	global $arflite_column_classes;
	$arflite_column_classes = array();

	global $arflite_submit_ajax_page;
	$arflite_submit_ajax_page = 0;

	global $arflite_entries_action_column_width;
	$arflite_entries_action_column_width = 120;

	global $arflite_is_multi_column_loaded;
	$arflite_is_multi_column_loaded = array();

	global $arflite_custom_css_array;
	$arflite_custom_css_array = array(
		'arf_form_outer_wrapper'      => array(
			'id'          => 'form_outer_wrapper',
			'onclick_1'   => 'arf_form_outer_wrapper',
			'onclick_2'   => __( 'Form outer wrapper', 'arforms-form-builder' ),
			'label_title' => __( 'Form outer wrapper', 'arforms-form-builder' ),
		),
		'arf_form_inner_wrapper'      => array(
			'id'          => 'form_inner_wrapper',
			'onclick_1'   => 'arf_form_inner_wrapper',
			'onclick_2'   => __( 'Form inner wrapper', 'arforms-form-builder' ),
			'label_title' => __( 'Form inner wrapper', 'arforms-form-builder' ),
		),
		'arf_form_title'              => array(
			'id'          => 'form_title',
			'onclick_1'   => 'arf_form_title',
			'onclick_2'   => __( 'Form Title', 'arforms-form-builder' ),
			'label_title' => __( 'Form title', 'arforms-form-builder' ),
		),
		'arf_form_description'        => array(
			'id'          => 'form_description',
			'onclick_1'   => 'arf_form_description',
			'onclick_2'   => __( 'Form description', 'arforms-form-builder' ),
			'label_title' => __( 'Form description', 'arforms-form-builder' ),
		),
		'arf_form_element_wrapper'    => array(
			'id'          => 'field_wrapper',
			'onclick_1'   => 'arf_form_element_wrapper',
			'onclick_2'   => __( 'Field wrapper', 'arforms-form-builder' ),
			'label_title' => __( 'Field Wrapper', 'arforms-form-builder' ),
		),
		'arf_form_element_label'      => array(
			'id'          => 'field_label',
			'onclick_1'   => 'arf_form_element_label',
			'onclick_2'   => __( 'Field label', 'arforms-form-builder' ),
			'label_title' => __( 'Field label', 'arforms-form-builder' ),
		),
		'arf_form_text_elements'      => array(
			'id'          => 'text_elements',
			'onclick_1'   => 'arf_form_text_elements',
			'label_title' => __( 'Textbox Elements', 'arforms-form-builder' ),
		),
		'arf_form_textarea_elements'  => array(
			'id'          => 'textarea_elements',
			'onclick_1'   => 'arf_form_textarea_elements',
			'label_title' => __( 'Textarea Elements', 'arforms-form-builder' ),
		),
		'arf_form_phone_elements'     => array(
			'id'          => 'phone_elements',
			'onclick_1'   => 'arf_form_phone_elements',
			'label_title' => __( 'Phone Elements', 'arforms-form-builder' ),
		),
		'arf_form_number_elements'    => array(
			'id'          => 'number_elements',
			'onclick_1'   => 'arf_form_number_elements',
			'label_title' => __( 'Number Elements', 'arforms-form-builder' ),
		),
		'arf_form_email_elements'     => array(
			'id'          => 'email_elements',
			'onclick_1'   => 'arf_form_email_elements',
			'label_title' => __( 'Email Elements', 'arforms-form-builder' ),
		),
		'arf_form_date_elements'      => array(
			'id'          => 'date_elements',
			'onclick_1'   => 'arf_form_date_elements',
			'label_title' => __( 'Date Elements', 'arforms-form-builder' ),
		),
		'arf_form_time_elements'      => array(
			'id'          => 'time_elements',
			'onclick_1'   => 'arf_form_time_elements',
			'label_title' => __( 'Time Elements', 'arforms-form-builder' ),
		),
		'arf_form_url_elements'       => array(
			'id'          => 'url_elements',
			'onclick_1'   => 'arf_form_url_elements',
			'label_title' => __( 'Website Elements', 'arforms-form-builder' ),
		),
		'arf_form_image_url_elements' => array(
			'id'          => 'img_url_elements',
			'onclick_1'   => 'arf_form_image_url_elements',
			'label_title' => __( 'Image URL Elements', 'arforms-form-builder' ),
		),
		'arf_form_submit_button'      => array(
			'id'          => 'submit_wrapper',
			'onclick_1'   => 'arf_form_submit_button',
			'onclick_2'   => __( 'Submit Wrapper', 'arforms-form-builder' ),
			'label_title' => __( 'Submit Wrapper', 'arforms-form-builder' ),
		),
		'arf_form_success_message'    => array(
			'id'          => 'success_message',
			'onclick_1'   => 'arf_form_success_message',
			'onclick_2'   => __( 'Success Message', 'arforms-form-builder' ),
			'label_title' => __( 'Success Message', 'arforms-form-builder' ),
		),
		'arf_form_error_message'      => array(
			'id'          => 'validation_error',
			'onclick_1'   => 'arf_form_error_message',
			'onclick_2'   => __( 'Validation (error)', 'arforms-form-builder' ),
			'label_title' => __( 'Validation (error)', 'arforms-form-builder' ),
		),
	);

	global $arflite_is_arf_preview;
	$arflite_is_arf_preview = 0;

	require_once ABSPATH . 'wp-includes/pluggable.php';

	if ( class_exists( 'WP_Widget' ) ) {
		include_once ARFLITE_FORMPATH . '/core/widgets/arflitewidgetform.php';
		add_action( 'widgets_init', 'arflite_init_widget' );
	}

	/**
	 * Function to register ARFormslite WordPress Widget
	 *
	 * @package ARFormslite
	 */
	function arflite_init_widget() {
		$is_block_widget_editor = get_theme_support( 'widgets-block-editor' );
		if ( $is_block_widget_editor ) {
			return false;
		}
		return register_widget( 'ARFLITEwidgetForm' );
	}

	if ( file_exists( ARFLITE_FORMPATH . '/core/vc/arflite_class_vc_extend.php' ) ) {
		include_once ARFLITE_FORMPATH . '/core/vc/arflite_class_vc_extend.php';
		global $arformslite_vdextend;
		$arformslite_vdextend = new ARFormslite_VCExtendArp();
	}

	// adding smiley field.
	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_smiley_field.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_smiley_field.php';
	}

	// adding switch field.
	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_switch_field.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_switch_field.php';
	}

	// adding multiselect field
	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_multiselect_field.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_multiselect_field.php';
	}

	// adding matrix field
	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_matrix_field.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_matrix_field.php';
	}

	// adding spinner field
	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_spinner_field.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_spinner_field.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_post_value.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_post_value.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_conditional_redirect_to_url.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_conditional_redirect_to_url.php';
	}


	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_confirmation_summary.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_confirmation_summary.php';
	}


	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_field_type_conversion.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_field_type_conversion.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_convertkit.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_convertkit.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_hubspot.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_hubspot.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_mailerlite.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_mailerlite.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_sendinblue.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_sendinblue.php';
	}

	if ( file_exists( ARFLITE_VIEWS_PATH . '/arflite_drip.php' ) ) {
		include_once ARFLITE_VIEWS_PATH . '/arflite_drip.php';
	}

	global $arflite_fields_with_external_js, $arflite_bootstraped_fields_array;
	$arflite_fields_with_external_js  = array();
	$arflite_bootstraped_fields_array = apply_filters( 'arflite_bootstraped_field_from_outside', array( 'select', 'date', 'time' ) );


	global $arflite_installed_field_types;
	$arflite_installed_field_types = array(
		'text',
		'textarea',
		'checkbox',
		'radio',
		'select',
		'email',
		'captcha',
		'number',
		'phone',
		'date',
		'time',
		'url',
		'image',
		'hidden',
		'html',
	);

	global $arflite_date_check_arr;

	$arflite_date_check_arr = array(
		'MMM D, YYYY'  => 'M d,Y',
		'MM/DD/YYYY'   => 'm/d/Y',
		'MMMM D, YYYY' => 'F d, Y',
		'DD/MM/YYYY'   => 'd/m/Y',
		'D MMM, YYYY'  => 'd M, Y',
		'D MMMM, YYYY' => 'd F, Y',
		'YYYY/MM/DD'   => 'Y/m/d',
		'YYYY, MMM DD' => 'Y, M d',
		'YYYY, MMMM D' => 'Y, F d',
		'D.MMMM.YY'    => 'd.F.y',
		'D.MM.YYYY'    => 'd.m.Y',
		'YYYY.MM.D'    => 'Y.m.d',
		'D. MMMM YYYY' => 'd. F Y',

	);

	/**
	 * Function to define country telephone codes
	 *
	 * @package ARFormslite
	 */
	function arflite_get_country_code() {
		$country_code = array(
			0   => array(
				'name'      => __( 'Afghanistan', 'arforms-form-builder' ),
				'dial_code' => '+93',
				'code'      => 'af',
			),
			1   => array(
				'name'      => __( 'Aland Islands', 'arforms-form-builder' ),
				'dial_code' => '+358',
				'code'      => 'ax',
			),
			2   => array(
				'name'      => __( 'Albania', 'arforms-form-builder' ),
				'dial_code' => '+355',
				'code'      => 'al',
			),
			3   => array(
				'name'      => __( 'Algeria', 'arforms-form-builder' ),
				'dial_code' => '+213',
				'code'      => 'dz',
			),
			4   => array(
				'name'      => __( 'American Samoa', 'arforms-form-builder' ),
				'dial_code' => '+1684',
				'code'      => 'as',
			),
			5   => array(
				'name'      => __( 'Andorra', 'arforms-form-builder' ),
				'dial_code' => '+376',
				'code'      => 'ad',
			),
			6   => array(
				'name'      => __( 'Angola', 'arforms-form-builder' ),
				'dial_code' => '+244',
				'code'      => 'ao',
			),
			7   => array(
				'name'      => __( 'Anguilla', 'arforms-form-builder' ),
				'dial_code' => '+1264',
				'code'      => 'ai',
			),
			8   => array(
				'name'      => __( 'Antigua and Barbuda', 'arforms-form-builder' ),
				'dial_code' => '+1268',
				'code'      => 'ag',
			),
			9   => array(
				'name'      => __( 'Argentina', 'arforms-form-builder' ),
				'dial_code' => '+54',
				'code'      => 'ar',
			),
			10  => array(
				'name'      => __( 'Armenia', 'arforms-form-builder' ),
				'dial_code' => '+374',
				'code'      => 'am',
			),
			11  => array(
				'name'      => __( 'Aruba', 'arforms-form-builder' ),
				'dial_code' => '+297',
				'code'      => 'aw',
			),
			12  => array(
				'name'      => __( 'Australia', 'arforms-form-builder' ),
				'dial_code' => '+61',
				'code'      => 'au',
			),
			13  => array(
				'name'      => __( 'Austria', 'arforms-form-builder' ),
				'dial_code' => '+43',
				'code'      => 'at',
			),
			14  => array(
				'name'      => __( 'Azerbaijan', 'arforms-form-builder' ),
				'dial_code' => '+994',
				'code'      => 'az',
			),
			15  => array(
				'name'      => __( 'Bahamas', 'arforms-form-builder' ),
				'dial_code' => '+1242',
				'code'      => 'bs',
			),
			16  => array(
				'name'      => __( 'Bahrain', 'arforms-form-builder' ),
				'dial_code' => '+973',
				'code'      => 'bh',
			),
			17  => array(
				'name'      => __( 'Bangladesh', 'arforms-form-builder' ),
				'dial_code' => '+880',
				'code'      => 'bd',
			),
			18  => array(
				'name'      => __( 'Barbados', 'arforms-form-builder' ),
				'dial_code' => '+1246',
				'code'      => 'bb',
			),
			19  => array(
				'name'      => __( 'Belarus', 'arforms-form-builder' ),
				'dial_code' => '+375',
				'code'      => 'by',
			),
			20  => array(
				'name'      => __( 'Belgium', 'arforms-form-builder' ),
				'dial_code' => '+32',
				'code'      => 'be',
			),
			21  => array(
				'name'      => __( 'Belize', 'arforms-form-builder' ),
				'dial_code' => '+501',
				'code'      => 'bz',
			),
			22  => array(
				'name'      => __( 'Benin', 'arforms-form-builder' ),
				'dial_code' => '+229',
				'code'      => 'bj',
			),
			23  => array(
				'name'      => __( 'Bermuda', 'arforms-form-builder' ),
				'dial_code' => '+1441',
				'code'      => 'bm',
			),
			24  => array(
				'name'      => __( 'Bhutan', 'arforms-form-builder' ),
				'dial_code' => '+975',
				'code'      => 'bt',
			),
			25  => array(
				'name'      => __( 'Bolivia', 'arforms-form-builder' ),
				'dial_code' => '+591',
				'code'      => 'bo',
			),
			26  => array(
				'name'      => __( 'Bosnia and Herzegovina', 'arforms-form-builder' ),
				'dial_code' => '+387',
				'code'      => 'ba',
			),
			27  => array(
				'name'      => __( 'Botswana', 'arforms-form-builder' ),
				'dial_code' => '+267',
				'code'      => 'bw',
			),
			28  => array(
				'name'      => __( 'Brazil', 'arforms-form-builder' ),
				'dial_code' => '+55',
				'code'      => 'br',
			),
			29  => array(
				'name'      => __( 'British Indian Ocean Territory', 'arforms-form-builder' ),
				'dial_code' => '+246',
				'code'      => 'io',
			),
			30  => array(
				'name'      => __( 'British Virgin Islands', 'arforms-form-builder' ),
				'dial_code' => '+1284',
				'code'      => 'vg',
			),
			31  => array(
				'name'      => __( 'Brunei', 'arforms-form-builder' ),
				'dial_code' => '+673',
				'code'      => 'bn',
			),
			32  => array(
				'name'      => __( 'Bulgaria', 'arforms-form-builder' ),
				'dial_code' => '+359',
				'code'      => 'bg',
			),
			33  => array(
				'name'      => __( 'Burkina Faso', 'arforms-form-builder' ),
				'dial_code' => '+226',
				'code'      => 'bf',
			),
			34  => array(
				'name'      => __( 'Burundi', 'arforms-form-builder' ),
				'dial_code' => '+257',
				'code'      => 'bi',
			),
			35  => array(
				'name'      => __( 'Cambodia', 'arforms-form-builder' ),
				'dial_code' => '+855',
				'code'      => 'kh',
			),
			36  => array(
				'name'      => __( 'Cameroon', 'arforms-form-builder' ),
				'dial_code' => '+237',
				'code'      => 'cm',
			),
			37  => array(
				'name'      => __( 'Canada', 'arforms-form-builder' ),
				'dial_code' => '+1',
				'code'      => 'ca',
			),
			38  => array(
				'name'      => __( 'Cape Verde', 'arforms-form-builder' ),
				'dial_code' => '+238',
				'code'      => 'cv',
			),
			39  => array(
				'name'      => __( 'Caribbean Netherlands', 'arforms-form-builder' ),
				'dial_code' => '+599',
				'code'      => 'bq',
			),
			40  => array(
				'name'      => __( 'Cayman Islands', 'arforms-form-builder' ),
				'dial_code' => '+1345',
				'code'      => 'ky',
			),
			41  => array(
				'name'      => __( 'Central African Republic', 'arforms-form-builder' ),
				'dial_code' => '+236',
				'code'      => 'cf',
			),
			42  => array(
				'name'      => __( 'Chad', 'arforms-form-builder' ),
				'dial_code' => '+235',
				'code'      => 'td',
			),
			43  => array(
				'name'      => __( 'Chile', 'arforms-form-builder' ),
				'dial_code' => '+56',
				'code'      => 'cl',
			),
			44  => array(
				'name'      => __( 'China', 'arforms-form-builder' ),
				'dial_code' => '+86',
				'code'      => 'cn',
			),
			45  => array(
				'name'      => __( 'Christmas Island', 'arforms-form-builder' ),
				'dial_code' => '+61',
				'code'      => 'cx',
			),
			46  => array(
				'name'      => __( 'Cocos Islands', 'arforms-form-builder' ),
				'dial_code' => '+61',
				'code'      => 'cc',
			),
			47  => array(
				'name'      => __( 'Colombia', 'arforms-form-builder' ),
				'dial_code' => '+57',
				'code'      => 'co',
			),
			48  => array(
				'name'      => __( 'Comoros', 'arforms-form-builder' ),
				'dial_code' => '+269',
				'code'      => 'km',
			),
			49  => array(
				'name'      => __( 'Congo (DRC)', 'arforms-form-builder' ),
				'dial_code' => '+243',
				'code'      => 'cd',
			),
			50  => array(
				'name'      => __( 'Congo (Republic)', 'arforms-form-builder' ),
				'dial_code' => '+242',
				'code'      => 'cg',
			),
			51  => array(
				'name'      => __( 'Cook Islands', 'arforms-form-builder' ),
				'dial_code' => '+682',
				'code'      => 'ck',
			),
			52  => array(
				'name'      => __( 'Costa Rica', 'arforms-form-builder' ),
				'dial_code' => '+506',
				'code'      => 'cr',
			),
			53  => array(
				'name'      => __( 'Cote d\'Ivoire', 'arforms-form-builder' ),
				'dial_code' => '+225',
				'code'      => 'ci',
			),
			54  => array(
				'name'      => __( 'Croatia', 'arforms-form-builder' ),
				'dial_code' => '+385',
				'code'      => 'hr',
			),
			55  => array(
				'name'      => __( 'Cuba', 'arforms-form-builder' ),
				'dial_code' => '+53',
				'code'      => 'cu',
			),
			56  => array(
				'name'      => __( 'Curacao', 'arforms-form-builder' ),
				'dial_code' => '+599',
				'code'      => 'cw',
			),
			57  => array(
				'name'      => __( 'Cyprus', 'arforms-form-builder' ),
				'dial_code' => '+357',
				'code'      => 'cy',
			),
			58  => array(
				'name'      => __( 'Czech Republic', 'arforms-form-builder' ),
				'dial_code' => '+420',
				'code'      => 'cz',
			),
			59  => array(
				'name'      => __( 'Denmark', 'arforms-form-builder' ),
				'dial_code' => '+45',
				'code'      => 'dk',
			),
			60  => array(
				'name'      => __( 'Djibouti', 'arforms-form-builder' ),
				'dial_code' => '+253',
				'code'      => 'dj',
			),
			61  => array(
				'name'      => __( 'Dominica', 'arforms-form-builder' ),
				'dial_code' => '+1767',
				'code'      => 'dm',
			),
			62  => array(
				'name'      => __( 'Dominican Republic', 'arforms-form-builder' ),
				'dial_code' => '+1',
				'code'      => 'do',
			),
			63  => array(
				'name'      => __( 'Ecuador', 'arforms-form-builder' ),
				'dial_code' => '+593',
				'code'      => 'ec',
			),
			64  => array(
				'name'      => __( 'Egypt', 'arforms-form-builder' ),
				'dial_code' => '+20',
				'code'      => 'eg',
			),
			65  => array(
				'name'      => __( 'El Salvador', 'arforms-form-builder' ),
				'dial_code' => '+503',
				'code'      => 'sv',
			),
			66  => array(
				'name'      => __( 'Equatorial Guinea', 'arforms-form-builder' ),
				'dial_code' => '+240',
				'code'      => 'gq',
			),
			67  => array(
				'name'      => __( 'Eritrea', 'arforms-form-builder' ),
				'dial_code' => '+291',
				'code'      => 'er',
			),
			68  => array(
				'name'      => __( 'Estonia', 'arforms-form-builder' ),
				'dial_code' => '+372',
				'code'      => 'ee',
			),
			69  => array(
				'name'      => __( 'Ethiopia', 'arforms-form-builder' ),
				'dial_code' => '+251',
				'code'      => 'et',
			),
			70  => array(
				'name'      => __( 'Falkland Islands', 'arforms-form-builder' ),
				'dial_code' => '+500',
				'code'      => 'fk',
			),
			71  => array(
				'name'      => __( 'Faroe Islands', 'arforms-form-builder' ),
				'dial_code' => '+298',
				'code'      => 'fo',
			),
			72  => array(
				'name'      => __( 'Fiji', 'arforms-form-builder' ),
				'dial_code' => '+679',
				'code'      => 'fj',
			),
			73  => array(
				'name'      => __( 'Finland', 'arforms-form-builder' ),
				'dial_code' => '+358',
				'code'      => 'fi',
			),
			74  => array(
				'name'      => __( 'France', 'arforms-form-builder' ),
				'dial_code' => '+33',
				'code'      => 'fr',
			),
			75  => array(
				'name'      => __( 'French Guiana', 'arforms-form-builder' ),
				'dial_code' => '+594',
				'code'      => 'gf',
			),
			76  => array(
				'name'      => __( 'French Polynesia', 'arforms-form-builder' ),
				'dial_code' => '+689',
				'code'      => 'pf',
			),
			77  => array(
				'name'      => __( 'Gabon', 'arforms-form-builder' ),
				'dial_code' => '+241',
				'code'      => 'ga',
			),
			78  => array(
				'name'      => __( 'Gambia', 'arforms-form-builder' ),
				'dial_code' => '+220',
				'code'      => 'gm',
			),
			79  => array(
				'name'      => __( 'Georgia', 'arforms-form-builder' ),
				'dial_code' => '+995',
				'code'      => 'ge',
			),
			80  => array(
				'name'      => __( 'Germany', 'arforms-form-builder' ),
				'dial_code' => '+49',
				'code'      => 'de',
			),
			81  => array(
				'name'      => __( 'Ghana', 'arforms-form-builder' ),
				'dial_code' => '+233',
				'code'      => 'gh',
			),
			82  => array(
				'name'      => __( 'Gibraltar', 'arforms-form-builder' ),
				'dial_code' => '+350',
				'code'      => 'gi',
			),
			83  => array(
				'name'      => __( 'Greece', 'arforms-form-builder' ),
				'dial_code' => '+30',
				'code'      => 'gr',
			),
			84  => array(
				'name'      => __( 'Greenland', 'arforms-form-builder' ),
				'dial_code' => '+299',
				'code'      => 'gl',
			),
			85  => array(
				'name'      => __( 'Grenada', 'arforms-form-builder' ),
				'dial_code' => '+1473',
				'code'      => 'gd',
			),
			86  => array(
				'name'      => __( 'Guadeloupe', 'arforms-form-builder' ),
				'dial_code' => '+590',
				'code'      => 'gp',
			),
			87  => array(
				'name'      => __( 'Guam', 'arforms-form-builder' ),
				'dial_code' => '+1671',
				'code'      => 'gu',
			),
			88  => array(
				'name'      => __( 'Guatemala', 'arforms-form-builder' ),
				'dial_code' => '+502',
				'code'      => 'gt',
			),
			89  => array(
				'name'      => __( 'Guernsey', 'arforms-form-builder' ),
				'dial_code' => '+44',
				'code'      => 'gg',
			),
			90  => array(
				'name'      => __( 'Guinea', 'arforms-form-builder' ),
				'dial_code' => '+224',
				'code'      => 'gn',
			),
			91  => array(
				'name'      => __( 'Guinea-Bissau', 'arforms-form-builder' ),
				'dial_code' => '+245',
				'code'      => 'gw',
			),
			92  => array(
				'name'      => __( 'Guyana', 'arforms-form-builder' ),
				'dial_code' => '+592',
				'code'      => 'gy',
			),
			93  => array(
				'name'      => __( 'Haiti', 'arforms-form-builder' ),
				'dial_code' => '+509',
				'code'      => 'ht',
			),
			94  => array(
				'name'      => __( 'Honduras', 'arforms-form-builder' ),
				'dial_code' => '+504',
				'code'      => 'hn',
			),
			95  => array(
				'name'      => __( 'Hong Kong', 'arforms-form-builder' ),
				'dial_code' => '+852',
				'code'      => 'hk',
			),
			96  => array(
				'name'      => __( 'Hungary', 'arforms-form-builder' ),
				'dial_code' => '+36',
				'code'      => 'hu',
			),
			97  => array(
				'name'      => __( 'Iceland', 'arforms-form-builder' ),
				'dial_code' => '+354',
				'code'      => 'is',
			),
			98  => array(
				'name'      => __( 'India', 'arforms-form-builder' ),
				'dial_code' => '+91',
				'code'      => 'in',
			),
			99  => array(
				'name'      => __( 'Indonesia', 'arforms-form-builder' ),
				'dial_code' => '+62',
				'code'      => 'id',
			),
			100 => array(
				'name'      => __( 'Iran', 'arforms-form-builder' ),
				'dial_code' => '+98',
				'code'      => 'ir',
			),
			101 => array(
				'name'      => __( 'Iraq', 'arforms-form-builder' ),
				'dial_code' => '+964',
				'code'      => 'iq',
			),
			102 => array(
				'name'      => __( 'Ireland', 'arforms-form-builder' ),
				'dial_code' => '+353',
				'code'      => 'ie',
			),
			103 => array(
				'name'      => __( 'Isle of Man', 'arforms-form-builder' ),
				'dial_code' => '+44',
				'code'      => 'im',
			),
			104 => array(
				'name'      => __( 'Israel', 'arforms-form-builder' ),
				'dial_code' => '+972',
				'code'      => 'il',
			),
			105 => array(
				'name'      => __( 'Italy', 'arforms-form-builder' ),
				'dial_code' => '+39',
				'code'      => 'it',
			),
			106 => array(
				'name'      => __( 'Jamaica', 'arforms-form-builder' ),
				'dial_code' => '+1',
				'code'      => 'jm',
			),
			107 => array(
				'name'      => __( 'Japan', 'arforms-form-builder' ),
				'dial_code' => '+81',
				'code'      => 'jp',
			),
			108 => array(
				'name'      => __( 'Jersey', 'arforms-form-builder' ),
				'dial_code' => '+44',
				'code'      => 'je',
			),
			109 => array(
				'name'      => __( 'Jordan', 'arforms-form-builder' ),
				'dial_code' => '+962',
				'code'      => 'jo',
			),
			110 => array(
				'name'      => __( 'Kazakhstan', 'arforms-form-builder' ),
				'dial_code' => '+7',
				'code'      => 'kz',
			),
			111 => array(
				'name'      => __( 'Kenya', 'arforms-form-builder' ),
				'dial_code' => '+254',
				'code'      => 'ke',
			),
			112 => array(
				'name'      => __( 'Kiribati', 'arforms-form-builder' ),
				'dial_code' => '+686',
				'code'      => 'ki',
			),
			113 => array(
				'name'      => __( 'Kosovo', 'arforms-form-builder' ),
				'dial_code' => '+383',
				'code'      => 'xk',
			),
			114 => array(
				'name'      => __( 'Kuwait', 'arforms-form-builder' ),
				'dial_code' => '+965',
				'code'      => 'kw',
			),
			115 => array(
				'name'      => __( 'Kyrgyzstan', 'arforms-form-builder' ),
				'dial_code' => '+996',
				'code'      => 'kg',
			),
			116 => array(
				'name'      => __( 'Laos', 'arforms-form-builder' ),
				'dial_code' => '+856',
				'code'      => 'la',
			),
			117 => array(
				'name'      => __( 'Latvia', 'arforms-form-builder' ),
				'dial_code' => '+371',
				'code'      => 'lv',
			),
			118 => array(
				'name'      => __( 'Lebanon', 'arforms-form-builder' ),
				'dial_code' => '+961',
				'code'      => 'lb',
			),
			119 => array(
				'name'      => __( 'Lesotho', 'arforms-form-builder' ),
				'dial_code' => '+266',
				'code'      => 'ls',
			),
			120 => array(
				'name'      => __( 'Liberia', 'arforms-form-builder' ),
				'dial_code' => '+231',
				'code'      => 'lr',
			),
			121 => array(
				'name'      => __( 'Libya', 'arforms-form-builder' ),
				'dial_code' => '+218',
				'code'      => 'ly',
			),
			122 => array(
				'name'      => __( 'Liechtenstein', 'arforms-form-builder' ),
				'dial_code' => '+423',
				'code'      => 'li',
			),
			123 => array(
				'name'      => __( 'Lithuania', 'arforms-form-builder' ),
				'dial_code' => '+370',
				'code'      => 'lt',
			),
			124 => array(
				'name'      => __( 'Luxembourg', 'arforms-form-builder' ),
				'dial_code' => '+352',
				'code'      => 'lu',
			),
			125 => array(
				'name'      => __( 'Macau', 'arforms-form-builder' ),
				'dial_code' => '+853',
				'code'      => 'mo',
			),
			126 => array(
				'name'      => __( 'North Macedonia', 'arforms-form-builder' ),
				'dial_code' => '+389',
				'code'      => 'mk',
			),
			127 => array(
				'name'      => __( 'Madagascar', 'arforms-form-builder' ),
				'dial_code' => '+261',
				'code'      => 'mg',
			),
			128 => array(
				'name'      => __( 'Malawi', 'arforms-form-builder' ),
				'dial_code' => '+265',
				'code'      => 'mw',
			),
			129 => array(
				'name'      => __( 'Malaysia', 'arforms-form-builder' ),
				'dial_code' => '+60',
				'code'      => 'my',
			),
			130 => array(
				'name'      => __( 'Maldives', 'arforms-form-builder' ),
				'dial_code' => '+960',
				'code'      => 'mv',
			),
			131 => array(
				'name'      => __( 'Mali', 'arforms-form-builder' ),
				'dial_code' => '+223',
				'code'      => 'ml',
			),
			132 => array(
				'name'      => __( 'Malta', 'arforms-form-builder' ),
				'dial_code' => '+356',
				'code'      => 'mt',
			),
			133 => array(
				'name'      => __( 'Marshall Islands', 'arforms-form-builder' ),
				'dial_code' => '+692',
				'code'      => 'mh',
			),
			134 => array(
				'name'      => __( 'Martinique', 'arforms-form-builder' ),
				'dial_code' => '+596',
				'code'      => 'mq',
			),
			135 => array(
				'name'      => __( 'Mauritania', 'arforms-form-builder' ),
				'dial_code' => '+222',
				'code'      => 'mr',
			),
			136 => array(
				'name'      => __( 'Mauritius', 'arforms-form-builder' ),
				'dial_code' => '+230',
				'code'      => 'mu',
			),
			137 => array(
				'name'      => __( 'Mayotte', 'arforms-form-builder' ),
				'dial_code' => '+262',
				'code'      => 'yt',
			),
			138 => array(
				'name'      => __( 'Mexico', 'arforms-form-builder' ),
				'dial_code' => '+52',
				'code'      => 'mx',
			),
			139 => array(
				'name'      => __( 'Micronesia', 'arforms-form-builder' ),
				'dial_code' => '+691',
				'code'      => 'fm',
			),
			140 => array(
				'name'      => __( 'Moldova', 'arforms-form-builder' ),
				'dial_code' => '+373',
				'code'      => 'md',
			),
			141 => array(
				'name'      => __( 'Monaco', 'arforms-form-builder' ),
				'dial_code' => '+377',
				'code'      => 'mc',
			),
			142 => array(
				'name'      => __( 'Mongolia', 'arforms-form-builder' ),
				'dial_code' => '+976',
				'code'      => 'mn',
			),
			143 => array(
				'name'      => __( 'Montenegro', 'arforms-form-builder' ),
				'dial_code' => '+382',
				'code'      => 'me',
			),
			144 => array(
				'name'      => __( 'Montserrat', 'arforms-form-builder' ),
				'dial_code' => '+1664',
				'code'      => 'ms',
			),
			145 => array(
				'name'      => __( 'Morocco', 'arforms-form-builder' ),
				'dial_code' => '+212',
				'code'      => 'ma',
			),
			146 => array(
				'name'      => __( 'Mozambique', 'arforms-form-builder' ),
				'dial_code' => '+258',
				'code'      => 'mz',
			),
			147 => array(
				'name'      => __( 'Myanmar', 'arforms-form-builder' ),
				'dial_code' => '+95',
				'code'      => 'mm',
			),
			148 => array(
				'name'      => __( 'Namibia', 'arforms-form-builder' ),
				'dial_code' => '+264',
				'code'      => 'na',
			),
			149 => array(
				'name'      => __( 'Nauru', 'arforms-form-builder' ),
				'dial_code' => '+674',
				'code'      => 'nr',
			),
			150 => array(
				'name'      => __( 'Nepal', 'arforms-form-builder' ),
				'dial_code' => '+977',
				'code'      => 'np',
			),
			151 => array(
				'name'      => __( 'Netherlands', 'arforms-form-builder' ),
				'dial_code' => '+31',
				'code'      => 'nl',
			),
			152 => array(
				'name'      => __( 'New Caledonia', 'arforms-form-builder' ),
				'dial_code' => '+687',
				'code'      => 'nc',
			),
			153 => array(
				'name'      => __( 'New Zealand', 'arforms-form-builder' ),
				'dial_code' => '+64',
				'code'      => 'nz',
			),
			154 => array(
				'name'      => __( 'Nicaragua', 'arforms-form-builder' ),
				'dial_code' => '+505',
				'code'      => 'ni',
			),
			155 => array(
				'name'      => __( 'Niger', 'arforms-form-builder' ),
				'dial_code' => '+227',
				'code'      => 'ne',
			),
			156 => array(
				'name'      => __( 'Nigeria', 'arforms-form-builder' ),
				'dial_code' => '+234',
				'code'      => 'ng',
			),
			157 => array(
				'name'      => __( 'Niue', 'arforms-form-builder' ),
				'dial_code' => '+683',
				'code'      => 'nu',
			),
			158 => array(
				'name'      => __( 'Norfolk Island', 'arforms-form-builder' ),
				'dial_code' => '+672',
				'code'      => 'nf',
			),
			159 => array(
				'name'      => __( 'North Korea', 'arforms-form-builder' ),
				'dial_code' => '+850',
				'code'      => 'kp',
			),
			160 => array(
				'name'      => __( 'Northern Mariana Islands', 'arforms-form-builder' ),
				'dial_code' => '+1670',
				'code'      => 'mp',
			),
			161 => array(
				'name'      => __( 'Norway', 'arforms-form-builder' ),
				'dial_code' => '+47',
				'code'      => 'no',
			),
			162 => array(
				'name'      => __( 'Oman', 'arforms-form-builder' ),
				'dial_code' => '+968',
				'code'      => 'om',
			),
			163 => array(
				'name'      => __( 'Pakistan', 'arforms-form-builder' ),
				'dial_code' => '+92',
				'code'      => 'pk',
			),
			164 => array(
				'name'      => __( 'Palau', 'arforms-form-builder' ),
				'dial_code' => '+680',
				'code'      => 'pw',
			),
			165 => array(
				'name'      => __( 'Palestine', 'arforms-form-builder' ),
				'dial_code' => '+970',
				'code'      => 'ps',
			),
			166 => array(
				'name'      => __( 'Panama', 'arforms-form-builder' ),
				'dial_code' => '+507',
				'code'      => 'pa',
			),
			167 => array(
				'name'      => __( 'Papua New Guinea', 'arforms-form-builder' ),
				'dial_code' => '+675',
				'code'      => 'pg',
			),
			168 => array(
				'name'      => __( 'Paraguay', 'arforms-form-builder' ),
				'dial_code' => '+595',
				'code'      => 'py',
			),
			169 => array(
				'name'      => __( 'Peru', 'arforms-form-builder' ),
				'dial_code' => '+51',
				'code'      => 'pe',
			),
			170 => array(
				'name'      => __( 'Philippines', 'arforms-form-builder' ),
				'dial_code' => '+63',
				'code'      => 'ph',
			),
			171 => array(
				'name'      => __( 'Poland', 'arforms-form-builder' ),
				'dial_code' => '+48',
				'code'      => 'pl',
			),
			172 => array(
				'name'      => __( 'Portugal', 'arforms-form-builder' ),
				'dial_code' => '+351',
				'code'      => 'pt',
			),
			173 => array(
				'name'      => __( 'Puerto Rico', 'arforms-form-builder' ),
				'dial_code' => '+1',
				'code'      => 'pr',
			),
			174 => array(
				'name'      => __( 'Qatar', 'arforms-form-builder' ),
				'dial_code' => '+974',
				'code'      => 'qa',
			),
			175 => array(
				'name'      => __( 'Reunion', 'arforms-form-builder' ),
				'dial_code' => '+262',
				'code'      => 're',
			),
			176 => array(
				'name'      => __( 'Romania', 'arforms-form-builder' ),
				'dial_code' => '+40',
				'code'      => 'ro',
			),
			177 => array(
				'name'      => __( 'Russia', 'arforms-form-builder' ),
				'dial_code' => '+7',
				'code'      => 'ru',
			),
			178 => array(
				'name'      => __( 'Rwanda', 'arforms-form-builder' ),
				'dial_code' => '+250',
				'code'      => 'rw',
			),
			179 => array(
				'name'      => __( 'Saint Barthelemy', 'arforms-form-builder' ),
				'dial_code' => '+590',
				'code'      => 'bl',
			),
			180 => array(
				'name'      => __( 'Saint Helena', 'arforms-form-builder' ),
				'dial_code' => '+290',
				'code'      => 'sh',
			),
			181 => array(
				'name'      => __( 'Saint Kitts and Nevis', 'arforms-form-builder' ),
				'dial_code' => '+1869',
				'code'      => 'kn',
			),
			182 => array(
				'name'      => __( 'Saint Lucia', 'arforms-form-builder' ),
				'dial_code' => '+1758',
				'code'      => 'lc',
			),
			183 => array(
				'name'      => __( 'Saint Martin', 'arforms-form-builder' ),
				'dial_code' => '+590',
				'code'      => 'mf',
			),
			184 => array(
				'name'      => __( 'Saint Pierre and Miquelon', 'arforms-form-builder' ),
				'dial_code' => '+508',
				'code'      => 'pm',
			),
			185 => array(
				'name'      => __( 'Saint Vincent and the Grenadines', 'arforms-form-builder' ),
				'dial_code' => '+1784',
				'code'      => 'vc',
			),
			186 => array(
				'name'      => __( 'Samoa', 'arforms-form-builder' ),
				'dial_code' => '+685',
				'code'      => 'ws',
			),
			187 => array(
				'name'      => __( 'San Marino', 'arforms-form-builder' ),
				'dial_code' => '+378',
				'code'      => 'sm',
			),
			188 => array(
				'name'      => __( 'Sao Tome and Principe', 'arforms-form-builder' ),
				'dial_code' => '+239',
				'code'      => 'st',
			),
			189 => array(
				'name'      => __( 'Saudi Arabia', 'arforms-form-builder' ),
				'dial_code' => '+966',
				'code'      => 'sa',
			),
			190 => array(
				'name'      => __( 'Senegal', 'arforms-form-builder' ),
				'dial_code' => '+221',
				'code'      => 'sn',
			),
			191 => array(
				'name'      => __( 'Serbia', 'arforms-form-builder' ),
				'dial_code' => '+381',
				'code'      => 'rs',
			),
			192 => array(
				'name'      => __( 'Seychelles', 'arforms-form-builder' ),
				'dial_code' => '+248',
				'code'      => 'sc',
			),
			193 => array(
				'name'      => __( 'Sierra Leone', 'arforms-form-builder' ),
				'dial_code' => '+232',
				'code'      => 'sl',
			),
			194 => array(
				'name'      => __( 'Singapore', 'arforms-form-builder' ),
				'dial_code' => '+65',
				'code'      => 'sg',
			),
			195 => array(
				'name'      => __( 'Sint Maarten', 'arforms-form-builder' ),
				'dial_code' => '+1721',
				'code'      => 'sx',
			),
			196 => array(
				'name'      => __( 'Slovakia', 'arforms-form-builder' ),
				'dial_code' => '+421',
				'code'      => 'sk',
			),
			197 => array(
				'name'      => __( 'Slovenia', 'arforms-form-builder' ),
				'dial_code' => '+386',
				'code'      => 'si',
			),
			198 => array(
				'name'      => __( 'Solomon Islands', 'arforms-form-builder' ),
				'dial_code' => '+677',
				'code'      => 'sb',
			),
			199 => array(
				'name'      => __( 'Somalia', 'arforms-form-builder' ),
				'dial_code' => '+252',
				'code'      => 'so',
			),
			200 => array(
				'name'      => __( 'South Africa', 'arforms-form-builder' ),
				'dial_code' => '+27',
				'code'      => 'za',
			),
			201 => array(
				'name'      => __( 'South Korea', 'arforms-form-builder' ),
				'dial_code' => '+82',
				'code'      => 'kr',
			),
			202 => array(
				'name'      => __( 'South Sudan', 'arforms-form-builder' ),
				'dial_code' => '+211',
				'code'      => 'ss',
			),
			203 => array(
				'name'      => __( 'Spain', 'arforms-form-builder' ),
				'dial_code' => '+34',
				'code'      => 'es',
			),
			204 => array(
				'name'      => __( 'Sri Lanka', 'arforms-form-builder' ),
				'dial_code' => '+94',
				'code'      => 'lk',
			),
			205 => array(
				'name'      => __( 'Sudan', 'arforms-form-builder' ),
				'dial_code' => '+249',
				'code'      => 'sd',
			),
			206 => array(
				'name'      => __( 'Suriname', 'arforms-form-builder' ),
				'dial_code' => '+597',
				'code'      => 'sr',
			),
			207 => array(
				'name'      => __( 'Svalbard and Jan Mayen', 'arforms-form-builder' ),
				'dial_code' => '+47',
				'code'      => 'sj',
			),
			208 => array(
				'name'      => __( 'Swaziland', 'arforms-form-builder' ),
				'dial_code' => '+268',
				'code'      => 'sz',
			),
			209 => array(
				'name'      => __( 'Sweden', 'arforms-form-builder' ),
				'dial_code' => '+46',
				'code'      => 'se',
			),
			210 => array(
				'name'      => __( 'Switzerland', 'arforms-form-builder' ),
				'dial_code' => '+41',
				'code'      => 'ch',
			),
			211 => array(
				'name'      => __( 'Syria', 'arforms-form-builder' ),
				'dial_code' => '+963',
				'code'      => 'sy',
			),
			212 => array(
				'name'      => __( 'Taiwan', 'arforms-form-builder' ),
				'dial_code' => '+886',
				'code'      => 'tw',
			),
			213 => array(
				'name'      => __( 'Tajikistan', 'arforms-form-builder' ),
				'dial_code' => '+992',
				'code'      => 'tj',
			),
			214 => array(
				'name'      => __( 'Tanzania', 'arforms-form-builder' ),
				'dial_code' => '+255',
				'code'      => 'tz',
			),
			215 => array(
				'name'      => __( 'Thailand', 'arforms-form-builder' ),
				'dial_code' => '+66',
				'code'      => 'th',
			),
			216 => array(
				'name'      => __( 'Timor-Leste', 'arforms-form-builder' ),
				'dial_code' => '+670',
				'code'      => 'tl',
			),
			217 => array(
				'name'      => __( 'Togo', 'arforms-form-builder' ),
				'dial_code' => '+228',
				'code'      => 'tg',
			),
			218 => array(
				'name'      => __( 'Tokelau', 'arforms-form-builder' ),
				'dial_code' => '+690',
				'code'      => 'tk',
			),
			219 => array(
				'name'      => __( 'Tonga', 'arforms-form-builder' ),
				'dial_code' => '+676',
				'code'      => 'to',
			),
			220 => array(
				'name'      => __( 'Trinidad and Tobago', 'arforms-form-builder' ),
				'dial_code' => '+1868',
				'code'      => 'tt',
			),
			221 => array(
				'name'      => __( 'Tunisia', 'arforms-form-builder' ),
				'dial_code' => '+216',
				'code'      => 'tn',
			),
			222 => array(
				'name'      => __( 'Turkey', 'arforms-form-builder' ),
				'dial_code' => '+90',
				'code'      => 'tr',
			),
			223 => array(
				'name'      => __( 'Turkmenistan', 'arforms-form-builder' ),
				'dial_code' => '+993',
				'code'      => 'tm',
			),
			224 => array(
				'name'      => __( 'Turks and Caicos Islands', 'arforms-form-builder' ),
				'dial_code' => '+1649',
				'code'      => 'tc',
			),
			225 => array(
				'name'      => __( 'Tuvalu', 'arforms-form-builder' ),
				'dial_code' => '+688',
				'code'      => 'tv',
			),
			226 => array(
				'name'      => __( 'U.S. Virgin Islands', 'arforms-form-builder' ),
				'dial_code' => '+1340',
				'code'      => 'vi',
			),
			227 => array(
				'name'      => __( 'Uganda', 'arforms-form-builder' ),
				'dial_code' => '+256',
				'code'      => 'ug',
			),
			228 => array(
				'name'      => __( 'Ukraine', 'arforms-form-builder' ),
				'dial_code' => '+380',
				'code'      => 'ua',
			),
			229 => array(
				'name'      => __( 'United Arab Emirates', 'arforms-form-builder' ),
				'dial_code' => '+971',
				'code'      => 'ae',
			),
			230 => array(
				'name'      => __( 'United Kingdom', 'arforms-form-builder' ),
				'dial_code' => '+44',
				'code'      => 'gb',
			),
			231 => array(
				'name'      => __( 'United States', 'arforms-form-builder' ),
				'dial_code' => '+1',
				'code'      => 'us',
			),
			232 => array(
				'name'      => __( 'Uruguay', 'arforms-form-builder' ),
				'dial_code' => '+598',
				'code'      => 'uy',
			),
			233 => array(
				'name'      => __( 'Uzbekistan', 'arforms-form-builder' ),
				'dial_code' => '+998',
				'code'      => 'uz',
			),
			234 => array(
				'name'      => __( 'Vanuatu', 'arforms-form-builder' ),
				'dial_code' => '+678',
				'code'      => 'vu',
			),
			235 => array(
				'name'      => __( 'Vatican City', 'arforms-form-builder' ),
				'dial_code' => '+39',
				'code'      => 'va',
			),
			236 => array(
				'name'      => __( 'Venezuela', 'arforms-form-builder' ),
				'dial_code' => '+58',
				'code'      => 've',
			),
			237 => array(
				'name'      => __( 'Vietnam', 'arforms-form-builder' ),
				'dial_code' => '+84',
				'code'      => 'vn',
			),
			238 => array(
				'name'      => __( 'Wallis and Futuna', 'arforms-form-builder' ),
				'dial_code' => '+681',
				'code'      => 'wf',
			),
			239 => array(
				'name'      => __( 'Western Sahara', 'arforms-form-builder' ),
				'dial_code' => '+212',
				'code'      => 'eh',
			),
			240 => array(
				'name'      => __( 'Yemen', 'arforms-form-builder' ),
				'dial_code' => '+967',
				'code'      => 'ye',
			),
			241 => array(
				'name'      => __( 'Zambia', 'arforms-form-builder' ),
				'dial_code' => '+260',
				'code'      => 'zm',
			),
			242 => array(
				'name'      => __( 'Zimbabwe', 'arforms-form-builder' ),
				'dial_code' => '+263',
				'code'      => 'zw',
			),
		);
		return $country_code;
	}

	function arflite_retrieve_attrs_for_wp_kses( $prevent_script = false ) {
		$allowed_html_arr = array(
			'a'          => array(
				'title'  => array(),
				'href'   => array(),
				'target' => array(),
				'class'  => array(),
				'id'     => array(),
				'style'  => array(),
			),
			'arftotal'   => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'b'          => array(),
			'blockquote' => array(),
			'br'         => array(),
			'button'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
				'title' => array(),
			),
			'canvas'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'center'     => array(),
			'code'       => array(),
			'dd'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'del'        => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'div'        => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
				'title' => array(),
			),
			'dl'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'dt'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'em'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'embed'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'font'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'frame'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'frameset'   => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h1'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h2'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h3'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h4'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'h5'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'hr'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'i'          => array(),
			'iframe'     => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'img'        => array(
				'class'  => array(),
				'id'     => array(),
				'style'  => array(),
				'src'    => array(),
				'alt'    => array(),
				'height' => array(),
				'width'  => array(),
			),
			'label'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
				'for'   => array(),
			),
			'li'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'link'       => array(
				'href' => array(),
				'type' => array(),
			),
			'meta'       => array(),
			'object'     => array(),
			'ol'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'p'          => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'path'       => array(
				'id'        => array(),
				'd'         => array(),
				'fill'      => array(),
				'fill-rule' => array(),
				'clip-rule' => array(),
			),
			'pre'        => array(),
			'q'          => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
				'title' => array(),
			),
			'script'     => array(
				'src'  => array(),
				'type' => array(),
			),
			'strike'     => array(),
			'sub'        => array(),
			'sup'        => array(),
			'svg'        => array(
				'id'      => array(),
				'height'  => array(),
				'width'   => array(),
				'x'       => array(),
				'y'       => array(),
				'viewBox' => array(),
			),
			'strong'     => array(),
			'style'      => array(
				'type' => array(),
			),
			'tfooter'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'tbody'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'thead'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'th'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'td'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'tr'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'table'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'u'          => array(),
			'ul'         => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
		);

		if( true == $prevent_script ){
			unset( $allowed_html_arr['script'] );
		}
		return apply_filters( 'arflite_allowed_html_array_for_wp_kses', $allowed_html_arr );
	}

	function arflite_restricted_file_extension() {
		$restricted_extensions = array(
			'php',
			'php3',
			'php4',
			'php5',
			'pl',
			'py',
			'jsp',
			'asp',
			'exe',
			'cgi',
		);

		return apply_filters( 'arflite_restricted_file_extensions_outside', $restricted_extensions );
	}

	/**
	 * Function to retrieve country name from IP Address
	 *
	 * @param string $ip_address - value must be an IP address.
	 *
	 * @package ARFormslite
	 */
	function arflite_get_country_from_ip( $ip_address = '' ) {
		if ( '' == $ip_address ) {
			return '';
		}

		$country_reader = new Reader( ARFLITE_MODELS_PATH . '/geoip/inc/GeoLite2-Country.mmdb' );
		$country_name   = '';
		try {
			$record       = $country_reader->country( $ip_address );
			$country_name = $record->country->name;
		} catch ( Exception $e ) {
			$country_name = '';
		}
		return $country_name;
	}

	if ( ! function_exists( 'arflite_json_decode' ) ) {

		/**
		 * Function to decode the json data
		 *
		 * @param string/array/object $values   - can hold up the value of json string, array or object.
		 * @param boolean             $as_array - specify wheather json string need decode the value in array or object.
		 *
		 * @package ARFormslite
		 */
		function arflite_json_decode( $values, $as_array = false ) {

			if ( is_array( $values ) || is_object( $values ) ) {
				return $values;
			}

			$return_array = json_decode( $values, $as_array );
			if ( json_last_error() != JSON_ERROR_NONE ) {
				$return_array = maybe_unserialize( $values );
				if ( ! $as_array ) {
					$return_array = (object) $return_array;
				}
			}

			return $return_array;

		}
	}

	/**
	 * Function to remove directory that passed in the parameter.
	 *
	 * @param string $src - must be a valid and writable directory path.
	 *
	 * @package ARFormslite
	 */
	function arflite_rmdir( $src ) {
		if ( file_exists( $src ) ) {
			$dir = opendir( $src );
			while ( false !== ( $file = readdir( $dir ) ) ) {
				if ( ( '.' != $file ) && ( '..' != $file ) ) {
					$full = $src . '/' . $file;
					if ( is_dir( $full ) ) {
						@arflite_rmdir( $full );
					} else {
						@unlink( $full );
					}
				}
			}
			closedir( $dir );
			rmdir( $src );
		}
	}
//}