<?php
/*
Plugin Name: Easy Modal
Plugin URI: http://easy-modal.com
Description: Easily create & style modals with any content. Theme editor to quickly style your modals. Add forms, social media boxes, videos & more. 
Author: Daniel Iser
Version: 2.1.0
Author URI: http://danieliser.com
Text Domain: easy-modal
*/
if (!defined('EMCORE'))
	define('EMCORE', __FILE__);
if (!defined('EMCORE_NAME'))
	define('EMCORE_NAME', 'Easy Modal');
if (!defined('EMCORE_SLUG'))
	define('EMCORE_SLUG', trim(dirname(plugin_basename(__FILE__)), '/'));
if (!defined('EMCORE_DIR'))
	define('EMCORE_DIR', WP_PLUGIN_DIR . '/' . EMCORE_SLUG);
if (!defined('EMCORE_URL'))
	define('EMCORE_URL', plugins_url() . '/' . EMCORE_SLUG);
if (!defined('EMCORE_NONCE'))
	define('EMCORE_NONCE', EMCORE_SLUG.'_nonce' );
if (!defined('EMCORE_VERSION'))
	define('EMCORE_VERSION', '2.1.0' );
if (!defined('EMCORE_DB_VERSION'))
	define('EMCORE_DB_VERSION', '1.1' );
if (!defined('EMCORE_API_URL'))
	define('EMCORE_API_URL', 'http://easy-modal.com');

load_plugin_textdomain( EMCORE_SLUG, false, EMCORE_SLUG.'/languages');

class EModal {
	public function __construct()
	{
		$this->initialize_db_tables();

		register_deactivation_hook( __FILE__, array($this,'deactivate') );
		register_activation_hook( __FILE__, array($this,'activate') );
		register_activation_hook( __FILE__, array($this,'install_data') );

		if(!emodal_get_option(EMCORE_SLUG.'_uninstalled'))
		{
			add_action('plugins_loaded', array($this, 'activate'));
			add_action('emodal_db_update', array($this,'install'), 1);
		}

		if (is_admin())
			new EModal_Admin;
		else
			new EModal_Site;

		add_filter('emodal_modal_content', 'wptexturize');
		add_filter('emodal_modal_content', 'convert_smilies');
		add_filter('emodal_modal_content', 'convert_chars');
		add_filter('emodal_modal_content', 'wpautop');
		add_filter('emodal_modal_content', 'shortcode_unautop');
		add_filter('emodal_modal_content', 'prepend_attachment');
		add_filter('emodal_modal_content', 'do_shortcode', 11);
    	// add_filter("plugins_api", array($this, "get_addon_info"), 100, 3);
		//add_filter('plugins_api', array($this, 'inject_addons'), 10, 3);

	}
	public function deactivate()
	{
		emodal_delete_option(EMCORE_SLUG.'_uninstalled');
	}
	public function inject_addons($response, $action, $args) {
		
		$addon_list = EModal_License::available_addons();
		$addon_slugs = array();
		$addons = array();
		if(!empty($addon_list))
		{
			foreach($addon_list as $addon)
			{
				$addon_slugs[] = $addon->slug;
				$addons[$addon->slug] = $addon;
			}
			//Does this request concern our plugin?
			if ( ($action !== 'plugin_information') || empty($args->slug) || empty($_GET['em']) || !in_array($args->slug, $addon_slugs) ) {
				return $response;
			}
		}

		$addon = $addons[$args->slug];
		//This is basically a stripped-down and simplified version of what the update checker library does.
		$response = new StdClass();
		foreach ($addon as $key => $value)
		{
		    $response->$key = $value;
		}
		$response->name = $addon->name;
		$response->version = $addon->version;
		$response->download_link = $addon->download_url;

		return $response;
	}

	public function initialize_db_tables()
	{
		global $wpdb;
		$tables = apply_filters('emodal_db_tables', array('em_modals', 'em_modal_metas', 'em_themes', 'em_theme_metas'));
		foreach($tables as $table)
		{
			$wpdb->{$table} = $wpdb->prefix . $table;
		}
	}
	public function process_install($multisite_blog = false)
	{
		do_action('emodal_db_update', $multisite_blog);
	}
	public function install_data()
	{
		$theme = new EModal_Model_Theme;
		$theme->set_fields(array());
		$theme->save();
	}
	public function activate($networkwide)
	{
		global $wpdb;
		if (function_exists('is_multisite') && is_multisite()) {
		    // check if it is a network activation - if so, run the activation function for each blog id
		    if ($networkwide)
		    {
                $old_blog = $wpdb->blogid;
		        // Get all blog ids
		        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		        foreach ($blogids as $blog_id)
		        {
		            switch_to_blog($blog_id);
		            $this->process_install(true);

		        }
		        switch_to_blog($old_blog);
		        return;
		    }  
		}
		$this->process_install();   
	}
	public function install($multisite_blog)
	{
		global $wpdb, $blog_id, $emodal_db_update_global;
		$emodal_db_update_global = false;

		$current_version = emodal_get_option(EMCORE_SLUG.'_db_version');
		if($current_version != EMCORE_DB_VERSION || $emodal_db_update_global)
		{
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	        if ( ! empty($wpdb->charset) )
	            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	        if ( ! empty($wpdb->collate) )
	            $charset_collate .= " COLLATE $wpdb->collate";

			$sql = "CREATE TABLE {$wpdb->prefix}em_themes (
				id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
				name varchar(150) NOT NULL DEFAULT '',
				created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				is_system tinyint(1) NOT NULL DEFAULT '0',
				is_trash tinyint(1) NOT NULL DEFAULT '0',
				PRIMARY KEY  (id)
			)$charset_collate;";
			dbDelta( $sql );


			$sql = "CREATE TABLE {$wpdb->prefix}em_theme_metas (
				id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
				theme_id mediumint(9) unsigned NOT NULL,
				overlay longtext,
				container longtext,
				close longtext,
				title longtext,
				content longtext,
				PRIMARY KEY (id)
			)$charset_collate;";
			dbDelta( $sql );


			$sql = "CREATE TABLE {$wpdb->prefix}em_modals (
				id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
				theme_id mediumint(9) unsigned NOT NULL DEFAULT '1',
				name varchar(150) NOT NULL DEFAULT '',
				title varchar(255) DEFAULT NULL,
				content longtext,
				created datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				is_sitewide tinyint(1) NOT NULL DEFAULT '0',
				is_system tinyint(1) NOT NULL DEFAULT '0',
				is_trash tinyint(1) NOT NULL DEFAULT '0',
				PRIMARY KEY  (id)
			)$charset_collate;";
			dbDelta( $sql );


			$sql = "CREATE TABLE {$wpdb->prefix}em_modal_metas (
				id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
				modal_id mediumint(9) unsigned NOT NULL,
				display longtext,
				close longtext,
				PRIMARY KEY  (id)
			)$charset_collate;";
			dbDelta( $sql );

			if(!$current_version && !emodal_get_option('EasyModal_Version'))
			{
				$this->install_data();
			}
			emodal_update_option(EMCORE_SLUG.'_db_version', EMCORE_DB_VERSION);
			$emodal_db_update_global = true;
		}
		if(emodal_get_option('EasyModal_Version') && !emodal_get_option(EMCORE_SLUG.'_migration_approval'))
		{
			new EModal_Migrate_Pre_V2;
		}
	}
}
add_action('plugins_loaded', 'emodal_initialize', 5);
function emodal_initialize()
{
	global $EModal, $EModal_License; 
	require EMCORE_DIR.'/init.php';
	$EModal = new EModal;
}