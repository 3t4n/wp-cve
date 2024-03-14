<?php

use DominoKitApp\Backend\Controller\DominoKitAjax;
use DominoKitApp\Frontend\Controller\DominoKitFilter;
use DominoKitApp\Backend\Package\DominoKitPersianDate;
use DominoKitApp\Backend\Package\DominokitWebZhaket;

defined('ABSPATH') || exit;

class DominoKitController
{
    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @var
     * plugins activation
     */
    private static $plugins;


    /**
     * @var array
     */
    public $dominokit_options = array();

    const SCREENS = [
        'product' => 'product',
        'page' => 'page',
        'post' => 'post',
        'shop_order' => 'shop_order',
        'shop_coupon' => 'shop_coupon',
        'ووکامرس_page_wc-reports' => 'report',
    ];

    /**
     * DominoKitController constructor.
     */
    public function __construct()
    {
        DominoKitAjax::instance();
        DominoKitFilter::instance();

        if (!class_exists('TGM_Plugin_Activation')) {
            require_once DOMKIT_INCLUDE . '/tgmpa/class-tgm-plugin-activation.php';
        }

        if (!class_exists('DominoKitPersianDate')) {
            require_once DOMKIT_APP . '/Backend/Package/DominoKitPersianDate.php';
        }

        $upd_toggleWooShamsi = !empty(get_option('dominokit_option_wooShamsi')) ? get_option('dominokit_option_wooShamsi') === "true" : false;
        if ($upd_toggleWooShamsi !== false) {
            \DominoKitPersianDate::instance();
        }

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_script_callback'));

        add_action('admin_menu', array($this, 'admin_menu_callback'));

        add_action('tgmpa_register', array($this, 'dominokit_register_required_plugins'));

        add_action('init', array($this, 'dominokit_options_callback'));

        self::$plugins = get_option('active_plugins');
        $GLOBALS['plugin_active'] = in_array('dominokit-pro/dominokit-pro.php', self::$plugins);
    }

    public function dominokit_options_callback()
    {
        $this->dominokit_options[0]['title'] = __('Appearance', 'dominokit');
        $this->dominokit_options[0]['description'] = __('Features that affect the appearance of the website', 'dominokit');
        $this->dominokit_options[0]['tab1'] = true;
        $this->dominokit_options[0]['tab2'] = false;
        $this->dominokit_options[0]['tab3'] = false;
        $this->dominokit_options[0]['errorTxt'] = __('The address is not correct', 'dominokit');

        $this->dominokit_options[1]['title'] = __('solarization', 'dominokit');
        $this->dominokit_options[1]['description'] = __('Keep in mind that if you enable these options, the Shamsi Saaz plugin will not be active', 'dominokit');
        $this->dominokit_options[1]['tab1'] = false;
        $this->dominokit_options[1]['tab2'] = true;
        $this->dominokit_options[1]['tab3'] = false;

        $this->dominokit_options[2]['title'] = __('other products', 'dominokit');
        $this->dominokit_options[2]['description'] = __('See other DominoDev products', 'dominokit');
        $this->dominokit_options[2]['tab1'] = false;
        $this->dominokit_options[2]['tab2'] = false;
        $this->dominokit_options[2]['tab3'] = true;

        if (!$GLOBALS['plugin_active']) {
            $this->dominokit_options[3]['title'] = __('Upgrade to pro', 'dominokit');
            $this->dominokit_options[3]['description'] = __('Thank you for choosing the Dominokit plugin', 'dominokit');
            $this->dominokit_options[3]['tab1'] = false;
            $this->dominokit_options[3]['tab2'] = false;
            $this->dominokit_options[3]['tab3'] = false;
            $this->dominokit_options[3]['tab4'] = true;
        }
    }

