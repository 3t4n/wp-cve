<?php
/**
 * Plugin Name: Link Whisper Free
 * Version: 0.7.1
 * Description: Quickly build smart internal links both to and from your content. Additionally, gain valuable insights with in-depth internal link reporting.
 * Author: Link Whisper
 * Author URI: https://linkwhisper.com
 * Tested up to: 6.4
 * Text Domain: wpil
 */

//autoloader
spl_autoload_register( 'wpil_autoloader' );
function wpil_autoloader( $class_name ) {
    if ( false !== strpos( $class_name, 'Wpil' ) ) {
        $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR;
        $class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';
        require_once $classes_dir . $class_file;
    }
}
define( 'WPIL_STORE_URL', 'https://linkwhisper.com');
define( 'WPIL_VERSION_NUMBER', '0.7.1');
define( 'WP_INTERNAL_LINKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define( 'WP_INTERNAL_LINKING_PLUGIN_URL', plugin_dir_url(__FILE__));
define( 'WPIL_PLUGIN_NAME', plugin_basename( __FILE__ ));
define( 'WPIL_OPTION_IGNORE_WORDS', 'wpil_2_ignore_words');
define( 'WPIL_OPTION_IGNORE_NUMBERS', 'wpil_2_ignore_numbers');
define( 'WPIL_OPTION_UPDATE_REPORTING_DATA_ON_SAVE', 'wpil_option_update_reporting_data_on_save');
define( 'WPIL_OPTION_DONT_COUNT_INBOUND_LINKS', 'wpil_option_dont_count_inbound_links');
define( 'WPIL_OPTION_POST_TYPES', 'wpil_2_post_types');
define( 'WPIL_OPTION_REPORT_LAST_UPDATED', 'wpil_2_report_last_updated');
define( 'WPIL_LINKS_OUTBOUND_INTERNAL_COUNT', 'wpil_links_outbound_internal_count');
define( 'WPIL_LINKS_INBOUND_INTERNAL_COUNT', 'wpil_links_inbound_internal_count');
define( 'WPIL_LINKS_OUTBOUND_EXTERNAL_COUNT', 'wpil_links_outbound_external_count');
define( 'WPIL_META_KEY_SYNC', 'wpil_sync_report3');
define( 'WPIL_META_KEY_SYNC_TIME', 'wpil_sync_report2_time');
define( 'WPIL_META_KEY_ADD_LINKS', 'wpil_add_links');
define( 'WPIL_EMAIL_OFFER_DISMISSED', 'wpil_email_offer_dismissed');
define( 'WPIL_SIGNED_UP_EMAIL_OFFER', 'wpil_signed_up_email_offer');
define( 'WPIL_PREMIUM_NOTICE_DISMISSED', 'wpil_premium_notice_dismissed');
define( 'WPIL_LINK_TABLE_IS_CREATED', 'wpil_link_table_is_created');
define( 'WPIL_STATUS_LINK_TABLE_EXISTS', get_option(WPIL_LINK_TABLE_IS_CREATED, false));
define( 'WPIL_STATUS_PROCESSING_START', microtime(true));


Wpil_Init::register_services();

register_activation_hook(__FILE__, [Wpil_Base::class, 'activate'] );
register_uninstall_hook(__FILE__, array(Wpil_Base::class, 'delete_link_whisper_data'));

if (is_admin())
{
    if(!function_exists('get_plugin_data'))
    {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
}


add_action('plugins_loaded', 'wpil_init');

if (!function_exists('wpil_init'))
{
    function wpil_init()
    {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'wpil');
        unload_textdomain('wpil');
        load_textdomain('wpil', WP_INTERNAL_LINKING_PLUGIN_DIR . 'languages/' . "wpil-" . $locale . '.mo');
        load_plugin_textdomain('wpil', false, WP_INTERNAL_LINKING_PLUGIN_DIR . 'languages');
    }
}

add_filter('plugin_row_meta', 'wpil_filter_plugin_row_meta', 4, 10);
if(!function_exists('wpil_filter_plugin_row_meta')){
    function wpil_filter_plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status){
        $plugin_slug = isset( $plugin_data['slug'] ) ? $plugin_data['slug'] : sanitize_title( $plugin_data['Name'] );
        if($plugin_slug === 'link-whisper'){
            $plugin_meta[] = sprintf(
                '<a href="%s" class="thickbox open-plugin-details-modal">%s</a>',
                esc_url( get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=link-whisper&section=changelog&TB_iframe=true' ),
                __('Change Log', 'wpil')
            );
        }

        return $plugin_meta;
    }
}

/**
 * A text logging function for use when error_log isn't a possibility.
 * I find myself copy-pasting file writers often enough that it makes sense to add a logger here for debugging
 * Can accept a string or array/object for writing
 * 
 * @param mixed $content The content to write to the file.
 **/
if(!function_exists('WPIL_TEXT_LOGGER')){
    function WPIL_TEXT_LOGGER($content){
        $file = fopen(trailingslashit(WP_INTERNAL_LINKING_PLUGIN_DIR) . 'wpil_text_log.txt', 'a');
        fwrite($file, print_r($content, true));
        fclose($file);
    }
}

if(false){
    // track errors based on shutdown in case something's not telling us there's an error
    function wpil_shutdown_tracking() {
        $error = error_get_last();
        error_log(print_r(array('shutdown error tracking', 'error' => $error, 'time' => microtime(true) - WPIL_STATUS_PROCESSING_START, 'last_error' => debug_backtrace()), true));
    }

    register_shutdown_function('wpil_shutdown_tracking');
}


