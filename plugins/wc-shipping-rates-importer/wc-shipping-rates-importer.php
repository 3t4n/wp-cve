<?php if (! defined('ABSPATH')) exit;
/**
 * Plugin Name: WC Shipping Rates Importer
 * Plugin URI: https://wordpress.org/plugins/wc-shipping-rates-importer/
 * Description: export and import Woocommerce Shipping Rates
 * Author: joesat
 * Version: 1.1.0
 * Author URI: https://wordpress.org/support/profile/joesat
 * License: GPL2
 */

/** define constants here */
define( 'WCSRI_SLUG', 'wcsri-shipping-rates-importer' );

define( 'WCSRI_DATA_DIR', dirname( __FILE__ ) . '/data/' );
define( 'WCSRI_TMPL_DIR', dirname( __FILE__ ) . '/tmpl/' );
define( 'WCSRI_INC_DIR', dirname( __FILE__ ) . '/includes/' );

define( 'WCSRI_FILE_UPLOAD_NAME', 'import_shipping_file' );

class WCSRI_Shipping_Rates_Importer {
	
	private $WCSRI_EXPORT_DIR;
	private $WCSRI_IMPORT_DIR;
	
	protected $_tables = array(
		'Zones' => 'woocommerce_shipping_zones',
		'Zone Locations' => 'woocommerce_shipping_zone_locations',
		'Zone Methods' => 'woocommerce_shipping_zone_methods',
		'Table Rates' => 'woocommerce_shipping_table_rates',
	);
	
	protected $_wc_installed = true;
	
	/**
	 * plugin class construct
	 *
	 * @since 1.0
	 *
	 */
	public function __construct( $data_dir ) {
		$this->WCSRI_EXPORT_DIR = $data_dir . '/export/';
		$this->WCSRI_IMPORT_DIR = $data_dir . '/import/';
		$this->init_hooks();
	}
	
	/**
	 * init language file, other resources and check pre-requisites
	 *
	 * @since 1.0
	 *
	 */
	public function init() {
		load_plugin_textdomain( WCSRI_SLUG, false, 
			dirname( plugin_basename(__FILE__) ) . '/lang/' );
			
		// add helper file
		require_once( WCSRI_INC_DIR . 'functions.php' );
		
		if ( ! function_exists( 'WC' ) ) {
			//add_action( 'admin_notices', array ( $this, 'notice_wc_not_installed' ) );
			$this->_wc_installed = false;
		}
	}
	