    public function enqueue_admin_script_callback()
    {
        $min = !DOMKIT_DEBUG ? '.min' : '';

        $current_page = urldecode(get_current_screen()->id);

        if ($current_page === 'toplevel_page_dominokit') {
            wp_enqueue_style('dominokit-bootstrap-rtl', DOMKIT_ASSETS . '/css/bootstrap.rtl.min.css', [], '');
            wp_enqueue_style('dominokit-style', DOMKIT_ASSETS . '/css/style' . $min . '.css', ['dominokit-bootstrap-rtl'], '');

            if (get_locale() === 'fa_IR') {
                wp_enqueue_style('dominokit-style-rtl', DOMKIT_ASSETS . '/css/style-rtl' . $min . '.css', ['dominokit-bootstrap-rtl'], '');
            }

            wp_enqueue_style('dominokit-fonts', DOMKIT_ASSETS . '/css/fonts' . $min . '.css', ['dominokit-style'], '');
            wp_enqueue_style('dominokit-fontawesome', DOMKIT_ASSETS . '/css/fontawesome.css', ['dominokit-style'], '');
            wp_enqueue_style('dominokit-sweetalert2', DOMKIT_ASSETS . '/sweetalert/sweetalert2.min.css', ['dominokit-style'], '');

            wp_enqueue_script('dominokit-vuejs', DOMKIT_ASSETS . '/js/vuejs/vue.js', [], '', true);
            wp_enqueue_script('dominokit-vuejs-axios', DOMKIT_ASSETS . '/js/vuejs/axios.min.js', ['dominokit-vuejs'], '', true);
            wp_enqueue_script('dominokit-sweetalert2', DOMKIT_ASSETS . '/sweetalert/sweetalert2.all.min.js', ['dominokit-vuejs'], '', true);
            wp_enqueue_script('dominokit-vuejs-backend', DOMKIT_ASSETS . '/js/vuejs-backend' . $min . '.js', ['dominokit-vuejs', 'dominokit-sweetalert2', 'dominokit-vuejs-axios'], '', true);

            $webZhaket = new DominokitWebZhaket('web', 'dominodev');
            $products = $webZhaket->getProduct();

            wp_localize_script('dominokit-vuejs-backend', 'dominokit', [
                'options' => $this->dominokit_options,
                'products' => $products,
                'ajax_url' => admin_url('admin-ajax.php'),
                'local' => get_locale()
            ]);

            $unavailable_products = get_option('woo_unavailable_products');
            $upd_toggleWooCart = !empty(get_option('dominokit_cart_button_product_txt')) ? get_option('dominokit_cart_button_product_txt') : false;
            $upd_toggleWooCartUrl = !empty(get_option('dominokit_cart_button_product_url')) ? get_option('dominokit_cart_button_product_url') : false;
            $upd_toggleWooShopCartUrl = !empty(get_option('dominokit_cart_button_shop_url')) ? get_option('dominokit_cart_button_shop_url') : false;
            $upd_toggleWooHidePrice = !empty(get_option('dominokit_price_hide_enabled')) ? get_option('dominokit_price_hide_enabled') : false;
            $upd_toggleWooHidePriceText = !empty(get_option('dominokit_price_hide_text')) ? get_option('dominokit_price_hide_text') : '';
            $upd_toggleWooHidePriceUrl = !empty(get_option('dominokit_price_hide_url')) ? get_option('dominokit_price_hide_url') : '';
            $upd_toggleWooReplacePrice = !empty(get_option('dominokit_replace_text_zero')) ? get_option('dominokit_replace_text_zero') : '';
            $upd_toggleWooShamsi = !empty(get_option('dominokit_option_wooShamsi')) ? get_option('dominokit_option_wooShamsi') : false;
            $upd_toggleWooDatepicker = !empty(get_option('dominokit_option_wooDatepicker')) ? get_option('dominokit_option_wooDatepicker') : false;


            wp_localize_script('dominokit-vuejs-backend', 'updOption', [
                'woo_unavailable_products' => $unavailable_products,
                'cart_button_product_txt' => $upd_toggleWooCart,
                'cart_button_product_url' => $upd_toggleWooCartUrl,
                'cart_button_shop_url' => $upd_toggleWooShopCartUrl,
                'product_hide_price' => [
                    'enabled_hide_price' => $upd_toggleWooHidePrice,
                    'text_hide_price' => $upd_toggleWooHidePriceText,
                    'url_hide_price' => $upd_toggleWooHidePriceUrl,
                ],
                'product_replace_price' => $upd_toggleWooReplacePrice,
                'enabled_wooShamsi' => $upd_toggleWooShamsi,
                'enabled_wooDatepicker' => $upd_toggleWooDatepicker
            ]);
        }

        $toggleWooDatepicker = get_option('dominokit_option_wooDatepicker') === 'true';

        if ($toggleWooDatepicker !== false) {

            if (isset(self::SCREENS[urldecode(get_current_screen()->id)])) {

                wp_enqueue_script('dominokit-datepicker', DOMKIT_ASSETS . '/js/vuejs/datepicker/jalalidatepicker.min.js', ['jquery'], '', true);

                wp_enqueue_script('dominokit-admin-date', DOMKIT_ASSETS . '/js/date.js', ['jquery'], '', true);

                wp_enqueue_script('dominokit-admin-product', DOMKIT_ASSETS . '/js/admin-shamsi' . $min . '.js', ['dominokit-admin-date'], '', true);

                wp_enqueue_style('dominokit-admin-shamsi', DOMKIT_ASSETS . '/css/admin-shamsi' . $min . '.css', [], '');

                wp_enqueue_style('dominokit-datepicker', DOMKIT_ASSETS . '/js/vuejs/datepicker/jalalidatepicker.min.css', [], '');

                add_action('admin_print_footer_scripts', array($this, 'dominokit_inline_js_callback'));
            }
        }
    }

