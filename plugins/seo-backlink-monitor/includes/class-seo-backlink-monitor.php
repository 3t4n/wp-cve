<?php

class SEO_Backlink_Monitor {

	protected $loader;

	public function __construct() {

		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	private function load_dependencies() {

		require_once SEO_BLM_PLUGIN_PATH . 'includes/class-seo-backlink-monitor-loader.php';
		require_once SEO_BLM_PLUGIN_PATH . 'includes/class-seo-backlink-monitor-helper.php';

		if (!class_exists('WP_List_Table')) {
			require_once( ABSPATH . 'wp-admin/includes/screen.php' );
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		require_once SEO_BLM_PLUGIN_PATH . 'admin/class-seo-backlink-monitor-admin.php';
		require_once SEO_BLM_PLUGIN_PATH . 'admin/inc/class-seo-backlink-monitor-parent-list-table.php';
		require_once SEO_BLM_PLUGIN_PATH . 'admin/inc/class-seo-backlink-monitor-child-list-table.php';

		$this->loader = new SEO_Backlink_Monitor_Loader();
	}

	private function define_admin_hooks() {

		$admin = new SEO_Backlink_Monitor_Admin();

		/* VERSION */
		$this->loader->add_action( 'plugins_loaded', $admin, 'check_for_db_updates' );

		/* LANGUAGE */
		$this->loader->add_action( 'plugins_loaded', $admin, 'load_textdomain' );

		/* CRON */
		$this->loader->add_action( 'seo_backlink_monitor_cron', $admin, 'seo_blm_cron_cb' );
		$this->loader->add_action( 'init', $admin, 'seo_blm_cron_activation_hook' );

		/* enqueue scripts and styles */
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'admin_enqueue_scripts', 999 );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'admin_enqueue_styles', 999 );

		/* add menu */
		$this->loader->add_action( 'admin_menu', $admin, 'seo_blm_admin_menu' );

		/* ajax */
		$this->loader->add_action( 'wp_ajax_seo_blm_list_table_ajax', $admin, 'seo_blm_list_table_ajax' );
		$this->loader->add_action( 'wp_ajax_seo_blm_refresh_link_ajax', $admin, 'seo_blm_refresh_link_ajax' );

		/* post handling */
		$this->loader->add_action( 'admin_post_seo_backlink_monitor_save_settings', $admin, 'seo_blm_save_settings' );
		$this->loader->add_action( 'admin_post_seo_backlink_monitor_add_link', $admin, 'seo_blm_add_link' );
		$this->loader->add_action( 'admin_post_seo_backlink_monitor_edit_link', $admin, 'seo_blm_edit_link' );
		$this->loader->add_action( 'admin_post_seo_backlink_monitor_add_multiple_links', $admin, 'seo_blm_add_multiple_links' );

	}

	public function run() {
		$this->loader->run();
	}

}
