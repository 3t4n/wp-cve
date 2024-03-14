<?php
/*
Plugin Name: avalex - Automatisch sichere Rechtstexte
Description: Ermöglicht die Einbindung der automatisch aktuellen Rechtstexte von avalex. Einen API Key erhalten Sie auf www.avalex.de.
Author: avalex GmbH
Author URI: https://avalex.de/
Version: 3.1.1
Text Domain: Avalex
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Avalex
{
	const PLUGIN_VERSION = '3.1.1'; //TODO update corresponding version digit on each code update
	protected $pluginPath = '';
	protected $tableName = '';
	protected $wp_version = '';
	protected $apiKey = '';
	protected $isKeyValid = false;
	protected $isDomainValid = false;
	protected $response = false;
	protected $recursiveCalls = 0;
	protected $notice = false;
	protected $cachePath = '';
	protected $dseHtml = '';
	protected $noticeType = '';
	protected $noticeMessage = '';
	protected $apiUrl         = 'https://avalex.de';
	protected $fallbackApiUrl = 'https://proxy.avalex.de';
	protected $types          = [
		'dse'      => '/avx-datenschutzerklaerung',
		'imprint'  => '/avx-impressum',
		'agb'      => '/avx-bedingungen',
		'widerruf' => '/avx-widerruf',
	];

	protected $shortcodes = [
		'datenschutz'        => 'dse',
		'impressum'          => 'imprint',
		'agb'                => 'agb',
		'widerrufsbelehrung' => 'widerruf',
		// 'avalex' => 'dse', //deprecated as of 3.0.0
	];

	protected $endpointsShortcodes = [
		'datenschutzerklaerung' => 'datenschutz',
		'impressum'             => 'impressum',
		'bedingungen'           => 'agb',
		'widerruf'              => 'widerrufsbelehrung',
	];

	protected $shortcodesLegaltexts = [];

	public function __construct()
	{
		// Call the functions that handle the stuff we need to do on plugin activation.
		register_activation_hook( __FILE__, array( $this, 'activatePlugin' ) );

		// And call the functions, when the user deactivates the plugin.
		register_deactivation_hook( __FILE__, array( $this, 'deactivatePlugin' ) );

		// We also want to load the language files (To make WordPress think it is translated).
		$this->loadLanguageFiles();

		// Set our variables.
		global $wpdb;
		global $wp_version;
		$this->pluginPath     = dirname( __FILE__ );
		$this->tableName      = $wpdb->prefix . 'avalex';
		$this->wp_version 	  = $wp_version;
		$this->apiKey         = esc_attr( get_option( 'avalex_api_key', false ) );
		$this->isKeyValid     = get_option( 'avalex_valid_api_key', false );
		$this->isDomainValid  = false;
		$this->response       = false;
		$this->recursiveCalls = 0;
		$this->notice         = false;
		$this->cachePath      = WP_CONTENT_DIR . '/cache';
		$this->avalexCronAction();
		$this->maybeUpdateDatabase();

		// Register a new cron schedule.
		add_filter( 'cron_schedules', array( $this, 'addQuarterlyCronSchedule' ) );

		// We also want to check if our cronjob is still active, because it is crucial for the functionality of this plugin.
		$this->registerCronJob();

		// Call our methods that we need as early as possible.
		add_action( 'admin_init', array( $this, 'init' ) );

		// Add our admin menu page.
		add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );

		// Add update functionality.
		add_action( 'pre_set_site_transient_update_plugins', array( $this, 'pluginUpdateNotification' ) );

		// We need an action to make it possible to force a DSE update from outside for important updates that can't wait.
		add_action( 'init', array( $this, 'forceUpdate' ) );

		// Add settings link to plugin page.
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'addSettingsLink' ) );

		// Add fallback for old shortcode which was deprecated as of 3.0.0, added with 3.0.1
		add_shortcode( 'avalex', array( $this, 'renderAvalexShortcodeFallback' ) );

		// Replace shortcodes with their corresponding avalex legal texts from DB.
		global $wpdb;
		if( $this->wp_version < 6.2 ) {
			$typeRows = $wpdb->get_results( "SELECT type FROM $this->tableName" );
		} else {
			$typeRows = $wpdb->get_results( $wpdb->prepare( "SELECT type FROM %i", $this->tableName ) );
		}
		
		foreach ( $typeRows as $typeRow ) {
			$typeParts = explode( '_', $typeRow->type );
			$shortcode = array_search( $typeParts[0], $this->shortcodes );
			if ( $shortcode ) {
				$shortcode = 'avalex_' . ( count( $typeParts ) > 1 ? "{$typeParts[1]}_" : 'de_' ) . $shortcode;
				add_shortcode( $shortcode, array( $this, 'renderAvalexShortcode' ) );
			}
		}
	}

	public function activatePlugin()
	{
		$this->createTable();
		$this->maybeDeleteOldCronJob();
		$this->registerCronJob();
	}

	public function checkPhpVersion()
	{
		if ( version_compare( PHP_VERSION, '7.0.0', '>' ) ) {
			return;
		}

		wp_die( 'PHP Version zu alt. Bitte nutzen Sie mindestens PHP 7.0.<br>Sie nutzen aktuell Version: ' . PHP_VERSION );
	}

	public function createTable()
	{
		global $wpdb;
		$charsetCollate = $wpdb->get_charset_collate();

		// First we want to drop any old tables of avalex, if there are some.
		$this->deleteAvalexTable();

		// Now create our new table.
		$sql = "CREATE TABLE $this->tableName (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        type text NOT NULL,
        data longtext NOT NULL,
        PRIMARY KEY  (id)
    ) $charsetCollate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		maybe_create_table( $this->tableName, $sql );
	}

	public function maybeUpdateDatabase()
	{
		global $wpdb;
		$charsetCollate = $wpdb->get_charset_collate();

		// Now create our new table.
		$sql = "CREATE TABLE $this->tableName (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            data longtext NOT NULL,
            type text NOT NULL,
            PRIMARY KEY  (id)
        ) $charsetCollate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		maybe_create_table( $this->tableName, $sql );
	}

	public function maybeDeleteOldCronJob()
	{
		if ( wp_next_scheduled( 'avalex_cron_event' ) ) {
			wp_clear_scheduled_hook( 'avalex_cron_event' );
		}

		if ( wp_next_scheduled( 'avalex_update_dse_cron_event' ) ) {
			wp_clear_scheduled_hook( 'avalex_update_dse_cron_event' );
		}
	}

	public function registerCronJob()
	{
		if ( ! wp_next_scheduled( 'avalex_update_dse_cron_event' ) ) {
			wp_schedule_event( time(), 'avalex_interval', 'avalex_update_dse_cron_event' );
		}
	}

	public function deactivatePlugin()
	{
		$this->maybeDeleteOldCronJob();
	}

	public function deleteAvalexTable()
	{
		global $wpdb;
		
		if( $this->wp_version < 6.2 ) {
			$wpdb->query( "DROP TABLE IF EXISTS $this->tableName" );
		} else {
			$wpdb->query( $wpdb->prepare( "DROP TABLE IF EXISTS %i", $this->tableName ) );
		}
	}

	public function deleteOptions()
	{
		delete_option( 'avalex_api_key' );
		delete_option( 'avalex_valid_api_key' );
	}

	public function avalexCronAction()
	{
		add_action( 'avalex_update_dse_cron_event', array( $this, 'fetchAvalexTexts' ) );
	}

	public function addQuarterlyCronSchedule( $schedules )
	{
		$schedules['avalex_interval'] = array(
			'interval' => 21600,
			'display'  => __( '4 times a day' )
		);
		return $schedules;
	}

	public function loadLanguageFiles()
	{
		load_plugin_textdomain( 'Avalex', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	public function init()
	{
		// If the user hits the submit button, we want to store the new apikey.
		if ( ! isset( $_POST['save_avalex'] ) ) {
			return;
		}

		if ( ! $this->saveApiKey() ) {
			return;
		}

		if ( ! $this->validateApiKey() ) {
			return;
		}

		$this->fetchAvalexTexts();
	}

	public function addAdminMenu()
	{
		add_options_page( 'avalex', 'avalex', 'manage_options', 'avalex', array( $this, 'avalexAdminPage' ) );
	}

	public function avalexAdminPage()
	{
		include $this->pluginPath . '/templates/admin_page.php';
	}

	public function addNotice()
	{
		if ( ! $this->notice ) {
			return false;
		}

		echo '<div class="notice notice-' . esc_attr( $this->noticeType ) . '"><p>' . esc_html( $this->noticeMessage ) . '</p></div>';
		return true;
	}

	private function saveApiKey()
	{
		// check permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		// verify nonce
		check_admin_referer( 'save_avalex' );

		// Check if api key was entered.
		$apiKey = sanitize_text_field( $_POST['avalex_api_key'] );
		update_option( 'avalex_api_key', $apiKey );

		if ( ! $apiKey ) {
			$this->notice        = true;
			$this->noticeType    = 'error';
			$this->noticeMessage = __( 'Please enter an API key.', 'Avalex' );
			return false;
		}

		if ( $apiKey ) {
			$this->apiKey = $apiKey;
			return true;
		}
	}

	private function showApiKey()
	{
		if ( $this->apiKey ) {
			echo esc_attr( $this->apiKey );
			return;
		}

		return false;
	}

	private function validateApiKey()
	{

		// Build the api url.
		$apiUrlDomain = $this->apiUrl . '/avx-datenschutzerklaerung';
		if ( $this->recursiveCalls == 1 ) {
			// $apiUrlDomain = $this->fallbackApiUrl . 'avx-datenschutzerklaerung';
		}

		$serverDomain = $this->trimDomain( home_url() );

		$apiUrlDomain = add_query_arg( 'apikey', $this->apiKey, $apiUrlDomain );
		$apiUrlDomain = add_query_arg( 'domain', $serverDomain, $apiUrlDomain );

		// Call the api.
		$response     = wp_remote_get( $apiUrlDomain );
		$responseCode = wp_remote_retrieve_response_code( $response );

		// If we have a 401, the key is not valid.
		if ( $responseCode == 401 ) {
			$this->notice        = true;
			$this->noticeType    = 'error';
			$this->noticeMessage = 'Der API-Key ist ungültig.';
			$this->isKeyValid    = false;
			update_option( 'avalex_valid_api_key', false );
			return false;
		}

		// 200 means api key is valid, so we can grab the domain and proceed.
		if ( $responseCode == 200 ) {
			update_option( 'avalex_valid_api_key', true );
			$this->isKeyValid     = 1;
			$this->response       = json_decode( wp_remote_retrieve_body( $response ), true );
			$this->recursiveCalls = 0;
			return true;
		}

		if ( is_wp_error( $response ) ) {
			// When this is the first time the error happens, we want to try the fallback url.
			if ( $this->recursiveCalls == 0 ) {
				$this->recursiveCalls = 1;
				return $this->validateApiKey();
			}

			$errorMessage        = $response->get_error_message();
			$this->notice        = true;
			$this->noticeType    = 'error';
			$this->noticeMessage = 'Beim Datenabgleich  mit dem Avalex Server ist etwas schiefgelaufen. Bitte wenden Sie sich an den Support mit den folgenden Informationen:<br>' . $errorMessage;
			return false;
		}

		if ( $responseCode == 400 ) {
			// Website not configured.
			$this->notice        = true;
			$this->noticeType    = 'error';
			$this->noticeMessage = 'Webseite noch nicht fertig konfiguriert oder die hinterlegte Domain stimmt nicht mit ihrem Server überein.';
			$this->isKeyValid    = false;
			update_option( 'avalex_valid_api_key', false );
			return false;
		}


		update_option( 'avalex_valid_api_key', false );
		return false;
	}

	public function trimDomain( $domain )
	{
		// Clean domain
		$domain = preg_replace( '/^https?:\/\/(www\.)?([^\/]+).*/', '$2', $domain );
		return $domain;
	}

	public function fetchAvalexTexts()
	{
		// Get languages used on the current domain.
		$apiUrl       = $this->apiUrl . '/avx-get-domain-langs';
		$serverDomain = $this->trimDomain( home_url() );
		$apiUrl       = add_query_arg( 'apikey', $this->apiKey, $apiUrl );
		$apiUrl       = add_query_arg( 'domain', $serverDomain, $apiUrl );
		$apiUrl       = add_query_arg( 'version', Avalex::PLUGIN_VERSION, $apiUrl );
		$response     = wp_remote_get( $apiUrl );
		$responseCode = wp_remote_retrieve_response_code( $response );
		$addedTexts   = [];

		if ( $responseCode == 401 ) {
			// API Key not authorized, shouldn't happen at this point, but you never know.
			return;
		}

		// We got something back, let's see if the body has some data.
		$langs = wp_remote_retrieve_body( $response );

		// If the data is empty (no langs have been entered on the avalex side), we only retrieve the default German legal texts types.
		if ( empty( $langs ) ) {
			// Loop trough every type and save everything in its own row.
			foreach ( $this->types as $type => $endpoint ) {
				$this->fetchAvalexDse( $endpoint, $type, 'de' );
			}
		} // The customer has entered the set of languages used on their domain, we loop through them and retrieve each one's corresponding legal text.
		else {
			$langs = json_decode( $langs, true );
			foreach ( $langs as $langCode => $langTexts ) {
				foreach ($langTexts as $endpoint => $legaltextUrl) {
                    $type = array_search ("/avx-$endpoint", $this->types) . ($langCode !== 'de' ? "_$langCode" : '');
                    if($this->fetchAvalexDse("/avx-$endpoint", $type, $langCode)) {
						$addedTexts[] = $type;
                        $this->shortcodesLegaltexts[] = "avalex_{$langCode}_{$this->endpointsShortcodes[$endpoint]}";
                    }
                }
			}
		}

		// clear not existing languages
		global $wpdb;
		if( $this->wp_version < 6.2 ) {
			$currentDb = $wpdb->get_results( "SELECT id, type FROM $this->tableName" );
		} else {
			$currentDb = $wpdb->get_results( $wpdb->prepare( "SELECT id, type FROM %i", $this->tableName ) );
		}
		
		if ( $addedTexts && $currentDb ) {
			foreach ( $currentDb as $entry ) {
				if ( ! in_array( $entry->type, $addedTexts ) ) {
					if( $this->wp_version < 6.2 ) {
						$wpdb->query( $wpdb->prepare( "DELETE FROM $this->tableName WHERE id = %d", $entry->id ) );
					} else {
						$wpdb->query( $wpdb->prepare( "DELETE FROM %i WHERE id = %d", $this->tableName, $entry->id ) );
					}
				}
			}
		}

	}

	public function fetchAvalexDse( $endpoint, $type, $langCode = null )
	{
		$apiUrl = $this->apiUrl . $endpoint;

		if ( $this->recursiveCalls == 1 ) {
			$apiUrl = $this->fallbackApiUrl . $endpoint;
		}

		$serverDomain = $this->trimDomain( home_url() );

		$apiUrl = add_query_arg( 'apikey', $this->apiKey, $apiUrl );
		$apiUrl = add_query_arg( 'domain', $serverDomain, $apiUrl );

		if ( $langCode ) {
			$apiUrl = add_query_arg( 'lang', $langCode, $apiUrl );
		}

		$args = [
			'timeout' => 10, //Set connection timeout to 10 seconds.
		];

		$response     = wp_remote_get( $apiUrl, $args );
		$responseCode = wp_remote_retrieve_response_code( $response );

		if ( $responseCode == 401 ) {
			// API Key not authorized, shouldn't happen at this point, but you never know.
			return;
		}

		if ( $responseCode == 200 ) {
			// We got something back, let's see if the body has some data.
			$data = wp_remote_retrieve_body( $response );

			// If the data is empty, we do nothing to avoid overwriting the DSE with empty content.
			if ( empty( $data ) ) {
				return false;
			}

			// Alright, we should be safe to actually save the dse in the database. But to be sure, we sanitize the data.
			 $sanitizedData = sanitize_post_field('post_content', trim($data), false, 'display');
            $trimmedData = preg_replace("/\r|\n/", '', $sanitizedData);
            $this->dseHtml = $trimmedData;
            $this->writeDseIntoDatabase($type);
            $this->recursiveCalls = 0;

			// Set the data for the successfull update notice.
			$this->notice        = true;
			$this->noticeType    = 'success';
			$this->noticeMessage = 'Der API Key und die avalex Rechtstexte wurden aktualisiert.';

			// Now we delete the whole cache of WordPress to make sure the update actually shows.
			$this->emptyCache();

			return true;
		}

		if ( is_wp_error( $response ) ) {
			if ( $this->recursiveCalls == 0 ) {
				$this->recursiveCalls = 1;
				return $this->fetchAvalexDse( $endpoint, $type, $langCode );
			}

			$errorMessage        = $response->get_error_message();
			$this->notice        = true;
			$this->noticeType    = 'error';
			$this->noticeMessage = 'Beim Datenabgleich  mit dem Avalex Server ist etwas schiefgelaufen. Bitte wenden Sie sich an den Support mit den folgenden Informationen:<br>' . $errorMessage;
			return false;
		}
	}

	public function writeDseIntoDatabase( $type )
	{
		if ( ! $this->dseHtml ) {
			return;
		}

		// Write our new data into the database. And delete the old row beforehand.
		global $wpdb;
		if( $this->wp_version < 6.2 ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM $this->tableName WHERE type = %s", $type ) );
		} else {
			$wpdb->query( $wpdb->prepare( "DELETE FROM %i WHERE type = %s", $this->tableName, $type ) );
		}

		$wpdb->insert(
			$this->tableName, array(
			'time' => current_time( 'mysql' ),
			'data' => $this->dseHtml,
			'type' => $type,
		), array(
				'%s',
				'%s',
				'%s',
			)
		);
	}

	public function getDseFromDatabase( $type )
	{
		// Write our new data into the database.
		global $wpdb;
		if( $this->wp_version < 6.2 ) {
			$dseRow = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $this->tableName WHERE type = %s ORDER BY time DESC LIMIT 1", $type ) );
		} else {
			$dseRow = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %i WHERE type = %s ORDER BY time DESC LIMIT 1", $this->tableName, $type ) );
		}
		
		if ( ! $dseRow ) {
			return;
		}

		return $dseRow[0]->data;
	}

	// Retrieve existing types of legal texts other than German.
	public function getOtherLangsLegaltextsFromDatabase()
	{
		global $wpdb;
		if( $this->wp_version < 6.2 ) {
			$typeRow = $wpdb->get_results( "SELECT data, type FROM $this->tableName WHERE type LIKE '%\_%'" );
		} else {
			$typeRow = $wpdb->get_results( $wpdb->prepare( "SELECT data, type FROM %i WHERE type LIKE '%\_%'", $this->tableName ) );
		}
		
		if ( ! $typeRow ) {
			return [];
		}

		return $typeRow;
	}

	public function renderAvalexShortcodeFallback( $atts, $content )
	{
		return do_shortcode( '[avalex_de_datenschutz]' );
	}

	public function renderAvalexShortcode( $atts, $content, $shortcode )
	{
		if ( ! $this->isKeyValid ) {

			// Try to revalidate.
			if ( $this->validateApiKey() ) {
				$this->renderAvalexShortcode( false, false, $shortcode );
			}

			return 'Avalex ist noch nicht fertig eingerichtet.';
		}

		// Add lang code (if relevant) to legal text type while keeping consistency with legacy content from older plugin versions
		$shortcodeParts = explode( '_', $shortcode );
		$type           = $this->shortcodes[$shortcodeParts[2]];
		if ( $shortcodeParts[1] != 'de' ) {
			$type .= '_' . $shortcodeParts[1];
		}

		// First we check if by whatever reason the dse is empty, if it is, we try to fetch the new data.
		if ( ! $this->getDseFromDatabase( $type ) || empty( $this->getDseFromDatabase( $type ) ) ) {
			if ( ! $this->validateApiKey() ) {
				if ( $this->addNotice() ) {
					return;
				}
				return 'API Key ungültig.';
			}

			if ( ! $this->fetchAvalexTexts() && $this->recursiveCalls < 2 ) {
				$this->recursiveCalls ++;
				$this->renderAvalexShortcode( false, false, $shortcode );
				return false;
			}
		}

		// We have a DSE and we will use it!
		return $this->getDseFromDatabase( $type );
	}

	public function getDseTime()
	{
		global $wpdb;
		// first try to get the timestamp of an german version
		if( $this->wp_version < 6.2 ) {
			$result = $wpdb->get_row( "SELECT time FROM $this->tableName WHERE type = 'dse' or type = 'imprint' or type = 'agb' or type = 'widerruf'" );
		} else {
			$result = $wpdb->get_row( $wpdb->prepare( "SELECT time FROM %i WHERE type = 'dse' or type = 'imprint' or type = 'agb' or type = 'widerruf'", $this->tableName ) );
		}
		
		if ( ! $result ) {
			// no timestamp found? try to get another timestamp
			if( $this->wp_version < 6.2 ) {
				$result = $wpdb->get_row( "SELECT time FROM $this->tableName" );
			} else {
				$result = $wpdb->get_row( $wpdb->prepare( "SELECT time FROM %i", $this->tableName ) );
			}
		}

		if ( ! $result ) {
			echo 'Noch keine DSE vorhanden.';
			return false;
		}

		// We want to show the date in a nice format.
		$timestamp = esc_attr( $result->time );
		echo date_i18n( 'd.m.Y H:i', strtotime( $timestamp ) ) . ' Uhr';
	}

	public function pluginUpdateNotification( $transient )
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$url = $this->apiUrl . '/files/wordpress/package.json';

		if ( $this->recursiveCalls == 1 ) {
			$url = $this->fallbackApiUrl . '/files/wordpress/package.json';
		}

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			if ( $this->recursiveCalls == 0 ) {
				$this->recursiveCalls = 1;
				return $this->pluginUpdateNotification( $transient );
			}

			$errorMessage        = $response->get_error_message();
			$this->notice        = true;
			$this->noticeType    = 'error';
			$this->noticeMessage = 'Beim Datenabgleich  mit dem Avalex Server ist etwas schiefgelaufen. Bitte wenden Sie sich an den Support mit den folgenden Informationen:<br>' . $errorMessage;
			return false;
		}

		$body    = json_decode( wp_remote_retrieve_body( $response ) );
		$version = $body->version;

		$pluginData = get_plugin_data( __FILE__, false, false );

		if ( version_compare( $version, $pluginData['Version'], '<=' ) ) {
			return $transient;
		}

		if ( $this->recursiveCalls == 0 ) {
			$updateInfo = array(
				'plugin'      => plugin_basename( __FILE__ ),
				'slug'        => plugin_basename( __FILE__ ),
				'new_version' => $version,
				'url'         => 'https://avalex.de',
				'package'     => 'https://avalex.de/files/wordpress/avalex_wordpress.zip',
			);
		}

		if ( $this->recursiveCalls == 1 ) {
			$updateInfo = array(
				'plugin'      => plugin_basename( __FILE__ ),
				'slug'        => plugin_basename( __FILE__ ),
				'new_version' => $version,
				'url'         => 'https://avalex.de',
				'package'     => 'https://proxy.avalex.de/files/wordpress/avalex_wordpress.zip',
			);
		}

		$this->recursiveCalls = 0;

		$transient->response[plugin_basename( __FILE__ )] = (object)$updateInfo;
		return $transient;
	}

	public function forceUpdate()
	{
		if ( ! isset( $_GET['force_dse_update'] ) || $_GET['force_dse_update'] != true ) {
			return;
		}

		$this->fetchAvalexTexts();
		$this->emptyCache();
	}

	/**
	 * Clear the cache of compatible plugins to prevent old content on relevant pages
	 *
	 * Inspired by: https://wordpress.org/plugins/clear-cache-for-widgets/
	 *
	 * Last Update: 2023-11-02
	 *
	 * @return void
	 */
	public function emptyCache()
	{
		// if W3 Total Cache is being used, clear the cache
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		} // if WP Super Cache is being used, clear the cache
		elseif ( function_exists( 'wp_cache_clean_cache' ) ) {
			global $file_prefix, $supercachedir;
			if ( empty( $supercachedir ) && function_exists( 'get_supercache_dir' ) ) {
				$supercachedir = get_supercache_dir();
			}
			wp_cache_clean_cache( $file_prefix );
		} elseif ( class_exists( 'WpeCommon' ) ) {
			// be extra careful, just in case 3rd party changes things on us
			if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
				WpeCommon::purge_memcached();
			}
			if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
				WpeCommon::clear_maxcdn_cache();
			}
			if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
				WpeCommon::purge_varnish_cache();
			}
		} // WP Fastest Cache
		elseif ( method_exists( 'WpFastestCache', 'deleteCache' ) && ! empty( $wp_fastest_cache ) ) {
			$wp_fastest_cache->deleteCache( true );
		} // Kinsta Cache
		elseif ( class_exists( '\Kinsta\Cache' ) && ! empty( $kinsta_cache ) ) {
			$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
		} // GoDaddy Cache
		elseif ( class_exists( '\WPaaS\Cache' ) ) {
			ccfm_godaddy_purge();
		} // WP Optimize
		elseif ( class_exists( 'WP_Optimize' ) && defined( 'WPO_PLUGIN_MAIN_PATH' ) ) {
			if ( ! class_exists( 'WP_Optimize_Cache_Commands' ) ) {
				include_once( WPO_PLUGIN_MAIN_PATH . 'cache/class-cache-commands.php' );
			}

			if ( class_exists( 'WP_Optimize_Cache_Commands' ) ) {
				$wpoptimize_cache_commands = new WP_Optimize_Cache_Commands();
				$wpoptimize_cache_commands->purge_page_cache();
			}
		} // Breeze Admin
		elseif ( class_exists( 'Breeze_Admin' ) ) {
			do_action( 'breeze_clear_all_cache' );
		} // LSCWP_V
		elseif ( defined( 'LSCWP_V' ) ) {
			do_action( 'litespeed_purge_all' );
		} // SG CachePress
		elseif ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			sg_cachepress_purge_cache();
		} // Autooptimize
		elseif ( class_exists( 'autoptimizeCache' ) ) {
			autoptimizeCache::clearall();
		} // Cache Enabler
		elseif ( class_exists( 'Cache_Enabler' ) ) {
			Cache_Enabler::clear_total_cache();
		} // WP Rocket
		elseif ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
			if ( function_exists( 'rocket_clean_minify' ) ) {
				rocket_clean_minify();
			}
		}
	}

	 public function addSettingsLink($links) {
        $links = array_merge(array(
            '<a href="' . esc_url(admin_url('/options-general.php?page=avalex')) . '">Einstellungen</a>',
        ), $links);
        return $links;
    }
}

// Call our class.
new Avalex();