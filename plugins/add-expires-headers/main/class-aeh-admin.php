<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
* Declaring Class
*/
class AEH_Admin {
  public function __construct() {
    $this->includes();
    add_action( 'admin_enqueue_scripts', array( $this, 'aeh_enqueue_scripts' ) );
    add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
    add_filter( 'plugin_action_links_' . AEH_BASENAME, array( $this, 'settings_link' ) );
  }

  /*  enqueue scripts */
  public function aeh_enqueue_scripts() {
    $this->register_scripts();
    if (isset($_GET['page']) && ($_GET['page'] == 'aeh_pro_plugin_options')) {
        wp_enqueue_style('aeh_material_css');
				wp_enqueue_style('aeh_css');
        wp_enqueue_script('aeh_material_js');
				wp_enqueue_style( 'aeh_icons' );
        wp_enqueue_script('aeh_js');
    }
    wp_enqueue_script('aeh_ajax_js');
  }

  /* registering scripts */
  private function register_scripts() {
    wp_register_style( 'aeh_material_css', AEH_URL . 'assests/css/materialize.min.css', false, null);
		wp_register_style( 'aeh_css', AEH_URL . 'assests/css/aeh.css', false, null);
		wp_register_style( 'aeh_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
    wp_register_script('aeh_material_js', AEH_URL . 'assests/js/materialize.min.js', array('jquery'), null, true);
		wp_register_script('aeh_js', AEH_URL . 'assests/js/aeh.js', array('jquery'), null, true);
    wp_register_script('aeh_ajax_js', AEH_URL . 'assests/js/aeh-ajax.js', array('jquery'), null, true);
		wp_localize_script('aeh_ajax_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'purge_cache_nonce' => wp_create_nonce( 'purge-cache-nonce' ),'maybelater_nonce' => wp_create_nonce( 'maybelater-nonce' ),'alreadydid_nonce' => wp_create_nonce( 'alreadydid-nonce' ),));
	}

	/* plugins settings link creation */
  public function settings_link( $links, $url_only = false, $networkwide = false ) {
    $settings_page = is_multisite() && is_network_admin() ? network_admin_url( 'admin.php?page=aeh_pro_plugin_options' ) : menu_page_url( 'aeh_pro_plugin_options', false );
    $settings_page = $url_only && $networkwide && is_multisite() ? network_admin_url( 'admin.php?page=aeh_pro_plugin_options' ) : $settings_page;
    $settings      = '<a href="' . $settings_page . '">Settings</a>';
    if ( $url_only ) {
      return $settings_page;
    }
    if ( ! empty( $links ) ) {
      array_unshift( $links, $settings );
    } else {
      $links = array( $settings );
    }
    return $links;
  }

	/* adding views to plugin settings */
  private function includes() {
		include_once AEH_DIR . 'inc/abstract-aeh-view.php';
		include_once AEH_DIR . 'inc/class-aeh-admin-view.php';
	}

	/* adding menu link to admin menu */
  public function add_menu_pages() {
		$title = "Add Expires Headers";
		$this->pages['aeh'] = new AEH_Admin_View( $title, 'aeh_pro_plugin_options' );
	}
}
