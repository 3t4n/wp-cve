<?php

/**
 * Plugin Name:       Darklup
 * Plugin URI:        https://darklup.com/
 * Description:       All in one WordPress plugin to create a stunning dark version for your WordPress website and dashboard.
 * Version:           3.2.4
 * Author:            Darklup
 * Author URI:        https://darklup.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       darklup-lite
 * Domain Path:       /languages
 */

// Block Direct access
if (!defined('ABSPATH')) {
    die(__('You should not access this file directly!.', 'darklup-lite'));
}

/**
 * Define all constant
 *
 */

// Define Constants for direct access alert message.
if (!defined('DARKLUPLITE_ALERT_MSG')) {
    define('DARKLUPLITE_ALERT_MSG', esc_html__('You should not access this file directly.!', 'darklup-lite'));
}

// Version constant
if (!defined('DARKLUPLITE_VERSION')) {
    define('DARKLUPLITE_VERSION', '3.2.4');
}

// Plugin dir path constant
if (!defined('DARKLUPLITE_DIR_PATH')) {
    define('DARKLUPLITE_DIR_PATH', trailingslashit(plugin_dir_path(__FILE__)));
}
// Plugin dir url constant
if (!defined('DARKLUPLITE_DIR_URL')) {
    define('DARKLUPLITE_DIR_URL', trailingslashit(plugin_dir_url(__FILE__)));
}
// Plugin dir admin assets url constant
if (!defined('DARKLUPLITE_DIR_ADMIN_ASSETS_URL')) {
    define('DARKLUPLITE_DIR_ADMIN_ASSETS_URL', trailingslashit(DARKLUPLITE_DIR_URL . 'admin/assets'));
}
// Admin dir path constant
if (!defined('DARKLUPLITE_DIR_ADMIN')) {
    define('DARKLUPLITE_DIR_ADMIN', trailingslashit(DARKLUPLITE_DIR_PATH . 'admin'));
}
// Inc dir path constant
if (!defined('DARKLUPLITE_DIR_INC')) {
    define('DARKLUPLITE_DIR_INC', trailingslashit(DARKLUPLITE_DIR_PATH . 'inc'));
}
// Page builder dir path constant
if (!defined('DARKLUPLITE_DIR_PAGE_BUILDER')) {
    define('DARKLUPLITE_DIR_PAGE_BUILDER', trailingslashit(DARKLUPLITE_DIR_PATH . 'page-builder'));
}
// Plugin Base Path
if (!defined('DARKLUPLITE_BASE_PATH')) {
    define('DARKLUPLITE_BASE_PATH', plugin_basename(__FILE__));
}

// Plugin uninstall hook
register_uninstall_hook(__FILE__, 'pluginDarklupLiteDeleted');
function pluginDarklupLiteDeleted()
{
    delete_option('darkluplite_settings');
}

/**
 * Darklup final class
 */

final class DarklupLite
{

    /**
     * Get the plugin object
     *
     * @since  1.0.0
     * @var null
     */
    private static $instance = null;

    /**
     * DarklupLite constructor
     *
     * @since  1.0.0
     * @return void
     */

    public function __construct()
    {

        $this->includeFiels();
        
        
        // $this->pluginActivate();
        // Register Elementor New Widgets
        add_action('elementor/widgets/widgets_registered', [$this, 'elementorOnWidgetsRegistered']);
        // Plugin activation hook
        register_activation_hook(__FILE__, [$this, 'pluginActivate']);
        
        // Update recent settings
        $this->updateRecentSettings();
        $this->appsero_init_tracker_darklup_lite_wp_dark_mode();

        if (time() < 1638705540) {
            add_action('wp_dashboard_setup', [$this, 'darkluplite_dashboard_widgets']);
        }
    }

    /**
     *
     * @since 1.0.0
     * @return object
     */

