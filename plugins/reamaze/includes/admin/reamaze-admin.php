<?php
/**
 * Reamaze Admin
 *
 * @class       Reamaze_Admin
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Reamaze_Admin class.
 */
class Reamaze_Admin {

  /**
   * Constructor
   */
  public function __construct() {
    add_action( 'admin_init', array($this, 'install' ) );
    add_action( 'init', array($this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'current_screen_includes' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
    add_filter( 'comment_row_actions', array( $this, '_comment_row_actions' ), 10, 2 );
  }

  /**
   * Includes
   */
  public function includes() {
    $reamazeAccountId = get_option('reamaze_account_id');
    $reamazeApiKey = wp_get_current_user()->reamaze_api_key;

    if ( ! empty( $reamazeAccountId ) && ! empty( $reamazeApiKey ) ) {
      Reamaze\API\Config::setBrand( get_option('reamaze_account_id') );
      Reamaze\API\Config::setCredentials( get_reamaze_email(), $reamazeApiKey );
    }

    // Classes we only need during non-ajax requests
    if ( ! reamaze_is_ajax() ) {
      include_once( 'reamaze-admin-menus.php' );
    }
  }

  /**
   * Includes for current screen
   */
  public function current_screen_includes() {
    $screen = get_current_screen();

    switch ( $screen-> id ) {
      case 'dashboard':
        include( 'reamaze-admin-dashboard-widgets.php' );
        break;
    }
  }

  /**
   * Admin Scripts
   */
  public function admin_scripts() {
    global $reamaze;
    wp_enqueue_script( 'jquery-postmessage', $reamaze->plugin_url() . '/assets/js/admin/jquery.postmessage.min.js' );
    wp_enqueue_script( 'jquery-deparam', $reamaze->plugin_url() . '/assets/js/admin/jquery.deparam.min.js' );
    wp_enqueue_script( 'jquery-colorbox', $reamaze->plugin_url() . '/assets/js/admin/jquery.colorbox.min.js' );
    wp_enqueue_script( 'jquery-markitup', $reamaze->plugin_url() . '/assets/js/admin/jquery.markitup.js' );
    wp_enqueue_script( 'reamaze-markitup-driver', $reamaze->plugin_url() . '/assets/js/admin/markitup-driver.js' );
    wp_enqueue_script( 'reamaze-admin', $reamaze->plugin_url() . '/assets/js/admin/reamaze-admin.js' );
    wp_enqueue_script( 'reamaze-js', 'https://d3itxuyrq7vzpz.cloudfront.net/assets/reamaze-loader.js', array(), null );
    wp_enqueue_style( 'colorbox-css', $reamaze->plugin_url() . '/assets/css/colorbox.css' );
    wp_enqueue_style( 'reamaze-admin', $reamaze->plugin_url() . '/assets/css/admin/reamaze-admin.css' );
    wp_enqueue_style( 'reamaze-markitup', $reamaze->plugin_url() . '/assets/css/admin/markitup.css' );
    wp_enqueue_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );

    wp_localize_script( 'reamaze-admin', 'reamaze_context', array( 'dashboard_url' => admin_url( '/admin.php?page=reamaze' ) ) );
  }

  public function install() {
    if (get_option('reamaze_version') != Reamaze::$version) {
      $this->add_option_defaults();

      // create kb page
      //$this->create_page( 'support', 'reamaze_post_reamaze-kb', 'Support', '[reamaze_kb_embed]' );

      // create conversations history page
      //$this->create_page( 'contact', 'reamaze_post_reamaze-support', 'Contact', '[reamaze_support_embed]' );

      update_option('reamaze_version', Reamaze::$version);
    }
  }

  public function _comment_row_actions( $actions, $comment ) {
    $conversationSlug = get_comment_meta( $comment->comment_ID, 'reamaze-conversation', true );

    if ( $comment->comment_type != 'pingback' ) {
      if ( ! $conversationSlug ) {
        $actions['reamaze'] = '<a class="reamaze-create-conversation" href="javascript:;" data-id="' . $comment->comment_ID . '">' . __('Create Reamaze Conversation', 'reamaze' ) . '</a>';
      } else {
        $actions['reamaze'] = '<a data-reamaze-path="/admin/conversations/' . $conversationSlug . '" href="https://' . get_option('reamaze_account_id') . '.reamaze.com/admin/conversations/' . $conversationSlug . '" target="_blank">' . __( 'View Reamaze Conversation', 'reamaze' ) . '</a>';
      }
    }

    return $actions;
  }

  public static function get_auth_key( $user_id, $user_email ) {
    return hash_hmac( 'sha256', $user_id . ':' . $user_email, get_option( 'reamaze_account_sso_key' ) );
  }

  private function add_option_defaults() {
    // Include settings so that we can run through defaults
    include_once('reamaze-admin-settings.php');

    $settings_pages = Reamaze_Admin_Settings::get_settings_pages();

    foreach ($settings_pages as $settings_page) {
      foreach ($settings_page->get_settings() as $setting) {
        $is_user_setting = isset($setting['user_setting']) && $setting['user_setting'];
        if (isset($setting['default']) && isset($setting['id']) && !$is_user_setting) {
          add_option($setting['id'], $setting['default']);
        }
      }
    }
  }

  private function create_page( $slug, $id, $title, $content ) {
    global $wpdb;

    $page_id = get_option( $id );

    if ( $page_id > 0 && get_post( $page_id ) ) {
      return false;
    }

    // does post exist?
    $page_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'page' AND post_name = '%s' LIMIT 1;", $slug ) );

    if ( $page_id ) {
      update_option( $id, $page_id );

      return $page_id;
    }

    $page_data = array(
      'post_content'   => $content, // The full text of the post.
      'post_name'      => $slug, // The name (slug) for your post
      'post_title'     => $title, // The title of your post.
      'post_status'    => 'publish', // Default 'draft'.
      'post_type'      => 'page', // Default 'post'.
      'post_author'    => 1, // The user ID number of the author. Default is the current user ID.
      'comment_status' => 'closed' // Default is the option 'default_comment_status', or 'closed'.
    );

    $page_id = wp_insert_post( $page_data );

    update_option( $id, $page_id );

    return $page_id;
  }
}


return new Reamaze_Admin();
