<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ays-pro.com/
 * @since             1.0.0
 * @package           Ays_Chatgpt_Assistant
 *
 * @wordpress-plugin
 * Plugin Name:       AI Assistant with ChatGPT by AYS
 * Plugin URI:        https://https://ays-pro.com/wordpress
 * Description:       Ays ChatGPT Assistant Plugin for Wordpress
 * Version:           1.8.3
 * Author:            Ays ChatGPT Assistant Team
 * Author URI:        https://ays-pro.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ays-chatgpt-assistant
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CHATGPT_ASSISTANT_VERSION', '1.8.3' );
define( 'CHATGPT_ASSISTANT_NAME_VERSION', '1.0.0' );
define( 'CHATGPT_ASSISTANT_NAME', 'ays-chatgpt-assistant' );

define( 'CHATGPT_ASSISTANT_CLASS_PREFIX', 'ays-chatgpt-assistant' );
define( 'CHATGPT_ASSISTANT_ID_PREFIX', 'ays-chatgpt-assistant' );
define( 'CHATGPT_ASSISTANT_NAME_PREFIX', 'ays_chatgpt_assistant' );
define( 'CHATGPT_ASSISTANT_OPTIONS_PREFIX', 'chatgpt_assistant_' );
define( 'CHATGPT_ASSISTANT_DB_PREFIX', 'ayschatgpt_' );

if( ! defined( 'CHATGPT_ASSISTANT_BASENAME' ) )
    define( 'CHATGPT_ASSISTANT_BASENAME', plugin_basename( __FILE__ ) );

if( ! defined( 'CHATGPT_ASSISTANT_DIR' ) )
    define( 'CHATGPT_ASSISTANT_DIR', plugin_dir_path( __FILE__ ) );

if( ! defined( 'CHATGPT_ASSISTANT_BASE_URL' ) )
    define( 'CHATGPT_ASSISTANT_BASE_URL', plugin_dir_url(__FILE__ ) );

if( ! defined( 'CHATGPT_ASSISTANT_ASSETS_PATH' ) )
    define( 'CHATGPT_ASSISTANT_ASSETS_PATH', plugin_dir_path( __FILE__ ) . 'assets' );