    public static function getInstance()
    {

        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * File include
     *
     * @since 1.0.0
     * @return viod
     */

    public function includeFiels()
    {

        require_once DARKLUPLITE_DIR_INC . 'class-helper.php';
        require_once DARKLUPLITE_DIR_INC . 'class-enqueue.php';
        require_once DARKLUPLITE_DIR_INC . 'class-hooks.php';
        require_once DARKLUPLITE_DIR_INC . 'class-color-preset.php';
        require_once DARKLUPLITE_DIR_INC . 'class-switcher-style.php';
        require_once DARKLUPLITE_DIR_INC . 'class-dark-inline-css.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/class-settings-fields.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'admin.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'inc/class-admin-page.php';
        require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'shortcode/class-switch-shortcode.php';
        require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'wpbakery/darkluplite-vc-init.php';

        global $pagenow;
        if ($pagenow != 'widgets.php') {
            require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'gutenberg-block/darkluplite-switch-block/src/init.php';
        }
        require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'wp-widget/widget-darkmode-switch.php';
        // Elemenor custom control
        require_once DARKLUPLITE_DIR_INC . 'custom-controls/elemenor-control/custom-control.php';
    }

    /**
     * Plugin activation default settings
     *
     * @since 1.0.0
     * @return void
     */
    public function pluginActivate()
    {

        // Default options set
        $defaultOption = array(

            "frontend_darkmode" => 'yes',
            "apply_bg_overlay" => 'yes',
            "switch_in_desktop" => 'yes',
            "switch_in_mobile" => 'yes',
            "color_preset_enabled" => 'yes',
            "color_mode" => 'presets',
            "custom_color_enabled" => 'no',
            "admin_color_preset_enabled" => 'yes',
            "admin_custom_color_enabled" => 'no',
            "switch_style" => '1',
            "switch_style_mobile" => '1',
            "switch_style_menu" => '1',
            "color_preset" => '1',
            "admin_color_preset" => '1',
            "color_admin_preset" => '1',
            "switch_position" => 'bottom_right',
            "desktop_switch_position" => 'bottom_right',
            "color_modes" => 'darklup_dynamic',
            "full_color_settings" => 'front_end_colors',
            "switch_cases" => 'desktop_switch',
            "darkluplite_image_effects" => 'yes',
        );

        // if (!get_option("darkluplite_settings")) {
        //     update_option('darkluplite_settings', $defaultOption);
        // }
        
        $darkluplite_options = get_option( 'darkluplite_settings' );
        
        if (!$darkluplite_options) {
            update_option('darkluplite_settings', $defaultOption);
        }else{
            $getMode = '';
            if( !empty( $darkluplite_options['color_modes'] ) ) {
                $getMode = $darkluplite_options['color_modes'];
            }
            if($getMode == ''){
                $getPrevMode = '';
                if( !empty( $darkluplite_options['full_color_settings'] ) ) {
                    $getPrevMode = $darkluplite_options['full_color_settings'];
                }
                if($getPrevMode == 'darklup_dynamic'){
                    $darkluplite_options['color_modes'] = 'darklup_dynamic';
                    update_option('darkluplite_settings', $darkluplite_options);
                }else{
                    $getMode = 'darklup_presets';
                    $darkluplite_options['color_modes'] = 'darklup_presets';
                    update_option('darkluplite_settings', $darkluplite_options);
                }
            }
        }
        
    }

    /**
     * Add default values for recent settings
     *
     * @return void
     */
    public function updateRecentSettings(){
        $darkluplite_options = get_option( 'darkluplite_settings' );
        
        if ($darkluplite_options) {
            $getMode = '';
            if( !empty( $darkluplite_options['color_modes'] ) ) {
                $getMode = $darkluplite_options['color_modes'];
            }
            
            if($getMode == ''){
                $getPrevMode = '';
                if( !empty( $darkluplite_options['full_color_settings'] ) ) {
                    $getPrevMode = $darkluplite_options['full_color_settings'];
                }
                if($getPrevMode == 'darklup_dynamic'){
                    $darkluplite_options['color_modes'] = 'darklup_dynamic';
                    update_option('darkluplite_settings', $darkluplite_options);
                }else{
                    // $getMode = 'darklup_presets';
                    $darkluplite_options['color_modes'] = 'darklup_presets';
                    update_option('darkluplite_settings', $darkluplite_options);
                }
            }
        }
    }
    
    /**
     * Initialize the plugin tracker
     *
     * @return void
     */
    public function appsero_init_tracker_darklup_lite_wp_dark_mode()
    {

        if (!class_exists('Appsero\Client')) {
            require_once __DIR__ . '/appsero/src/Client.php';
        }

        $client = new Appsero\Client('b190c422-bdd4-48ca-be75-7ae3fc5d6d07', 'Darklup Lite - WP Dark Mode', __FILE__);

        // Active insights
        $client->insights()->init();
    }

    /* ====== Display Dashboard Widget ======= */
    public function darkluplite_dashboard_widgets()
    {
        global $wp_meta_boxes;
        wp_add_dashboard_widget('darkluplite_news_widget', 'Darklup - News & Updates', [$this, 'darkluplite_dashboard_news_updates']);
    }

    public function darkluplite_dashboard_news_updates()
    {
        echo '

        <div class="darkluplite-news-banner">
            <a href="https://darklup.com/pricing/" target="_blank">
                <img src="https://darklup.com/plugin-dashboard-news-contents/black_friday_cover.jpg" alt="Darklup Black Friday Banner">
            </a>
        </div>

        <div class="darkluplite-news-description">
            <p>Darklup Black Friday sale is here. Enjoy up to <strong>70% Off</strong> on Darklup premium plans. <a href="https://darklup.com/pricing/" target="_blank"><strong>BUY NOW</strong></a>.
            <br>
            <a href="https://darklup.com/pricing/" target="_blank">👉 https://darklup.com/pricing/</a></p>
        </div>

        <div class="line-divider"></div>


        <div class="darkluplite-news-blog-posts">
            <p><a href="https://wpcommerz.com/black-friday-and-cyber-monday-deals/" target="_blank">Best WordPress Black Friday And Cyber Monday Deals 2021</a></p>
            <p><a href="https://darklup.com/googles-new-amazing-dark-mode-comforts-your-sore-eyes/" target="_blank">Google’s New Amazing Dark Mode Comforts Your Sore Eyes</a></p>
            <p><a href="https://darklup.com/windows-11-default-dark-mode/" target="_blank">Microsoft Windows 11 Brings Default Dark Mode & We Empower WordPress With Default Dark Mode</a></p>
            <p><a href="https://darklup.com/how-to-turn-on-dark-mode-on-smart-devices/" target="_blank">How To Turn On Dark Mode On Smart Devices In 2021 | Best Ways</a></p>
        </div>

        <div class="line-divider"></div>

        <div class="darkluplite-news-footer">
            <ul>
                <li><a href="https://darklup.com/blog/" target="_blank">Blog <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <div class="li-divider"></div>
                <li><a href="https://wpcommerz.com/contact/" target="_blank">Help <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <div class="li-divider"></div>
                <li><a href="https://darklup.com/pricing/" target="_blank">Go Pro <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <div class="li-divider"></div>
                <li><a href="https://wpcommerz.com/black-friday/" target="_blank">Black Friday <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
                <div class="li-divider"></div>
                <li><a href="https://www.facebook.com/groups/816424282580036" target="_blank">Community <span aria-hidden="true" class="dashicons dashicons-external"></span></a></li>
            </ul>
        </div>

        ';
    }
    /* ====== Display Dashboard Widget ======= */

    /**
     * Delete settings options when plugin deactivate
     *
     * @since 1.0.0
     * @return void
     */
    public function pluginDeactivate()
    {
        //delete_option('darkluplite_settings');
    }
    /**
     * Elementor widgets registered
     * @since 1.0.0
     * @return void
     */
    public function elementorOnWidgetsRegistered()
    {

        require_once DARKLUPLITE_DIR_PAGE_BUILDER . 'elementor-widget/elementor-darkmode-switch.php';

        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \DarklupLite\Widgets\DarklupLite_Darkmode_Switch());
    }
}

/**
 * DarklupLite Initialization
 */
function darkluplite_add_crossorigin_to_css($html, $handle) {
    $site_url = get_site_url();
    if (strpos($html, '.css') !== false && strpos($html, $site_url) === false && strpos($html, 'crossorigin="anonymous"') === false) {
        $html = str_replace('<link', '<link crossorigin="anonymous"', $html);
    }
    return $html;
}
add_filter('style_loader_tag', 'darkluplite_add_crossorigin_to_css', 10, 2);

function darkluplite_check_premium_activation()
{
    if (!in_array('darklup/darklup.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        DarklupLite::getInstance();
    }
}
add_action('darkluplite_init', 'darkluplite_check_premium_activation', 10, 2);
do_action('darkluplite_init');