	/**
	 * call wp hooks to initialize plugin
	 *
	 * @since 1.0
	 *
	 */
	protected function init_hooks() {
		// add activation/deactivation hooks
		register_activation_hook ( __FILE__, array ( $this, 'install' ) );
		register_deactivation_hook ( __FILE__, array ( $this, 'uninstall' ) );
		
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'setup' ) );
	}
	
	/**
	 * setup admin menu page, and plugin page
	 *
	 * @since 1.0
	 *
	 */
	public function setup() {
		add_menu_page( __( 'WC Shipping Rates Importer' ), __( 'WC Import Shipping' ), 'manage_options', 
			WCSRI_SLUG, array( $this, 'main' ), '', 57  );

		// possibly add a submenu later
		//add_submenu_page( WCSRI_SLUG, __( 'WC Shipping Rates Importer' ), __( 'WC Import Shipping' ), 'manage_options', WCSRI_SLUG );
	}
	
	/**
	 * handles all requests and execute appropriate logic
	 *
	 * @since 1.0
	 *
	 */
	public function main () {
		$admin_notice = array();
		$import_file = '';		
		$wc_installed = $this->_wc_installed;
		$errorMsg = array();
		$importDisabled = false;
		$exportDisabled = false;
		
		if ( $_POST && $wc_installed ) {
			$action = strtolower( $_POST[WCSRI_SLUG . '-action'] );
			switch ($action) {
				case 'upload':
					$result = $this->_upload();
					if ( true === $result['status'] ) {
						// upload ok
						$admin_notice = array( 'success', __( 'File upload successful. Click Import to finish the import process.' ) );
						$import_file = $result['msg'];
						
						// fetch db stats
						$db_stats = $this->_get_zone_stats();
						
						// fetch upload stats
						$upload_stats = $this->_get_upload_stats($result['msg']);
					}
					else {
						// upload error
						$errorMsg[] =  $result['msg'];
					}
					break;
					
				case 'export':
					$this->_export();
					break;
					
				case 'import':
					$result = $this->_import( $_POST['import_file'] );
					$admin_notice = array( 'success', __( 'Import completed successfully.' ) );
					break;
					
				default:
					$errorMsg = __( 'Invalid action called.' );
			}
		}
		
		// always check data folder, if writeable
		if (!is_writable( $this->WCSRI_IMPORT_DIR ) ) {
			$errorMsg[] = __( 'Import directory not writeable. Please configure manually.') ;
			$importDisabled = true;
		}
		
		if (!is_writable( $this->WCSRI_EXPORT_DIR ) ) {
			$errorMsg[] = __( 'Export directory not writeable. Please configure manually.') ;
			$exportDisabled = true;
		}
		
		if ( $errorMsg ) {
			$admin_notice = array( 'error', implode( '<br>', $errorMsg ) );
		}
		
		// call the template
		include_once(WCSRI_TMPL_DIR . 'admin.php');
	}

	/**
	 * fetch current table row counts for shipping zone tables
	 *
	 * @since 1.0
	 *
	 */
	protected function _get_zone_stats() {
		global $wpdb;
		
		// init return array, 
		// table_rates pertains to WC Table Rate Shipping DB table, initialize to NULL
		$stats = array();
		
		foreach ( $this->_tables AS $table ) {
			$result = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . $table . "'", ARRAY_A);
			$stats[$table] = null; // initialize to null
			if ( !empty( $result ) ) {
				$result = $wpdb->get_results( 'SELECT COUNT(*) AS c FROM ' . $wpdb->prefix . $table, ARRAY_A );
				$stats[$table] = ( int ) @$result[0]['c'];
			}
		}
		return $stats;
	}
	
	/**
	 * check uploaded file's stat
	 *
	 * @since 1.0
	 *
	 */
	protected function _get_upload_stats( $file ) {
		$contents = file_get_contents ( $this->WCSRI_IMPORT_DIR . $file );
		$json_contents = json_decode( $contents );
		
		$stats = array();
		foreach ( $json_contents AS $table => $content ) {
			$stats[$table] = count( $content );
		}
		return $stats;
	}
	
	/**
	 * upload file and prep for import
	 *
	 * @since 1.0
	 * @return 
	 */		
	protected function _upload()
	{
		$result = array( 
			'status' => false,
			'msg' => '',
		);
		if ( !empty( $_FILES[WCSRI_FILE_UPLOAD_NAME] ) ) {
			$upload = $_FILES[WCSRI_FILE_UPLOAD_NAME];
			if (0 === $upload['error']) {
				if ( file_exists( $upload['tmp_name'] ) ) {
					$ext = pathinfo( $upload['name'], PATHINFO_EXTENSION );
					if ( $ext === 'json' ) {
						$contents = file_get_contents ( $upload['tmp_name'] );
						$json_contents = json_decode( $contents );
						if ( null !== $json_contents ) {
							// move file to import 
							$hash = md5 ( $contents );
							$file_name = date( 'Y-m-d' ) . '-' .  $hash . '.json';
							
							// remove file if it exist
							@unlink( $this->WCSRI_IMPORT_DIR . $file_name );
							
							if ( move_uploaded_file( $upload['tmp_name'], $this->WCSRI_IMPORT_DIR . $file_name ) ) {
								$result['status'] = true;
								$result['msg'] = $file_name;
							}
							else {
								$result['msg'] = __( 'Error moving uploaded file.' );
							}
						}
						else {
							$result['msg'] = __( 'Empty or invalid file upload contents.' );
						}
					}
					else {
						$result['msg'] = __( 'Invalid file type.' );
					}
				}
				else {
					// file not uploaded to 
					$result['msg'] = __( 'Error uploading file.' );
				}
			}
			else {
				// error uploading file
				$result['msg'] = __( 'Error uploading file.' );
			}
		}
		else {
			// there was no file uploaded 
			$result['msg'] = __( 'No file upload found.' );
		}
		
		return $result;
	}
	
	/**
	 * import data from uploaded json file
	 *
	 * @since 1.0
	 * @todo 
	 * 1. timeouts for large files might occur. work on this later
	 * 2. double check schema if source and dest are the same
	 * 3. generate stats - how many records were successfully inserted and number of queries failed
	 */
	protected function _import( $file ) {
		global $wpdb;
		
		set_time_limit( 0 );
		
		$contents = file_get_contents ( $this->WCSRI_IMPORT_DIR . $file );
		$json_contents = json_decode( $contents );
		
		$stats = array();
		foreach ( $json_contents AS $table => $rows ) {
			$result = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . $table . "'", ARRAY_A);
			if ( !empty( $result ) ) {
				// purge table first
			    $wpdb->query( 'TRUNCATE ' . $wpdb->prefix . $table );
				foreach ( $rows AS $row ) {
					$values = array();
					foreach ( $row AS $value ) {
						$values[] = "'" . $wpdb->_real_escape($value) . "'";
					}
					$wpdb->query( 'INSERT INTO ' . $wpdb->prefix . $table . ' VALUES(' . implode(',', $values) . ')' );
				}
			}
		}
		
		return true;
	}
					
	/**
	 * export shipping tables into a downloadable file
	 *
	 * @since 1.0
	 *
	 */
	protected function _export() {
		global $wpdb;
		
		$records = array();
		foreach ( $this->_tables AS $table ) {
			$result = $wpdb->get_results( "SHOW TABLES LIKE '" . $wpdb->prefix . $table . "'", ARRAY_A);
			if ( !empty( $result ) ) {
				$result = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . $table, ARRAY_A );
				$records[$table] = $result;
			}
			else {
				$records[$table] = array(); 
			}
		}
		$records = json_encode( $records );
		$hash = md5( $records );
		
		$file_name = date( 'Y-m-d' ) . '-' .  $hash . '.json';
		if ( $f = fopen( $this->WCSRI_EXPORT_DIR . $file_name, 'wb' ) ) {
			fwrite( $f, $records );
			fclose($f);
			wcsri_download_file( $this->WCSRI_EXPORT_DIR . $file_name, $file_name );
		}
		
		die();
	}
	
	/**
	 * plugin activation process
	 *
	 * @since 1.0
	 *
	 */
	public function install() {
		
		// create directories, if these do not exist, suppress error on failure
		@mkdir( WCSRI_DATA_DIR );
		@mkdir( $this->WCSRI_EXPORT_DIR );
		@mkdir( $this->WCSRI_IMPORT_DIR );
		
		try {
			@chmod( WCSRI_DATA_DIR, '774');
			@chmod( $this->WCSRI_EXPORT_DIR, '774');
			@chmod( $this->WCSRI_IMPORT_DIR, '774');
		}
		catch (Exception $e) {
			@chmod( WCSRI_DATA_DIR, '775');
			@chmod( $this->WCSRI_EXPORT_DIR, '775');
			@chmod( $this->WCSRI_IMPORT_DIR, '775');
		}
	}
	
	public function notice_wc_not_installed() {
		$class = 'notice notice-error';
		$message = __( 'WooCommerce is required for WC Shipping Rates Importer to function properly.' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}

	/**
	 * plugin deactivation process and clean-up
	 *
	 * @since 1.0
	 * @todo clear contents - remove all exported and imported files
	 */
	public function uninstall() {   
		
	}
}


// instantiate plugin class in admin panel only
if ( is_admin() ) {
	$wc_importer = new WCSRI_Shipping_Rates_Importer( WCSRI_DATA_DIR );
}
