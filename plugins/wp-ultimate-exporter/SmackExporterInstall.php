<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
exit; // Exit if accessed directly

class ExpInstall {

	protected static $instance = null,$smack_instance,$tables_instance,$plugin_instance;
	private static $db_updates = array();
	/**
	 * SmackCSVInstall Constructor
	 */
	public function __construct() {
		$plugin_instance = Plugin::getInstance();
	}

	/**
	 * SmackCSVInstall Instance
	 */
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$smack_instance = new ExpInstall();
		}
		return self::$instance;
	}

	/**
	 * Hook in tabs.
	 */
	public static function init($slug) {				
		add_filter('plugin_action_links_'.$slug, array(__CLASS__, 'plugin_row_meta'), 10, 3);			
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param       mixed $links Plugin Row Meta
	 * @param       mixed $file  Plugin Base file
	 * @return      array
	 */
	public static function plugin_row_meta( $links, $file ) {		
		
		$active_plugins = get_option('active_plugins');
		if(in_array('wp-ultimate-csv-importer/wp-ultimate-csv-importer.php', $active_plugins)){
			return $links;
		}
		else{
			
		$row_meta = array(
			'install_csv_importer' => '<a style="font-weight: bold;color: #d54e21;font-size: 105%;" href="' . esc_url( apply_filters( 'install_csv_importer',  'https://wordpress.org/plugins/wp-ultimate-csv-importer/' ) ) . '" title="' . esc_attr( __( 'Install CSV Importer', 'wp-ultimate-csv-importer' ) ) . '" target="_blank">' . __( 'Install CSV Importer', 'wp-ultimate-csv-importer' ) . '</a>'
	);
		return $row_meta + $links;
	}
	}

}
