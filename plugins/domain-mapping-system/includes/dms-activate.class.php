<?php

/**
 * @class DMS_Activate
 *        Designed to handle all the logic upon activation process
 * @since 1.6
 */
class DMS_Activate {

	/**
	 * Plugin base name
	 *
	 * @var string $plugin_base_name
	 */
	private $plugin_base_name;

	/**
	 * Plugin dir
	 *
	 * @var string $plugin_dir
	 */
	private $plugin_dir;

	/**
	 * Holds wpdb instance
	 *
	 * @since  1.6
	 * @access private
	 * @var wpdb $wpdb we store the wpdb instance
	 */
	private $wpdb;

	/**
	 * Plugin version
	 *
	 * @since  1.6
	 * @var string $version
	 */
	public $version;

	/**
	 * Constructor
	 * Define all the needed properties here
	 *
	 * @param  string  $plugin_base_name
	 * @param  string  $plugin_dir
	 * @param  wpdb  $wpdb
	 */
	public function __construct( $plugin_base_name, $version, $plugin_dir, $wpdb ) {
		$this->plugin_base_name = $plugin_base_name;
		$this->plugin_dir       = $plugin_dir;
		$this->version          = $version;
		$this->wpdb             = $wpdb;
	}

	/**
	 * Set plugin version in the database
	 *
	 * @since 1.6
	 */
	public function setVersion( $version ) {
		if ( $version !== $this->version ) {
			update_option( 'dms_version', $this->version );
		}
	}

	/**
	 * Migrate to custom tables version (1.6)
	 *
	 * @since 1.6
	 */
	public function migrateTo1point6() {
		if ( ! $this->checkTablesExistence() ) {
			$this->createTables();
			$dms_map = get_option( 'dms_map' );
			if ( ! empty( $dms_map ) && ! is_array( $dms_map ) ) {
				$this->migrateToArrayOption( $dms_map );
			}
			/**
			 * Statement that added from 1.6
			 * In this case we have "dms_map" option as array.
			 * Needed to migrate data into the new table and remove mapping from the "dms_map" option.
			 */
			$dms_map = get_option( 'dms_map' );
			if ( ! empty( $dms_map ) && is_array( $dms_map ) ) {
				// Migrate to custom tables
				$this->migrateToCustomTables( $dms_map );
			}
		}
	}

	/**
	 * Check if "dms_mappings" table exists
	 *
	 * @return bool
	 * @since 1.6
	 */
	public function checkTablesExistence() {
		$table = $this->wpdb->get_var( "SHOW TABLES LIKE '" . $this->wpdb->prefix . "dms_mappings'" );

		return ! empty( $table );
	}

	/**
	 * Method for handling all the functionality related to activation
	 *
	 * @since 1.6
	 */
	public function activate() {
        // Generate API secret
        $this->generateSecret();
		/**
		 * Very first activation case is related to empty "dms_usage_page" option
		 * Create tables
		 * Checks for "dms_usage_page" option existence
		 */
		$this->createTables();
		if ( get_option( 'dms_use_page' ) === false ) {
			$this->addConfigOptions();
		} else {
			/**
			 * Next activations case
			 * Check for old style dms_map structure
			 */
			$dms_map = get_option( 'dms_map' );
			if ( ! empty( $dms_map ) && ! is_array( $dms_map ) ) {
				$this->migrateToArrayOption( $dms_map );
			}
			/**
			 * Statement that added from 1.6
			 * In this case we have "dms_map" option as array.
			 * Needed to migrate data into the new table and remove mapping from the "dms_map" option.
			 */
			$dms_map = get_option( 'dms_map' );
			if ( ! empty( $dms_map ) && is_array( $dms_map ) ) {
				// Migrate to custom tables
				$this->migrateToCustomTables( $dms_map );
			}
		}
		// Sets 'host' column's index Non_unique if it is unique
		$this->upgraderProcessComplete();
		// Activate/deactivate Free/Pro
		$this->activateDeactivatePlan();
		// Create mu helper
		$this->createDMSMuHelper();
		// Change htaccess in case WordPress installation is sub-directory based
		$base_host = DMS_Helper::getBaseHost();
		if ( DMS_Helper::isSubDirectoryInstall() ) {
			DMS_Helper::changeHtaccess($base_host);
		}
	}

