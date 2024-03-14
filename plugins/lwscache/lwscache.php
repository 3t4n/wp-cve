<?php
/**
 * Plugin Name:       LWSCache
 * Plugin URI:        https://www.lws.fr/
 * Description:       Cleans nginx's proxy cache whenever a post is edited/published.
 * Version:           2.8.2
 * Author:            LWS
 * Author URI:        https://www.lws.fr
 * Requires at least: 5.0
 * Tested up to:      6.4
 *
 * @link              https://www.lws.fr
 * @since             1.0
 * @package           
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Hide all errors for the client
// error_reporting(0);

define('LWSCACHE_URL', plugin_dir_url(__FILE__));

/**
 * Base URL of plugin
 */
if ( ! defined( 'LWS_CACHE_BASEURL' ) ) {
	define( 'LWS_CACHE_BASEURL', plugin_dir_url( __FILE__ ) );
}

/**
 * Base Name of plugin
 */
if ( ! defined( 'LWS_CACHE_BASENAME' ) ) {
	define( 'LWS_CACHE_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Base PATH of plugin
 */
if ( ! defined( 'LWS_CACHE_BASEPATH' ) ) {
	define( 'LWS_CACHE_BASEPATH', plugin_dir_path( __FILE__ ) );
}

function lws_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}

$check_plugins = array(
    "wp-rocket/wp-rocket.php" => "WP Rocket",
    "powered-cache/powered-cache.php" => "Powered Cache",
    "wp-super-cache/wp-cache.php" => "WP Super Cache",
    "wp-optimize/wp-optimize.php" => "WP-Optimize",
    "wp-fastest-cache/wpFastestCache.php" => "WP Fastest Cache",
    "w3-total-cache/w3-total-cache.php" => "W3 Total Cache",
    "autoptimize/autoptimize.php" => "Autoptimize",
    "breeze/breeze.php" => "Breeze",
    "cache-enabler/cache-enabler.php" => "Cache Enabler",
    "cache-master/cache-master.php" => "Cache Master",
    "comet-cache/comet-cache.php" => "Comet Cache",
    "hummingbird-performance/wp-hummingbird.php" => "Hummingbird",
    "hyper-cache/advanced-cache.php" => "Hyper Cache",
    "litespeed-cache/litespeed-cache.php" => "LiteSpeed Cache",
    "sg-cachepress/sg-cachepress.php" => "Speed Optimizer",
    // "lws-optimize/lws-optimize.php" => "LWS Optimize,
);

// function lwscache_check_compatibility(){
//     global $check_plugins;
//     foreach ($check_plugins as $plugin => $name) {
//         if (is_plugin_active( $plugin )) {
//             add_action( 'admin_notices', 'lwscache_other_cache_plugin' );
//             break;
//         }        
//     }
// }
// add_action( 'admin_init', 'lwscache_check_compatibility' );

function lwscache_other_cache_plugin() {
    global $check_plugins;
    ?>
    <div class="notice notice-error is-dismissible" style="padding-bottom: 10px;">
        <p>
            <?php _e( 'We have detected that the following plugins are activated, which are not compatible with LWSCache: ', 'lwscache' ); ?>
        </p>
        <?php foreach ($check_plugins as $plugin => $name) : ?>
            <?php if (is_plugin_active($plugin)) : ?>        
            <div style="display: flex; align-items: center; gap: 8px; line-height: 35px;"><?php echo $name; ?> 
                <a class="wp-core-ui button" value="<?php echo $plugin; ?>" id="lwscache_deactivate_button" style="display: flex; align-items: center; width: fit-content;">
                    <?php _e('Deactivate', 'lwscache'); ?>
                </a>
            </div>
            <?php endif ?>  
        <?php endforeach; ?>
    </div>

    <script>
        document.querySelectorAll('a[id^="lwscache_deactivate_button"]').forEach(function(element){
            element.addEventListener('click', function(event){       
                let el = this;         
                let slug = this.getAttribute('value');
                this.style.pointerEvents = "none";
                this.innerHTML = `
                    <img src="<?php echo plugin_dir_url(__FILE__); ?>/admin/icons/loading_black.svg" width="20px">
                `;
                var data = {                
                    _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('deactivate_plugin_lwscache')); ?>',        
                    action: "lwscache_other_cache_plugin_ajax",                    
                    data: {id: slug},
                };
                jQuery.post(ajaxurl, data, function(response){                    
                    el.innerHTML = `<?php _e('Deactivated', 'lwscache'); ?>`;
                });
            })
        });
    </script>
    <?php
}

