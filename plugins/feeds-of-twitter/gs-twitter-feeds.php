<?php
/**
 *
 * @package   Gs_twitter_Feed
 * @author    GS Plugins <hello@gsplugins.com>
 * @license   GPL-2.0+
 * @link      https://www.gsplugins.com
 * @copyright 2017 GS Plugins
 *
 * @wordpress-plugin
 * Plugin Name:			GS Feeds for Twitter
 * Plugin URI:			https://www.gsplugins.com/wordpress-plugins
 * Description:       	WordPress Twitter Feeds to display recent tweets elegantly. GS Twitter Feeds is powerful plugin to display latest Twitter Feeds, HashTag, User Card & Collections. GS Twitter Feed packed with necessary controlling options & 9 different themes to present Feeds & Hashtag, 2 different themes for User Card & Collection with eye catching effects. Check <a href="https://twitter.gsplugins.com">GS Twitter Feeds Demo</a> and <a href="https://docs.gsplugins.com/wordpress-twitter-feed">Documentation</a>.
 * Version:           	1.2.1
 * Author:       		GS Plugins
 * Author URI:       	https://www.gsplugins.com
 * Text Domain:       	gstwf
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 */

if( ! defined( 'GSTWF_HACK_MSG' ) ) define( 'GSTWF_HACK_MSG', __( 'Sorry cowboy! This is not your place', 'gstwf' ) );

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) die( GSTWF_HACK_MSG );

/**
 * Defining constants
 */
