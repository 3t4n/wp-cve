<?php
/**
 * Class for Terms and Conditions per Product Settings.
 *
 * @package Terms_And_Conditions_Per_Product
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If class exists, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Settings' ) ) {

    /**
     * Class
     */
    class TACPP4_Terms_Conditions_Settings {

        protected static $instance = null;

        static $tacpp_option_name;

        public static function get_instance() {
            null === self::$instance and self::$instance = new self;

            return self::$instance;
        }


        /**
         * Constructor for class.
         */
        public function __construct() {
            self::$tacpp_option_name = 'tacpp_admin_settings';

            if ( is_admin() ) {
                add_action( 'admin_menu',
                    array( __CLASS__, 'add_settings_menu_entry' ), 100 );
                add_action( 'admin_init', array( $this, 'update_settings' ) );

                add_filter( 'woocommerce_admin_field_full_info',
                    array( $this, 'show_setting_info' ) );

                add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
            }
        }

        /**
         * Check if log acceptance is enabled
         */
        public static function log_acceptance_enabled() {
            $log_enabled = false;

            $settings = get_option( self::$tacpp_option_name );

            // Check the site's settings
            if ( isset( $settings['log_acceptance'] ) && $settings['log_acceptance'] == 1
            ) {
                $log_enabled = true;
            }

            // Check if user just submitted the enabled setting
            if ( $log_enabled == false && isset( $_REQUEST['tacpp_wc_terms_log_acceptance'] ) && $_REQUEST['tacpp_wc_terms_log_acceptance'] == 1 ) {
                $log_enabled = true;
            }

            return apply_filters( 'tacpp_log_acceptance_enabled', $log_enabled );
        }

        public function admin_enqueue_scripts() {
            // Custom plugin script.
            wp_enqueue_style(
                'terms-per-product-admin-settings',
                TACPP4_PLUGIN_URL . 'assets/css/terms-admin-settings.css',
                '',
                TACPP4_PLUGIN_VERSION
            );

            /**
             * Enqueue the tooltip in the admin settings page
             */

            if ( isset( $_GET['page'] ) && $_GET['page'] == 'tacpp' ) {

                wp_enqueue_script(
                    'js-popper',
                    'https://unpkg.com/@popperjs/core@2',
                    array( 'jquery' ),
                    TACPP4_PLUGIN_VERSION,
                    true
                );

                wp_enqueue_script(
                    'js-tooltip',
                    'https://unpkg.com/tippy.js@6',
                    array( 'jquery' ),
                    TACPP4_PLUGIN_VERSION,
                    true
                );

                wp_enqueue_style( 'woocommerce_admin_styles' );
            }
        }

        public static function add_settings_menu_entry() {

            add_menu_page(
                __( 'Terms and Conditions', 'terms-and-conditions-per-product' ),
                __( 'Terms of Use', 'terms-and-conditions-per-product' ),
                'manage_woocommerce', // Required user capability
                'tacpp',
                array( __CLASS__, 'tacpp_submenu_settings_page_callback' ),
                TACPP4_PLUGIN_URL . 'assets/images/avatar.svg'
            );

//            add_submenu_page(
//                "tacpp",
//                __( 'Settings', 'terms-and-conditions-per-product' ),
//                __( 'Settings', 'terms-and-conditions-per-product' ),
//                'manage_woocommerce',
//                "tacpp_settings",
//                array( __CLASS__, 'tacpp_submenu_settings_page_callback' )
//            );

        }

        public static function tacpp_submenu_settings_page_callback() {
            $template = apply_filters(
                'tacpp_submenu_settings_page_template',
                TACPP4_PLUGIN_PATH . 'templates/admin-settings-page.php'
            );

            include( $template );
        }

        public static function tacpp_submenu_tacpp_page_callback() {
            $template = apply_filters(
                'tacpp_submenu_tacpp_page_callback',
                TACPP4_PLUGIN_PATH . 'templates/admin-tacpp-page.php'
            );

            include( $template );
        }


        private static function get_settings() {

            $settings = get_option( self::$tacpp_option_name );

            // Set checkbox value to yes in order to be checked
            $terms_on_product = '';
            if ( isset( $settings['terms_on_product'] ) && $settings['terms_on_product'] === 1 ) {
                $terms_on_product = 'yes';
            }

            $terms_must_read = '';
            if ( isset( $settings['terms_must_read'] ) && $settings['terms_must_read'] === 1 ) {
                $terms_must_read = 'yes';
            }

            $terms_modal_value = '';
            if ( isset( $settings['terms_modal'] ) && $settings['terms_modal'] === 1 ) {
                $terms_modal_value = 'yes';
            }

            $wc_modal_value = '';
            if ( isset( $settings['wc_terms_modal'] ) && $settings['wc_terms_modal'] === 1 ) {
                $wc_modal_value = 'yes';
            }

            $terms_hide_wc_terms = '';
            if ( isset( $settings['hide_default_terms'] ) && $settings['hide_default_terms'] === 1 ) {
                $terms_hide_wc_terms = 'yes';
            }

            $wc_log_acceptance_value = '';
            if ( isset( $settings['log_acceptance'] ) && $settings['log_acceptance'] === 1 ) {
                $wc_log_acceptance_value = 'yes';
            }

            $setting_image_folder = TACPP4_PLUGIN_URL . 'assets/images/settings/';

            $tooltips = array(
                'must_read'     => sprintf(
                    __(
                        'Enable this option to ensure the users open the terms and conditions link (if one exists) before completing the checkout process. %s',
                        'terms-and-conditions-per-product'
                    ),
                    '<span class="tacpp-setting-image">
								<img src="' . $setting_image_folder . 'must-open-link.jpg" alt="Force opening terms URL">
							</span>'
                ),

                'show_terms'     => sprintf(
                    __( 'Enable this option to display the terms and conditions of a product on the product\'s single page. %s', 'terms-and-conditions-per-product' ),
                    '<span class="tacpp-setting-image">
								<img src="' . $setting_image_folder . 'show-on-product-page.jpg" alt="Display on single product page">
							</span>'
                ),
                'terms_modal'    => sprintf(
                    __( 'Enable this option to enable users to view the terms and conditions in a modal window, ensuring that they can read and accept them without leaving the current page or losing their focus.  %s', 'terms-and-conditions-per-product' ),
                    '<span class="tacpp-setting-image">
								<img src="' . $setting_image_folder . 'terms-modal.jpg" alt="View the terms in a modal">
							</span>'
                ),
                'wc_terms_modal' => sprintf(
                    __( 'Enable this option to enable users to view the default WooCommerce terms and conditions in a modal window.  %s', 'terms-and-conditions-per-product' ),
                    '<span class="tacpp-setting-image">
								<img src="' . $setting_image_folder . 'terms-modal.jpg" alt="View the terms in a modal">
							</span>'
                ),
                'log_acceptance' => sprintf(
                    __( 'Enable this option to log the user\'s terms and conditions acceptance and display it on the order\'s edit page.  %s', 'terms-and-conditions-per-product' ),
                    '<span class="tacpp-setting-image">
								<img src="' . $setting_image_folder . 'log-acceptance.jpg" alt="Log and display the user\'s terms acceptance">
							</span>'
                ),
                'hide_default_terms' => sprintf(
                    __( 'Enable this option to hide the default WooCommerce terms from the checkout page when custom terms exist.  %s', 'terms-and-conditions-per-product' ),
                    '<span class="tacpp-setting-image">
								<img src="' . $setting_image_folder . 'hide-wc-terms.jpg" alt="Log and display the user\'s terms acceptanct">
							</span>'
                ),

            );


            $settings = array(
                'section_title'           => array(
                    'name' => __( 'Configure Terms and Conditions per Product',
                        'terms-and-conditions-per-product' ),
                    'type' => 'title',
                    'desc' => '',
                    'id'   => 'tacpp_settings_section_title'
                ),
                'terms_must_read'   => array(
                    'name'              => __( "Ensure users open the terms' link before purchase", 'terms-and-conditions-per-product' ),
                    'desc'              => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip'          => $tooltips['must_read'],
                    'type'              => 'checkbox',
                    'id'                => 'tacpp_terms_must_read',
                    'value'             => $terms_must_read,
                    'class'             => "terms-ui-toggle",
                    'custom_attributes' => array( 'data-tooltip' => '' ),
                ),
                'terms_on_product_page'   => array(
                    'name'              => __( "Show terms on the single product's page", 'terms-and-conditions-per-product' ),
                    'desc'              => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip'          => $tooltips['show_terms'],
                    'type'              => 'checkbox',
                    'id'                => 'tacpp_terms_on_product_page',
                    'value'             => $terms_on_product,
                    'class'             => "terms-ui-toggle",
                    'custom_attributes' => array( 'data-tooltip' => '' ),
                ),
                'terms_modal'             => array(
                    'name'     => __( 'Open product terms in a modal',
                        'terms-and-conditions-per-product' ),
                    'desc'     => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip' => $tooltips['terms_modal'],
                    'type'     => 'checkbox',
                    'id'       => 'tacpp_terms_in_modal',
                    'value'    => $terms_modal_value,
                    'class'    => "terms-ui-toggle",
                ),
                'wc_terms_modal'          => array(
                    'name'     => __( 'Open WooCommerce terms in a modal',
                        'terms-and-conditions-per-product' ),
                    'desc'     => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip' => $tooltips['wc_terms_modal'],
                    'type'     => 'checkbox',
                    'id'       => 'tacpp_wc_terms_in_modal',
                    'value'    => $wc_modal_value,
                    'class'    => "terms-ui-toggle",
                ),
                'hide_default_terms'   => array(
                    'name'              => __( "Hide default WooCommerce Terms", 'terms-and-conditions-per-product' ),
                    'desc'              => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip'          => $tooltips['hide_default_terms'],
                    'type'              => 'checkbox',
                    'id'                => 'tacpp_terms_hide_default_terms',
                    'value'             => $terms_hide_wc_terms,
                    'class'             => "terms-ui-toggle",
                    'custom_attributes' => array( 'data-tooltip' => '' ),
                ),
                'wc_terms_log_acceptance' => array(
                    'name'     => __( 'Log User Acceptance',
                        'terms-and-conditions-per-product' ),
                    'desc'     => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip' => $tooltips['log_acceptance'],
                    'type'     => 'checkbox',
                    'id'       => 'tacpp_wc_terms_log_acceptance',
                    'value'    => $wc_log_acceptance_value,
                    'class'    => "terms-ui-toggle",
                ),


            );

            if ( ! tacppp_fs()->is_paying_or_trial() ) {

                $settings['terms_modal']             = array(
                    'name'              => __( 'Open product terms in a modal [Premium]',
                        'terms-and-conditions-per-product' ),
                    'desc'              => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip'          => $tooltips['terms_modal'],
                    'type'              => 'checkbox',
                    'id'                => 'tacpp_terms_in_modal',
                    'value'             => 0,
                    'class'             => 'disabled terms-ui-toggle',
                    'custom_attributes' => array( 'disabled' => 'disabled' ),
                );
                $settings['wc_terms_modal']          = array(
                    'name'              => __( 'Open WooCommerce terms in a modal [Premium]',
                        'terms-and-conditions-per-product' ),
                    'desc'              => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip'          => $tooltips['wc_terms_modal'],
                    'type'              => 'checkbox',
                    'id'                => 'tacpp_wc_terms_in_modal',
                    'value'             => 0,
                    'class'             => 'disabled terms-ui-toggle',
                    'custom_attributes' => array( 'disabled' => 'disabled' ),
                );
                $settings['wc_terms_log_acceptance'] = array(
                    'name'              => __( 'Log User Acceptance [Premium]',
                        'terms-and-conditions-per-product' ),
                    'desc'              => '<span class="woocommerce-help-tip setting-info"></span>',
                    'desc_tip'          => $tooltips['log_acceptance'],
                    'type'              => 'checkbox',
                    'id'                => 'tacpp_wc_terms_log_acceptance',
                    'value'             => 0,
                    'class'             => 'terms-ui-toggle disabled',
                    'custom_attributes' => array( 'disabled' => 'disabled' ),
                );
            }

            $settings['section_end'] = array(
                'type' => 'sectionend',
                'id'   => 'tacpp_settings_section_end'
            );

            return apply_filters( 'tacpp_admin_settings_form', $settings );
        }

        /**
         * Store TACPP settings to DB
         */
        public function update_settings() {
            if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'tacpp' || ! $_POST ) {
                return;
            }
            $settings = get_option( self::$tacpp_option_name );

            $settings['terms_on_product'] = ( isset( $_POST['tacpp_terms_on_product_page'] ) ? 1 : 0 );
            $settings['terms_must_read'] = ( isset( $_POST['tacpp_terms_must_read'] ) ? 1 : 0 );

            $settings['terms_modal'] = ( isset( $_POST['tacpp_terms_in_modal'] ) ? 1 : 0 );

            $settings['wc_terms_modal'] = ( isset( $_POST['tacpp_wc_terms_in_modal'] ) ? 1 : 0 );

            $settings['log_acceptance'] = ( isset( $_POST['tacpp_wc_terms_log_acceptance'] ) ? 1 : 0 );

            $settings['hide_default_terms'] = ( isset( $_POST['tacpp_terms_hide_default_terms'] ) ? 1 : 0 );

            update_option( self::$tacpp_option_name, $settings );

        }

        public function show_setting_info( $settings ) {
            ?>
			<tr>
				<th scope="row" class="" colspan="2">
                    <?php echo wp_kses_post( wpautop( wptexturize( $settings['content'] ) ) ); ?>
				</td></tr>
            <?php
        }
    }

    new TACPP4_Terms_Conditions_Settings();
}
