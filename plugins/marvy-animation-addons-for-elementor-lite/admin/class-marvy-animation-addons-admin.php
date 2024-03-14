<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       example.com
 * @since      1.7.2
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/admin
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/admin
 * @author     Iqonic Design <hello@iqonic.design>
 */
class Marvy_Animation_Addons_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.7.2
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.7.2
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.7.2
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.7.2
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Boilerplate_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Boilerplate_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('sweetalert2', plugin_dir_url(__FILE__) . 'assets/css/sweetalert2.min.css', array(), $this->version, 'all');
        wp_enqueue_style('marvy-custom-admin', plugin_dir_url(__FILE__) . 'assets/css/marvy-custom-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.7.2
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Boilerplate_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Boilerplate_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script('sweetalert2', plugin_dir_url(__FILE__) . 'assets/js/sweetalert2.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('marvy-admin-custom-js', plugin_dir_url(__FILE__) . 'assets/js/marvy-custom-admin.js', array('jquery'), $this->version, true);
        wp_localize_script('marvy-admin-custom-js', 'localize', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('save_marvy_settings')
        ));
    }

    public function save_settings()
    {
        $updated = false;
        if (
            isset($_POST['action'])
            && isset($_POST['_ajax_nonce'])
            && 'save_marvy_settings' === $_POST['action']
            && wp_verify_nonce($_POST['_ajax_nonce'], 'save_marvy_settings') && current_user_can('manage_options')
        ) {

            $fields = sanitize_text_field($_POST['fields']);

            if (!isset($fields)) {
                wp_send_json($updated);
            }
            $config = (function_exists('marvy_get_config')) ? marvy_get_config() : '';
            $config_pro = (function_exists('marvy_pro_get_config')) ? marvy_pro_get_config() : [];
            if ($config !== '') {
                parse_str($fields, $settings);
                $defaults = array_fill_keys(array_keys($config), false);
                $defaults_pro = array_fill_keys(array_keys($config_pro), false);
                $defaults_pro = $defaults_pro === null ? [] : $defaults_pro;
                $main_defaults = array_merge($defaults, $defaults_pro);

                $elements = array_merge($main_defaults, array_fill_keys(array_keys(array_intersect_key($settings, $main_defaults)), true));
                $old_options = get_option('marvy_option_settings');
                $old_options = !empty($old_options) ? $old_options : [];
                if ($elements !== $old_options) {
                    // update new settings
                    $updated = update_option('marvy_option_settings', $elements);
                } else if ($elements === $old_options) {
                    // for same data
                    $updated = true;
                }
            }
        }
        wp_send_json($updated);
    }

    public function admin_menu()
    {
        if (current_user_can('manage_options')) {
            add_menu_page(
                __('Marvy Animation', 'marvy-lang'),
                __('Marvy Animation', 'marvy-lang'),
                "manage_options",
                "marvy-animation",
                [$this, 'options_page'],
                plugin_dir_url(__DIR__) . 'admin/assets/images/logo.svg',
                59
            );
        }
    }

    public function options_page()
    {
        if (current_user_can('manage_options')) { ?>
            <form method="POST" id="marvy-settings" name="marvy-settings">
                <div class="marvy-main">
                    <div class="marvy-header">
                        <div class="marvy-header-left">
                            <div class="marvy-header-logo">
                                <img src="<?php echo plugin_dir_url(__DIR__) ?>admin/assets/images/logo.png" alt="" width="45" height="35">
                            </div>
                            <h3 class="header-title"><?php esc_html_e("Marvy Animation Addons", "marvy-lang") ?></h3>
                        </div>
                        <?php if (!isMarvyProInstall()) { ?>
                            <div class="marvy-header-right">
                                <a class="get-pro-btn float-right" href="https://codecanyon.net/item/marvy-background-animations-for-elementor/28285063" target="_blank">Get Pro</a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="marvy-tabs">
                        <ul>
                            <li class="tab-active"><a class="tab" href="#general"><?php esc_html_e("General", "marvy-lang") ?></a></li>
                            <li><a class="tab" href="#animation"><?php esc_html_e("Animation", "marvy-lang") ?></a></li>
                        </ul>
                        <div class="marvy-tab">
                            <div id="general" class="marvy-tab-detail">
                                <?php require_once(MARVY_ANIMATION_ADDONS_PLUGIN_PATH . 'admin/view/general.php'); ?>
                            </div>
                            <div id="animation" class="marvy-tab-detail">
                                <?php require_once(MARVY_ANIMATION_ADDONS_PLUGIN_PATH . 'admin/view/elements.php'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php }
    }

    public function iqonic_sale_banner_notice()
    {
        $type="plugins" ;
        $product="common"; 
        $get_sale_detail= get_transient('iq-notice');
        if(is_null($get_sale_detail) || $get_sale_detail===false ){
            $get_sale_detail =wp_remote_get("https://assets.iqonic.design/wp-product-notices/notices.json?ver=" . wp_rand()) ;
            set_transient('iq-notice',$get_sale_detail ,3600)  ;
        }

        if (!is_wp_error($get_sale_detail) && $content = json_decode(wp_remote_retrieve_body($get_sale_detail), true)) {
            if(get_user_meta(get_current_user_id(),$content['data']['notice-id'],true)) return;
            
            $currentTime =  current_datetime();
            if (($content['data']['start-sale-timestamp']  < $currentTime->getTimestamp() && $currentTime->getTimestamp() < $content['data']['end-sale-timestamp'] )&& isset($content[$type][$product])){

            ?>
            <div class="iq-notice notice notice-success is-dismissible" style="padding: 0;">
                <a target="_blank" href="<?php echo esc_url($content[$type][$product]['sale-ink']??"#")  ?>">
                    <img src="<?php echo esc_url($content[$type][$product]['banner-img'] ??"#" )  ?>" style="object-fit: contain;padding: 0;margin: 0;display: block;" width="100%" alt="">
                </a>
                <input type="hidden" id="iq-notice-id" value="<?php echo esc_html($content['data']['notice-id']) ?>">
                <input type="hidden" id="iq-notice-nounce" value="<?php echo wp_create_nonce('iq-dismiss-notice') ?>">
            </div>
            <?php
                wp_enqueue_script('iq-admin-notice',MARVY_ANIMATION_ADDONS_PLUGIN_URL."admin/assets/js/marvy-admin-notice.js",['jquery'],false,true);
            }
        }
    }
    public function iq_dismiss_notice() {
        if(wp_verify_nonce($_GET['nounce'],'iq-dismiss-notice')){
            update_user_meta(get_current_user_id(),$_GET['key'],1);
        }
    }
}
