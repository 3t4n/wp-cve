<?php
/*
 * Plugin Name: Reamaze Helpdesk for WordPress
 * Plugin URI:  https://www.reamaze.com?referrer=wordpress
 * Description: Reamaze Helpdesk, Customer Support and Live Chat for WordPress
 * Version:     2.3.2
 * Author:      The Reamaze Team
 * Author URI:  https://www.reamaze.com?referrer=wordpress
 */
include_once( 'includes/lib/reamaze-api-client-php/autoload.php' );

class Reamaze {
  public static $version = '2.3.2';

  public function __construct() {
    $this->includes();

    add_action( 'init', array( 'Reamaze_Shortcodes', 'init' ) );
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );

    $this->_setupWidget();
  }

  public function includes() {
    include_once( 'includes/reamaze-functions.php' );

    if (is_admin()) {
      include_once( 'includes/admin/reamaze-admin.php' );
    } else {
      add_action( 'wp_enqueue_scripts', array ( &$this, 'enqueue_frontend_scripts' ) );
      // disable divi's jquery compatibility script
      add_filter( 'et_builder_enable_jquery_body', '__return_false' );
    }

    include_once( 'includes/reamaze-shortcodes.php' );
    include_once( 'includes/reamaze-ajax.php' );
    Reamaze_Ajax::init();
  }

  public function plugin_url() {
    return untrailingslashit( plugins_url( '/', __FILE__ ) );
  }

  public function enqueue_frontend_scripts() {
    wp_enqueue_style( 'reamaze-frontend', $this->plugin_url() . '/assets/css/reamaze-frontend.css' );

    $reamazeAccountId = get_option( 'reamaze_account_id' );

    if ( $reamazeAccountId ) {
      wp_enqueue_script( 'reamaze-js', "https://cdn.reamaze.com/assets/reamaze-loader.js", array(), false, true );
    }
  }

  public function reamaze_widget_code() {
    $reamazeAccountId = get_option( 'reamaze_account_id' );
    $display = get_option( 'reamaze_widget_display' );

    if ( ! $reamazeAccountId ) {
      return;
    }

    $code = get_option( 'reamaze_widget_code' );

    if ( !empty( $code ) ) {
      echo $code;
    } else {
      ?>
      <script type="text/javascript">
        var _support = _support || { 'ui': {}, 'user': {} };
        _support['account'] = '<?php echo $reamazeAccountId; ?>';
      </script>
      <?php
    }

    $cue_code = get_option( 'reamaze_cue_code' );

    if ( !empty( $cue_code )) {
      echo $cue_code;
    }

    ?>
    <script type="text/javascript">
      <?php if ( is_user_logged_in() ) {
        $this->_renderWidgetUserData();
      } ?>
    </script>
    <?php

    if ( $display == 'none' || ( $display == 'auth' && ! is_user_logged_in() ) ) { ?>
      <script>
        _support['ui']['widget'] = false;
      </script>
    <?php }
  }

  public function plugin_action_links( $links ) {
    return array_merge( $links, array(
      'settings' => '<a href="' . admin_url( 'admin.php?page=reamaze-settings' ) . '" title="' . __( 'Settings', 'reamaze' ) . '">' . __( 'Settings', 'reamaze' ) . '</a>',
      'help' => '<a href="javascript:;" data-reamaze-lightbox="kb">' . __( 'Help', 'reamaze' ) . '</a>'
    ) );
  }

  protected function _renderWidgetUserData() {
    if ( ! is_user_logged_in() ) {
      return;
    }

    $user = wp_get_current_user();

    ?>
    _support['user'] = _support['user'] || {};
    _support['user']['id'] = '<?php echo esc_js( $user->ID ); ?>';
    _support['user']['email'] = '<?php echo esc_js( $user->user_email ); ?>';
    _support['user']['name'] = '<?php echo esc_js( $user->display_name ); ?>';
    _support['user']['authkey'] = '<?php echo hash_hmac( 'sha256', $user->ID . ':' . $user->user_email, get_option( 'reamaze_account_sso_key' ) ); ?>';
    <?php
  }

  protected function _setupWidget() {
    add_action( 'wp_footer', array( &$this, 'reamaze_widget_code' ) );
  }
}

$GLOBALS['reamaze'] = new Reamaze();