// add_action("wp_ajax_lwscache_other_cache_plugin_ajax", "lwscache_remove_other_cache");
// function lwscache_remove_other_cache()
// {
//     check_ajax_referer('deactivate_plugin_lwscache', '_ajax_nonce');
//     if (isset($_POST['action'])) {
//         if (isset($_POST['data'])){
//             deactivate_plugins(htmlspecialchars($_POST['data']['id']));
//         }
//     }
// }


add_action("init", "lwscache_WPRocket_PoweredCache");
function lwscache_WPRocket_PoweredCache(){

    if (lws_is_plugin_active("powered-cache/powered-cache.php") && get_option('lws_cache_poweredcache_addons') != "blocked"){
        update_option('lws_cache_poweredcache_addons', true);
        $htaccess = file_get_contents(ABSPATH . "/.htaccess");
        $htaccess = str_replace('ExpiresByType  text/html                       "access plus 0 seconds"', '', $htaccess);
        file_put_contents(ABSPATH . "/.htaccess", $htaccess); 
    }

    if (lws_is_plugin_active("wp-rocket/wp-rocket.php") && get_option('lws_cache_wprocket_addons') != "blocked"){
        add_filter('rocket_htaccess_mod_expires', __NAMESPACE__ . '\remove_htaccess_html_expire');
    
        if ( ! function_exists( 'flush_rocket_htaccess' ) ) {
            return false;
        }
        // Update WP Rocket .htaccess rules. Is a WPRocket function
        flush_rocket_htaccess();

        update_option('lws_cache_wprocket_addons', true);
    }
}

// Add itself to one of WPRocket filter
function remove_htaccess_html_expire( $rules ) {
	
	$rules = preg_replace( '@\s*#\s*Your document html@', '', $rules );
	$rules = preg_replace( '@\s*ExpiresByType text/html\s*"access plus \d+ (seconds|minutes|hour|week|month|year)"@', '', $rules );

	return $rules;
}

function lwscache_wprockethtaccess_remove() {
    if (is_plugin_active("wp-rocket-htaccess-no-expires-html/wp-rocket-htaccess-no-expires-html.php") && get_option('lws_cache_wprocket_addons') == true){
    ?>
        <div class="notice notice-warning is-dismissible" style="padding-bottom: 10px;">
            <p><?php _e( 'You are using the WPRocket Companion plugin to remove HTML expires rules. However LWSCache already does all that this plugin can offer.', 'lwscache' ); ?></p>
            <a class="wp-core-ui button" style="display:flex; align-items: center; width: fit-content;" id="deactivate_wprocket_companion"><?php _e('Deactivate the plugin', 'lwscache'); ?></a>
        </div>
        <script>
            document.getElementById('deactivate_wprocket_companion').addEventListener('click', function(event){          
                this.innerHTML = `
                    <img src="<?php echo plugin_dir_url(__FILE__); ?>/admin/icons/loading_black.svg" width="20px">
                `;
                var data = {                
                    _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('deactivate_wprocket_companion')); ?>',        
                    action: "lwscache_deactivate_companion_rocket",
                    data: true,
                };
                jQuery.post(ajaxurl, data, function(response){
                    location.reload();
                });
            })
        </script>
    <?php
    }
}
add_action( 'admin_notices', 'lwscache_wprockethtaccess_remove' );

// Hide all notices while on the plugin page (even LWS ones)
add_action( 'admin_notices', function() { 
    if ( substr( get_current_screen()->id, 0, 29 ) == "toplevel_page_options-general") {
        remove_all_actions( 'admin_notices' ); 
    }
}, 0 );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lws-cache-activator.php
 */
function activate_lws_cache() {
    set_transient('lwscache_remind_me', 3024000);        
	require_once LWS_CACHE_BASEPATH . 'includes/class-lws-cache-activator.php';
	LWSCache_Activator::activate();
}