	/**
	 * Plugin deactivate
	 * 
	 * @since 1.7.7
	 */
	public function deactivate() {
		// Remove mu helper
		$this->deleteDMSMuHelper();
		// Remove htaccess change, in case WordPress is installed in sub dir
		$base_host = DMS_Helper::getBaseHost();
		if ( DMS_Helper::isSubDirectoryInstall() ) {
			DMS_Helper::revertHtaccess($base_host);
		}
	}

	/**
	 * Checks activated plan (free or pro) and activates the opposite
	 */
	private function activateDeactivatePlan() {
		// Here we need to create unique
		// Deactivate other active version
		$plugin_base_name              = $this->plugin_base_name;
		$plugin_dir_path               = $this->plugin_dir;
		$free_plugin_base_name         = strpos( $plugin_base_name,
			'-pro' ) === false ? $plugin_base_name : str_replace( '-pro', '', $plugin_base_name );
		$premium_plugin_base_name      = strpos( $plugin_base_name,
			'-pro' ) !== false ? $plugin_base_name : str_replace( basename( $plugin_dir_path ),
			basename( $plugin_dir_path ) . '-pro', $plugin_base_name );
		$is_premium_version_activation = current_filter() !== 'activate_' . $free_plugin_base_name;
		// This logic is relevant only to plugins since both the free and premium versions of a plugin can be active at the same time.
		// 1. If running in the activation of the FREE module, get the basename of the PREMIUM.
		// 2. If running in the activation of the PREMIUM module, get the basename of the FREE.
		$other_version_basename = ( $is_premium_version_activation ? $free_plugin_base_name : $premium_plugin_base_name );
		/**
		 * If the other module version is active, deactivate it.
		 *
		 * is_plugin_active() checks if the plugin is active on the site or the network level and
		 * deactivate_plugins() deactivates the plugin whether it's activated on the site or network level.
		 *
		 */
		if ( is_plugin_active( $other_version_basename ) ) {
			deactivate_plugins( $other_version_basename );
		}
	}

	/**
	 * Add main config options about the plugin usage.
	 * Usage of posts/pages/categories/custom_posts/post_archives
	 */
	private function addConfigOptions() {
		// Retrieve types if any
		$types = DMS::getCustomPostTypes();
		// Very first stage
		update_option( 'dms_use_page', 'on' );
		update_option( 'dms_use_post', 'on' );
		update_option( 'dms_use_categories', 'on' );
		foreach ( $types as $cpt ) {
			update_option( "dms_use_{$cpt['name']}", 'on' );
			if ( $cpt['has_archive'] ) {
				update_option( "dms_use_{$cpt['name']}_archive", 'on' );
			}
		}
	}

	/**
	 * Migration from old option structure data storage to custom table structure
	 *
	 * @param  array  $dms_map
	 *
	 * @since 1.6
	 */
	private function migrateToCustomTables( $dms_map ) {
		$wpdb = $this->wpdb;
		if ( is_array( $dms_map ) ) {
			if ( ! empty( $dms_map['domains'] ) && is_array( $dms_map['domains'] ) ) {
				$migrated = true;
				$domains  = $dms_map['domains'];
				foreach ( $domains as $key => $domain ) {
					$ok      = $wpdb->insert( $wpdb->prefix . 'dms_mappings', array(
						'host'  => $domain['host'],
						'order' => $key
					), array(
						'%s',
						'%d'
					) );
					$host_id = $wpdb->insert_id;
					if ( ! empty( $ok ) && ! empty( $host_id ) ) {
						$values  = $domain['mappings']['values'];
						$primary = $domain['mappings']['primary'];
						foreach ( $values as $value ) {
							$ok_value = $wpdb->insert( $wpdb->prefix . 'dms_mapping_values', array(
								'host_id' => $host_id,
								'value'   => $value,
								'primary' => (int) ( $primary === $value )
							), array(
								'%d',
								'%s',
								'%d'
							) );
							if ( empty( $ok_value ) ) {
								$migrated = false;
								break;
							}
						}
					} else {
						$migrated = false;
						break;
					}
				}
				// If migration succeeded
				if ( $migrated ) {
					// Some log possible here or could be following 
					// delete_option( 'dms_map' );
				}
			}
		}
	}

