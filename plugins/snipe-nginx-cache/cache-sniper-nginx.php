<?php
/**
 * Plugin Name: Cache Sniper for Nginx
 * Description: Purge the Nginx FastCGI Cache within WordPress on a global or per-page basis.
 * Version: 1.0.4.2
 * Author: Thorn Technologies LLC
 * License: MIT
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once plugin_dir_path( __FILE__ ) . 'includes/common_utils.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/cache-sniper-nginx-comments.php';

class Cache_Sniper_Nginx {

  use CSNX_Common_Utils;

  public function __construct() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/filesystem_helper.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/render_helper.php';
    add_action( 'admin_enqueue_scripts', [ $this, 'csnx_load_actions_js' ] );
    add_action( 'admin_init', [ $this, 'csnx_register_settings' ] );
    add_action( 'admin_menu', [ $this, 'csnx_create_tools_page' ] );
    add_action( 'wp_before_admin_bar_render', [ $this, 'csnx_tweaked_admin_bar' ] );
    add_filter( 'post_row_actions', [ $this, 'csnx_modify_list_row_actions' ], 10, 2 );
    add_filter( 'page_row_actions', [ $this, 'csnx_modify_list_row_actions' ], 10, 2 );
    add_action( 'add_meta_boxes', [ $this, 'csnx_register_metabox' ] );
    add_action( 'wp_ajax_delete_entire_cache', [ $this, 'csnx_delete_entire_cache' ] );
    add_action( 'wp_ajax_delete_current_page_cache', [ $this, 'csnx_delete_current_page_cache' ] );
    add_action( 'wp_ajax_delete_homepage_cache', [ $this, 'wp_ajax_csnx_delete_home_page_cache' ] );
    add_action( 'save_post', [ $this, 'csnx_delete_current_page_cache_on_update' ], 10, 3 );
    add_action( 'delete_post', [ $this, 'csnx_delete_current_page_cache_on_update' ] );
    add_action( 'wp_trash_post', [ $this, 'csnx_delete_current_page_cache_on_update' ] );
  }

  /**
   * Load javascript.
   */
  public function csnx_load_actions_js() {
    wp_enqueue_script( $this->get_plugin_name() . "_cache_actions", plugins_url( $this->get_plugin_url() . "/js/cache_actions.js" ), [], time(), true );
  }

  /**
   * Register plugin's settings.
   */
  public function csnx_register_settings() {
    register_setting( $this->get_plugin_name(), $this->get_cache_path_setting(), 'sanitize_text_field' );
    register_setting( $this->get_plugin_name(), $this->get_cache_levels_setting(), [ $this, 'validate_levels' ] );
    register_setting( $this->get_plugin_name(), $this->get_cache_clear_on_update_setting(), 'absint' );
    register_setting( $this->get_plugin_name(), $this->get_cache_clear_on_comments_setting(), 'absint' );
    register_setting( $this->get_plugin_name(), $this->get_home_page_cache_clear_on_update_setting(), 'absint' );
    register_setting( $this->get_plugin_name(), $this->get_home_page_cache_clear_on_comments_setting(), 'absint' );
  }

  /**
   * Make sure that levels field is formatted right.
   * @param string $data
   */
  public function validate_levels($data) {
    $pattern = '/^((1|2)(:(1|2)){0,2})?$/';
    if (preg_match($pattern, $data)) {
      return $data;
    } else {
      wp_die( 'Level format is invalid.' );
    }
  }

  /**
   * Add tools page.
   */
  public function csnx_create_tools_page() {
    add_management_page( CSNX_Render_Helper::PLUGIN_NAME, CSNX_Render_Helper::PLUGIN_NAME, 'manage_options', $this->get_plugin_name(), [ $this, 'csnx_build_form' ] );
  }

  /**
   * Build the form on the tools page.
   */
  public function csnx_build_form() {
    $render = CSNX_Render_Helper::get_instance();
    $render->settings_form();
  }

  /**
   * Add menu to the admin toolbar.
   */
  function csnx_tweaked_admin_bar() {
    $render = CSNX_Render_Helper::get_instance();
    $render->admin_bar();
  }

  /**
   * Register metabox.
   */
  public function csnx_register_metabox() {
    add_meta_box(
      'nginx_cache_sniper_metabox',
      CSNX_Render_Helper::PLUGIN_NAME,
      [ $this, 'csnx_render_metabox' ],
      ['post', 'page'],
      'side',
      'low'
    );
  }

  /**
   * Render metabox.
   */
  public function csnx_render_metabox( $post ) {
    $render = CSNX_Render_Helper::get_instance();
    echo '<p>' . $render->delete_current_page( $post )  . '</p>';
  }

  /**
   * Add an action to the list of posts and pages.
   * This action is next to Edit, Quick Edit, Trash and View actions.
   */
  public function csnx_modify_list_row_actions( $actions, $post ) {
    $render = CSNX_Render_Helper::get_instance();
    $actions = array_merge( $actions, [
      'cache_purge' => $render->delete_current_page( $post )
    ]);
    return $actions;
  }

  /**
   * Delete entire cache.
   */
  public function csnx_delete_entire_cache() {
    $path = $this->get_option_cache_path();
    $filesystem = CSNX_Filesystem_Helper::get_instance();
    $cache_deleted = $filesystem->delete_sub_directories( $path );
    die(json_encode([$cache_deleted]));
  }

  /**
   * Delete current page cache.
   */
  public function csnx_delete_current_page_cache() {
    if ( isset($_GET["post"]) ) {
      $permalink = get_permalink( $_GET['post'] );
    } else {
      die(json_encode(['error' => 'Page/post was not supplied']));
    }
    $path = $this->get_option_cache_path();
    $levels = $this->get_option_cache_levels();
    $filesystem = CSNX_Filesystem_Helper::get_instance();
    $cache_path = $filesystem->get_nginx_cache_path( $path, $permalink, $levels );
    $directory_deleted = $filesystem->delete( $cache_path );
    die(json_encode([$directory_deleted]));
  }

  /**
   * Delete home page cache.
   */
  public function csnx_delete_home_page_cache() {
    $homepage_url = trailingslashit(home_url());
    $path = $this->get_option_cache_path();
    $levels = $this->get_option_cache_levels();
    $filesystem = CSNX_Filesystem_Helper::get_instance();
    $cache_path = $filesystem->get_nginx_cache_path( $path, $homepage_url, $levels );
    $filesystem->delete( $cache_path );
  }

  /**
   * Delete home page cache through ajax.
   */
  public function wp_ajax_csnx_delete_home_page_cache() {
    $homepage_url = trailingslashit(home_url());
    $path = $this->get_option_cache_path();
    $levels = $this->get_option_cache_levels();
    $filesystem = CSNX_Filesystem_Helper::get_instance();
    $cache_path = $filesystem->get_nginx_cache_path( $path, $homepage_url, $levels );
    $directory_deleted = $filesystem->delete( $cache_path );
    die(json_encode([$directory_deleted]));
  }

  /**
   * Delete cache on page/post create/update/delete.
   */
  public function csnx_delete_current_page_cache_on_update( $post_id, $post = null, $update = true ) {
    // If this is just a revision, don't clear cache.
    if ( wp_is_post_revision( $post_id ) )
      return;

    if ( get_option( $this->get_home_page_cache_clear_on_update_setting() ) == 1 ) {
      $this->csnx_delete_home_page_cache();
    }

    if ( get_option( $this->get_cache_clear_on_update_setting() ) == 1 && $update == true ) {
      $permalink = get_permalink( $post_id );
      $path = $this->get_option_cache_path();
      $levels = $this->get_option_cache_levels();
      $filesystem = CSNX_Filesystem_Helper::get_instance();
      $cache_path = $filesystem->get_nginx_cache_path( $path, $permalink, $levels );
      $filesystem->delete( $cache_path );
    }
  }
}

if ( is_admin() ) {
  new Cache_Sniper_Nginx();
}
new Cache_Sniper_Nginx_Comments();
