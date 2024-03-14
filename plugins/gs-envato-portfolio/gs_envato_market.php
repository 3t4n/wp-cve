<?php 
/**
 *
 * @package   GS Envato Portfolio
 * @author    GS Plugins <hello@gsplugins.com>
 * @license   GPL-2.0+
 * @link      https://www.gsplugins.com
 * @copyright 2017 GS Plugins
 *
 * @wordpress-plugin
 * Plugin Name:			GS Portfolio for Envato Lite
 * Plugin URI:			https://www.gsplugins.com/wordpress-plugins
 * Description:       	Best Responsive Envato plugin for Wordpress to display Envato portfolio. Display anywhere at your site using shortcode like [gs_envato theme="gs_envato_theme1"] & widgets. Check more shortcode examples and documentation at <a href="https://envato.gsplugins.com">GS Envato Portfolio PRO Demos & Docs</a>
 * Version:           	1.4.1
 * Author:       		GS Plugins
 * Author URI:       	https://www.gsplugins.com
 * Text Domain:       	gs-envato
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
*/

if( ! defined( 'GSENVATO_HACK_MSG' ) ) define( 'GSENVATO_HACK_MSG', __( 'Sorry cowboy! This is not your place', 'gs-envato' ) );

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GSENVATO_HACK_MSG );

/**
 * Defining constants
 */
