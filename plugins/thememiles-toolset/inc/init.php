<?php

class Thememiles_Toolset {

    private $theme_author = 'thememiles';

	public static function instance() {

		static $instance = null;

		if ( null === $instance ) {
			$instance = new Thememiles_Toolset;
		}

		return $instance;
	}

	public function run() {
        $this->load_dependencies();

        if ( thememiles_toolset_get_current_theme_author() == $this->theme_author ) {
            $this->hooks();
        }

	}

    private function load_dependencies() {

        require_once THEMEMILES_TOOLSET_PATH . 'inc/functions.php';
        require_once THEMEMILES_TOOLSET_PATH . 'inc/hooks.php';

    }

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hooks() {

		$plugin_admin = thememiles_toolset_hooks();
        add_filter( 'advanced_import_demo_lists', array( $plugin_admin, 'add_demo_lists' ), 10, 1 );
        add_filter( 'admin_menu', array( $plugin_admin, 'import_menu' ), 10, 1 );
        add_filter( 'wp_ajax_thememiles_toolset_getting_started', array( $plugin_admin, 'install_advanced_import' ), 10, 1 );
        add_filter( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ), 10, 1 );
        add_filter( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ), 10, 1 );
       
        /*Replace terms and post ids*/
        add_action( 'advanced_import_replace_term_ids', array( $plugin_admin, 'replace_term_ids' ), 20 );
        add_action( 'advanced_import_replace_post_ids', array( $plugin_admin, 'replace_post_ids' ), 20 );

    }


}