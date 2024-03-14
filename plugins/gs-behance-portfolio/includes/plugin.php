<?php

namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

final class Plugin {

    private static $instance = null;

    public $shortcode;
    public $db;
    public $integrations;
    public $helpers;
    public $addons;
    public $data;
    public $scrapper;
    public $builder;
    public $ajax;
    public $assets;
    public $scripts;    
    public $templateLoader;

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

    /**
     * Class Constructor
     *
     * @since  2.0.12
     * @return void
     */
    public function __construct() {

        $this->builder          = new Builder;
        $this->helpers          = new Helpers;
        $this->scrapper         = new Scrapper;
        $this->data             = new DataLayer;
        $this->ajax             = new Ajax;
        $this->shortcode        = new Shortcode;
        $this->scripts          = new Scripts;
        $this->db               = new Database;
        $this->integrations     = new Integrations;
        $this->templateLoader   = new TemplateLoader;

        require_once GSBEH_PLUGIN_DIR . 'includes/gs-common-pages/gs-behance-common-pages.php';        
        require_once GSBEH_PLUGIN_DIR . 'includes/asset-generator/gs-load-asset-generator.php';

        // register widget
        add_action( 'gs_task_hook', [ $this, 'resync_data_task' ] );
        add_action( 'init', [ $this, 'init' ] );
        add_action( 'init', [ $this, 'plugin_update_version' ], 0 );
        
        // firing the initial compatibility migration
        add_action( 'plugins_loaded', [ $this, 'plugin_loaded' ] );
        add_action( 'plugins_loaded', [ $this, 'compatibility_migration' ] );
        add_action( 'in_admin_header', [$this, 'disable_admin_notices'], 1000 );
    }

    function disable_admin_notices() {
        global $parent_file;
        if ( $parent_file != 'gs-behance-shortcode' ) return;
        remove_all_actions( 'network_admin_notices' );
        remove_all_actions( 'user_admin_notices' );
        remove_all_actions( 'admin_notices' );
        remove_all_actions( 'all_admin_notices' );
    }

    public function plugin_loaded() {
        plugin()->db->migration();
    }

    /**
     * Plugin Initialization
     *
     * @since  2.0.12
     * @return void
     */
    public function init() {
        // Schedule Events
        if ( ! wp_next_scheduled( 'gs_task_hook' ) ) {
            wp_schedule_event( time(), 'daily', 'gs_task_hook' );
        }
    }

    public function plugin_update_version() {
    
        $old_version = get_option('gsbeh_plugin_version');
    
        if (GSBEH_VERSION === $old_version) return;
        
        plugin()->builder->maybe_upgrade_data($old_version);
        
        gsBehanceAssetGenerator()->assets_purge_all();

        update_option('gsbeh_plugin_version', GSBEH_VERSION);
        
    }

    /**
     * Scheduled event task for updating data.
     * 
     * @since  2.0.12
     * @return void
     */
    public function resync_data_task() {
        $be_option_key 	= 'be_meta';
        $be_meta   	   	= (array) get_option( $be_option_key, [] );

        if ( ! empty( $be_meta ) ) {
            foreach ( $be_meta as $user => $page ) {
                if ( ' ' === $user ) {
                    continue;
                }
                plugin()->data->update_data( $user );
            }
        }
    }

    /**
     * This method is responsible for any of the database
     * migration for the backwards compatibility.
     * 
     * @since  2.0.12
     * @return void
     */
    public function compatibility_migration() {
        if ( ! get_option( 'upgraded_to_' . GSBEH_VERSION, false ) ) {

            global $wpdb;
            $table_name = plugin()->db->get_data_table();

            // Empty table
            $wpdb->query("TRUNCATE TABLE $table_name");

            // resync data.
            plugin()->resync_data_task();
    
            // Set upgraded to current version.
            update_option( 'upgraded_to_' . GSBEH_VERSION, true );
        }
    }
}

function plugin() {
	return Plugin::get_instance();
}
plugin();
