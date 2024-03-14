<?php
/*
 * Plugin Name: Zara 4
 * Plugin URI: https://zara4.com
 * Description: Compress your images.
 * Author: Zara 4
 * Version: 1.2.17.2
 * Author URI: https://zara4.com
 * License GPL2
 */
if( ! class_exists( 'Zara4_WordPressPlugin' ) ) {


	/**
	 * Class Zara4_WordPressPlugin
	 *
	 * @author	support@zara4.com
	 * @version 1.2.17.2
	 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
	 */
	if( ! defined( 'ZARA4_DEV' ) ) { define( 'ZARA4_DEV', isset( $_SERVER['ZARA4_DEV'] ) && $_SERVER['ZARA4_DEV'] ); }
  if( ! defined( 'ZARA4_VERSION' ) ) { define( 'ZARA4_VERSION', '1.2.17.2' ); }
  if( ! defined( 'ZARA4_PLUGIN_BASE_URL' ) ) { define( 'ZARA4_PLUGIN_BASE_URL', plugins_url( '', __FILE__) ); }
	class Zara4_WordPressPlugin {

		const SETTINGS_OPTION_NAME = '_zara4_settings';
		const OPTIMISATION_OPTION_NAME = '_zara4_optimisation';

		private $settings = array();



		/**
		 * Construct a new Zara4_WordPressPlugin
		 */
		function __construct() {

			//
			// Import libraries
			//
			$plugin_directory = dirname( __FILE__ );

      require_once( $plugin_directory . '/lib/WordPress/Install/Installer.php' );
      require_once( $plugin_directory . '/lib/WordPress/Install/Database.php' );

      require_once( $plugin_directory . '/lib/WordPress/AssetLoader.php' );
      require_once( $plugin_directory . '/lib/WordPress/AdminNotice.php' );
      require_once( $plugin_directory . '/lib/WordPress/Database.php' );
      require_once( $plugin_directory . '/lib/WordPress/DashboardWidget.php' );
      require_once( $plugin_directory . '/lib/WordPress/Util.php' );
      require_once( $plugin_directory . '/lib/WordPress/UsingTestCredentialsException.php' );
      require_once( $plugin_directory . '/lib/WordPress/View.php' );
      require_once( $plugin_directory . '/lib/WordPress/Ajax.php' );
      require_once( $plugin_directory . '/lib/WordPress/EventHandler.php' );
      require_once( $plugin_directory . '/lib/WordPress/MediaTable.php' );
      require_once( $plugin_directory . '/lib/WordPress/Settings.php' );
      require_once( $plugin_directory . '/lib/WordPress/Validation.php' );
      require_once( $plugin_directory . '/lib/WordPress/Attachment/Attachment.php');
      require_once( $plugin_directory . '/lib/WordPress/Zara4.php' );
      require_once( $plugin_directory . '/lib/WordPress/Attachment/ImageFile/ImageFile.php');
      require_once( $plugin_directory . '/lib/WordPress/Attachment/ImageFile/MetaData.php');
      require_once( $plugin_directory . '/lib/WordPress/Attachment/ImageFile/MetaDataFile.php');
      require_once( $plugin_directory . '/lib/WordPress/Attachment/ImageFile/MetaDataDatabase.php');
      require_once( $plugin_directory . '/lib/WordPress/Attachment/ImageFile/MetaDataRecord.php');

			require_once( $plugin_directory . '/lib/API/Communication/Util.php' );
			require_once( $plugin_directory . '/lib/API/Exception.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Exception.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Config.php' );
			require_once( $plugin_directory . '/lib/API/Communication/AccessDeniedException.php' );
			require_once( $plugin_directory . '/lib/API/Communication/AccessToken/AccessToken.php' );
			require_once( $plugin_directory . '/lib/API/Communication/AccessToken/RefreshableAccessToken.php' );
			require_once( $plugin_directory . '/lib/API/Communication/AccessToken/ReissuableAccessToken.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Authentication/Authenticator.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Authentication/ApplicationAuthenticator.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Authentication/UserAuthenticator.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Grant/GrantRequest.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Grant/ClientCredentialsGrantRequest.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Grant/PasswordGrant.php' );
			require_once( $plugin_directory . '/lib/API/Communication/Grant/RefreshTokenGrant.php' );
			require_once( $plugin_directory . '/lib/API/ImageProcessing/Image.php' );



			//
			// Load Settings
			//
			$this->settings = new Zara4_WordPress_Settings();



      //
      // Installation
      //
      add_action( 'plugins_loaded', array( 'Zara4_WordPress_Install_Installer', 'install' ) );


      // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


			// Settings page
			add_action( 'admin_menu', function() {
        add_options_page( 'Zara 4 Settings', 'Zara 4', 'manage_options', 'zara-4', array( 'Zara4_WordPress_View', 'settings_page' ) );
      } );


      // Factory reset hook listener
      add_action( 'wp_loaded', array( 'Zara4_WordPress_View', 'factory_reset_hook' ) );


      // --- --- ---


			// Add settings link to plugin
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {
        return array_merge( $links, array(
          '<a href="' . admin_url( 'options-general.php?page=zara-4' ) . '">Settings</a>',
        ) );
      } );


			// Enqueue assets used by Zara 4
			add_action( 'admin_enqueue_scripts', array( 'Zara4_WordPress_AssetLoader', 'enqueue_assets' ) );


			// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

			//
			// Notices
			//
			if( ! $this->settings->has_api_credentials() ) {
				add_action( 'admin_notices', array( 'Zara4_WordPress_AdminNotice', 'continue_setup' ) );
			}


			// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

			//
			// Dashboard Widget - coming soon
			//
			add_action( 'wp_dashboard_setup', array( 'Zara4_WordPress_DashboardWidget', 'zara4_widget' ) );


			// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

			//
			// Media Columns
			//

			// Specify additional media columns
			add_filter( 'manage_media_columns', array( 'Zara4_WordPress_MediaTable', 'add_media_columns' ) );

			// Add media row data for new columns
			add_action( 'manage_media_custom_column', array( 'Zara4_WordPress_MediaTable', 'fill_media_columns' ), 10, 2 );


			// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

			//
			// AJAX routes
			//

      // Compress
			add_action( 'wp_ajax_zara4_compress', array( 'Zara4_WordPress_Ajax', 'compress' ) );
			add_action( 'wp_ajax_zara4_compress_sizes', array( 'Zara4_WordPress_Ajax', 'compress_sizes' ) );

      // Restore Backup
			add_action( 'wp_ajax_zara4_restore_backup', array( 'Zara4_WordPress_Ajax', 'restore_backup' ) );
      add_action( 'wp_ajax_zara4_restore_backup_for_sizes', array( 'Zara4_WordPress_Ajax', 'restore_backup_for_sizes' ) );

      // Delete Backup
			add_action( 'wp_ajax_zara4_delete_backup', array( 'Zara4_WordPress_Ajax', 'delete_backup' ) );
			add_action( 'wp_ajax_zara4_delete_backup_for_sizes', array( 'Zara4_WordPress_Ajax', 'delete_backup_for_sizes' ) );

      // Bulk
      add_action( 'wp_ajax_zara4_exclude_from_bulk_compression', array( 'Zara4_WordPress_Ajax', 'exclude_from_bulk_compression' ) );
      add_action( 'wp_ajax_zara4_include_in_bulk_compression', array( 'Zara4_WordPress_Ajax', 'include_in_bulk_compression' ) );
      add_action( 'wp_ajax_zara4_exclude_all_uncompressed_images_from_bulk_compression', array( 'Zara4_WordPress_Ajax', 'exclude_all_uncompressed_images_from_bulk_compression' ) );
      add_action( 'wp_ajax_zara4_include_all_uncompressed_images_in_bulk_compression', array( 'Zara4_WordPress_Ajax', 'include_all_uncompressed_images_in_bulk_compression' ) );

      // Information
			add_action( 'wp_ajax_zara4_uncompressed_images', array( 'Zara4_WordPress_Ajax', 'uncompressed_images' ) );
			add_action( 'wp_ajax_zara4_image_classification_counts', array( 'Zara4_WordPress_Ajax', 'image_classification_counts' ) );
			add_action( 'wp_ajax_zara4_compression_info', array( 'Zara4_WordPress_Ajax', 'compression_info' ) );

      // Backup
      add_action( 'wp_ajax_zara4_images_with_backup', array( 'Zara4_WordPress_Ajax', 'images_with_backup' ) );
      add_action( 'wp_ajax_zara4_delete_all_backups', array( 'Zara4_WordPress_Ajax', 'delete_all_backups' ) );

			// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

			//
			// Event hooks
			//

			// This function generates metadata for an image attachment. It also creates a thumbnail and other intermediate
			// sizes of the image attachment based on the sizes defined on the Settings_Media_Screen.
			// See https://codex.wordpress.org/Function_Reference/wp_generate_attachment_metadata
			add_filter( 'wp_generate_attachment_metadata', array( 'Zara4_WordPress_EventHandler', 'upload_attachment'), 10, 2 );


			// Handle when an attachment (including media image) is deleted
			add_action( 'delete_attachment', array( 'Zara4_WordPress_EventHandler', 'delete_attachment' ) );


		}


	}
}


// --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


//
// Boot Zara 4 Plugin
//
new Zara4_WordPressPlugin();