if( ! defined( 'GSENVATO_VERSION' ) ) define( 'GSENVATO_VERSION', '1.4.1' );
if( ! defined( 'GSENVATO_MENU_POSITION' ) ) define( 'GSENVATO_MENU_POSITION', 31 );
if( ! defined( 'GSENVATO_PLUGIN_DIR' ) ) define( 'GSENVATO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! defined( 'GSENVATO_FILES_DIR' ) ) define( 'GSENVATO_FILES_DIR', GSENVATO_PLUGIN_DIR . 'gs-envato-assets' );
if( ! defined( 'GSENVATO_PLUGIN_URI' ) ) define( 'GSENVATO_PLUGIN_URI', plugins_url( '', __FILE__ ) );
if( ! defined( 'GSENVATO_FILES_URI' ) ) define( 'GSENVATO_FILES_URI', GSENVATO_PLUGIN_URI . '/gs-envato-assets' );

require_once GSENVATO_FILES_DIR . '/includes/gs-envato-item-shortcode.php';
require_once GSENVATO_FILES_DIR . '/admin/class.settings-api.php';
require_once GSENVATO_FILES_DIR . '/admin/gs_envato_options_config.php';
require_once GSENVATO_FILES_DIR . '/gs-envato-scripts.php';
require_once GSENVATO_FILES_DIR . '/gs-common-pages/gs-envato-common-pages.php';

if ( ! function_exists('gs_envato_pro_link') ) {
	function gs_envato_pro_link( $gsEnvato_links ) {
		$gsEnvato_links[] = '<a class="gs-pro-link" href="https://www.gsplugins.com/product/wordpress-envato-plugin" target="_blank">Go Pro!</a>';
		$gsEnvato_links[] = '<a href="https://www.gsplugins.com/wordpress-plugins" target="_blank">GS Plugins</a>';
		return $gsEnvato_links;
	}
	add_filter( 'plugin_action_links_' .plugin_basename(__FILE__), 'gs_envato_pro_link' );
}


/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_gs_envato_portfolio() {

    if ( ! class_exists( 'AppSero\Insights' ) ) {
        require_once GSENVATO_FILES_DIR . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( '1d25e482-f52d-41c5-b3d9-e8598c9ec923', 'GS Portfolio – Envato', __FILE__ );

    // Active insights
    $client->insights()->init();
}

appsero_init_tracker_gs_envato_portfolio();



/**
 * @review_dismiss()
 * @review_pending()
 * @gsenvato_review_notice_message()
 * Make all the above functions working.
 */
function gsenvato_review_notice(){

    gsenvato_review_dismiss();
    gsenvato_review_pending();

    $activation_time    = get_site_option( 'gsenvato_active_time' );
    $review_dismissal   = get_site_option( 'gsenvato_review_dismiss' );
    $maybe_later        = get_site_option( 'gsenvato_maybe_later' );

    if ( 'yes' == $review_dismissal ) {
        return;
    }

    if ( ! $activation_time ) {
        add_site_option( 'gsenvato_active_time', time() );
    }
    
    $daysinseconds = 259200; // 3 Days in seconds.
   
    if( 'yes' == $maybe_later ) {
        $daysinseconds = 604800 ; // 7 Days in seconds.
    }

    if ( time() - $activation_time > $daysinseconds ) {
        add_action( 'admin_notices' , 'gsenvato_review_notice_message' );
    }
}
add_action( 'admin_init', 'gsenvato_review_notice' );

if( !function_exists( 'remove_envato_admin_notices' ) ) {
    function remove_envato_admin_notices( ) {
        if ( isset( $_GET['page'] ) && in_array(  $_GET['page'], ['gs-envato-plugins-help','gs-envato-plugins-lite','envato-settings','gs-envato-plugins-premium'])  ) {
            remove_all_actions( 'network_admin_notices' );
            remove_all_actions( 'user_admin_notices' );
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }
}

add_action( 'in_admin_header',  'remove_envato_admin_notices' );

/**
 * For the notice preview.
 */
function gsenvato_review_notice_message(){
    $scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
    $url         = $_SERVER['REQUEST_URI'] . $scheme . 'gsenvato_review_dismiss=yes';
    $dismiss_url = wp_nonce_url( $url, 'gsenvato-review-nonce' );

    $_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'gsenvato_review_later=yes';
    $later_url   = wp_nonce_url( $_later_link, 'gsenvato-review-nonce' );
    ?>
    
    <div class="gslogo-review-notice">
        <div class="gslogo-review-thumbnail">
            <img src="<?php echo GSENVATO_FILES_URI ?>/assets/img/icon-128x128.png" alt="">
        </div>
        <div class="gslogo-review-text">
            <h3><?php _e( 'Leave A Review?', 'gs-pinterest' ) ?></h3>
            <p><?php _e( 'We hope you\'ve enjoyed using <b>GS Portfolio – Envato</b>! Would you consider leaving us a review on WordPress.org?', 'gs-envato' ) ?></p>
            <ul class="gslogo-review-ul">
                <li>
                    <a href="https://wordpress.org/support/plugin/gs-envato-portfolio/reviews/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php _e( 'Sure! I\'d love to!', 'gs-envato' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php _e( 'I\'ve already left a review', 'gs-envato' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $later_url ?>">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php _e( 'Maybe Later', 'gs-envato' ) ?>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsplugins.com/contact/" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <?php _e( 'I need help!', 'gs-envato' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-dismiss"></span>
                        <?php _e( 'Never show again', 'gs-envato' ) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <?php
}

/**
 * For Dismiss! 
 */
function gsenvato_review_dismiss(){

    if ( ! is_admin() ||
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_wpnonce'] ) ||
        ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gsenvato-review-nonce' ) ||
        ! isset( $_GET['gsenvato_review_dismiss'] ) ) {

        return;
    }

    add_site_option( 'gsenvato_review_dismiss', 'yes' );   
}

/**
 * For Maybe Later Update.
 */
function gsenvato_review_pending() {

    if ( ! is_admin() ||
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_wpnonce'] ) ||
        ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gsenvato-review-nonce' ) ||
        ! isset( $_GET['gsenvato_review_later'] ) ) {

        return;
    }
    // Reset Time to current time.
    update_site_option( 'gsenvato_active_time', time() );
    update_site_option( 'gsenvato_maybe_later', 'yes' );
}

/**
 * Remove Reviews Metadata on plugin Deactivation.
 */
function gsenvato_deactivate() {
    delete_option('gsenvato_active_time');
    delete_option('gsenvato_maybe_later');
    delete_option('gsadmin_maybe_later');
}
register_deactivation_hook(__FILE__, 'gsenvato_deactivate');


/**
 * Activation redirects
 *
 * @since v1.0.0
 */
function gsenv_activate() {
    add_option('gsenv_activation_redirect', true);
}
register_activation_hook(__FILE__, 'gsenv_activate');

/**
 * Redirect to options page
 *
 * @since v1.0.0
 */
function gsenv_redirect() {
    if (get_option('gsenv_activation_redirect', false)) {
        delete_option('gsenv_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            
            wp_redirect("admin.php?page=gs-envato-plugins-help");
        }
    }
}
add_action('admin_init', 'gsenv_redirect');


/**
 * Admin Notice
 */
function gsenv_admin_notice() {
  if ( current_user_can( 'install_plugins' ) ) {
    global $current_user ;
    $user_id = $current_user->ID;
    /* Check that the user hasn't already clicked to ignore the message */
    if ( ! get_user_meta($user_id, 'gsenv_ignore_notice279') ) {
      echo '<div class="gstesti-admin-notice updated" style="display: flex; align-items: center; padding-left: 0; border-left-color: #EF4B53"><p style="width: 32px;">';
      echo '<img style="width: 100%; display: block;"  src="' . plugins_url('gs-envato-portfolio-lite/gs-envato-assets/assets/img/icon-128x128.png'). '" ></p><p> ';
      printf(__('<strong>GS Envato Portfolio</strong> now powering huge websites. Use the coupon code <strong>ENJOY25P</strong> to redeem a <strong>25&#37; </strong> discount on Pro. <a href="https://www.gsplugins.com/product/wordpress-envato-plugin/" target="_blank" style="text-decoration: none;"><span class="dashicons dashicons-smiley" style="margin-left: 10px;"></span> Apply Coupon</a>
        <a href="%1$s" style="text-decoration: none; margin-left: 10px;"><span class="dashicons dashicons-dismiss"></span> I\'m good with free version</a>'),  admin_url( 'admin.php?page=gs-env-help&gsenv_nag_ignore=0' ));
      echo "</p></div>";
    }
  }
}
add_action('admin_notices', 'gsenv_admin_notice');

/**
 * Nag Ignore
 */
function gsenv_nag_ignore() {
  global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['gsenv_nag_ignore']) && '0' == $_GET['gsenv_nag_ignore'] ) {
             add_user_meta($user_id, 'gsenv_ignore_notice279', 'true', true);
  }
}
add_action('admin_init', 'gsenv_nag_ignore');


if ( ! function_exists('gsenv_row_meta') ) {
    function gsenv_row_meta( $meta_fields, $file ) {
  
    if ( $file != 'gs-envato-portfolio/gs_envato_market.php' ) {
        return $meta_fields;
    }
    
    echo "<style>.gsenv-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.gsenv-rate-stars svg{ fill:#ffb900; } .gsenv-rate-stars svg:hover{ fill:#ffb900 } .gsenv-rate-stars svg:hover ~ svg{ fill:none; } </style>";

    $plugin_rate   = "https://wordpress.org/support/plugin/gs-envato-portfolio/reviews/?rate=5#new-post";
    $plugin_filter = "https://wordpress.org/support/plugin/gs-envato-portfolio/reviews/?filter=5";
    $svg_xmlns     = "https://www.w3.org/2000/svg";
    $svg_icon      = '';

    for ( $i = 0; $i < 5; $i++ ) {
        $svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
    }

    // Set icon for thumbsup.
    $meta_fields[] = '<a href="' . esc_url( $plugin_filter ) . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __( 'Vote!', 'gscs' ) . '</a>';

    // Set icon for 5-star reviews. v1.1.22
    $meta_fields[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'gscs' ) . "'><i class='gsenv-rate-stars'>" . $svg_icon . "</i></a>";

    return $meta_fields;
    }
    add_filter( 'plugin_row_meta','gsenv_row_meta', 10, 2 );
}

if( ! function_exists('gsadmin_signup_notice')){
    function gsadmin_signup_notice(){

        gsadmin_signup_pending() ;
        $activation_time    = get_site_option( 'gsadmin_active_time' );
        $maybe_later        = get_site_option( 'gsadmin_maybe_later' );
    
        if ( ! $activation_time ) {
            add_site_option( 'gsadmin_active_time', time() );
        }
        
        if( 'yes' == $maybe_later ) {
            $daysinseconds = 604800 ; // 7 Days in seconds.
            if ( time() - $activation_time > $daysinseconds ) {
                add_action( 'admin_notices' , 'gsadmin_signup_notice_message' );
            }
        }else{
            add_action( 'admin_notices' , 'gsadmin_signup_notice_message' );
        }
    
    }
    // add_action( 'admin_init', 'gsadmin_signup_notice' );
    /**
     * For the notice signup.
     */
    function gsadmin_signup_notice_message(){
        $scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
        $_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'gsadmin_signup_later=yes';
        $later_url   = wp_nonce_url( $_later_link, 'gsadmin-signup-nonce' );
        ?>
        <div class=" gstesti-admin-notice updated gsteam-review-notice">
            <div class="gsteam-review-text">
                <h3><?php _e( 'GS Plugins Affiliate Program is now LIVE!', 'gst' ) ?></h3>
                <p>Join GS Plugins affiliate program. Share our 80% OFF lifetime bundle deals or any plugin with your friends/followers and earn up to 50% commission. <a href="https://www.gsplugins.com/affiliate-registration/?utm_source=wporg&utm_medium=admin_notice&utm_campaign=aff_regi" target="_blank">Click here to sign up.</a></p>
                <ul class="gsteam-review-ul">
                    <li style="display: inline-block;margin-right: 15px;">
                        <a href="<?php echo $later_url ?>" style="display: inline-block;color: #10738B;text-decoration: none;position: relative;">
                            <span class="dashicons dashicons-dismiss"></span>
                            <?php _e( 'Hide Now', 'gst' ) ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php
    }

    /**
     * For Maybe Later signup.
     */
    function gsadmin_signup_pending() {

        if ( ! is_admin() ||
            ! current_user_can( 'manage_options' ) ||
            ! isset( $_GET['_wpnonce'] ) ||
            ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gsadmin-signup-nonce' ) ||
            ! isset( $_GET['gsadmin_signup_later'] ) ) {

            return;
        }
        // Reset Time to current time.
        update_site_option( 'gsadmin_maybe_later', 'yes' );
    }
}