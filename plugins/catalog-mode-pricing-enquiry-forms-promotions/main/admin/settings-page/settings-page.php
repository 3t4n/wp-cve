<?php

if ( !class_exists( 'Reon' ) ) {

    return;
}

if ( !class_exists( 'WModes_Admin_Page' ) ) {

    require_once (dirname( __FILE__ ) . '/catalog-mode-section/catalog-mode-section.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-section/catalog-mode-panel.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-section/catalog-mode-panel-note.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-section/catalog-mode-panel-modes.php');
    require_once (dirname( __FILE__ ) . '/catalog-mode-section/catalog-mode-panel-conditions.php');

    require_once (dirname( __FILE__ ) . '/product-prices-section/product-prices-section.php');
    require_once (dirname( __FILE__ ) . '/product-prices-section/product-prices-panel.php');
    require_once (dirname( __FILE__ ) . '/product-prices-section/product-prices-panel-options.php');
    require_once (dirname( __FILE__ ) . '/product-prices-section/product-prices-panel-products.php');
    require_once (dirname( __FILE__ ) . '/product-prices-section/product-prices-panel-max.php');
    require_once (dirname( __FILE__ ) . '/product-prices-section/product-prices-panel-conditions.php');

    require_once (dirname( __FILE__ ) . '/product-options-section/product-options-section.php');
    require_once (dirname( __FILE__ ) . '/product-options-section/product-options-panel.php');
    require_once (dirname( __FILE__ ) . '/product-options-section/product-options-panel-note.php');
    require_once (dirname( __FILE__ ) . '/product-options-section/product-options-panel-options.php');
    require_once (dirname( __FILE__ ) . '/product-options-section/product-options-panel-conditions.php');

    require_once (dirname( __FILE__ ) . '/settings-ui-badge-section/settings-ui-badge-section.php');
    require_once (dirname( __FILE__ ) . '/settings-ui-countdown-sections/settings-ui-countdown-sections.php');
    require_once (dirname( __FILE__ ) . '/settings-ui-sections/settings-ui-sections.php');
    require_once (dirname( __FILE__ ) . '/settings-section/settings-section.php');

    class WModes_Admin_Page {

        private static $option_name = "wmodes_settings";
        private static $menu_slug = "wmodes-settings";

        public static function init() {

            self::init_page();

            WModes_Admin_Catalog_Mode_Page::init();
            WModes_Admin_Product_Prices_Page::init();
            WModes_Admin_Product_Options_Page::init();
            WModes_Admin_Settings_Badge_Styles_Page::init();
            WModes_Admin_Settings_CountDown_Styles_Page::init();
            WModes_Admin_Settings_Styles_Section_Page::init();
            WModes_Admin_Settings_Section_Page::init();

            self::init_data_store();

            add_filter( 'reon/get-option-page-' . self::$option_name . '-sections', array( new self(), 'config_all_sections' ), 10 );
            add_filter( 'reon/get-option-page-' . self::$option_name . '-section-title', array( new self(), 'get_page_title' ), 10, 2 );

            add_filter( 'reon/process-save-options-' . self::$option_name, array( new self(), 'save_options' ), 10 );

            add_filter( 'wmodes-admin/get-disabled-list', array( new self(), 'get_disabled_list' ), 10, 2 );
            add_filter( 'wmodes-admin/get-disabled-grouped-list', array( new self(), 'get_grouped_disabled_list' ), 10, 2 );

            add_filter( 'plugin_action_links_' . plugin_basename( WMODES_MAIN_FILE ), array( new self(), 'get_plugin_links' ), 10, 1 );
        }

        public static function get_option_name() {

            return self::$option_name;
        }

        public static function get_page_slug() {

            return self::$menu_slug;
        }

        public static function init_page() {

            $version_text = sprintf( esc_html__( 'Lite v%s', 'wmodes-tdm' ), WMODES_VERSION );

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $version_text = sprintf( esc_html__( 'Premium v%s', 'wmodes-tdm' ), WMODES_VERSION );
            }

            $args = array(
                'option_name' => self::$option_name,
                'database' => 'option',
                'sanitize_mode' => 'recursive',
                'slug' => self::$menu_slug,
                'url_base' => 'admin.php',
                'default_min_height' => '700px',
                'width' => 'auto',
                'page_id' => 'wmodes_page',
                'enable_section_title' => true,
                'aside_width' => '210px',
                'display' => array(
                    'enabled' => true,
                    'image' => WMODES_ASSETS_URL . 'images/aside_logo.png',
                    'title' => esc_html__( 'Catalog Mode', 'wmodes-tdm' ),
                    'sub_title' => esc_html__( 'Pricing, Enquiry Forms & Promotions', 'wmodes-tdm' ),
                    'version' => $version_text,
                    'styles' => array(
                        'bg_image' => WMODES_ASSETS_URL . 'images/aside_bg.png',
                        'bg_color' => '#0073aa',
                        'color' => '#fff',
                        'height' => '195px',
                    ),
                ),
                'ajax' => array(
                    'save_msg' => esc_html__( 'Done!!', 'wmodes-tdm' ),
                    'save_error_msg' => esc_html__( 'Unable to save your settings', 'wmodes-tdm' ),
                    'reset_msg' => esc_html__( 'Done!!', 'wmodes-tdm' ),
                    'reset_error_msg' => esc_html__( 'Unable to reset reset your settings', 'wmodes-tdm' ),
                    'nonce_error_msg' => esc_html__( 'invalid nonce', 'wmodes-tdm' ),
                ),
                'menu' => array(
                    'enable' => true,
                    'title' => esc_html__( 'Catalog Mode - Pricing, Enquiry Forms & Promotions', 'wmodes-tdm' ),
                    'page_title' => esc_html__( 'Catalog Mode - Pricing, Enquiry Forms & Promotions', 'wmodes-tdm' ),
                    'icon' => 'dashicons-admin-generic',
                    'priority' => 3,
                    'parent' => 'woocommerce',
                    'capability' => 'manage_woocommerce',
                ),
                'import_export' => array(
                    'enable' => true,
                    'min_height' => '565px',
                    'title' => esc_html__( 'Import / Export', 'wmodes-tdm' ),
                    'import' => array(
                        'title' => esc_html__( 'Import Settings', 'wmodes-tdm' ),
                        'desc' => esc_html__( 'Here you can import new settings. Simply paste the settings url or data on the field below.', 'wmodes-tdm' ),
                        'url_button_text' => esc_html__( 'Import from url', 'wmodes-tdm' ),
                        'url_textbox_desc' => esc_html__( "Paste the url to another site's settings below and click the 'Import Now' button.", 'wmodes-tdm' ),
                        'url_textbox_hint' => esc_html__( "Paste the url to another site's settings here...", 'wmodes-tdm' ),
                        'data_button_text' => esc_html__( 'Import Data', 'wmodes-tdm' ),
                        'data_textbox_desc' => esc_html__( "Paste your backup settings below and click the 'Import Now' button.", 'wmodes-tdm' ),
                        'data_textbox_hint' => esc_html__( 'Paste your backup settings here...', 'wmodes-tdm' ),
                        'import_button_text' => esc_html__( 'Import Now', 'wmodes-tdm' ),
                        'warn_text' => esc_html__( 'Warning! This will override all existing settings. proceed with caution!', 'wmodes-tdm' ),
                    ),
                    'export' => array(
                        'title' => esc_html__( 'Export Settings', 'wmodes-tdm' ),
                        'desc' => esc_html__( 'Here you can backup your current settings. You can later use it to restore your settings.', 'wmodes-tdm' ),
                        'download_button_text' => esc_html__( 'Download Data', 'wmodes-tdm' ),
                        'url_button_text' => esc_html__( 'Export url', 'wmodes-tdm' ),
                        'url_textbox_desc' => esc_html__( 'Copy the url below, use it to transfer the settings from this site.', 'wmodes-tdm' ),
                        'data_button_text' => esc_html__( 'Export Data', 'wmodes-tdm' ),
                        'data_textbox_desc' => esc_html__( 'Copy the data below, use it as your backup.', 'wmodes-tdm' ),
                    ),
                ),
                'header_buttons' => array(
                    'reset_all_text' => esc_html__( 'Reset All', 'wmodes-tdm' ),
                    'reset_section_text' => esc_html__( 'Reset Section', 'wmodes-tdm' ),
                    'save_section_text' => esc_html__( 'Save Section', 'wmodes-tdm' ),
                ),
                'footer_buttons' => array(
                    'reset_all_text' => esc_html__( 'Reset All', 'wmodes-tdm' ),
                    'reset_section_text' => esc_html__( 'Reset Section', 'wmodes-tdm' ),
                    'save_section_text' => esc_html__( 'Save Section', 'wmodes-tdm' ),
                ),
                'page_links' => self::get_page_links(),
                'social_links' => self::get_social_links(),
            );

            Reon::set_option_page( $args );
        }

        public static function config_all_sections( $in_sections ) {

            $in_sections[] = array(
                'title' => esc_html__( 'Catalog Modes', 'wmodes-tdm' ),
                'id' => 'catalog_modes',
                'group' => 1,
            );

            $in_sections[] = array(
                'title' => esc_html__( 'Products Pricing', 'wmodes-tdm' ),
                'id' => 'product_prices',
                'group' => 2,
            );


            $in_sections[] = array(
                'title' => esc_html__( 'Products Settings', 'wmodes-tdm' ),
                'id' => 'product_options',
                'group' => 3,
            );

            $in_sections[] = array(
                'title' => esc_html__( 'UI Designs', 'wmodes-tdm' ),
                'id' => 'styles',
                'group' => 4,
            );

            $in_sections[] = array(
                'title' => esc_html__( 'Product Badge Designs', 'wmodes-tdm' ),
                'id' => 'badge_styles_settings',
                'group' => 4,
                'subsection' => true
            );

            $in_sections[] = array(
                'title' => esc_html__( 'Countdown Designs', 'wmodes-tdm' ),
                'id' => 'countdown_styles_settings',
                'group' => 5,
                'subsection' => true
            );

            $in_sections[] = array(
                'title' => esc_html__( 'Other UI Designs', 'wmodes-tdm' ),
                'id' => 'styles_settings',
                'group' => 6,
                'subsection' => true
            );

            $in_sections[] = array(
                'title' => esc_html__( 'General Settings', 'wmodes-tdm' ),
                'id' => 'settings',
                'group' => 7,
            );

            return $in_sections;
        }

        public static function get_page_title( $in_title, $tab ) {

            $section_titles = self::get_section_titles();

            if ( isset( $section_titles[ $tab ] ) ) {

                return $section_titles[ $tab ];
            }

            return $in_title;
        }

        public static function init_data_store() {

            global $wmodes_settings;

            $wmodes_settings = get_option( self::get_option_name(), array() );
        }

        public static function save_options( $options ) {

            $options[ 'using_external_css' ] = 'yes';
            $options[ 'custom_css_vertion' ] = current_time( 'U' );

            if ( !WModes_Views_CSS::save_css( $options ) ) {

                $options[ 'using_external_css' ] = 'no';
                $options[ 'custom_css_vertion' ] = 0;
            }

            return $options;
        }

        public static function sanitize_wmodes_kses_post_box( $option ) {

            $allow_html = WModes_Main::get_allow_html();

            return wp_kses( $option, $allow_html );
        }

        public static function get_plugin_links( $links ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                unset( $links[ 'deactivate' ] );

                $add_on_text = esc_html__( 'WooCommerce Catalog Mode - Premium (Add-On)', 'wmodes-tdm' );

                $required_text = sprintf( esc_html__( 'Required by %s', 'wmodes-tdm' ), $add_on_text );

                $no_deactivate_tag = '<span style="color: #313639">' . $required_text . '</span>';

                array_unshift( $links, $no_deactivate_tag );

                return $links;
            }

            $doc_link = '<a href="' . esc_url( 'https://zencrew.freshdesk.com/support/solutions/51000121630' ) . '">' . esc_html__( 'Documentation', 'wmodes-tdm' ) . '</a>';

            array_unshift( $links, $doc_link );

            $settings_url = admin_url( 'admin.php?page=wmodes-settings' );

            $settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'wmodes-tdm' ) . '</a>';

            array_unshift( $links, $settings_link );

            return $links;
        }

        public static function get_premium_messages( $message_id = '' ) {

            $premium_url = "https://codecanyon.net/item/woocommerce-catalog-mode-pricing-enquiry-forms-promotions/43498179?ref=zendcrew";
            $message = esc_html__( 'This feature is available on premium version', 'wmodes-tdm' );
            $link_text = esc_html__( 'Premium Feature', 'wmodes-tdm' );

            switch ( $message_id ) {

                case 'short':

                    return '<a href="' . $premium_url . '" target="_blank">' . $link_text . '</a>';
                default:

                    return '<a href="' . $premium_url . '" target="_blank">' . $link_text . '</a> - ' . $message . ' ' . $message_id;
            }
        }

        public static function get_disabled_list( $list, $options ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return array();
            }

            $d_list = array();

            $prem_max = count( $options );

            for ( $i = 1; $i <= $prem_max; $i++ ) {

                $d_key = 'prem_' . $i;

                if ( isset( $options[ $d_key ] ) ) {

                    $d_list[] = $d_key;
                }
            }

            return $d_list;
        }

        public static function get_grouped_disabled_list( $list, $grouped_options ) {

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                return array();
            }

            $options = array();

            foreach ( $grouped_options as $grouped_option ) {

                if ( !isset( $grouped_option[ 'options' ] ) ) {
                    continue;
                }

                foreach ( $grouped_option[ 'options' ] as $key => $option ) {

                    $options[ $key ] = $option;
                }
            }

            return self::get_disabled_list( $list, $options );
        }

        private static function get_page_links() {

            $page_links = array(
                array(
                    'id' => 'wmds_documentation',
                    'title' => esc_html__( 'Documentation', 'wmodes-tdm' ),
                    'icon' => 'fa fa-file-text',
                    'href' => esc_url( 'https://zencrew.freshdesk.com/support/solutions/51000121630' ),
                    'target' => '_blank',
                    'show_in' => 'both'
                ),
            );

            if ( defined( 'WMODES_PREMIUM_ADDON' ) ) {

                $page_links[] = array(
                    'id' => 'wmds_help',
                    'title' => esc_html__( 'Help', 'wmodes-tdm' ),
                    'icon' => 'fa fa-question-circle',
                    'href' => esc_url( 'https://zencrew.freshdesk.com/support/tickets' ),
                    'target' => '_blank',
                    'show_in' => 'both'
                );
            } else {

                $page_links[] = array(
                    'id' => 'wmds_get_premium',
                    'title' => esc_html__( 'Premium Version', 'wmodes-tdm' ),
                    'icon' => 'fa fa-file-text-o',
                    'href' => esc_url( 'https://codecanyon.net/item/woocommerce-catalog-mode-pricing-enquiry-forms-promotions/43498179?ref=zendcrew' ),
                    'target' => '_blank',
                    'show_in' => 'both'
                );
            }

            return $page_links;
        }

        private static function get_social_links() {

            return array(
                array(
                    'id' => 'wmds_facebook',
                    'title' => esc_html__( 'Facebook', 'wmodes-tdm' ),
                    'icon' => 'fa fa-facebook',
                    'href' => esc_url( 'http://www.facebook.com/zendcrew' ),
                    'target' => '_blank',
                ),
                array(
                    'id' => 'wmds_linkedin',
                    'title' => esc_html__( 'LinkedIn', 'wmodes-tdm' ),
                    'icon' => 'fa fa-linkedin',
                    'href' => esc_url( 'https://www.linkedin.com/company/zendcrew' ),
                    'target' => '_blank',
                ),
                array(
                    'id' => 'wmds_stack_overflow',
                    'title' => esc_html__( 'Stack Overflow', 'wmodes-tdm' ),
                    'icon' => 'fa fa-stack-overflow',
                    'href' => esc_url( 'https://stackoverflow.com/users/8692713/zendcrew' ),
                    'target' => '_blank',
                ),
                array(
                    'id' => 'wmds_instagram',
                    'title' => esc_html__( 'Instagram', 'wmodes-tdm' ),
                    'icon' => 'fa fa-instagram',
                    'href' => esc_url( 'https://www.instagram.com/zendcrew/' ),
                    'target' => '_blank',
                ),
            );
        }

        private static function get_section_titles() {

            $section_titles = array();

            $section_titles[ 1 ] = esc_html__( 'Catalog Modes', 'wmodes-tdm' );
            $section_titles[ 2 ] = esc_html__( 'Products Pricing', 'wmodes-tdm' );
            $section_titles[ 3 ] = esc_html__( 'Products Settings', 'wmodes-tdm' );
            $section_titles[ 4 ] = esc_html__( 'Product Badge Designs', 'wmodes-tdm' );
            $section_titles[ 5 ] = esc_html__( 'Product Badge Designs', 'wmodes-tdm' );
            $section_titles[ 6 ] = esc_html__( 'Countdown Designs', 'wmodes-tdm' );
            $section_titles[ 7 ] = esc_html__( 'Other UI Designs', 'wmodes-tdm' );
            $section_titles[ 8 ] = esc_html__( 'General Settings', 'wmodes-tdm' );
            $section_titles[ 9 ] = esc_html__( 'Import / Export', 'wmodes-tdm' );

            return $section_titles;
        }

    }

    WModes_Admin_Page::init();
}


