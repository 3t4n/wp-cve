<?php
namespace DarklupLite;

/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.0.0
 * @author
 * @Websites:
 *
 */

if (!defined('ABSPATH')) {
    die(DARKLUPLITE_ALERT_MSG);
}

/**
 * DarklupLite_Settings_Page class
 */
class DarklupLite_Settings_Page
{

    /**
     * DarklupLite_Settings_Page constructor
     *
     * @since  1.0.0
     * @return void
     */
    public $offer_setting_key = 'darkluplite_offer_notice_dismissed';
    public $offer_dismissed = 'offer_dismissed';
    public $admin_slug = 'darkluplite-setting-admin';
    
    public function __construct()
    {

        $darkluplite_options = get_option('darkluplite_options');

        add_action('admin_menu', array($this, 'addPluginPage'));
        add_action('admin_init', array($this, 'pageInit'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('plugin_action_links_' . DARKLUPLITE_BASE_PATH, array($this, 'darkluplite_action_links'));
        //dashboard widget
        add_action('wp_dashboard_setup', [$this, 'darkluplite_dashboard_widgets'], 10);
        
        // Ajax save actions
        add_action('wp_ajax_nopriv_darkluplite_save_admin_settings', [$this, 'saveAdminSettings']);
        add_action('wp_ajax_darkluplite_save_admin_settings', [$this, 'saveAdminSettings']);
        
        // add_action( 'admin_notices', [$this, 'darklup_admin_darkluplite_offer_notice'] );
        
        // add_action('wp_ajax_dismiss_darkluplite_offer_notice', [$this,'dismiss_darkluplite_offer_notice']);
        // add_action('wp_ajax_nopriv_dismiss_darkluplite_offer_notice', [$this,'dismiss_darkluplite_offer_notice']);
        
        // Remove transient 
        // $this->delete_darkluplite_offer_notice_transient();
        
    }
    

    public function darklup_admin_darkluplite_offer_notice()
    {
        
        $dismissed = get_option( $this->offer_setting_key );
        if ($dismissed == $this->offer_dismissed )  return;
        
        
        $class = 'notice notice-info is-dismissible darkluplite-offer--notice';
        if(wp_is_mobile()){
            $img_link = DARKLUPLITE_DIR_URL."assets/img/halloween-darklup-mobile.jpg";
        }else{
            $img_link = DARKLUPLITE_DIR_URL."assets/img/halloween-darklup.jpg";
        }
        $notice_url = 'https://darklup.com/pricing/';
        $admin_url = admin_url('admin-ajax.php');
        
        printf( '<div class="%1$s"><div class="darkluplite-offer-notice--inner"><a href="%2$s" target="_blank"><img src="%3$s" alt="Buy Now"></a></div></div>', esc_attr( $class ), esc_url( $notice_url ),esc_url( $img_link ) );
        ?>
        <script>
            jQuery(document).on('click', '.darkluplite-offer--notice button.notice-dismiss', function () {
                let  daeklupAdminAjaxUrl = '<?php echo $admin_url; ?>';
                jQuery.ajax({
                    type: 'POST',
                    data: {
                        action: 'dismiss_darkluplite_offer_notice',
                    },
                    url: daeklupAdminAjaxUrl
                });
            });
        </script>
        <?php

    }
    function dismiss_darkluplite_offer_notice() {
        
        if ( get_option( $this->offer_setting_key ) !== false ) {
            // The option already exists, so update it.
            update_option( $this->offer_setting_key, $this->offer_dismissed );
        } else {
            // The option hasn't been created yet, so add it with $autoload set to 'no'.
            $deprecated = null;
            $autoload = 'no';
            add_option( $this->offer_setting_key, $this->offer_dismissed, $deprecated, $autoload );
        }
        
    }
    function delete_darkluplite_offer_notice_transient() {
        update_option( $this->offer_setting_key, '' );
    }
    
    /* action links on plugin page */
    public function darkluplite_action_links($links)
    {
        $settings_url = add_query_arg('page', $this->admin_slug, get_admin_url() . 'admin.php');
        $pro_url = 'https://darklup.com';

        $setting_arr = array('<a href="' . esc_url($settings_url) . '">' . __('Settings', 'darklup-lite') . '</a>');
        $pro_arr = array('<a class="darkluplite-get-pro" target="_blank" href="' . esc_url($pro_url) . '">' . __('Get Pro', 'darklup-lite') . '</a>');

        $links = array_merge($setting_arr, $links,$pro_arr);

        return $links;
    }

    /**
     * Admin menu page
     *
     * @since  1.0.0
     * @return void
     */
    public function addPluginPage()
    {
        add_menu_page(
            esc_html__('Darklup', 'darklup-lite'),
            esc_html__('Darklup', 'darklup-lite'),
            'manage_options',
            $this->admin_slug,
            array($this, 'adminPage'),
            esc_url(DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/darkluplite-icon.svg'),
            6
        );

        add_submenu_page($this->admin_slug,
            esc_html__('Darklup', 'darklup-lite'),
            esc_html__('Settings', 'darklup-lite'),
            'manage_options',
            $this->admin_slug,
            array($this, 'adminPage')
        );

        add_submenu_page($this->admin_slug,
            esc_html__('Get Pro', 'darklup-lite'),
            esc_html__('Get Pro', 'darklup-lite'),
            'manage_options',
            'darkluplite-get-pro',
            array($this, 'darkluplite_get_pro')
        );
        add_submenu_page(
            $this->admin_slug,
            __( 'Useful Plugins', 'darklup-lite' ),
            __( 'Useful Plugins', 'darklup-lite' ),
            'manage_options',
            'darklup-lite-useful-plugins',
            [ $this, 'useful_plugins_page' ],
            50
        );
    }
    public function useful_plugins_page()
    {
        // include recomendedplugin page
        require_once DARKLUPLITE_DIR_ADMIN . 'useful-plugins.php';
    }
    public function prepareSettingsForDb($darkluplite_settings = [])
    {
        $sanitized_settings = [];
        if(is_array($darkluplite_settings)){
            foreach ($darkluplite_settings as $darkluplite_setting) {
                if(isset($darkluplite_setting['name']) && isset($darkluplite_setting['value'])){
                    $name = $darkluplite_setting['name'];
                    $value = $darkluplite_setting['value'];
                    
                    $start_str = "darkluplite_settings[";
                    $end_str = "]";
                    $array_end = "][]";
                    
                    if (strpos($name, $start_str) === 0) {
                        $name = substr($name, strlen($start_str));
                        
                        if (substr($name, -strlen($array_end)) === $array_end) {
                            $name = substr($name, 0, -strlen($array_end));
                            $sanitized_settings[sanitize_textarea_field($name)][] = sanitize_textarea_field($value);
                        }else{
                            if (substr($name, -strlen($end_str)) === $end_str) {
                                $name = substr($name, 0, -strlen($end_str));
                                $sanitized_settings[sanitize_textarea_field($name)] = sanitize_textarea_field($value);
                            } 
                        }
                    }else{
                        $sanitized_settings[sanitize_textarea_field($name)] = sanitize_textarea_field($value);
                    }
                    
                }
            }
        }
        return $sanitized_settings;
    }
    public function saveAdminSettings()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            die( __( 'You do not have access to this settings.', 'darluplite' ) ); 
        }
        
        $darkluplite_settings		= $_POST['data'];
        $sanitized_settings = $this->prepareSettingsForDb($darkluplite_settings);
        
        if(!is_array($sanitized_settings)){
            die( __( 'Invalid settings value, try refrashing the page and clear the cache.', 'darluplite' ) ); 
        }    
        
        $nonce = $sanitized_settings['darluplitenonce'];
        if ( ! wp_verify_nonce( $nonce, 'darluplitenonce' ) ) {
            die( __( 'Invalid nonce, try refrashing the page and clear the cache.', 'darluplite' ) ); 
        }
        
        $updated = update_option('darkluplite_settings', $sanitized_settings);
        if($updated){
            echo 'update_success';
        }else{
            $pre_options = get_option('darkluplite_settings');
            if($sanitized_settings == $pre_options) {
                echo 'same';
                exit();
            }
            
            echo 'fail';
        }
        
        exit();
    }
    
    /**
     * register setting
     *
     * @since  1.0.0
     * @return void
     */
    public function pageInit()
    {
        //register our settings
        register_setting('darkluplite-settings-group', 'darkluplite_settings');
    }

    /**
     * DarklupLite settings page
     *
     * @since  1.0.0
     * @return void
     */
    public function adminPage()
    {

        // check if the user have submitted the settings
        if (isset($_GET['settings-updated'])) {
            // add settings saved message with the class of "updated"
            add_settings_error('darkluplite_messages', 'darkluplite_message', esc_html__('Settings Saved', 'darklup-lite'), 'updated');
        }
        // show error/update messages
        settings_errors('darkluplite_messages');

        // Admin page form
        Admin_Page_Components::formArea();

    }

    public function darkluplite_get_pro()
    {?>
<script>
window.open("https://darklup.com", "_blank");
</script>
<?php $this->adminPage();
    }

    /**
     * Admin enqueue scripts
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueueScripts()
    {
        $js_in_footer = false;
        $js_in_footer = true;
        wp_enqueue_style('wp-color-picker');


        wp_enqueue_style('darkluplite-grid', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/darkluplite-grid.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('magnific', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/magnific.min.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('nice-select', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/nice-select.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('select2', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/select2.min.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('darkluplite-style', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/style.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('darkluplite-new-style', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/new-style.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('darkluplite-switch', DARKLUPLITE_DIR_URL . 'assets/css/darkluplite-switch.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('darkluplite-responsive', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/responsive.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('darkluplite-dashboard-widget', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/dashboard-widget.css', array(), DARKLUPLITE_VERSION, false);
        wp_enqueue_style('toastr-widget', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/toastr.min.css', array(), DARKLUPLITE_VERSION, false);

        wp_enqueue_script('ace-editor', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'js//ace/ace.js', array('jquery'), '1.0', true);
        wp_enqueue_script('magnific', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'js/magnific.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('select', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'js/select.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('select2', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'js/select2.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('darkluplite-chart-js', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'js/darkluplite-chart.js', array('jquery'), '1.0');
        wp_enqueue_script('toastr-js', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'js/toastr.min.js', array('jquery'), '1.0');

        DarklupLite_Enqueue::addDarklupJSWithDynamicVersion('darkluplite-main', 'admin/assets/js/main.js', array('jquery', 'wp-color-picker'), true);
        
        $darklup_js = [
            1 => Color_Preset::getColorPreset(1),
            2 => Color_Preset::getColorPreset(2),
            3 => Color_Preset::getColorPreset(3),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ];

        wp_localize_script('darkluplite-main', 'darklupPresets', $darklup_js);


        $dashboardDarkMode = false;
        $getDashboardDarkMOde = Helper::getOptionData('backend_darkmode');
        if($getDashboardDarkMOde == 'yes') $dashboardDarkMode = true;
        if(!$dashboardDarkMode) return;
        
        $colorMode = 'darklup_dynamic';
        $getMode = Helper::getOptionData('color_modes');
        
        if($getMode !== 'darklup_dynamic'){
            $colorMode = 'darklup_presets';
            wp_enqueue_style('darkluplite-admin-variables', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/admin-variable.css', array(), DARKLUPLITE_VERSION, false);
            DarklupLite_Enqueue::addDarklupJSWithDynamicVersion('darklup_presets', $src = 'assets/es-js/presets.js', $dep = NULL, $js_footer = false);
        }else{
            wp_enqueue_style('darklup-dynamic-new', DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'css/new-dynamic-style.css', array(), DARKLUPLITE_VERSION, false);
            DarklupLite_Enqueue::addDarklupJSWithDynamicVersion();
        }
        
        // Localize Variables
        // $DarklupJs = $this->getDarklupJs();
        $DarklupJs = Helper::getDarklupJs('admin_');
        wp_localize_script($colorMode, 'DarklupJs', $DarklupJs);
        $frontObj = Helper::getFrontendObject();
        wp_localize_script( $colorMode, 'frontendObject', $frontObj);
        
        
        
        
    }
    public function getDarklupJs()
    {
        $colorPreset = Helper::getOptionData('admin_color_preset');
        $presetColor = Color_Preset::getColorPreset($colorPreset);

        $customBg = Helper::getOptionData('admin_custom_bg_color');
        $customBg = Helper::is_real_color($customBg);

        // Custom colors
        $customSecondaryBg = Helper::getOptionData('admin_custom_secondary_bg_color');
        $customSecondaryBg = Helper::is_real_color($customSecondaryBg);

        $customTertiaryBg = Helper::getOptionData('admin_custom_tertiary_bg_color');
        $customTertiaryBg = Helper::is_real_color($customTertiaryBg);


        $bgColor = esc_html($presetColor['background-color']);
        if($customBg) $bgColor = $customBg;
        $bgColor = \DarklupLite\Helper::hex_to_color($bgColor);

        $bgSecondaryColor = esc_html($presetColor['secondary_bg']);
        if($customSecondaryBg) $bgSecondaryColor = $customSecondaryBg;
        $bgSecondaryColor = \DarklupLite\Helper::hex_to_color($bgSecondaryColor);

        $bgTertiary = esc_html($presetColor['tertiary_bg']);
        if($customTertiaryBg) $bgTertiary = $customTertiaryBg;
        $bgTertiary = \DarklupLite\Helper::hex_to_color($bgTertiary);

        $darklup_js = [
            'primary_bg' => $bgColor,
            'secondary_bg' => $bgSecondaryColor,
            'tertiary_bg' => $bgTertiary,
            'bg_image_dark_opacity' => '0.5',
            'exclude_element' => '',
            'exclude_bg_overlay' => '',
        ];
        return $darklup_js;
    }
    /**
     * DarklupLite  Analytics
     *
     * @since  1.1.3
     * @return void
     */
    public function darkluplite_dashboard_widgets()
    {

        wp_add_dashboard_widget('darkluplite_dark_mode', esc_html__('Darklup Dark Mode Usage', 'darklup-lite'), [
            $this,
            'darkluplite_analytics_dashboard_widget',
        ]);

        // Globalize the metaboxes array, this holds all the widgets for wp-admin.
        global $wp_meta_boxes;

        // Get the regular dashboard widgets array
        // (which already has our new widget but appended at the end).
        $default_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

        // Backup and delete our new dashboard widget from the end of the array.
        $darkluplite_widget_backup = array('darkluplite_dark_mode' => $default_dashboard['darkluplite_dark_mode']);
        unset($default_dashboard['darkluplite_dark_mode']);

        // Merge the two arrays together so our widget is at the beginning.
        $sorted_dashboard = array_merge($darkluplite_widget_backup, $default_dashboard);

        // Save the sorted array back into the original metaboxes.
        $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
    }

    /**
     * DarklupLite  Analytics  Dashboard Widget
     *
     * @since  1.1.3
     * @return void
     */
    public function darkluplite_analytics_dashboard_widget()
    {

        $label_data = [
            '20 Dec',
            '21 Dec',
            '22 Dec',
            '24 Dec',
            '25 Dec',
            '27 Dec',
            '29 Dec',
        ];

        $values = ['5', '25', '20', '15', '12', '10', '3'];
        ?>

        <div class="darklup-chart-wrapper">
            <div class="darklup-chart-header">
                <span><?php esc_html_e("How much percentage of users use dark mode in last 7 days.", 'darklup-lite');?></span>
            </div>

            <div class="darklup-chart">
                <canvas id="darklup_analytics_Chart" style="width: 394px;height: 300px;"
                    data-labels='<?php echo json_encode($label_data); ?>'
                    data-values='<?php echo json_encode($values); ?>'></canvas>
            </div>
            <div class="darklup-chart-modal-wrapper">
                <div class="darklup-chart-modal">
                    <h1>Go Premium</h1>
                    <p>Purchase our premium version to unlock these features</p>
                    <a target="_blank" href="https://darklup.com/pricing/">Get Pro</a>
                </div>
            </div>
        </div>

    <?php
}

}

if (is_admin()) {
    $DarklupLite_Settings_Page = new DarklupLite_Settings_Page();
}