function lwscache_review_ad_plugin(){
    ?>
    <script>
        function lwscache_remind_me(){
            var data = {                
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('reminder_for_cache')); ?>',        
                action: "lwscache_reminder_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response){
                jQuery("#lwsache_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lwsache_review_notice").addClass("lws_hidden");
                }, 800);    
            });

        }

        function lwscache_do_not_bother_me(){
            var data = {                
                _ajax_nonce: '<?php echo esc_attr(wp_create_nonce('donotask_for_cache')); ?>',        
                action: "lwscache_donotask_ajax",
                data: true,
            };
            jQuery.post(ajaxurl, data, function(response){
                jQuery("#lwsache_review_notice").addClass("animationFadeOut");
                setTimeout(() => {
                    jQuery("#lwsache_review_notice").addClass("lws_hidden");
                }, 800); 
            });            
        }
    </script>

    <div class="notice notice-info is-dismissible lwscache_review_block_general" id="lwscache_review_notice">
        <div class="lwscache_circle">
            <img class="lwscache_review_block_image" src="<?php echo esc_url(LWSCACHE_URL . "admin/icons/plugin_lwscache.svg")?>" width="40px" height="40px">
        </div>
        <div style="padding:16px">
            <h1 class="lwscache_review_block_title"> <?php esc_html_e('Thank you for using LWS Cache!', 'lwscache'); ?></h1>
            <p class="lwscache_review_block_desc"><?php _e('Evaluate our plugin to support our antivirus and help us make it even better!', 'lwscache' ); ?></p>
            <a class="lwscache_button_rate_plugin" href="https://wordpress.org/support/plugin/lwscache/reviews/" target="_blank" ><img style="margin-right: 8px;" src="<?php echo esc_url(LWSCACHE_URL . "admin/icons/noter.svg")?>" width="15px" height="15px"><?php esc_html_e('Rate', ''); ?></a>
            <a class="lwscache_review_button_secondary" onclick="lwscache_remind_me()"><?php esc_html_e('Remind me later', 'lwscache'); ?></a>
            <a class="lwscache_review_button_secondary" onclick="lwscache_do_not_bother_me()"><?php esc_html_e('Do not ask again', 'lwscache'); ?></a>
        </div>
    </div>
    <?php
}

//AJAX Reminder//
add_action("wp_ajax_lwscache_reminder_ajax", "lwscache_remind_me_later");
function lwscache_remind_me_later(){
    check_ajax_referer('reminder_for_cache', '_ajax_nonce');
    if (isset($_POST['data'])){
        set_transient('lwscache_remind_me', 2592000);        
    }
}

//AJAX Reminder//
add_action("wp_ajax_lwscache_deactivate_companion_rocket", "lwscache_deactivate_companion_rocket");
function lwscache_deactivate_companion_rocket(){
    check_ajax_referer('deactivate_wprocket_companion', '_ajax_nonce');
    deactivate_plugins('wp-rocket-htaccess-no-expires-html/wp-rocket-htaccess-no-expires-html.php');
}

//AJAX Reminder//
add_action("wp_ajax_lwscache_donotask_ajax", "lwscache_do_not_ask");
function lwscache_do_not_ask(){
    check_ajax_referer('donotask_for_cache', '_ajax_nonce');
    if (isset($_POST['data'])){
        update_option('lwscache_do_not_ask_again', true);        
    }
}

