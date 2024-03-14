<?php
/**
 * Plugin Name: Stylish Cost Calculator
 * Plugin URI:  https://stylishcostcalculator.com
 * Description: A Stylish Cost Calculator / Price Estimate Form for your site.
 * Version:     7.7.4
 * Author:      Designful
 * Author URI:  https://stylishcostcalculator.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: scc
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
define( 'STYLISH_COST_CALCULATOR_VERSION', '7.7.4' );
define( 'SCC_URL', plugin_dir_url( __FILE__ ) );
define( 'SCC_DIR', __DIR__ );
define( 'SCC_LIB_DIR', __DIR__ . '/lib' );
define( 'SCC_LIB_URL', plugins_url( '/lib', __FILE__ ) );
define( 'SCC_ASSETS_URL', plugins_url( '/assets', __FILE__ ) );
define( 'SCC_TOOLTIP_BASEURL', SCC_ASSETS_URL . '/images/tooltip-images' );
define( 'SCC_TEMPLATE_PREVIEW_BASEURL', SCC_ASSETS_URL . '/images/template-previews' );
// loading constants
require plugin_dir_path( __FILE__ ) . '/utils/scc-global-constants.php';
require_once plugin_dir_path( __FILE__ ) . '/functions.php';
require plugin_dir_path( __FILE__ ) . '/admin/integrations/gutenberg-block/class-scc-gutenberg-block.php';
require plugin_dir_path( __FILE__ ) . '/elementor-widgets/class-scc-elementor-widget-init.php';
require plugin_dir_path( __FILE__ ) . '/admin/controllers/initialSetupWizardController.php';
require plugin_dir_path( __FILE__ ) . '/cron/notifications.php';
define(
    'SCC_ALLOWTAGS',
    [
        'h4'     => [
            'class' => [],
        ],
        'b'      => [
            'class' => [],
        ],
        'strong' => [
            'class' => [],
        ],
        'br'     => [],
        'hr'     => [],
        'li'     => [
            'class' => [],
        ],
        'ul'     => [
            'class' => [],
        ],
        'i'      => [
            'title'       => [],
            'data-toggle' => [],
        ],
        'div'    => [
            'class' => [],
            'id'    => [],
        ],
        'img'    => [
            'src'   => [],
            'class' => [],
            'alt'   => [],
        ],
        'a'      => [
            'href'   => [],
            'class'  => [],
            'target' => [],
        ],
        'span'   => [
            'class' => [],
        ],
    ]
);

if ( ! defined( 'SCC_TELEMETRY_ENDPOINT' ) ) {
    define( 'SCC_TELEMETRY_ENDPOINT', 'https://telemetry.stylishcostcalculator.com' );
}
class df_scc_plugin {

    public function __construct() {
        //?Handles ajax requests
        add_action( 'init', [ $this, 'df_scc_load_ajax' ] );
        //?creates tables if doesnt exists
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] !== 'activate' ) {
            $this->checkTablesExists();
        }
        //?hides funky messages of WordPress
        add_action( 'admin_print_scripts', [ $this, 'scc_admin_hide_notices' ] );
        register_activation_hook( __FILE__, [ $this, 'do_install_scc' ] );
        register_deactivation_hook( __FILE__, [ $this, 'do_uninstall_scc' ] );
        add_action( 'upgrader_process_complete', [ $this, 'post_upgrade_tasks' ], 10, 2 );
        //*Loads menu
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        // add the stylish cost calculator free version to exclusion list of All in One SEO
        add_filter(
            'aioseo_conflicting_shortcodes',
            function ( $conflictingShortcodes ) {
                $conflictingShortcodes['Stylish Cost Calculator'] = 'scc_calculator';

                return $conflictingShortcodes;
            }
        );
        //*shortcodeload
        add_shortcode( 'scc_calculator', [ $this, 'scc_shortcode1' ] );
        add_shortcode( 'scc_calculator-total', [ $this, 'create_scc_total_tag' ] );
        // *Loads script styles for frontend shorcode
        add_action( 'wp_enqueue_scripts', [ $this, 'scc_register_shortcode_calculator' ] );
        // *Loads script styles for backend shortcode in preview
        add_action( 'admin_enqueue_scripts', [ $this, 'scc_register_shortcode_calculator' ] );
        $this->scc_wpoption_add();
        add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'my_plugin_action_links' ] );
        add_action( 'admin_bar_menu', [ $this, 'scc_bar_menu' ], 100 );
        add_action(
            'plugins_loaded',
            function () {
                $block            = new \DF_SCC\GutenbergBlock\SCC_Gutenberg_Block();
                $elementor_widget = new \DF_SCC\ElementorIntegration\SCC_Elementor_Widget_Init();

                if ( $elementor_widget->allow_load() ) {
                    $elementor_widget->load();
                }

                if ( $block->allow_load() ) {
                    $block->load();
                }
            }
        );
    }
    public function checkTablesExists() {
        global $wpdb;
        $res = $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}df_scc_forms'" );

        if ( $res == null ) {
            //creates tables if dont exists
            $this->do_install_scc();
            require_once __DIR__ . '/admin/controllers/migrateController.php';
            $m = new migrateController();
            $m::update_wpOptions();
        }
        $this->scc_alter_tables();
    }
    public function scc_alter_tables() {
        global $wpdb;
        $forms_table_cols = $wpdb->get_col( "DESC {$wpdb->prefix}df_scc_forms", 0 );

        if ( ! in_array( 'ShowFormBuilderOnDetails', $forms_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_forms` ADD ShowFormBuilderOnDetails varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'false' " );
        }

        if ( ! in_array( 'turnoffQty', $forms_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_forms` ADD turnoffQty varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'false' " );
        }

        if ( ! in_array( 'urlStatsArray', $forms_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_forms` ADD urlStatsArray text COLLATE utf8mb4_unicode_ci DEFAULT NULL " );
        }

        if ( ! in_array( 'emailQuoteRecipients', $forms_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_forms` ADD emailQuoteRecipients tinyint(1) NOT NULL DEFAULT 1" );
        }

        if ( ! in_array( 'created_at', $forms_table_cols ) ) {
            $wpdb->query( $wpdb->prepare( "ALTER TABLE `{$wpdb->prefix}df_scc_forms` ADD created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'", [] ) );
        }

        if ( ! in_array( 'wrapper_max_width', $forms_table_cols ) ) {
            $wpdb->query( $wpdb->prepare( "ALTER TABLE `{$wpdb->prefix}df_scc_forms` ADD wrapper_max_width SMALLINT(10) NOT NULL DEFAULT 1000", [] ) );
        }
        $elements_table_cols = $wpdb->get_col( "DESC {$wpdb->prefix}df_scc_elements" );

        if ( ! in_array( 'element_woocomerce_product_id', $elements_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_elements` ADD element_woocomerce_product_id varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL " );
        }
        $elements_table_cols = $wpdb->get_col( "DESC {$wpdb->prefix}df_scc_elements" );

        if ( ! in_array( 'showInputBoxSlider', $elements_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_elements` ADD showInputBoxSlider tinyint(10) NOT NULL DEFAULT 0 " );
        }

        if ( ! in_array( 'value5', $elements_table_cols ) ) {
            $wpdb->query( "ALTER TABLE `{$wpdb->prefix}df_scc_elements` ADD value5 tinyint(10) DEFAULT 1" );
        }
    }
    public function scc_bar_menu( $adminBar ) {
        $args = [
            'id'       => 'scc-edit-calculator',
            'title'    => 'Edit SCC Calculator',
            'href'     => admin_url( 'admin.php?page=scc_edit_items' ),
            'meta'     => [ 'class' => 'scc-top-bar-' ],
            'position' => 100,
        ];
        $adminBar->add_node( $args );
    }
    public function do_install_scc() {
        //create the table used by the component if it does not exist
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_elementitems` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `order` int(11) NOT NULL,
    `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `price` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `value1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value5` tinyint(10) COLLATE utf8mb4_unicode_ci DEFAULT 1,
    `uniqueId` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `woocomerce_product_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `opt_default` tinyint(1) NOT NULL DEFAULT 0,
    `element_id` bigint(20) UNSIGNED NOT NULL
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_elements` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `orden` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `titleElement` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `value1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value3` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value4` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `length` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `uniqueId` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `mandatory` tinyint(1) NOT NULL DEFAULT 0,
    `showTitlePdf` tinyint(1) NOT NULL DEFAULT 0,
    `titleColumnDesktop` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `titleColumnMobile` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `showPriceHint` tinyint(1) NOT NULL DEFAULT 0,
    `displayFrontend` tinyint(1) NOT NULL DEFAULT 0,
    `displayDetailList` tinyint(1) NOT NULL DEFAULT 0,
    `showInputBoxSlider` tinyint(10) NOT NULL DEFAULT 0,
    `subsection_id` bigint(20) UNSIGNED NOT NULL,
    `element_woocomerce_product_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_forms` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `formname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `inheritFontType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `titleFontSize` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `titleFontType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `titleFontWeight` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `titleColorPicker` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ServicefontSize` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `fontType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `fontWeight` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ServiceColorPicker` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `objectSize` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `objectColorPicker` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `elementSkin` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `addContainer` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `addtoCheckout` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `buttonStyle` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffborder` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffemailquote` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnviewdetails` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffcoupon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `barstyle` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnofffloating` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `removeTotal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `minimumTotal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `minimumTotalChoose` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `removeTitle` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffUnit` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffQty` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffSave` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `turnoffTax` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `taxVat` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `showTaxBeforeTotal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `symbol` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `emailQuoteRecipients` tinyint(1) NOT NULL DEFAULT 1,
    `removeCurrency` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `userCompletes` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `userClicksf` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `formFieldsArray` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `webhookSettings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `showFieldsQuoteArray` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `translation` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `paypalConfigArray` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `preCheckoutQuoteForm` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `combine_checkout_items` tinyint(1) NOT NULL DEFAULT 0,
    `combine_checkout_woocommerce_product_id` bigint(20) UNSIGNED NOT NULL,
	`progress_indicator_style` bigint(20) UNSIGNED DEFAULT 1,
    `isWoocommerceCheckoutEnabled` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `isStripeEnabled` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `ShowFormBuilderOnDetails` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'false',
    `urlStatsArray` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `showSearchBar` tinyint(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `invoice_number_settings` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `wrapper_max_width` SMALLINT(10) NOT NULL DEFAULT 1000,
    `created_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_sections` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `order` int(11) NOT NULL,
    `accordion` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    `showSectionTotal` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `showSectionTotalOnPdf` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `section_in_page` tinyint(10) NOT NULL DEFAULT 0,
    `form_id` bigint(20) UNSIGNED NOT NULL
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_subsections` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `order` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
    `section_id` bigint(20) UNSIGNED NOT NULL
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_conditions` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `op` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `value` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `element_id` bigint(20) UNSIGNED NOT NULL,
    `condition_element_id` bigint(20) UNSIGNED DEFAULT NULL,
    `elementitem_id` bigint(20) UNSIGNED DEFAULT NULL,
    `condition_set` bigint(20) UNSIGNED DEFAULT 1
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_quote_submissions` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `status` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
    `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `opened` tinyint(1) NOT NULL,
    `starred` tinyint(1) NOT NULL,
    `submit_fields` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `quote_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `user_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `browser_ua` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `calc_id` bigint(20) UNSIGNED NOT NULL
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}df_scc_coupons` (
    `id` bigint(20) UNSIGNED NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `startdate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `enddate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `discountpercentage` double(10,2) NOT NULL DEFAULT 0.00,
    `discountvalue` double(10,2) NOT NULL DEFAULT 0.00,
    `minspend` double(10,2) NOT NULL DEFAULT 0.00,
    `maxspend` double(10,2) NOT NULL DEFAULT 0.00,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL
  )  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_elementitems`
    ADD PRIMARY KEY (`id`),
    ADD KEY `scc_elementitems_element_id_index` (`element_id`);"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_elements`
    ADD PRIMARY KEY (`id`),
    ADD KEY `scc_elements_subsection_id_index` (`subsection_id`);"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_forms`
    ADD PRIMARY KEY (`id`);"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_sections`
    ADD PRIMARY KEY (`id`),
    ADD KEY `scc_sections_form_id_index` (`form_id`);"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_subsections`
    ADD PRIMARY KEY (`id`),
    ADD KEY `scc_subsections_section_id_index` (`section_id`);"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_coupons`
    ADD PRIMARY KEY (`id`);"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_conditions`
    ADD PRIMARY KEY (`id`),
    ADD KEY `scc_conditions_condition_element_id_foreign` (`condition_element_id`),
    ADD KEY `scc_conditions_element_id_index` (`element_id`),
    ADD KEY `scc_conditions_elementitem_id_index` (`elementitem_id`)"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_quote_submissions`
    ADD PRIMARY KEY (`id`),
    ADD KEY `scc_quote_submissions_calc_id_foreign` (`calc_id`)"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_conditions`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_elementitems`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_elements`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_sections`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_subsections`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_quote_submissions`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_coupons`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_elementitems`
    ADD CONSTRAINT `scc_elementitems_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `{$wpdb->prefix}df_scc_elements` (`id`) ON DELETE CASCADE;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_elements`
    ADD CONSTRAINT `scc_elements_subsection_id_foreign` FOREIGN KEY (`subsection_id`) REFERENCES `{$wpdb->prefix}df_scc_subsections` (`id`) ON DELETE CASCADE;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_sections`
    ADD CONSTRAINT `scc_sections_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `{$wpdb->prefix}df_scc_forms` (`id`) ON DELETE CASCADE;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_subsections`
    ADD CONSTRAINT `scc_subsections_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `{$wpdb->prefix}df_scc_sections` (`id`) ON DELETE CASCADE;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_quote_submissions`
    ADD CONSTRAINT `scc_quote_submissions_calc_id_foreign` FOREIGN KEY (`calc_id`) REFERENCES `{$wpdb->prefix}df_scc_forms` (`id`) ON DELETE CASCADE;"
        );
        $wpdb->query(
            "ALTER TABLE `{$wpdb->prefix}df_scc_conditions`
    ADD CONSTRAINT `scc_conditions_condition_element_id_foreign` FOREIGN KEY (`condition_element_id`) REFERENCES `{$wpdb->prefix}df_scc_elements` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `scc_conditions_element_id_foreign` FOREIGN KEY (`element_id`) REFERENCES `{$wpdb->prefix}df_scc_elements` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `scc_conditions_elementitem_id_foreign` FOREIGN KEY (`elementitem_id`) REFERENCES `{$wpdb->prefix}df_scc_elementitems` (`id`) ON DELETE CASCADE"
        );
        /**
         * seed the initial option values
         */
        $default_subject     = 'Your Quote Request On ' . get_bloginfo( 'url' );
        $scc_color_scheme    = get_option( 'df_scc_color-scheme' );
        $scc_currency        = get_option( 'df_scc_currency' );
        $scc_currency        = get_option( 'df_scc_currencytext' );
        $scc_licensed        = get_option( 'df_scc_licensed' );
        $scc_fontsettings    = get_option( 'df_scc_fontsettings' );
        $scc_emailsender     = get_option( 'df_scc_emailsender' );
        $scc_emailsubject    = get_option( 'df_scc_emailsubject' );
        $scc_sendername      = get_option( 'df_scc_sendername' );
        $scc_messageform     = get_option( 'df_scc_messageform' );
        $scc_email_send_copy = get_option( 'df_scc_email_send_copy' );

        if ( ! isset( $scc_email_send_copy ) ) {
            add_option( 'df_scc_email_send_copy', '' );
        }

        if ( ! $scc_color_scheme ) {
            add_option( 'df_scc_color-scheme', 1 );
        }

        if ( ! $scc_currency ) {
            add_option( 'df_scc_currency', 'USD' );
        }

        if ( ! $scc_currency ) {
            add_option( 'df_scc_currencytext', 'U.S. Dollar' );
        }

        if ( ! $scc_licensed ) {
            add_option( 'df_scc_licensed', '0' );
        }

        if ( ! $scc_fontsettings ) {
            add_option( 'df_scc_fontsettings', '' );
        }

        if ( ! $scc_emailsender ) {
            add_option( 'df_scc_emailsender', '' );
        }

        if ( ! $scc_emailsubject ) {
            update_option( 'df_scc_emailsubject', $default_subject );
        }

        if ( ! $scc_sendername ) {
            add_option( 'df_scc_sendername', '' );
        }

        if ( ! $scc_messageform ) {
            add_option( 'df_scc_messageform', "Hello <customer-name>, <br><br> Attached to this email is a PDF file that contains your quote. <br> If you have any further questions please call us, email us here ____. <br><br> Sincerely,<br> Your Company Name<br><br> <hr><br> <b>Customer's Name</b> l <customer-name> <b>Customer's Phone</b> l <customer-phone> <b>Customer's Emai</b> l <customer-email> <b>Customer's IP</b> l <customer-ip-address> <b>Browser Info</b> l <customer-browser-info ><b>Device</b> l <device> <b> Referral </b> | <customer-referral>" );
        }
        update_option( 'scc_v7_tables_ready', true );
        update_option( 'scc_installation_timestamp', time() );
        // Setup Wizard
        set_transient( 'df_scc_post_activation_setup_redirect', true, 30 );
    }

    public function do_uninstall_scc() {
        if ( function_exists( 'wp_get_current_user' ) ) {
            $user     = wp_get_current_user();
            $userData = (array) $user->data;
            unset( $userData['user_pass'] );
            unset( $userData['user_activation_key'] );
            $userData['site_title']       = get_bloginfo();
            $userData['site_url']         = home_url();
            $userData['scc_free_version'] = STYLISH_COST_CALCULATOR_VERSION;
            $headers                      = [
                'user-agent'   => 'SCC/' . STYLISH_COST_CALCULATOR_VERSION . '/' . md5( esc_url( home_url() ) ) . ';',
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ];
            wp_remote_post(
                'https://hook.us1.make.com/rb2u1v5x7fih55n3qahm77cgb5rpsrud',
                [
                    'method'      => 'POST',
                    'timeout'     => 5,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => false,
                    'headers'     => $headers,
                    'body'        => json_encode( $userData ),
                    'cookies'     => [],
                ]
            );

            return 0;
        }
    }

    public function df_scc_load_ajax() {
        require __DIR__ . '/stylish-cost-ajax.php';
        require SCC_DIR . '/admin/views/uninstallSurveyModal.php';
        ( new \SCC\Admin\Controllers\InitialSetupWizardController() )->hooks();
    }
    public function scc_shortcode1( $atts ) {
        if ( ! is_admin() ) {
            add_action(
                'wp_footer',
                function () {
                    require SCC_DIR . '/admin/views/modalTemplates.php';
                }
            );
            //wp_localize_script( 'scc-frontend', 'pageCalcFront' . intval( $atts['idvalue'] ), array( 'nonce' => wp_create_nonce( 'calculator-front-page' . intval( $atts['idvalue'] ) ) ) );
            $data       = [ 'nonce' => wp_create_nonce( 'calculator-front-page' . intval( $atts['idvalue'] ) ) ];
            $calc_id    =  intval( $atts['idvalue'] );
            $array_data = json_encode( $data );
            add_action( 'wp_footer', function () use ( $calc_id, $array_data ) {
                printf( '<script type="text/javascript">var pageCalcFront%s = %s</script>', $calc_id, $array_data );
            } );
        }
        wp_enqueue_style( 'scc-admin-style' );
        wp_enqueue_style( 'scc-checkbox1' );
        wp_enqueue_style( 'scc-tom-select' );
        wp_enqueue_script( 'scc-tom-select' );
        wp_enqueue_style( 'scc-bootstrapslider-css' );
        wp_enqueue_script( 'scc-bootstrapslider-js' );
        wp_enqueue_script( 'scc-frontend' );
        wp_enqueue_script( 'scc-nouislider' );
        wp_enqueue_script( 'wp-util' );
        wp_enqueue_script( 'scc-translate-js' );
        wp_enqueue_script( 'scc-bootstrap-min3' );

        /* $currencies_array = 'window["scc_currencies"] = ' . json_encode(
            require_once( SCC_DIR . '/lib/currency_data.php' )
        );
        wp_add_inline_script( 'scc-frontend', $currencies_array ); */

        if ( ! function_exists( 'scc_currency_array_script' ) ) {
            function scc_currency_array_script() {
                $currencies_array = 'window["scc_currencies"] = ' . json_encode( require SCC_DIR . '/lib/currency_data.php' );
                echo '<script type="text/javascript">' . $currencies_array . '</script>';
            }
        }
        add_action( 'wp_footer', 'scc_currency_array_script' );

        ob_start();
        extract(
            shortcode_atts(
                [
                    'idvalue' => null,
                ],
                $atts,
                'bt_cc_item'
            )
        );

        if ( ! function_exists( 'get' ) ) {
            function get( $id ) {
                global $wpdb;
                $scc_form = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}df_scc_forms WHERE id =%d ;", $id ) );

                if ( $scc_form ) {
                    $scc_form->turnoffemailquote = true;
                    $scc_form->turnoffcoupon     = true;
                    $form_id                     = $scc_form->id;
                    $sections                    = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}df_scc_sections WHERE form_id =%d ORDER By `order`;", $form_id ) );
                    $scc_form->sections          = $sections;

                    foreach ( $sections as $section ) {
                        $section_id          = $section->id;
                        $subsection          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}df_scc_subsections WHERE section_id =%d ;", $section_id ) );
                        $section->subsection = $subsection;

                        foreach ( $section->subsection as $sub ) {
                            $sub_id       = $sub->id;
                            $elements     = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}df_scc_elements WHERE subsection_id =%d ORDER By orden +0; ", $sub_id ) );
                            $sub->element = $elements;

                            foreach ( $sub->element as $el2 ) {
                                $elem_id         = $el2->id;
                                $condition       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}df_scc_conditions WHERE element_id =%d ;", $elem_id ) );
                                $el2->conditions = $condition;

                                foreach ( $el2->conditions as $c ) {
                                    if ( $c->elementitem_id ) {
                                        $element             = $wpdb->get_row( $wpdb->prepare( "SELECT `name` FROM {$wpdb->prefix}df_scc_elementitems WHERE id =%d ;", $c->elementitem_id ) );
                                        $c->elementitem_name = $element;
                                    }

                                    if ( $c->condition_element_id ) {
                                        $element              = $wpdb->get_row( $wpdb->prepare( "SELECT `titleElement`,`type` FROM {$wpdb->prefix}df_scc_elements WHERE id =%d ;", $c->condition_element_id ) );
                                        $c->element_condition = $element;
                                    }
                                }
                            }

                            foreach ( $sub->element as $el ) {
                                $elem_id  = $el->id;
                                $elements = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}df_scc_elementitems WHERE element_id =%d ;", $elem_id ) );
                                // change 'price' property of element to zero if it is an empty string value, so it doesn't return javascript NaN value
                                $elements         = array_map(
                                    function ( $e ) {
                                        if ( $e->price == '' ) {
                                            $e->price = 0;
                                        }

                                        return $e;
                                    },
                                    $elements
                                );
                                $el->elementitems = $elements;
                            }
                        }
                    }

                    return $scc_form;
                }
            }
        }
        require SCC_DIR . '/lib/wp-google-fonts/google-fonts.php';
        $form = get( $idvalue );

        if ( ! $form ) {
            return "<h4 style='color:red'>Invalid calculator with ID {$atts['idvalue']}</h4>";
        }
        $form->showFieldsQuoteArray = json_decode( stripslashes( $form->showFieldsQuoteArray ), true );
        $allfonts2                  = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
        $allfonts2i                 = $allfonts2->items;
        $fontUsed2                  = ! empty( $form->titleFontType ) ? $allfonts2i[ $form->titleFontType ] : null;
        $fontUsed2Variant           = ( $form->titleFontWeight != '' ) ? $form->titleFontWeight : 'regular';
        $google_font_links          = [];
        /**
         *Title font
         */
        $fontFamilyService2 = 'inherit';
        $fontFamilyTitle2   = 'inherit';
        // always set font types to inherit on free copy
        $form->inheritFontType = 'true';

        if ( $form->inheritFontType == 'null' || $form->inheritFontType == 'false' ) {
            $fonts[0]['kind']     = $fontUsed2->kind;
            $fonts[0]['family']   = $fontUsed2->family;
            $fonts[0]['variants'] = [ $fontUsed2Variant ];
            $fonts[0]['subsets']  = $fontUsed2->subsets;
            $fontFamilyTitle2     = $fonts[0]['family'];
            $font_link            = $scc_googlefonts_var->style_late( $fonts );
            array_push( $google_font_links, $font_link ); //load google fonts css
        }
        /**
         *Service font
         */
        $allfonts3i       = $allfonts2->items;
        $fontUsed3        = ! empty( $form->fontType ) ? $allfonts3i[ $form->fontType ] : null;
        $fontUsed3Variant = ( $form->fontWeight != '' ) ? $form->fontWeight : 'regular';

        if ( $form->inheritFontType == 'null' || $form->inheritFontType == 'false' ) {
            $fonts2[0]['kind']     = $fontUsed3->kind;
            $fonts2[0]['family']   = $fontUsed3->family;
            $fonts2[0]['variants'] = [ $fontUsed3Variant ];
            $fonts2[0]['subsets']  = $fontUsed3->subsets;
            $fontFamilyService2    = $fonts2[0]['family'];
            $font_link             = $scc_googlefonts_var->style_late( $fonts );
            array_push( $google_font_links, $font_link ); //load google fonts css
        }
        /**
         *Object font
         */
        $colorObject                   = $form->objectColorPicker;
        $currency_style                = get_option( 'df_scc_currency_style', 'default' ); // dot or comma
        $currency                      = get_option( 'df_scc_currency', 'USD' );
        $currency_conversion_mode      = get_option( 'df_scc_currency_coversion_mode', 'off' );
        $currency_conversion_selection = get_option( 'df_scc_currency_coversion_manual_selection' );
        require SCC_DIR . '/admin/views/generateFrontendForm.php';

        return ob_get_clean();
    }
    /**
     * creates additional total tag placeholder, later populated by javascript
     *
     * @param mixed $attributes
     *
     * @return void
     */
    public function create_scc_total_tag( $attributes ) {
        // parse the attribute, if not supplied, default value is set
        $attributes = shortcode_atts(
            [
                'idvalue'         => 0,
                'combine'         => 0,
                'currency-symbol' => 0,
                'prefix-text'     => '',
                'apply-math'      => 0,
            ],
            $attributes
        );
        // $prefixText and $mathParams holds the array data to a variable
        $prefixText = $attributes['prefix-text'];
        $mathParams = $attributes['apply-math'] ? json_encode( explode( ':', $attributes['apply-math'] ) ) : '["add","0"]';
        // if combine attribute is there, idvalue does not work
        if ( $attributes['idvalue'] && ! ( $attributes['combine'] ) ) {
            $calculatorId = absint( $attributes['idvalue'] );
            $html         = "<span class=\"scc-multiple-total-wrapper calcid-$calculatorId\" data-math={$mathParams}>
    <span>$prefixText</span>
    <span class=\"multi-total-currency-prefix\"></span>
    <span class=\"scc-total\">0</span>
    <span class=\"multi-total-currency-suffix\"></span>
    </span>";
        }
        // if there is combine attribute, this html is printed
        if ( $attributes['combine'] ) {
            $ourFormula     = $attributes['combine'];
            $currencySymbol = isset( $attributes['currency-symbol'] ) ? absint( $attributes['currency-symbol'] ) : 1;
            $calcValues     = explode( ',', $ourFormula );

            if ( ! empty( $calcValues ) && count( $calcValues ) > 1 ) {
                $calcValues = json_encode( $calcValues );
                $html       = "<span class=\"scc-multiple-total-wrapper scc-combination\" data-combination={$calcValues} data-curr-sym={$currencySymbol} data-math={$mathParams}>
      <span>$prefixText</span>
      <span class=\"multi-total-currency-prefix\"></span>
      <span class=\"scc-total\"></span>
      <span class=\"multi-total-currency-suffix\"></span>
      </span>";
            }
        }

        return $html;
    }
    // START OF Hide Admin Notices At Top //
    public function scc_admin_hide_notices() {
        $exclusionPages = [ 'scc_edit_items', 'scc-diagnostics', 'scc-quote-management-screen', 'scc-tabs', 'scc-help', 'scc-license-help', 'scc-global-settings', 'scc-coupons-management', 'Stylish_Cost_Calculator_Migration', 'stylish_cost_calculator_premium_settings' ];

        if ( empty( $_REQUEST['page'] ) || ! in_array( $_REQUEST['page'], $exclusionPages ) ) {
            return;
        }
        global $wp_filter;

        foreach ( [ 'user_admin_notices', 'admin_notices', 'all_admin_notices' ] as $notices_type ) {
            if ( empty( $wp_filter[ $notices_type ]->callbacks ) || ! is_array( $wp_filter[ $notices_type ]->callbacks ) ) {
                continue;
            }

            foreach ( $wp_filter[ $notices_type ]->callbacks as $priority => $hooks ) {
                foreach ( $hooks as $name                                 => $arr ) {
                    if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
                        unset( $wp_filter[ $notices_type ]->callbacks[ $priority ][ $name ] );
                        continue;
                    }
                    $class = ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) ? strtolower( get_class( $arr['function'][0] ) ) : '';

                    if (
                        ! empty( $class ) &&
                        strpos( $class, 'scc' ) !== false
                    ) {
                        continue;
                    }

                    if (
                        ! empty( $class ) &&
                        strpos( $class, 'appsero' ) !== false
                    ) {
                        continue;
                    }

                    if (
                        ! empty( $name ) && (
                            strpos( $name, 'scc' ) === false
                        )
                    ) {
                        unset( $wp_filter[ $notices_type ]->callbacks[ $priority ][ $name ] );
                    }
                }
            }
        }
    }
    // END OF Hide Admin Notices At Top //
    // *register scripts style for calculator shortcode
    public function scc_register_shortcode_calculator() {
        wp_register_script( 'scc-bootstrap-min3', SCC_URL . 'lib/bootstrap/bootstrap.bundle.min.js', [ 'jquery' ], '5.1.3', false );

        wp_register_style( 'scc-admin-style', SCC_URL . 'assets/css/scc-front-end.css', [], STYLISH_COST_CALCULATOR_VERSION );
        wp_register_style( 'scc-checkbox1', SCC_URL . 'assets/css/checkboxes/checkboxes.css', [], STYLISH_COST_CALCULATOR_VERSION );
        wp_register_script( 'scc-tom-select', SCC_URL . 'lib/tom-select/tom-select.base.min.js', [], STYLISH_COST_CALCULATOR_VERSION, true );
        wp_register_style( 'scc-tom-select', SCC_URL . 'lib/tom-select/tom-select.css', [], STYLISH_COST_CALCULATOR_VERSION );
        wp_register_script( 'scc-frontend', SCC_URL . 'assets/js/scc-frontend.js', [ 'jquery', 'wp-util' ], STYLISH_COST_CALCULATOR_VERSION, true );
        wp_register_script( 'scc-bootstrapslider-js', SCC_URL . 'lib/bootstrap-slider/js/bootstrap-slider.js', [ 'jquery' ], STYLISH_COST_CALCULATOR_VERSION, false );
        wp_register_script( 'scc-translate-js', SCC_URL . 'lib/translate/jquery.translate.js', [ 'jquery' ], STYLISH_COST_CALCULATOR_VERSION, false );
        wp_register_script( 'scc-nouislider', SCC_URL . 'lib/nouislider/nouislider.min.js', [], STYLISH_COST_CALCULATOR_VERSION );

        if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
            wp_enqueue_script( 'jquery' );
        }

        if ( is_admin() && get_current_screen()->base == 'stylish-cost-calculator_page_scc_edit_items' ) {
        }
    }
    public function admin_menu() {
        if ( ! class_exists( 'migrateController' ) ) {
            require __DIR__ . '/admin/controllers/migrateController.php';
        }
        $i = new migrateController();
        SCC_Notifications_Cron::schedule_cron_event();
        add_menu_page( __( 'Stylish Cost Calculator', 'scc' ), __( 'Stylish Cost Calculator', 'scc' ), 'manage_options', 'scc-tabs', 'ssc_test_data', SCC_URL . '/assets/images/scc_dashicon.png', null );
        // ADD PAGE
        add_submenu_page( 'scc-tabs', 'Add New', 'Add New', 'manage_options', 'scc-tabs', 'ssc_test_data' );
        // EDIT PAGE
        add_submenu_page( 'scc-tabs', 'All Calculator Forms', 'All Calculator Forms ', 'manage_options', 'scc_edit_items', 'ssc_test_data', null );

        // HELP AND VIDEOS
        add_submenu_page( 'scc-tabs', 'Help & Videos', 'Help & Tutorials', 'manage_options', 'scc-help', 'ssc_test_data', null );
        // MEMBERS
        add_submenu_page( 'scc-tabs', 'Members', 'Members Portal', 'manage_options', 'scc-license-help', 'ssc_test_data', null );
        // COUPON
        add_submenu_page( 'scc-tabs', 'Coupon', 'Coupon Codes', 'manage_options', 'scc-coupons-management', 'ssc_test_data', null );

        // QUOTE FOR CALCULATOR
        add_submenu_page( '', 'Quote Viewer', null, 'manage_options', 'scc-quote-management-screen', 'ssc_test_data', null );
        // DIAGNOSTICS
        add_submenu_page( 'scc-tabs', 'Diagnostics', 'Diag & Sys Info', 'manage_options', 'scc-diagnostics', 'ssc_test_data', null );
        //COUPON
        add_submenu_page( '', 'Quote Viewer', null, 'manage_options', 'scc-coupons-management', 'ssc_test_data', null );
        // GLOBAL SETTINGS
        add_submenu_page( 'scc-tabs', 'Global Settings', 'Global Settings', 'manage_options', 'scc-global-settings', 'ssc_test_data', null );
        // Uncomment to use migration page
        add_submenu_page( '', 'Migçrate your database', 'Migrate', 'manage_options', 'Stylish_Cost_Calculator_Migration', 'ssc_test_data', null );
        function ssc_test_data() {
            // $template = dirname(__DIR__,1) . '/formController.php';
            $template = __DIR__ . '/admin/controllers/dealer.php';
            // if (file_exists($template)) {
            require $template;
            // }
        }
    }
    public function post_upgrade_tasks( $upgrader_object, $options ) {
        $current_plugin_path_name = plugin_basename( __FILE__ );

        if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
            foreach ( $options['plugins'] as $each_plugin ) {
                if ( $each_plugin == $current_plugin_path_name ) {
                    // ensure cron schedulers
                    if ( ! class_exists( 'SCC_Notifications_Cron' ) ) {
                        require plugin_dir_path( __FILE__ ) . '/cron/notifications.php';
                    }
                    $this->scc_alter_tables();
                    SCC_Notifications_Cron::schedule_cron_event();
                }
            }
        }
    }
    /**
     *Ads wp_options
     *
     * @param scc_currency
     * @param scc_currencytext
     * @param scc_currency_style
     * @param scc_currency_coversion_mode
     * @param scc_currency_coversion_manual_selection
     * todo: scc_currency_style (default, comma)
     * todo: scc_currency_conversion_mode (manual_selection,auto_detect,off)
     */
    public function scc_wpoption_add() {
        add_option( 'df_scc_currency', 'USD' );
        add_option( 'df_scc_currencytext', 'U.S. Dollar' );
        add_option( 'df_scc_currency_style', 'default' );
        add_option( 'df_scc_currency_coversion_mode', 'off' );
        add_option( 'df_scc_currency_coversion_manual_selection', 'CAD' );
    }
    public function my_plugin_action_links( $links ) {
        $links = array_merge(
            [
                '<a href="' . admin_url( 'admin.php' ) . '?page=scc-tabs' . '">' . __( 'Add Calculator', 'textdomain' ) . '</a>',
                '<a href="' . admin_url( 'admin.php' ) . '?page=scc_edit_items' . '">' . __( 'Edit Existing', 'textdomain' ) . '</a>',
                '<a href="https://stylishcostcalculator.com/?utm_source=inside-plugin&utm_medium=wordpress&utm_content=buy-premium-cta-banner">Buy Now</a>',
                '<a target="_blank" href="https://stylishcostcalculator.com/">' . __( 'Website', 'textdomain' ) . '</a>',
                '<a target="_blank" href="https://stylishcostcalculator.com/support">' . __( 'Support', 'textdomain' ) . '</a>',
                '<a href="' . admin_url( 'admin.php' ) . '?page=scc-global-settings' . '">' . __( 'Global Settings', 'textdomain' ) . '</a>',
            ],
            $links
        );

        return $links;
    }
}
new df_scc_plugin();