if ( ! defined( 'GSTWF_VERSION' ) ) define( 'GSTWF_VERSION', '1.2.1' );
if ( ! defined( 'GSTWF_MENU_POSITION' ) ) define( 'GSTWF_MENU_POSITION', '31' );
if ( ! defined( 'GSTWF_PLUGIN_DIR' ) ) define( 'GSTWF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'GSTWF_PLUGIN_URI' ) ) define( 'GSTWF_PLUGIN_URI', plugins_url( '', __FILE__ ) );
if ( ! defined( 'GSTWF_FILES_DIR' ) ) define( 'GSTWF_FILES_DIR', GSTWF_PLUGIN_DIR . 'assets' );
if ( ! defined( 'GSTWF_FILES_URI' ) ) define( 'GSTWF_FILES_URI', GSTWF_PLUGIN_URI . '/assets' );

require_once GSTWF_PLUGIN_DIR .'lib/twitter-feed-for-developers.php';
require_once GSTWF_PLUGIN_DIR .'lib/gs-twitter-feed-enque-scripts.php';
require_once GSTWF_PLUGIN_DIR .'lib/gs-twitter-shortcode.php';
require_once GSTWF_PLUGIN_DIR .'lib/gs-twitter-widgets.php';
require_once GSTWF_FILES_DIR .'/admin/class.settings-api.php';
require_once GSTWF_FILES_DIR .'/admin/gs_twitterfeed_options_config.php';
include_once GSTWF_PLUGIN_DIR .'lib/twitteroauth/twitteroauth.php';
require_once GSTWF_PLUGIN_DIR .'gs-common-pages/gs-twitter-common-pages.php';


if ( ! function_exists('gs_twitter_feed_pro_link') ) {
	function gs_twitter_feed_pro_link( $gsTwFeed_links ) {
		$gsTwFeed_links[] = '<a class="gs-pro-link" href="https://www.gsplugins.com/product/wordpress-twitter-feeds" target="_blank">Go Pro!</a>';
		$gsTwFeed_links[] = '<a href="https://www.gsplugins.com/wordpress-plugins" target="_blank">GS Plugins</a>';
		return $gsTwFeed_links;
	}
	add_filter( 'plugin_action_links_' .plugin_basename(__FILE__), 'gs_twitter_feed_pro_link' );
}

function api_upgrade_notice() {
		
    printf( '<div class="notice notice-error"><p>Due to changes with <b><i>Twitter’s API</b></i> the plugin will not update feeds. <br> 
    <b>GS Plugins</b> is working on a solution to see updated tweets in feeds again.</p></div>' );
	
}

add_action( 'admin_notices', 'api_upgrade_notice' );

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_feeds_of_twitter() {

    if ( ! class_exists( 'GSTWAppsero\Client' ) ) {
        require_once GSTWF_PLUGIN_DIR . 'appsero/Client.php';
    }

    $client = new GSTWAppsero\Client( '398ee361-a795-4148-9038-a421c3cb1675', 'GS Feeds for Twitter', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_feeds_of_twitter();

if( !function_exists( 'remove_twitter_admin_notices' ) ) {
    function remove_twitter_admin_notices( ) {
        if ( isset( $_GET['page'] ) && in_array( $_GET['page'], [ 'twitter-feed-settings', 'gs-twitter-plugins-premium', 'gs-twitter-plugins-lite', 'gs-twitter-plugins-help' ] ) ) {
            remove_all_actions( 'network_admin_notices' );
            remove_all_actions( 'user_admin_notices' );
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }
}

add_action( 'in_admin_header',  'remove_twitter_admin_notices' );

/**
 * @gstwitter_review_dismiss()
 * @gstwitter_review_pending()
 * @gstwitter_review_notice_message()
 * Make all the above functions working.
 */
function gstwitter_review_notice(){

    gstwitter_review_dismiss();
    gstwitter_review_pending();

    $activation_time    = get_site_option( 'gstwitter_active_time' );
    $review_dismissal   = get_site_option( 'gstwitter_review_dismiss' );
    $maybe_later        = get_site_option( 'gstwitter_maybe_later' );

    if ( 'yes' == $review_dismissal ) {
        return;
    }

    if ( ! $activation_time ) {
        add_site_option( 'gstwitter_active_time', time() );
    }
    
    $daysinseconds = 259200; // 3 Days in seconds.
   
    if( 'yes' == $maybe_later ) {
        $daysinseconds = 604800 ; // 7 Days in seconds.
    }

    if ( time() - $activation_time > $daysinseconds ) {
        add_action( 'admin_notices' , 'gstwitter_review_notice_message' );
    }
}
add_action( 'admin_init', 'gstwitter_review_notice' );

/**
 * For the notice preview.
 */
function gstwitter_review_notice_message(){
    $scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
    $url         = $_SERVER['REQUEST_URI'] . $scheme . 'gstwitter_review_dismiss=yes';
    $dismiss_url = wp_nonce_url( $url, 'gstwitter-review-nonce' );

    $_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'gstwitter_review_later=yes';
    $later_url   = wp_nonce_url( $_later_link, 'gstwitter-review-nonce' );
    ?>
    
    <div class="gslogo-review-notice">
        <div class="gslogo-review-thumbnail">
            <img src="<?php echo GSTWF_FILES_URI . '/img/icon-128x128.png'; ?>" alt="">
        </div>
        <div class="gslogo-review-text">
            <h3><?php _e( 'Leave A Review?', 'gstwf' ) ?></h3>
            <p><?php _e( 'We hope you\'ve enjoyed using <b>GS Feeds for Twitter</b>! Would you consider leaving us a review on WordPress.org?', 'gstwf' ) ?></p>
            <ul class="gslogo-review-ul">
                <li>
                    <a href="https://wordpress.org/support/plugin/feeds-of-twitter/reviews/" target="_blank">
                        <span class="dashicons dashicons-external"></span>
                        <?php _e( 'Sure! I\'d love to!', 'gstwf' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-smiley"></span>
                        <?php _e( 'I\'ve already left a review', 'gstwf' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $later_url ?>">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <?php _e( 'Maybe Later', 'gstwf' ) ?>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsplugins.com/contact/" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <?php _e( 'I need help!', 'gstwf' ) ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $dismiss_url ?>">
                        <span class="dashicons dashicons-dismiss"></span>
                        <?php _e( 'Never show again', 'gstwf' ) ?>
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
function gstwitter_review_dismiss(){

    if ( ! is_admin() ||
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_wpnonce'] ) ||
        ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gstwitter-review-nonce' ) ||
        ! isset( $_GET['gstwitter_review_dismiss'] ) ) {

        return;
    }

    add_site_option( 'gstwitter_review_dismiss', 'yes' );   
}

/**
 * For Maybe Later Update.
 */
function gstwitter_review_pending() {

    if ( ! is_admin() ||
        ! current_user_can( 'manage_options' ) ||
        ! isset( $_GET['_wpnonce'] ) ||
        ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'gstwitter-review-nonce' ) ||
        ! isset( $_GET['gstwitter_review_later'] ) ) {

        return;
    }
    // Reset Time to current time.
    update_site_option( 'gstwitter_active_time', time() );
    update_site_option( 'gstwitter_maybe_later', 'yes' );

}

/**
 * Remove Reviews Metadata on plugin Deactivation.
 */
function gstwitter_deactivate() {
    delete_option('gstwitter_active_time');
    delete_option('gstwitter_maybe_later');
}
register_deactivation_hook(__FILE__, 'gstwitter_deactivate');


if ( ! function_exists('gstwitter_row_meta') ) {
    function gstwitter_row_meta( $meta_fields, $file ) {
  
        if ( $file != 'feeds-of-twitter/gs-twitter-feeds.php' ) {
            return $meta_fields;
        }
    
        echo "<style>.gstwitter-rate-stars { display: inline-block; color: #ffb900; position: relative; top: 3px; }.gstwitter-rate-stars svg{ fill:#ffb900; } .gstwitter-rate-stars svg:hover{ fill:#ffb900 } .gstwitter-rate-stars svg:hover ~ svg{ fill:none; } </style>";
  
        $plugin_rate   = "https://wordpress.org/support/plugin/feeds-of-twitter/reviews/?rate=5#new-post";
        $plugin_filter = "https://wordpress.org/support/plugin/feeds-of-twitter/reviews/?filter=5";
        $svg_xmlns     = "https://www.w3.org/2000/svg";
        $svg_icon      = '';
  
        for ( $i = 0; $i < 5; $i++ ) {
          $svg_icon .= "<svg xmlns='" . esc_url( $svg_xmlns ) . "' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>";
        }
  
        // Set icon for thumbsup.
        $meta_fields[] = '<a href="' . esc_url( $plugin_filter ) . '" target="_blank"><span class="dashicons dashicons-thumbs-up"></span>' . __( 'Vote!', 'gscs' ) . '</a>';
  
        // Set icon for 5-star reviews. v1.1.22
        $meta_fields[] = "<a href='" . esc_url( $plugin_rate ) . "' target='_blank' title='" . esc_html__( 'Rate', 'gscs' ) . "'><i class='gstwitter-rate-stars'>" . $svg_icon . "</i></a>";
  
        return $meta_fields;
    }

    add_filter( 'plugin_row_meta','gstwitter_row_meta', 10, 2 );

}


function disable_twitter_pro() {
    if ( is_plugin_active( 'gs-twitter-feeds/gs-twitter-feeds.php' ) ) {
        deactivate_plugins( 'gs-twitter-feeds/gs-twitter-feeds.php', true );
    }
    add_option( 'gs_twitter_activation_redirect', true );
}

register_activation_hook( __FILE__, 'disable_twitter_pro' );

/**
 * Redirect to options page
 *
 * @since v1.0.0
 */
function gstwitter_redirect() {
    if (get_option('gs_twitter_activation_redirect', false)) {
        delete_option('gs_twitter_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=gs-twitter-plugins-help");
        }
    }
}
add_action( 'admin_init', 'gstwitter_redirect' );