    public function dominokit_register_required_plugins()
    {
        $plugins = array(
            array(
                'name' => 'woocommerce',
                'slug' => 'woocommerce',
                'required' => true,
            )
        );

        $config = array(
            'id' => 'dominokit',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu' => 'dominokit-install-plugins', // Menu slug.
            'parent_slug' => 'plugins.php',            // Parent menu slug.
            'capability' => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices' => true,                    // Show admin notices or not.
            'dismissable' => false,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message' => '',                      // Message to output right before the plugins table.
            'strings' => array(
                'page_title' => esc_html__('Install Required Plugins', 'dominokit'),
                'menu_title' => esc_html__('Install Plugins', 'dominokit'),
                'installing' => esc_html__('Installing Plugin: %s', 'dominokit'),
                'updating' => esc_html__('Updating Plugin: %s', 'dominokit'),
                'oops' => esc_html__('Something went wrong with the plugin API.', 'dominokit'),
                'notice_can_install_required' => _n_noop(
                    'This theme requires the following plugin: %1$s.',
                    'This theme requires the following plugins: %1$s.',
                    'dominokit'
                ),
                'notice_can_install_recommended' => _n_noop(
                    'This theme recommends the following plugin: %1$s.',
                    'This theme recommends the following plugins: %1$s.',
                    'dominokit'
                ),
                'notice_ask_to_update' => _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                    'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                    'dominokit'
                ),
                'notice_ask_to_update_maybe' => _n_noop(
                    'There is an update available for: %1$s.',
                    'There are updates available for the following plugins: %1$s.',
                    'dominokit'
                ),
                'notice_can_activate_required' => _n_noop(
                    'The following required plugin is currently inactive: %1$s.',
                    'The following required plugins are currently inactive: %1$s.',
                    'dominokit'
                ),
                'notice_can_activate_recommended' => _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.',
                    'The following recommended plugins are currently inactive: %1$s.',
                    'dominokit'
                ),
                'install_link' => _n_noop(
                    'Begin installing plugin',
                    'Begin installing plugins',
                    'dominokit'
                ),
                'update_link' => _n_noop(
                    'Begin updating plugin',
                    'Begin updating plugins',
                    'dominokit'
                ),
                'activate_link' => _n_noop(
                    'Begin activating plugin',
                    'Begin activating plugins',
                    'dominokit'
                ),
                'return' => esc_html__('Return to Required Plugins Installer', 'dominokit'),
                'plugin_activated' => esc_html__('Plugin activated successfully.', 'dominokit'),
                'activated_successfully' => esc_html__('The following plugin was activated successfully:', 'dominokit'),
                'plugin_already_active' => esc_html__('No action taken. Plugin %1$s was already active.', 'dominokit'),
                'plugin_needs_higher_version' => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'dominokit'),
                'complete' => esc_html__('All plugins installed and activated successfully. %1$s', 'dominokit'),
                'dismiss' => esc_html__('Dismiss this notice', 'dominokit'),
                'notice_cannot_install_activate' => esc_html__('There are one or more required or recommended plugins to install, update or activate.', 'dominokit'),
                'contact_admin' => esc_html__('Please contact the administrator of this site for help.', 'dominokit'),