add_action('admin_enqueue_scripts', 'lwscache_scripts');
function lwscache_scripts()
{
    wp_enqueue_style('lws_cache_css_out', LWSCACHE_URL . "admin/css/lws_cache_style_out.css");
    if (!get_transient('lwscache_remind_me') && !get_option('lwscache_do_not_ask_again')){
        add_action( 'admin_notices', 'lwscache_review_ad_plugin' );
    }
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lws-cache-deactivator.php
 */
function deactivate_lws_cache() {
	require_once LWS_CACHE_BASEPATH . 'includes/class-lws-cache-deactivator.php';
	LWSCache_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lws_cache' );
register_deactivation_hook( __FILE__, 'deactivate_lws_cache' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require LWS_CACHE_BASEPATH . 'includes/class-lws-cache.php';


/*AJAX DOWNLOAD AND ACTIVATE PLUGINS*/

//AJAX DL Plugin//
add_action("wp_ajax_lwscache_downloadPlugin", "wp_ajax_install_plugin");
//

//AJAX Activate Plugin//
add_action("wp_ajax_lwscache_activatePlugin", "lwscache_activate_plugin");
function lwscache_activate_plugin()
{
    if (isset($_POST['ajax_slug'])) {
        switch (sanitize_textarea_field($_POST['ajax_slug'])) {
            case 'lws-hide-login':
                activate_plugin('lws-hide-login/lws-hide-login.php');
                break;
            case 'lws-sms':
                activate_plugin('lws-sms/lws-sms.php');
                break;
            case 'lws-tools':
                activate_plugin('lws-tools/lws-tools.php');
                break;
            case 'lws-affiliation':
                activate_plugin('lws-affiliation/lws-affiliation.php');
                break;
            case 'lws-cleaner':
                activate_plugin('lws-cleaner/lws-cleaner.php');
                break;
            case 'lwscache':
                activate_plugin('lwscache/lwscache.php');
                break;
            case 'lws-optimize':
                activate_plugin('lws-optimize/lws-optimize.php');
                break;
        }
        
    }
    wp_die();
}
//

/*END AJAX*/



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_lws_cache() {

	global $lws_cache;

	$lws_cache = new LWSCache();
	$lws_cache->run();

	// Load WP-CLI command.
	if ( defined( 'WP_CLI' ) && WP_CLI ) {

		require_once LWS_CACHE_BASEPATH . 'class-lws-cache-wp-cli-command.php';
		\WP_CLI::add_command( 'lws-cache', 'LWSCache_WP_CLI_Command' );

	}

}
run_lws_cache();

add_action("wp_ajax_lwscache_change_cache_state", "lwscache_change_cache_state");
function lwscache_change_cache_state()
{
    check_ajax_referer('fastest_cache_change_state', '_ajax_nonce');
    if (isset($_POST['cache_state'])){        
        $state = sanitize_text_field($_POST['cache_state']);
        $array = (explode('/', ABSPATH));
        $path = implode('/', array($array[0], $array[1], $array[2]));        
        $api_key = file_get_contents($path . '/tmp/fc_token_api');
        wp_remote_post(
            "http://localhost:6084/api/domains/" . $_SERVER['HTTP_HOST'],
            array(
            'method'      => 'PUT',
            'headers'     => array('Authorization' => 'Bearer ' . $api_key, 'Content-Type' => "application/x-www-form-urlencoded" ),
            'body'		  => array(
                'template' => $state,
                ),
            )
        );

        wp_die('OK');
    }
    wp_die("FAILED");
}


add_action("wp_ajax_change_autopurge", "lwscache_change_autopurge");
function lwscache_change_autopurge()
{
    check_ajax_referer('lwscache_change_autopurge_nonce', '_ajax_nonce');
    if (isset($_POST['state'])){
        global $lws_cache_admin;

        $state = sanitize_text_field($_POST['state']);
        $nginx_settings = get_site_option('rt_wp_lws_cache_options', $lws_cache_admin->lws_cache_default_settings());


        if ($state == "true") {
            $nginx_settings['enable_purge'] = "1";
            update_site_option('rt_wp_lws_cache_options', $nginx_settings);
            update_option('lws_cache_wprocket_addons', true);
            update_option('lws_cache_poweredcache_addons', true);
        } else {
            $nginx_settings['enable_purge'] = "0";
            update_site_option('rt_wp_lws_cache_options', $nginx_settings);
            update_option('lws_cache_wprocket_addons', "blocked");
            update_option('lws_cache_poweredcache_addons', "blocked");
        }

        wp_die(json_encode(array('code' => "SUCCESS", 'data' => $state), JSON_PRETTY_PRINT));
    }
    wp_die(json_encode(array('code' => "NO_STATE", 'data' => "No state given, nothing changed"), JSON_PRETTY_PRINT));
}

add_action("wp_ajax_lwscache_get_excluded_url", "lwscache_exclude_urls");
function lwscache_exclude_urls()
{
    check_ajax_referer('lwscache_get_excluded_nonce', '_ajax_nonce');    
    wp_die(json_encode(array('code' => "SUCCESS", 'data' => get_site_option('lwscache_excluded_urls', array()), 'domain' => site_url()), JSON_PRETTY_PRINT));
}

add_action("wp_ajax_lwscache_save_excluded_url", "lwscache_save_urls");
function lwscache_save_urls()
{
    check_ajax_referer('lwscache_save_excluded_nonce', '_ajax_nonce');
    if (isset($_POST['data'])){
        $urls = array();

        foreach ($_POST['data'] as $data) {            
            $urls[] = esc_html($data['value']);
        }

        if (update_site_option('lwscache_excluded_urls', $urls)) {
            wp_die(json_encode(array('code' => "SUCCESS", 'data' => $urls, 'domain' => site_url()), JSON_PRETTY_PRINT));
        } else {
            wp_die(json_encode(array('code' => "FAILED", 'data' => $urls, 'domain' => site_url()), JSON_PRETTY_PRINT));

        }
    }
    wp_die(json_encode(array('code' => "NO_DATA", 'data' => $_POST, 'domain' => site_url()), JSON_PRETTY_PRINT));
}