if( ! defined( 'CHATGPT_ASSISTANT_ASSETS_URL' ) )
    define( 'CHATGPT_ASSISTANT_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );

if( ! defined( 'CHATGPT_ASSISTANT_ADMIN_PATH' ) )
    define( 'CHATGPT_ASSISTANT_ADMIN_PATH', plugin_dir_path( __FILE__ ) . 'admin' );

if( ! defined( 'CHATGPT_ASSISTANT_ADMIN_URL' ) )
    define( 'CHATGPT_ASSISTANT_ADMIN_URL', plugin_dir_url( __FILE__ ) . 'admin' );

if( ! defined( 'CHATGPT_ASSISTANT_PUBLIC_PATH' ) )
    define( 'CHATGPT_ASSISTANT_PUBLIC_PATH', plugin_dir_path( __FILE__ ) . 'public' );

if( ! defined( 'CHATGPT_ASSISTANT_PUBLIC_URL' ) )
    define( 'CHATGPT_ASSISTANT_PUBLIC_URL', plugin_dir_url( __FILE__ ) . 'public' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-chatgpt-assistant-activator.php
 */
function activate_chatgpt_assistant() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chatgpt-assistant-activator.php';
	Chatgpt_Assistant_Activator::db_update_check();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-chatgpt-assistant-deactivator.php
 */
function deactivate_chatgpt_assistant() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-chatgpt-assistant-deactivator.php';
	Chatgpt_Assistant_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_chatgpt_assistant' );
register_deactivation_hook( __FILE__, 'deactivate_chatgpt_assistant' );

add_action( 'plugins_loaded', 'activate_chatgpt_assistant' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-chatgpt-assistant.php';
require plugin_dir_path( __FILE__ ) . 'assistant/chatgpt-assistant-block.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_chatgpt_assistant() {

    add_action( 'admin_notices', 'chatgpt_assistant_general_admin_notice' );
	$plugin = new Chatgpt_Assistant();
	$plugin->run();

}

if( !function_exists( 'chatgpt_assistant_general_admin_notice' ) ){
    function chatgpt_assistant_general_admin_notice(){
        global $wpdb;
        if ( isset($_GET['page']) && strpos($_GET['page'], CHATGPT_ASSISTANT_NAME) !== false ) {
            ?>
             <div class="ays-notice-banner">
                <div class="navigation-bar">
                    <div id="navigation-container">
                        <div class="ays-navigation-container-logo-updrade-box">
                            <div>
                                <a class="logo-container" href="https://ays-pro.com/wordpress/chatgpt-assistant" target="_blank">
                                    <img class="logo" src="<?php echo esc_url(CHATGPT_ASSISTANT_ADMIN_URL) . '/images/icons/chatgpt-icon.png'; ?>" alt="ChatGPT Assistant logo" title="ChatGPT Assistant logo"/>
                                </a>
                            </div>
                            <div>
                                <a class="ays-navigation-container-updrade-button-box" href="https://ays-pro.com/wordpress/chatgpt-assistant?utm_source=gpt-free-dashboard&utm_medium=gpt-top-banner&utm_campaign=gpt-upgrade-button" target="_blank">
                                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" overflow="visible" preserveAspectRatio="none" viewBox="0 0 10 19" width="10" height="19"><g transform="translate(0, 0)"><defs><path id="path-168724975258819352" d="M13.088382853588492 16.160785048705847 C13.088382853588492 16.160785048705847 11.529044301614439 16.160785048705847 11.529044301614439 16.160785048705847 C9.92603607571933 16.160785048705847 9.124549034575494 16.160785048705847 8.788883229792116 15.620335606678474 C8.453217425008738 15.079769057513069 8.789941681622885 14.331454445475167 9.46339019485118 12.834591007123292 C9.46339019485118 12.834591007123292 12.572017697008222 5.925691448808299 12.572017697008222 5.925691448808299 C12.997902293861513 4.979184716361485 13.21073078026334 4.505931350138079 13.434143784972857 4.556533344482613 C13.657442977657558 4.607147049540951 13.657442977657558 5.128660267348211 13.657442977657558 6.171698413676532 C13.657442977657558 6.171698413676532 13.657442977657558 11.35939238930773 13.657442977657558 11.35939238930773 C13.657442977657558 11.63541391365411 13.657442977657558 11.773483229396309 13.74075337982127 11.859205654437273 C13.824063781984979 11.944928079478235 13.958248159240465 11.944928079478235 14.226503101726623 11.944928079478235 C14.226503101726623 11.944928079478235 15.785841653700675 11.944928079478235 15.785841653700675 11.944928079478235 C17.388884023203232 11.944928079478235 18.1903483019421 11.944928079478235 18.525979963118033 12.485377521505606 C18.86161162429397 13.025944070671017 18.524955654894715 13.774258682708917 17.85152990407138 15.271005013922752 C17.85152990407138 15.271005013922752 14.742868258306892 22.179974836520575 14.742868258306892 22.179974836520575 C14.316983661453602 23.126551833250204 14.104155175051774 23.599781778046005 13.880742170342259 23.549191494415275 C13.657442977657558 23.498601210784532 13.657442977657558 22.977006017980656 13.657442977657558 21.93404984664896 C13.657442977657558 21.93404984664896 13.657442977657558 16.74632073887635 13.657442977657558 16.74632073887635 C13.657442977657558 16.470299214529973 13.657442977657558 16.332229898787773 13.574132575493845 16.24650747374681 C13.490822173330136 16.160785048705847 13.356637796074649 16.160785048705847 13.088382853588492 16.160785048705847 Z" vector-effect="non-scaling-stroke"/></defs><g transform="translate(-8.65742747414592, -4.552861685310114)"><path d="M13.088382853588492 16.160785048705847 C13.088382853588492 16.160785048705847 11.529044301614439 16.160785048705847 11.529044301614439 16.160785048705847 C9.92603607571933 16.160785048705847 9.124549034575494 16.160785048705847 8.788883229792116 15.620335606678474 C8.453217425008738 15.079769057513069 8.789941681622885 14.331454445475167 9.46339019485118 12.834591007123292 C9.46339019485118 12.834591007123292 12.572017697008222 5.925691448808299 12.572017697008222 5.925691448808299 C12.997902293861513 4.979184716361485 13.21073078026334 4.505931350138079 13.434143784972857 4.556533344482613 C13.657442977657558 4.607147049540951 13.657442977657558 5.128660267348211 13.657442977657558 6.171698413676532 C13.657442977657558 6.171698413676532 13.657442977657558 11.35939238930773 13.657442977657558 11.35939238930773 C13.657442977657558 11.63541391365411 13.657442977657558 11.773483229396309 13.74075337982127 11.859205654437273 C13.824063781984979 11.944928079478235 13.958248159240465 11.944928079478235 14.226503101726623 11.944928079478235 C14.226503101726623 11.944928079478235 15.785841653700675 11.944928079478235 15.785841653700675 11.944928079478235 C17.388884023203232 11.944928079478235 18.1903483019421 11.944928079478235 18.525979963118033 12.485377521505606 C18.86161162429397 13.025944070671017 18.524955654894715 13.774258682708917 17.85152990407138 15.271005013922752 C17.85152990407138 15.271005013922752 14.742868258306892 22.179974836520575 14.742868258306892 22.179974836520575 C14.316983661453602 23.126551833250204 14.104155175051774 23.599781778046005 13.880742170342259 23.549191494415275 C13.657442977657558 23.498601210784532 13.657442977657558 22.977006017980656 13.657442977657558 21.93404984664896 C13.657442977657558 21.93404984664896 13.657442977657558 16.74632073887635 13.657442977657558 16.74632073887635 C13.657442977657558 16.470299214529973 13.657442977657558 16.332229898787773 13.574132575493845 16.24650747374681 C13.490822173330136 16.160785048705847 13.356637796074649 16.160785048705847 13.088382853588492 16.160785048705847 Z" style="stroke-width: 0; stroke-linecap: butt; stroke-linejoin: miter; fill: rgb(16, 163, 127);" vector-effect="non-scaling-stroke"/></g></g></svg>
                                    <span class="ays-navigation-container-updrade-button-text"><?php echo __('Upgrade', 'chatgpt-assistant'); ?></span>
                                </a>
                                <span style="font-size: 10px;font-style: normal;font-weight: 600;line-height: normal;margin-top:5px;"><?php echo __('One-time payment', 'chatgpt-assistant')?></span>
                            </div>
                        </div>
                        <ul id="menu">
                            <!-- <li class="modile-ddmenu-xss"><a class="ays-btn" href="https://ays-pro.com/wordpress/chatgpt-assistant/" target="_blank">PRO</a></li> -->
                            <!-- <li class="modile-ddmenu-xs"><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/reviews/?rate=5#new-post" target="_blank">Rate Us</a></li> -->
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://plugins.ays-demo.com/wordpress-chatgpt-plugin-demo/" target="_blank">DEMO</a></li>
                            <li class="modile-ddmenu-lg take_survay"><a class="ays-btn" href="https://ays-demo.com/chatgpt-ai-assistant-plugin-feedback-survey/" target="_blank">MAKE A SUGGESTION</a></li>
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://ays-pro.com/wordpress-chatgpt-assistant-user-manual" target="_blank">DOCUMENTATION</a></li>
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/" target="_blank">SUPPORT FORUM</a></li>
                            <!-- <li class="modile-ddmenu-lg take_survay"><a class="ays-btn" href="https://ays-demo.com/chatgpt-assistant-plugin-suggestion-box/" target="_blank">MAKE A SUGGESTION</a></li> -->
                            <li class="modile-ddmenu-lg"><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/" target="_blank">CONTACT US</a></li>
                            <li class="modile-ddmenu-md">
                                <a class="toggle_ddmenu" href="javascript:void(0);"><img src="<?php echo esc_url(CHATGPT_ASSISTANT_ADMIN_URL) . '/images/icons/ellipsis.svg'; ?>"/></a>
                                <ul class="ddmenu" data-expanded="false" style="align-items: flex-start;">
                                    <li><a class="ays-btn" href="https://plugins.ays-demo.com/wordpress-chatgpt-plugin-demo/" target="_blank">DEMO</a></li>
                                    <li><a class="ays-btn take_survay" href="https://ays-demo.com/chatgpt-ai-assistant-plugin-feedback-survey/" target="_blank">MAKE A SUGGESTION</a></li>
                                    <li><a class="ays-btn" href="https://ays-pro.com/wordpress-chatgpt-assistant-user-manual" target="_blank">DOCUMENTATION</a></li>
                                    <li><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/" target="_blank">SUPPORT FORUM</a></li>
                                    <!-- <li class="take_survay"><a class="ays-btn" href="https://ays-demo.com/chatgpt-assistant-plugin-suggestion-box/" target="_blank">MAKE A SUGGESTION</a></li> -->
                                    <li><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/" target="_blank">CONTACT US</a></li>
                                </ul>
                            </li>
                            <li class="modile-ddmenu-sm">
                                <a class="toggle_ddmenu" href="javascript:void(0);"><img src="<?php echo esc_url(CHATGPT_ASSISTANT_ADMIN_URL) . '/images/icons/ellipsis.svg'; ?>"/></a>
                                <ul class="ddmenu" data-expanded="false" style="align-items: flex-start;">
                                    <!-- <li><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/reviews/?rate=5#new-post" target="_blank">RATE US</a></li> -->
                                    <li><a class="ays-btn" href="https://ays-demo.com/chatgpt-assistant-demo/" target="_blank">DEMO</a></li>
                                    <li><a class="ays-btn take_survay" href="https://ays-demo.com/chatgpt-ai-assistant-plugin-feedback-survey/" target="_blank">MAKE A SUGGESTION</a></li>
                                    <li><a class="ays-btn" href="https://ays-pro.com/wordpress-chatgpt-assistant-user-manual" target="_blank">DOCUMENTATION</a></li>
                                    <li><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/" target="_blank">SUPPORT FORUM</a></li>
                                    <!-- <li class="take_survay"><a class="ays-btn" href="https://ays-demo.com/chatgpt-assistant-plugin-suggestion-box/" target="_blank">MAKE A SUGGESTION</a></li> -->
                                    <li><a class="ays-btn" href="https://wordpress.org/support/plugin/ays-chatgpt-assistant/" target="_blank">CONTACT US</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
             </div>
         <?php
        }
    }
}

run_chatgpt_assistant();