                'nag_type' => '',
            ),
        );

        tgmpa($plugins, $config);
    }

    public function admin_menu_callback()
    {
        $plugins = get_option('active_plugins');

        if (in_array('woocommerce/woocommerce.php', $plugins)) {

            add_menu_page(
                esc_html__('Dominokit', 'dominokit'),
                esc_html__('Dominokit', 'dominokit'),
                'manage_options',
                'dominokit',
                array($this, 'admin_dominokit_option_callback'),
                'data:image/svg+xml;base64,' . base64_encode('<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 109.77"><defs><style>.cls-1{fill-rule:evenodd;}</style></defs><path fill="#fff" class="cls-1" d="M26.13,8.81A17.18,17.18,0,0,0,13.24,4.72c14,11.42-2.58,26.17-11.79,9-3.78,8.49.16,16.31,8,20.33,3.7,1.9,5.65,3,7,4.53a11.73,11.73,0,0,1,1.86,2.92l3.49,8.26H7.11A2.32,2.32,0,0,0,4.8,52.1V93a16.87,16.87,0,0,0,16.82,16.82H96.79A16.87,16.87,0,0,0,113.61,93V52.1a2.32,2.32,0,0,0-2.32-2.32H88.89l13.07-22-9.37-5.4L76.21,49.78H65.79l0-2.86a.57.57,0,0,0-.12-.35c-.5-.6-.88-1-1.2-1.41h0c-.74-.86-1.12-1.29-1.12-1.57s.36-.75,1.09-1.65c.3-.37.66-.81,1.08-1.36a.57.57,0,0,0,.13-.36V37.05a.55.55,0,0,0-.55-.55l-4.85,0,.09-22.09,2.92-5.11a2.85,2.85,0,0,0,.46-1.55l0-5A2.83,2.83,0,0,0,60.82,0L54,0a2.83,2.83,0,0,0-2.79,2.87l.08,5.54A2.79,2.79,0,0,0,51.81,10l2.73,3.91-.09,22.63-5.74,0a.55.55,0,0,0-.55.56v3.1a.54.54,0,0,0,.14.36c.47.52.87,1,1.22,1.32,1,1.07,1.48,1.59,1.49,2s-.41.86-1.19,1.85c-.39.49-.87,1.1-1.44,1.86a.5.5,0,0,0-.12.35v1.86H35.19l-5.5-12.91a12.84,12.84,0,0,1-1.11-3.79,14.19,14.19,0,0,1,.28-3.75c1.33-7.78,4-14.25-2.73-20.52ZM9.43,63.94H109V54.41H9.43v9.53ZM109,68.57H95.13v7.79A3.17,3.17,0,0,1,92,79.53H80.86a3.18,3.18,0,0,1-3.17-3.17V68.57H41.82v7.79a3.18,3.18,0,0,1-3.17,3.17H27.55a3.18,3.18,0,0,1-3.18-3.17V68.57H9.43V93a12.24,12.24,0,0,0,12.19,12.2H96.79A12.24,12.24,0,0,0,109,93V68.57ZM75.29,18.83a15.81,15.81,0,0,1,5.3-2.57,10.13,10.13,0,0,1,7.87.85l20.38,11.75L107.61,31l7.75,4.51,7.52-13L115.14,18l-1.4,2.36L93.48,8.68a10.31,10.31,0,0,0-8-1.38c-4.24,1.14-7.65,5-10.23,11.53Z"/></svg>')
            );
        }
    }

    public function admin_dominokit_option_callback()
    {
        require_once DOMKIT_TEMPLATE . '/admin-ui-template.php';
    }

    /**
     * @return bool
     */
    public function dominokit_inline_js_callback()
    {
        $screen = get_current_screen()->id;

        if (!isset(self::SCREENS[urldecode($screen)])) {
            return true;
        }

        ?>
        <style type="text/css">
            #ui-datepicker-div {
                display: none !important;
            }
        </style>

        <?php

        $this->inline_js_shop_coupon();
        $this->inline_js_product();
        $this->inline_js_shop_order();
        $this->inline_js_report();

    }

    public function inline_js_product()
    {
        ?>
        <script type="text/javascript">
            jQuery(function ($) {

                let option = {
                    separatorChar: "-",
                }

                jalaliDatepicker.startWatch(option);

                $("#_sale_price_dates_from").attr('data-jdp', '');

                $("#_sale_price_dates_to").attr('data-jdp', '');

                $("div.woocommerce_variations").on("click", "a.sale_schedule", function () {
                    let el_to = $(this).parent().parent().next().find("input[name*=to]");

                    el_to.attr('data-jdp', '');

                    let el_from = $(this).parent().parent().next().find("input[name*=from]");

                    el_from.attr('data-jdp', '');
                });

            });
        </script>
        <?php
    }

    public function inline_js_shop_coupon()
    {
        global $expiry_date;

        if (isset($_GET['post']) && !empty($_GET['post']) && is_numeric($_GET['post'])) {
            $coupon = new WC_Coupon(intval($_GET['post']));
            $expiry_date = $coupon->get_date_expires('edit') ? $coupon->get_date_expires('edit')->date_i18n('Y-m-d') : '';
        }

        ?>
        <script type="text/javascript">
            jQuery(function ($) {
                let option = {
                    separatorChar: "-",
                }

                jalaliDatepicker.startWatch(option);

                $("input[name=expiry_date]").val('<?php echo !is_null($expiry_date) ? esc_attr($expiry_date) : "" ?>');

                $("input[name=expiry_date]").attr('data-jdp', '');
            });
        </script>
        <?php

    }

    public function inline_js_shop_order()
    {
        ?>
        <script type="text/javascript">
            jQuery(function ($) {
                let option = {
                    separatorChar: "-",
                }

                jalaliDatepicker.startWatch(option);

                let order_date = $("input[name=order_date]").val();

                $("input[name=order_date]").attr('data-jdp', '');
            });
        </script>
        <?php
    }

    public function inline_js_report()
    {
        ?>
        <script type="text/javascript">
            jQuery(function ($) {

                let option = {
                    separatorChar: "-",
                }

                jalaliDatepicker.startWatch(option);

                $("input[name=start_date]").attr('data-jdp', '');

                $("input[name=end_date]").attr('data-jdp', '');

            });
        </script>
        <?php
    }

    /**
     * @return DominoKitController|null
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

DominoKitController::instance();