	/**
	 * Migration from old row option data structure to array option data structure
	 *
	 * @param  string  $dms_map
	 */
	public function migrateToArrayOption( $dms_map ) {
		if ( is_string( $dms_map ) ) {
			/**
			 * Proper had to be version compare, but we don't have too many users yet
			 * Is old string structure
			 * Try to bring new to structure
			 */
			$new_dms_map = [];
			parse_str( $dms_map, $dms_map_arr );
			foreach ( $dms_map_arr as $key => $value ) {
				$new_dms_map['domains'][] = array(
					'host'     => str_replace( '_', '.', $key ),
					'mappings' => array(
						'values'  => [ $value ],
						'primary' => $value
					)
				);
			}
			// Update option
			update_option( 'dms_map', $new_dms_map );
		}
	}

	/**
	 * Create helper file for mu-plugins
	 */
	public function createDMSMuHelper() {
		// Create mkdir
		if ( ! file_exists( WPMU_PLUGIN_DIR ) ) {
			@mkdir( WPMU_PLUGIN_DIR );
		}
		if ( file_exists( WPMU_PLUGIN_DIR ) ) {
			$file = WPMU_PLUGIN_DIR . "/dms-helper.php";
			if ( ! file_exists( $file ) ) {
				file_put_contents( $file, '<?php
/*
* Plugin Name: Domain Mapping System Helper
* Plugin URI: https://gauchoplugins.com/
* Description: Allow main DMS plugin to be loaded from the mu-plugins directory 
* Version: 1.0.0
* Author: Gaucho Plugins
* Author URI: https://gauchoplugins.com/
* License: GPL3
*/
if(!defined(\'ABSPATH\')) {
	die();
}
// Check what version is active
global $wpdb;
$pro_active  = $wpdb->get_var( "SELECT `option_id` FROM " . $wpdb->prefix . "options WHERE option_name = \'active_plugins\' AND option_value like \'%domain-mapping-system-pro\/dms.php%\'" );
$free_active = $wpdb->get_var( "SELECT `option_id` FROM " . $wpdb->prefix . "options WHERE option_name = \'active_plugins\' AND option_value like \'%domain-mapping-system\/dms.php%\'" );

$free = ABSPATH . \'wp-content/plugins/domain-mapping-system/dms.php\';
$pro = ABSPATH . \'wp-content/plugins/domain-mapping-system-pro/dms.php\';
if ( file_exists( $pro ) && ! empty( $pro_active ) ) {
	$plugin_file = $pro;
} elseif ( file_exists( $free ) && ! empty( $free_active ) ) {
	$plugin_file = $free;
}
if ( !empty($plugin_file)) {
	require_once( $plugin_file );
}', FILE_APPEND );
			}
		}
	}


	/**
	 * Delete helper file from mu-plugins
	 */
	public function deleteDMSMuHelper() {
		$file = WPMU_PLUGIN_DIR . "/dms-helper.php";
		if ( file_exists( $file ) ) {
			unlink( $file );
		}
	}

	/**
	 * Create tables
	 * "dms_mappings" for store domains/hosts connected
	 * "dms_mapping_values" for store website entities per domain/host
	 *
	 * @return bool
	 * @since 1.6
	 */
	private function createTables() {
		$wpdb               = $this->wpdb;
		$dms_mappings       = "
		CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "dms_mappings` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `host` varchar(256) NOT NULL,
		  `path` varchar(512) DEFAULT NULL,
		  `order` int(11) DEFAULT NULL,
		  `main` tinyint(4) DEFAULT NULL,
		  `attachment_id` bigint(20) DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `Index 2` (`host`) USING BTREE,
		  KEY `Index 3` (`order`),
		  KEY `Index 4` (`main`),
		  KEY `Index 5` (`path`),
		  KEY `Index 6` (`attachment_id`)
		) AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;";
		$dms_mapping_values = "
		CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "dms_mapping_values` (
		  `host_id` int(11) NOT NULL DEFAULT 0,
		  `value` varchar(256) NOT NULL DEFAULT '',
		  `value_permalink_path` varchar(256) DEFAULT NULL,
		  `primary` tinyint(4) DEFAULT NULL,
		  `order` int(11) DEFAULT NULL,
		  KEY `Index 1` (`host_id`,`value`),
		  KEY `Index 2` (`primary`),
		  KEY `Index 3` (`order`),
		  KEY `Index 4` (`host_id`),
		  KEY `Index 5` (`value`),
		  KEY `Index 6` (`value_permalink_path`)
		) DEFAULT CHARSET=utf8;";

		return $wpdb->query( $dms_mappings ) && $wpdb->query( $dms_mapping_values );
	}

	/**
	 * Updates Index 2 from unique to Non_unique
	 *
	 * @param  WP_Upgrader|null  $upgrader
	 * @param  array  $hook_extra
	 */
	public function upgraderProcessComplete( $upgrader = null, $hook_extra = [] ) {
		$proceed = false;
		$wpdb    = $this->wpdb;
		if ( ! is_null( $upgrader ) && $upgrader->result['destination_name'] === $this->plugin_base_name ) {
			$proceed = true;
		} elseif ( is_null( $upgrader ) ) {
			$proceed = true;
		}
		if ( $proceed ) {
			$unique_host        = $wpdb->query( "SHOW INDEXES FROM `" . $wpdb->prefix . "dms_mappings` WHERE Column_name = 'host' AND NOT Non_unique" );
			$attachment_column  = $wpdb->get_var( "SHOW COLUMNS FROM `" . $wpdb->prefix . "dms_mappings` WHERE `Field` = 'attachment_id'" );
			$custom_html_column = $wpdb->get_var( "SHOW COLUMNS FROM `" . $wpdb->prefix . "dms_mappings` WHERE `Field` = 'custom_html'" );
			if ( !empty( $unique_host ) ) {
				$wpdb->query( 'ALTER TABLE ' . $wpdb->prefix . 'dms_mappings DROP INDEX `Index 2`' );
				$wpdb->query( 'CREATE INDEX `Index 2` ON ' . $wpdb->prefix . 'dms_mappings (`host`)' );
			}
			if ( empty( $attachment_column ) ) {
				$wpdb->query( 'ALTER TABLE ' . $wpdb->prefix . 'dms_mappings ADD attachment_id bigint(20)' );
				$wpdb->query( 'CREATE INDEX `Index 6` ON ' . $wpdb->prefix . 'dms_mappings (`attachment_id`)' );
			}
			if ( empty( $custom_html_column ) ) {
				$wpdb->query( 'ALTER TABLE ' . $wpdb->prefix . 'dms_mappings ADD custom_html text' );
			}
		}
	}

    /**
     * Generate secret for API
     * @return void
     * @throws Exception
     */
	public function generateSecret() {
		if ( is_multisite() ) {
			foreach ( get_sites( [ 'fields' => 'ids' ] ) as $blog_id ) {
				if ( empty( get_blog_option( $blog_id, 'dms_api_secret' ) ) ) {
					$secret = bin2hex( random_bytes( 32 ) );
					add_blog_option( $blog_id, 'dms_api_secret', $secret );
				}
			}
		} else {
			if ( empty( get_option( 'dms_api_secret' ) ) ) {
				$secret = bin2hex( random_bytes( 32 ) );
				add_option( 'dms_api_secret', $secret );
			}
		}
	}
}