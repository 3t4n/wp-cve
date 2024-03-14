<?php

/**
 * WC_Settings_Page_iZettle
 *
 * @class           WC_Settings_Page_iZettle
 * @since           1.0.0
 * @package         WC_iZettle_Integration
 * @category        Class
 * @author          BjornTech
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_Settings_Page_iZettle', false)) {

    class WC_Settings_Page_iZettle extends WC_Settings_Page
    {

        public function __construct()
        {
            $this->id = 'izettle';
            $this->label = __('Zettle Integration', 'woo-izettle-integration');

            add_action('woocommerce_settings_izettle_stocklevel_from_izettle_options_manual', array($this, 'show_izettle_stocklevel_from_izettle_options_manual_button'));
            add_action('woocommerce_settings_izettle_manual_products_options', array($this, 'show_sync_wc_products_button'));
            add_action('woocommerce_settings_izettle_connection_options', array($this, 'show_connection_button'));
            add_action('woocommerce_settings_izettle_advanced_options', array($this, 'show_clear_products_button'), 21);
            add_action('woocommerce_settings_izettle_advanced_options', array($this, 'show_force_token_button'), 20);
            add_action('woocommerce_settings_izettle_products_from_iz', array($this, 'show_izettle_product_button'));

            add_action('woocommerce_settings_izettle_stocklevel_from_woocommerce_options', array($this, 'izettle_stocklevel_from_woocommerce_options_description'));
            add_action('woocommerce_settings_izettle_stocklevel_from_izettle_options', array($this, 'izettle_stocklevel_from_izettle_options_description'));
            add_action('woocommerce_settings_izettle_products_to_izettle_options', array($this, 'izettle_products_to_izettle_options_description'));
            add_action('woocommerce_settings_izettle_products_from_izettle_options', array($this, 'izettle_products_from_izettle_options_description'));
            add_action('woocommerce_settings_izettle_connection_options', array($this, 'izettle_connection_options_description'));
            add_action('woocommerce_settings_izettle_advanced_options', array($this, 'izettle_advanced_options_description'), 10);

            add_action('izettle_show_connection_status', array($this, 'show_connection_status'));

            add_filter('woocommerce_get_sections_' . $this->id, array($this, 'get_izettle_sections'));
            add_filter('woocommerce_get_settings_' . $this->id, array($this, 'get_izettle_settings'));
            add_action('woocommerce_settings_' . $this->id, array($this, 'authorize_processing'), 5);
            add_action('woocommerce_settings_save_' . $this->id, array($this, 'force_connection'), 100);

            add_action('izettle_update_settings', array($this, 'update_settings'));

            parent::__construct();
        }

        /**
         * Get sections.
         *
         * @since 6.0.0
         *
         * @param array $sections
         *
         * @return array
         */
        public function get_izettle_sections($sections)
        {

            $sections = array_merge($sections, array(
                '' => __('General settings', 'woo-izettle-integration'),
                'product_sync' => __('Products to Zettle', 'woo-izettle-integration'),
                'products_from_iz' => __('Products from Zettle', 'woo-izettle-integration'),
                'izettle_stocklevel_from_izettle_options' => __('Zettle Purchases', 'woo-izettle-integration'),
                'advanced' => __('Advanced options', 'woo-izettle-integration'),
            ));

            return $sections;
        }

        /**
         * Force connection.
         *
         * @since 6.0.0
         */

        public function force_connection()
        {
            try {
                do_action('izettle_force_connection');
            } catch (IZ_Integration_API_Exception $e) {
                WC_IZ()->logger->add('Connection was forced but failed');
            }
        }

        /**
         * Handles the callback from the cloudservice when the customer did authorize the client
         *
         * @since 1.0.0
         */
        public function authorize_processing()
        {

            if (array_key_exists('organization_uuid', $_REQUEST)) {
                do_action('izettle_update_settings');

                IZ_Notice::clear();
                izettle_api()->set_valid_to($_REQUEST['valid_to']);
                izettle_api()->set_last_synced($_REQUEST['last_synced']);
                izettle_api()->set_organization_uuid($_REQUEST['organization_uuid']);
                izettle_api()->set_access_token('');
                izettle_api()->set_expires_in(0); // Forcing the client to connect again
                izettle_api()->set_is_trial($_REQUEST['is_trial']);
                try {
                    do_action('izettle_force_connection');
                    $message = sprintf(__('<strong>Congratulations!</strong> Your plugin was successfully connected to Zettle.', 'woo-izettle-integration'));
                    IZ_Notice::add($message, 'info');
                    WC_IZ()->logger->add(sprintf('Succcessfully authorized, organization uuid is %s', $_REQUEST['organization_uuid']));
                    return;
                } catch (IZ_Integration_API_Exception $e) {
                    $e->write_to_logs();
                    $error = $e->getMessage();
                }
            }

            if (array_key_exists('error', $_REQUEST)) {
                $error = 'unknown';
                if (is_array($_REQUEST['error'])) {
                    $error = implode(' - ', $_REQUEST['error']);
                } else {
                    $error = $_REQUEST['error'];
                }
                WC_IZ()->logger->add(sprintf('Error "%s" when connecting to Zettle', $error));

                IZ_Notice::add(
                    sprintf(__('Something went wrong when trying to connect the plugin to your Zettle account, contact hello@bjorntech.com for assistance', 'woo-izettle-integration')),
                    'error'
                );
            }
        }

        /**
         * Displays help text for stocklevel handling settings
         *
         * @since 6.0.0
         */

        public function izettle_stocklevel_from_woocommerce_options_description()
        {
            require_once 'views/html-admin-settings-stocklevel-from-woocommerce-options-desc.php';
        }

        public function izettle_stocklevel_from_izettle_options_description()
        {
            require_once 'views/html-admin-settings-stocklevel-from-izettle-options-desc.php';
        }

        public function izettle_products_to_izettle_options_description()
        {
            require_once 'views/html-admin-settings-products-to-izettle-options-desc.php';
        }

        public function izettle_products_from_izettle_options_description()
        {
            require_once 'views/html-admin-settings-products-from-izettle-options-desc.php';
        }

        public function izettle_connection_options_description()
        {
            require_once 'views/html-admin-settings-connection-options-desc.php';
        }

        public function izettle_advanced_options_description()
        {
            require_once 'views/html-admin-settings-advanced-options-desc.php';
        }

        public function show_connection_status()
        {

            $offset = get_option('gmt_offset') * HOUR_IN_SECONDS;
            $connection_status = apply_filters('izettle_connection_status', '');
            $valid_to = izettle_api()->get_valid_to() + $offset;
            $expires_in = izettle_api()->get_expires_in() + $offset;
            $next_sync = izettle_api()->get_last_synced() + WEEK_IN_SECONDS + $offset;

            echo '<div>';

            if ($connection_status == 'unauthorized') {
                echo '<p>' . __('Enter an address to where the confirmation e-mail should be sent and give the plugin access to your Zettle account by pressing <b>Authorize</b>', 'woo-izettle-integration') . '</p>';
                echo '<p>' . sprintf(__('When authorizing you agree to the BjornTech %s', 'woo-izettle-integration'), sprintf('<a href="https://www.bjorntech.com/privacy-policy/?utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product" target="_blank" rel="noopener">%s</a>', __('privacy policy', 'woo-izettle-integration'))) . '</p>';
            } elseif ($connection_status == 'expired') {
                echo '<p>' . sprintf(__('This plugin is authorized with Zettle but your subscription expired %s. You can buy a subscription from our webshop <a href="%s">here</a>', 'woo-izettle-integration'), date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $valid_to), 'https://www.bjorntech.com/product/woocommerce-izettle-integration-automatic-sync/?token=' . izettle_api()->get_organization_uuid()) . '</p>';
            } elseif ($connection_status == 'trial') {
                echo '<p>' . sprintf(__('<strong>Congratulations!</strong> This plugin is authorized with Zettle and your trial is valid until %s. Once the trial is over you need to buy a subscription in our <a href="%s">webshop</a>.', 'woo-izettle-integration'), date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $valid_to), 'https://www.bjorntech.com/product/woocommerce-izettle-integration-automatic-sync/?token=' . izettle_api()->get_organization_uuid()) . '</p>';
            } else {
                echo '<p>' . sprintf(__('This plugin has been authorized with Zettle. Your BjornTech sync account is valid until %s', 'woo-izettle-integration'), date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $valid_to)) . '</p>';
            }

            if ($connection_status == 'trial') {
                echo '<p>' . sprintf(__('To continue using the automatic sync when the trial has ended go to our <a href="%s">webshop</a> to purchase a subsription', 'woo-izettle-integration'), 'https://www.bjorntech.com/product/woocommerce-izettle-integration-automatic-sync/?token=' . izettle_api()->get_organization_uuid() . '&utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product') . '</p>';
            }

            if ($connection_status != 'unauthorized') {
                $token = izettle_api()->get_organization_uuid();
                echo '<p>' . sprintf(__('When communicating with BjornTech always refer to this installation id: %s', 'woo-izettle-integration'), $token) . '</p>';
            }

            echo '</div>';
        }

        public function show_connection_button()
        {
            echo '<tr valign="top">';
            echo '<th scope="row" class="titledesc">';
            echo '<label for="wciz_authorize">' . __('Authorize with Zettle', 'woo-izettle-integration') . '<span class="woocommerce-help-tip" data-tip="Authorize the plugin to get and write products/stock and to read purchases"></span></label>';
            echo '</th>';
            echo '<td class="forminp forminp-button">';
            echo '<button name="wciz_authorize" id="wciz_authorize" class="button">' . __('Authorize', 'woo-izettle-integration') . '</button>';
            echo '</td>';
            echo '</tr>';
        }

        public function show_clear_products_button()
        {
            if ('yes' === get_option('izettle_extra_admin')) {
                echo '<tr valign="top">';
                echo '<th scope="row" class="titledesc">';
                echo '<label for="wciz_clear_products">' . __('Clear products data', 'woo-izettle-integration') . '<span class="woocommerce-help-tip" data-tip="' . __('Clears Zettle metadata on all products', 'woo-izettle-integration') . '"></span></label>';
                echo '</th>';
                echo '<td class="forminp forminp-button">';
                echo '<button name="wciz_clear_products" id="wciz_clear_products" class="button">' . __('Clear', 'woo-izettle-integration') . '</button>';
                echo '</td>';
                echo '</tr>';
            }
        }

        public function show_force_token_button()
        {
            echo '<tr valign="top">';
            echo '<th scope="row" class="titledesc">';
            echo '<label for="wciz_tokenchange">' . __('Refresh connection', 'woo-izettle-integration') . '<span class="woocommerce-help-tip" data-tip="' . __('Request new token from service', 'woo-izettle-integration') . '"></span></label>';
            echo '</th>';
            echo '<td class="forminp forminp-button">';
            echo '<button name="wciz_tokenchange" id="wciz_tokenchange" class="button">' . __('Refresh', 'woo-izettle-integration') . '</button>';
            echo '</td>';
            echo '</tr>';
        }

        public function show_izettle_stocklevel_from_izettle_options_manual_button()
        {

            WC_Zettle_Helper::display_sync_button('wciz_sync_iz_purchases', 'wciz_stocklevel_obj wciz_stocklevel_obj_purchases_show');
        }

        public function show_izettle_product_button()
        {

            WC_Zettle_Helper::display_sync_button('wciz_sync_iz_products');
        }

        public function show_sync_wc_products_button()
        {

            WC_Zettle_Helper::display_sync_button('wciz_sync_wc_products');
        }

        public function get_post_statuses()
        {
            $statuses = get_post_statuses();
            $statuses['future'] = __('Scheduled', 'woocommerce');
            return $statuses;
        }

        public function get_product_types()
        {
            $types = wc_get_product_types();
            if (isset($types['grouped'])) {
                unset($types['grouped']);
            }
            if (isset($types['external'])) {
                unset($types['external']);
            }
            return $types;
        }

        public function update_settings()
        {
            if (!(($org_uuid = izettle_api()->get_organization_uuid()) && isset($org_uuid))) {

                WC_IZ()->logger->add('Updated to the new ecoding settings');
                update_option('izettle_use_advanced_encoding', 'yes');

                WC_IZ()->logger->add('Updated to the new US tax settings');
                update_option('izettle_use_new_us_tax_settings', 'yes');

                WC_IZ()->logger->add('Adding new option to delete variants');
                update_option('izettle_delete_variants', 'yes');

                WC_IZ()->logger->add('Update webhook priotity to 9');
                update_option('izettle_webhook_priority', '9');

                WC_IZ()->logger->add('Enable logging');
                update_option('izettle_logging', 'yes');

                //zettle_product_category_export_filter_v2
                WC_IZ()->logger->add('Adding new option to export product categories');
                update_option('zettle_product_category_export_filter_v2', 'yes');

                //zettle_force_change_stocklevel_in_woocommerce
                WC_IZ()->logger->add('Adding new option to force change stocklevel in WooCommerce');
                update_option('zettle_force_change_stocklevel_in_woocommerce', 'yes');

                //Trigger stock notification emails
                WC_IZ()->logger->add('Adding new option to trigger stock notification emails');
                update_option('izettle_trigger_stock_notification_emails', 'yes');

                //zettle_save_post_on_stockchange
                WC_IZ()->logger->add('Adding new option to save post on stockchange');
                update_option('zettle_save_post_on_stockchange', 'yes');

                //Trigger WP Post to save on product creation/update
                WC_IZ()->logger->add('Adding new option to trigger WP Post to save on product creation/update');
                update_option('zettle_save_post_on_order', 'yes');

                //_sale_special - izettle_product_pricelist
                WC_IZ()->logger->add('Adding new option to set price from Zettle tab');
                update_option('izettle_product_pricelist', '_sale_special');

                //zettle_hide_double_import_options
                WC_IZ()->logger->add('Adding new option to hide double import options');
                update_option('zettle_hide_double_import_options', 'yes');
            }
        }

        /**
         * Builds the settings page. Settings pages are also present in the different handlers
         *
         * @since 1.0.0
         */
        public function get_izettle_settings()
        {

            global $current_section;

            $category_options = array();

            $product_categories = WC_Zettle_Helper::get_product_categories();

            if (!empty($product_categories)) {
                foreach ($product_categories as $category) {
                    $category_options[$category->slug] = $category->name;
                }
            }

            $pricelists = array(
                '' => __('Regular price', 'woocommerce'),
                '_sale' => __('Sale price if available - Regular if not', 'woo-izettle-integration'),
                '_special' => __('Zettle price from the Zettle tab if available - Regular if not', 'woo-izettle-integration'),
                '_sale_special' => __('Zettle price from Zettle tab if available - Sale price if not - Regular price if not', 'woo-izettle-integration'),
                '_no_price' => __('Do not set the price', 'woo-izettle-integration'),
            );

            $fortnox_pricelists = apply_filters('fortnox_get_pricelist', array());

            $purchase_functions = array(
                '' => __('No download action, just download the purchase', 'woo-izettle-integration'),
                'wc_order' => __('Create a WooCommerce order based on the purchase', 'woo-izettle-integration'),
                'wc_stockchange' => __('Change WooCommerce stocklevels based on the purchase', 'woo-izettle-integration'),
            );

            if ((get_option('zettle_hide_double_import_options') == 'yes') && get_option('izettle_import_stocklevel') == 'yes') {
                unset($purchase_functions['wc_stockchange']);
            }

            if (!empty($fortnox_pricelists)) {

                foreach ($fortnox_pricelists['PriceLists'] as $fortnox_pricelist) {
                    $lists['fortnox_' . $fortnox_pricelist['Code']] = __('Fortnox pricelist', 'woo-izettle-integration') . ' ' . $fortnox_pricelist['Description'];
                }
                $pricelists = array_merge($pricelists, $lists);

                $purchase_functions['fortnox'] = __('Change stocklevel in Fortnox', 'woo-izettle-integration');
            }

            if (class_exists('WC_Product_Price_Based_Country', false)) {
                $zones = WCPBC_Pricing_Zones::get_zones();
                if ($zones) {
                    foreach ($zones as $zone) {
                        $wpbc_lists['wcpbc_' . WC_Zettle_Helper::get_wcpbc_pricing_zone_id($zone)] = __('Pricing zone', 'woo-izettle-integration') . ' ' . $zone->get_name();
                    }
                    $pricelists = array_merge($pricelists, $wpbc_lists);
                }
            }

            if ('izettle_stocklevel_from_izettle_options' == $current_section) {
                $settings[] = [
                    'title' => __('Process Zettle purchases', 'woo-izettle-integration'),
                    'type' => 'title',
                    'id' => 'izettle_stocklevel_from_izettle_options',
                ];
                $settings[] = [
                    'title' => __('Enable purchase processing', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('', 'woo-izettle-integration'),
                    'id' => 'zettle_enable_purchase_processing',
                    'default' => '',
                ];
                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_stocklevel_from_izettle_options',
                ];

                if ('yes' === get_option('zettle_enable_purchase_processing')) {

                    $settings[] = [
                        'type' => 'title',
                        'id' => 'izettle_stocklevel_from_izettle_options_manual',
                    ];
                    $settings[] = [
                        'title' => __('Manual download start date', 'woo-izettle-integration'),
                        'type' => 'date',
                        'default' => '',
                        'desc' => __('Select a date from where the manual download will start. If no date is selected the download will be a week back in time from now.', 'woo-izettle-integration'),
                        'id' => 'izettle_purchase_startdate',
                    ];
                    $settings[] = [
                        'type' => 'sectionend',
                        'id' => 'izettle_stocklevel_from_izettle_options_manual',
                    ];
                    $settings[] = [
                        'type' => 'title',
                        'id' => 'izettle_stocklevel_from_izettle_options_automatic',
                    ];
                    $settings[] = [
                        'title' => __('Automatic download', 'woo-izettle-integration'),
                        'type' => 'select',
                        'default' => '',
                        'desc' => __('Download purchases from Zettle. When a purchase is downloaded you can automatically or manually let the purchase affect the stocklevels of the products included in the purchase.', 'woo-izettle-integration'),
                        'id' => 'izettle_purchase_sync_model',
                        'options' => array(
                            '' => __('No automatic download', 'woo-izettle-integration'),
                            1440 => __('Once every day (06:00 local time)', 'woo-izettle-integration'),
                            60 => __('Every hour', 'woo-izettle-integration'),
                            15 => __('Every 15 minutes', 'woo-izettle-integration'),
                            5 => __('Every 5 minutes', 'woo-izettle-integration'),
                            1 => __('When created in Zettle', 'woo-izettle-integration'),
                        ),
                    ];
                    $settings[] = [
                        'type' => 'sectionend',
                        'id' => 'izettle_stocklevel_from_izettle_options_automatic',
                    ];
                    $settings[] = [
                        'type' => 'title',
                        'id' => 'izettle_stocklevel_from_izettle_options_action',
                    ];
                    $settings[] = [
                        'title' => __('Download action', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Select the action to be performed when a purchase is downloaded.', 'woo-izettle-integration'),
                        'id' => 'izettle_purchase_sync_function',
                        'default' => '',
                        'options' => $purchase_functions,
                    ];
                }

                if ('yes' === get_option('zettle_enable_purchase_processing') && 'wc_order' === get_option('izettle_purchase_sync_function')) {

                    $settings[] = [
                        'title' => __('Customer email on order', 'woo-izettle-integration'),
                        'type' => 'email',
                        'default' => '',
                        'desc' => __('The e-mail to be used on orders when "Create a WooCommerce order based on the purchase" is selected above. If left blank the system is using the admin e-mail', 'woo-izettle-integration'),
                        'id' => 'izettle_order_email',
                    ];
                    $settings[] = [
                        'title' => __('Customer number on order', 'woo-izettle-integration'),
                        'type' => 'number',
                        'default' => 0,
                        'desc' => __('The customer number used when creating orders from purchases. If set to 0 the order will be created on a "not logged in" customer', 'woo-izettle-integration'),
                        'id' => 'izettle_order_customer',
                    ];
                    $settings[] = [
                        'title' => __('Order status', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('When a WooCommerce order is created it should be set to this status.', 'woo-izettle-integration'),
                        'id' => 'izettle_set_order_to_status',
                        'default' => 'wc-completed',
                        'options' => wc_get_order_statuses(),
                    ];
                }
                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_stocklevel_from_izettle_options_action',
                ];
            } elseif ('product_sync' == $current_section) {

                $settings[] = [
                    'title' => __('Products to Zettle', 'woo-izettle-integration'),
                    'type' => 'title',
                    'id' => 'izettle_products_to_izettle_options',
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_products_to_izettle_options',
                ];

                $settings[] = [
                    'title' => __('Manual export', 'woo-izettle-integration'),
                    'type' => 'title',
                    'desc' => __('Start the manual export of products from WooCommerce to Zettle by pressing the <b>Start</b> button. When exporting a large number of products it can take some time until export is confirmed by a message.', 'woo-izettle-integration'),
                    'id' => 'izettle_manual_products_options',
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_manual_products_options',
                ];

                $settings[] = [
                    'title' => __('Automatic export', 'woo-izettle-integration'),
                    'type' => 'title',
                    'desc' => __('Select the speed of the automatic product export.', 'woo-izettle-integration'),
                    'id' => 'izettle_automatic_products_options',
                ];

                $settings[] = [
                    'title' => __('Export speed', 'woo-izettle-integration'),
                    'type' => 'select',
                    'default' => '',
                    'desc' => __('Select if and how often you want your WooCommerce products to be automatically created/updated in Zettle', 'woo-izettle-integration'),
                    'id' => 'izettle_product_sync_model',
                    'options' => array(
                        '' => __('Manually', 'woo-izettle-integration'),
                        1 => __('When changed in WooCommerce (recommended)', 'woo-izettle-integration'),
                        1440 => __('Once every day (06:00 local time)', 'woo-izettle-integration'),
                        60 => __('Every hour', 'woo-izettle-integration'),
                        15 => __('Every 15 minutes', 'woo-izettle-integration'),
                        5 => __('Every 5 minutes', 'woo-izettle-integration'),
                    ),
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_automatic_products_options',
                ];

                $settings[] = [
                    'title' => __('Data to export', 'woo-izettle-integration'),
                    'type' => 'title',
                    'desc' => __('The product sync creates a new set of products based on the products in WooCommerce, a product can be excluded from Zettle by checking the "exclude from Zettle", found at the product', 'woo-izettle-integration'),
                    'id' => 'izettle_products_options',
                ];

                $settings[] = [
                    'title' => __('Price', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Select the pricelist to be used when exporting price to Zettle', 'woo-izettle-integration'),
                    'id' => 'izettle_product_pricelist',
                    'default' => '',
                    'options' => $pricelists,
                ];

                $settings[] = [
                    'title' => __('Cost price', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Set the cost price from the Zettle tab on the product in Zettle', 'woo-izettle-integration'),
                    'default' => 'yes',
                    'id' => 'izettle_product_cost_price',
                ];

                $settings[] = [
                    'title' => __('Category', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Set the first category of a WooCommerce product as category on the Zettle product', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_update_category_on_izproduct',
                ];

                $settings[] = [
                    'title' => __('Update SKU', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Update the SKU in Zettle from WooCommerce.', 'woo-izettle-integration'),
                    'default' => 'yes',
                    'id' => 'zettle_update_sku',
                ];

                $settings[] = [
                    'title' => __('Add SKU to product name', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Add SKU to the end of the Zettle product name.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_add_sku_to_name',
                ];

                //izettle_put_sku_first

                $settings[] = [
                    'title' => __('Product tax rate', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Select how to set tax rate on Zettle products created from the plugin', 'woo-izettle-integration'),
                    'id' => 'izettle_handle_tax_rate',
                    'default' => '',
                    'options' => array(
                        '' => __('Let WooCommerce settings decide', 'woo-izettle-integration'),
                        'set_to_zero' => __('Always set tax rate to 0', 'woo-izettle-integration'),
                        'never_update' => __('Never update tax rate', 'woo-izettle-integration'),
                    ),
                ];

                $barcode_options = array(
                    '' => __('Do nothing', 'woo-izettle-integration'),
                    '_barcode' => __('Copy the barcode field from WooCommerce to Zettle', 'woo-izettle-integration'),
                    '_sku' => __('Copy the sku-field in WooCommerce to Zettle', 'woo-izettle-integration'),
                    '_meta' => __('Use a configurable metadata field', 'woo-izettle-integration'),
                );

                if (class_exists('WPM_Product_GTIN_WC')) {
                    $barcode_options['_wpm_gtin_code'] = __('Copy the barcode from "Product GTIN (EAN, UPC, ISBN) for WooCommerce"', 'woo-izettle-integration');
                }

                if (class_exists('WPSEO_WooCommerce_Schema')) {
                    $barcode_options['_yoast'] = __('Copy the barcode from the Yoast EAN field', 'woo-izettle-integration');
                }

                $settings[] = [
                    'title' => __('Barcode', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Select if and how to update the Zettle barcode field', 'woo-izettle-integration'),
                    'default' => '',
                    'options' => $barcode_options,
                    'id' => 'izettle_product_update_barcode',
                ];

                if ('_meta' == get_option('izettle_product_update_barcode')) {
                    $settings[] = [
                        'title' => __('Barcode meta', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('Enter the name of the product metadata field that should be used if selecting "Use a configurable metadata field" above.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_product_barcode_meta',
                    ];
                }

                $settings[] = [
                    'title' => __('Generate Barcode', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Choose to generate Barcode automatically on all products or manually in the product page.', 'woo-izettle-integration'),
                    'id' => 'izettle_product_barcode_generate',
                    'default' => '',
                    'options' => array(
                        '' => __('Do not generate barcode', 'woo-izettle-integration'),
                        'ean13_automatic' => __('Generate EAN13 Barcode automatically.', 'woo-izettle-integration'),
                        'ean13_manual' => __('Generate EAN13 Barcode manually.', 'woo-izettle-integration'),
                    ),
                ];

                if (get_option('izettle_product_barcode_generate')) {

                    $settings[] = [
                        'title' => __('EAN-13 Country code', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('A three digit Country code to use for the EAN-13 barcode or use the default 029 included in the "GS1 internal use" range of 020-029', 'woo-izettle-integration'),
                        'default' => '029',
                        'id' => 'izettle_product_barcode_country',
                    ];

                    $settings[] = [
                        'title' => __('EAN-13 Company code', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('A five digit Company code. If you have an official GS1 company code, enter this one. If not, leave blank', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_product_barcode_company',
                    ];

                    $settings[] = [
                        'title' => __('EAN-13 id', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Product-id is the preferred method. Use SKU only if you have SKU set on all products (and if it is numerical). Using SKU disconnects the Zettle products from the physical WooCommerce installation, something that can be useful if using barcode more widely.', 'woo-izettle-integration'),
                        'id' => 'izettle_product_barcode_identifier',
                        'default' => '',
                        'options' => array(
                            '' => __('Use product-id (recommended).', 'woo-izettle-integration'),
                            'sku' => __('Use SKU (all products and variants MUST have a nunerical value in the SKU field).', 'woo-izettle-integration'),
                        ),
                    ];
                }

                $settings[] = [
                    'title' => __('Stocklevel', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'default' => '',
                    'desc' => __('Check if you do want to overwrite the Zettle stocklevel with the one from WooCommerce', 'woo-izettle-integration'),
                    'id' => 'izettle_stocklevel_from_woocommerce',
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_products_options',
                ];

                $settings[] = [
                    'title' => __('Filter products to export', 'woo-izettle-integration'),
                    'type' => 'title',
                    'desc' => __('Select the products to be exported by selecting in the filters below.', 'woo-izettle-integration'),
                    'id' => 'izettle_products_filter',
                ];

                $settings[] = [
                    'title' => __('Include product categories', 'woo-izettle-integration'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select',
                    'css' => 'width: 400px;',
                    'id' => 'izettle_product_categories',
                    'default' => '',
                    'desc' => __('If you only want to export products included in certain product categories, select them here. Leave blank to include all categories.', 'woo-izettle-integration'),
                    'options' => $category_options,
                    'custom_attributes' => array(
                        'data-placeholder' => __('Select product categories or leave empty for all', 'woo-izettle-integration'),
                    ),
                ];

                //Exclude categories
                $settings[] = [
                    'title' => __('Exclude product categories', 'woo-izettle-integration'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select',
                    'css' => 'width: 400px;',
                    'id' => 'izettle_exclude_product_categories',
                    'default' => '',
                    'desc' => __('If you want to exclude products from certain product categories in the export, select them here. Leave blank to include all categories.', 'woo-izettle-integration'),
                    'options' => $category_options,
                    'custom_attributes' => array(
                        'data-placeholder' => __('Select product categories or leave empty for all', 'woo-izettle-integration'),
                    ),
                ];

                $settings[] = [
                    'title' => __('Include product types', 'woo-izettle-integration'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select',
                    'css' => 'width: 400px;',
                    'desc' => __('Select the type of product to be included in the product update', 'woo-izettle-integration'),
                    'default' => array('simple', 'variable'),
                    'options' => $this->get_product_types(),
                    'id' => 'izettle_products_include',
                ];

                $settings[] = [
                    'title' => __('Include product status', 'woo-izettle-integration'),
                    'type' => 'multiselect',
                    'class' => 'wc-enhanced-select',
                    'css' => 'width: 400px;',
                    'id' => 'izettle_product_status',
                    'default' => '',
                    'desc' => __('If you only want to sync products with a certain product status, select them here. Leave blank to sync all regardless of status.', 'woo-izettle-integration'),
                    'options' => $this->get_post_statuses(),
                    'custom_attributes' => array(
                        'data-placeholder' => __('Select product statuses to include when exporting products. Leave blank for all', 'woo-izettle-integration'),
                    ),
                ];

                $settings[] = [
                    'title' => __('Products in stock', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Include products in stock only. If you want previously synced products to be removed when out of stock, select "Delete products in Zettle" below as well.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_sync_in_stock_only',
                ];

                $settings[] = [
                    'title' => __('Delete products in Zettle', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Delete products in Zettle if they no longer exists in WooCommerce', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_delete_izettle_products',
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_products_filter',
                ];
            } elseif ('products_from_iz' == $current_section) {

                $zettle_categories = [];

                $zettle_current_categories = [];

                try {
                    $zettle_current_categories = WC_Zettle_Helper::retrieve_zettle_categories();
                } catch (Exception $e) {
                    WC_IZ()->logger->add('Error retrieving Zettle categories: ' . $e->getMessage());
                }

                if (!is_null($zettle_current_categories) && $zettle_current_categories && is_array($zettle_current_categories)) {
                    foreach ($zettle_current_categories as $zettle_category) {
                        $zettle_categories[$zettle_category->uuid] = $zettle_category->name;
                    }
                }

                if (empty($zettle_categories)) {
                    $zettle_categories[''] = __('No categories found', 'woo-izettle-integration');
                }

                $settings = array(
                    array(
                        'title' => __('Products from Zettle', 'woo-izettle-integration'),
                        'type' => 'title',
                        'id' => 'izettle_products_from_izettle_options',
                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'izettle_products_from_izettle_options',
                    ),
                    array(
                        'title' => __('Manual import', 'woo-izettle-integration'),
                        'type' => 'title',
                        'desc' => __('Start the manual import of products from Zettle to WooCommerce by pressing the <b>Start</b> button.', 'woo-izettle-integration'),
                        'id' => 'izettle_products_from_iz',
                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'izettle_products_from_iz',
                    ),
                    array(
                        'title' => __('Automatic import', 'woo-izettle-integration'),
                        'type' => 'title',
                        'desc' => __('Configure how to handle the import of new products and product changes of products from Zettle', 'woo-izettle-integration'),
                        'id' => 'izettle_webhook_create_options',
                    ),
                    array(
                        'title' => __('Automatic action', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Select what action should be performed on creation, update or deletetion of an Zettle product', 'woo-izettle-integration'),
                        'default' => '',
                        'options' => array(
                            '' => __('Do nothing', 'woo-izettle-integration'),
                            'create' => __('Create products in WooCommerce', 'woo-izettle-integration'),
                            'update' => __('Update products in WooCommerce', 'woo-izettle-integration'),
                            'create_update' => __('Create and update products in WooCommerce', 'woo-izettle-integration'),
                            'create_update_delete' => __('Create, update and delete products in WooCommerce', 'woo-izettle-integration'),
                        ),
                        'id' => 'izettle_when_changed_in_izettle',
                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'izettle_webhook_create_options',
                    ),
                    array(
                        'title' => __('Product data to be imported', 'woo-izettle-integration'),
                        'type' => 'title',
                        'desc' => __('Select what product data to import from Zettle.', 'woo-izettle-integration'),
                        'id' => 'izettle_import_options',
                    ),
                    array(
                        'title' => __('Product name', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Select to update the product name on the product or the "Product name" field in the Zettle tab will be updated when the product name is changed in Zettle', 'woo-izettle-integration'),
                        'default' => '',
                        'options' => array(
                            '' => __('Update WooCommerce product name', 'woo-izettle-integration'),
                            'iz_name' => __('Update Zettle product name', 'woo-izettle-integration'),
                        ),
                        'id' => 'izettle_import_name',
                    ),
                    array(
                        'title' => __('Price', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Set the price from Zettle on the product in WooCommerce', 'woo-izettle-integration'),
                        'default' => '',
                        'options' => array(
                            '' => __('Do not update price', 'woo-izettle-integration'),
                            'wc_price' => __('Update WooCommerce product price', 'woo-izettle-integration'),
                            'iz_price' => __('Update Zettle special product price', 'woo-izettle-integration'),
                        ),
                        'id' => 'izettle_import_price',
                    ),
                    array(
                        'title' => __('Barcode', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Select how to set the barcode should be updated from Zettle on the product in WooCommerce.', 'woo-izettle-integration'),
                        'default' => '',
                        'options' => array(
                            '' => __('Do not update the barcode', 'woo-izettle-integration'),
                            '_izettle_barcode' => __('Set the barcode in the Zettle tab on the product', 'woo-izettle-integration'),
                            '_custom_barcode' => __('Set the barcode in a custom meta field on the product', 'woo-izettle-integration'),
                            'sku' => __('Set the SKU field on the product (the SKU setting below must NOT be checked)', 'woo-izettle-integration'),
                        ),
                        'id' => 'izettle_import_barcode',
                    ),
                    (get_option('izettle_import_barcode') != '_custom_barcode') ? array() : array(
                        'title' => __('Barcode meta field', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('Set which meta field should be used for the Zettle barcode', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_barcode_meta',
                    ),
                    array(
                        'title' => __('SKU', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Set the SKU from Zettle on the product in WooCommerce', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_sku',
                    ),
                    array(
                        'title' => __('Cost price', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Set the cost price from Zettle on the product in WooCommerce', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_cost_price',
                    ),
                    array(
                        'title' => __('Category', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Set the category from Zettle on the product in WooCommerce.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_category',
                    ),
                    array(
                        'title' => __('Main image', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Import image from Zettle and set it as main image on the product in WooCommerce', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_images',
                    ),
                    array(
                        'title' => __('Global attributes', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Check if you want to create product attributes as global attributes', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_create_global_attributes',
                    ),
                    (!((get_option('zettle_hide_double_import_options') == 'yes') && (get_option('izettle_purchase_sync_function') == 'wc_stockchange'))) ? array(
                        'title' => __('Stocklevel', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Check if you do want to overwrite the WooCommerce stocklevel with the one from Zettle', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_import_stocklevel',
                    ) : array(),
                    array(
                        'type' => 'sectionend',
                        'id' => 'izettle_import_options',
                    ),
                    array(
                        'title' => __('Filter products to import', 'woo-izettle-integration'),
                        'type' => 'title',
                        'desc' => __('Select the products to be imported by selecting in the filters below.', 'woo-izettle-integration'),
                        'id' => 'izettle_products_import_filter',
                    ),
                    array(
                        'title' => __('Include Zettle categories', 'woo-izettle-integration'),
                        'type' => 'multiselect',
                        'class' => 'wc-enhanced-select',
                        'css' => 'width: 400px;',
                        'id' => 'izettle_products_import_include_categories',
                        'default' => '',
                        'desc' => __('If you only want to import products included in certain categories, select them here. Leave blank to enable for all categories.', 'woo-izettle-integration'),
                        'options' => $zettle_categories,
                        'custom_attributes' => array(
                            'data-placeholder' => __('Select categories to import or leave empty for all', 'woo-izettle-integration'),
                        ),
                    ),
                    array(
                        'title' => __('Exclude Zettle categories', 'woo-izettle-integration'),
                        'type' => 'multiselect',
                        'class' => 'wc-enhanced-select',
                        'css' => 'width: 400px;',
                        'id' => 'izettle_products_import_exclude_categories',
                        'default' => '',
                        'desc' => __('If you want to exclude importing products from certain categories, select them here. Leave blank to not exclude any categories.', 'woo-izettle-integration'),
                        'options' => $zettle_categories,
                        'custom_attributes' => array(
                            'data-placeholder' => __('Select categories to exclude or leave empty for none', 'woo-izettle-integration'),
                        ),
                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'izettle_products_import_filter',
                    )
                );
            } elseif ('advanced' == $current_section) {

                $settings[] = [
                    'title' => __('Advanced', 'woo-izettle-integration'),
                    'type' => 'title',
                    'id' => 'izettle_advanced_options',
                ];

                $settings[] = [
                    'title' => __('Image size to use from WooCommerce', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Images uploading to Zettle must be must be > 50*50px and < 5MB.', 'woo-izettle-integration'),
                    'id' => 'izettle_image_size',
                    'default' => '',
                    'options' => array(
                        '' => __('Zettle standard', 'woo-izettle-integration'),
                        'thumbnail' => __('Thumbnail', 'woo-izettle-integration'),
                        'medium' => __('Medium resolution', 'woo-izettle-integration'),
                        'medium_large' => __('Medium Large resolution', 'woo-izettle-integration'),
                        'large' => __('Large resolution', 'woo-izettle-integration'),
                        'full' => __('Original image resolution', 'woo-izettle-integration'),
                    ),
                ];

                $settings[] = [
                    'title' => __('Disable notices', 'woo-izettle-integration'),
                    'default' => '',
                    'desc' => __('Disable notices from Zettle, please note that you will not get any information about errors if checked.', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'id' => 'izettle_disable_notices',
                ];

                $settings[] = [
                    'title' => __('Remove product variations when more than (max value is 99)', 'woo-izettle-integration'),
                    'type' => 'number',
                    'default' => 99,
                    'desc' => __('Zettle can handle a manimum of 99 variations on a product. If a product has more variation than this configuration, the product is added without variations and with the price of the least expensive variation', 'woo-izettle-integration'),
                    'id' => 'izettle_number_of_variations',
                ];

                $settings[] = [
                    'title' => __('CRON disabled on server', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('If your server has CRON-jobs disabled you must check this box in order for the plugin to work', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_manual_cron',
                ];

                $settings[] = [
                    'title' => __('Do not match products using UUID', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('When checked the matching of products between Zettle and WooCommerce will not be done using the internal Zettle product UUID. Useful when connecting more than one website to IZettle.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_do_not_match_with_uuid',
                ];

                $settings[] = [
                    'title' => __('Do not match products using external references', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('When checked the matching of products between Zettle and WooCommerce will not be done using the external references.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_do_not_match_external_reference',
                ];

                $settings[] = [
                    'title' => __('Always upload image', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('When checked the plugin will upload the WooCommerce image every manual sync. Useful if images for some reason where corrupted. Do not forget to uncheck when the sync is ready.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_always_upload_image',
                ];

                $settings[] = [
                    'title' => __('Add SKU as external reference', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Useful if connecting the Zettle account to multiple websites.', 'woo-izettle-integration'),
                    'default' => 'yes',
                    'id' => 'zettle_sku_as_externalreference',
                ];

                $settings[] = [
                    'title' => __('Skip validating Zettle webhook signatures', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Will not check check the validity of Zettle webhook messages.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_skip_webhook_signature_check',
                ];

                $settings[] = [
                    'title' => __('Use improved JSON encoding', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Use improved JSON encoding when receiving messages from Zettle - handles more more advanced characters', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_use_advanced_encoding',
                ];

                $settings[] = [
                    'title' => __('Import behaviour', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Select import behaviour from Zettle to WooCommerce.', 'woo-izettle-integration'),
                    'default' => 'merge',
                    'options' => array(
                        'dry' => __('Dry run full import', 'woo-izettle-integration'),
                        'merge' => __('Merge products (recommended)', 'woo-izettle-integration'),
                        'new' => __('Create all products', 'woo-izettle-integration'),
                        'match_dry' => __('Dry run matching only', 'woo-izettle-integration'),
                        'match' => __('Match references', 'woo-izettle-integratio.'),

                    ),
                    'id' => 'izettle_import_type',
                ];

                $settings[] = [
                    'title' => __('Show product metabox', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Show a metabox with product information on WooCommerce products.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_show_product_metabox',
                ];

                if (wc_string_to_bool(get_option('izettle_add_sku_to_name'))) {
                    $settings[] = [
                        'title' => __('Put SKU first', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Put the SKU first in the Zettle product name.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_put_sku_first',
                    ];
                }

                $settings[] = [
                    'title' => __('Sort incoming variants alphabetically.', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Sort incoming variants from Zettle alphabetically.', 'woo-izettle-integration'),
                    'default' => 'yes',
                    'id' => 'zettle_sort_terms_alphabetically',
                ];

                if ('wc_order' === get_option('izettle_purchase_sync_function')) {
                    $settings[] = [
                        'title' => __('Zettle orders will not reduce stock', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Orders created from Zettle purchases will not reduce stock in WooCommerce', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'zettle_process_purchase_order_no_reduce_stock',
                    ];

                    $settings[] = [
                        'title' => __('Zettle orders will not trigger new order email', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Orders created from Zettle purchases will not trigger a new order email to be sent to the admin', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'zettle_no_new_order_email',
                    ];

                    $settings[] = [
                        'title' => __('Force WooCommerce to send out new order mail to admin', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Force WooCommerce to genereate a new order mail for orders created from Zettle purchases - Use only if you notice admin emails not being generated by Zettle orders', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'zettle_force_send_new_order_email_to_admin',
                    ];

                    if (get_option('izettle_set_order_to_status')) {

                        $order_statuses = wc_get_order_statuses();

                        //Add empty option
                        $order_statuses[''] = __('Do not change order status', 'woo-izettle-integration');

                        $settings[] = [
                            'title' => __('Set order status on no stock', 'woo-izettle-integration'),
                            'type' => 'select',
                            'desc' => __('Set the order status on Zettle orders where one or more items have no stock.', 'woo-izettle-integration'),
                            'id' => 'izettle_set_order_to_status_no_stock',
                            'default' => '',
                            'options' => $order_statuses
                        ];
                    }
                }

                $settings[] = [
                    'title' => __('Trigger stock notification emails', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Will trigger low stock and out of stock notification emails from stock changes in Zettle', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_trigger_stock_notification_emails',
                ];

                $settings[] = [
                    'title' => __('Trigger WP Post to save on stock change triggered from Zettle', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Will trigger the save_post action to trigger when stock changes are pushed from Zettle. May trigger additonal logic in Wordpress to happen when a product is changed or created by the plugin.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_save_post_on_stockchange',
                ];
                $settings[] = [
                    'title' => __('Trigger WP Post to save on product creation/update', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Will trigger the save_post action to trigger when products are created or updated. May trigger additonal logic in Wordpress to happen when a product is changed or created by the plugin.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_save_post_on_order',
                ];

                $wpml_active_languages = apply_filters('wpml_active_languages', false);

                if ($wpml_active_languages) {

                    $language_selection = array(
                        '' => __('Sync all products in all languages', 'woo-izettle-integration'),
                    );

                    foreach ($wpml_active_languages as $wpml_active_language) {
                        $language_selection[$wpml_active_language['language_code']] = $wpml_active_language['native_name'];
                    }

                    $settings[] = [
                        'title' => __('Sync WPML/Polylang language', 'woo-izettle-integration'),
                        'type' => 'select',
                        'desc' => __('Select the language to use for Zettle syncronization. If all products are synced duplicates will be created in Zettle.', 'woo-izettle-integration'),
                        'id' => 'zettle_wpml_default_language',
                        'default' => apply_filters('wpml_default_language', ''),
                        'options' => $language_selection,
                    ];
                }

                $currency_selection = array(
                    '' => __('Use WooCommerce default currency', 'woo-izettle-integration'),
                );

                $currency_selection = array_merge($currency_selection, get_woocommerce_currencies());

                $settings[] = [
                    'title' => __('Force Zettle integration currency', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Forces the plugin to use a specific currency when syncing products to Zettle', 'woo-izettle-integration'),
                    'id' => 'izettle_force_product_currency',
                    'default' => '',
                    'options' => $currency_selection,
                ];

                $settings[] = [
                    'title' => __('Import batch size', 'woo-izettle-integration'),
                    'type' => 'number',
                    'desc' => __('Batch size when importing products from Zettle (default 500). If your imports fail to complete lower this size and redo the import.', 'woo-izettle-integration'),
                    'default' => 500,
                    'id' => 'izettle_product_import_batch_size',
                ];

                $settings[] = [
                    'title' => __('Import Zettle custom unit name', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Import the Zettle custom unit name if it exists. Will be set as a meta data field on the product', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_import_unit_name',
                ];

                $settings[] = [
                    'title' => __('Force stocklevel updates', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Always updating of Zettle stocklevel when a product is updated.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_force_change_stocklevel_in_woocommerce',
                ];

                $settings[] = [
                    'title' => __('Only save Zettle stocklevel as meta', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Saves the Zettle stock value as a meta data field on the product instead of updating the normal stocklevel in WooCommerce.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_import_stocklevel_as_metadata_value',
                ];

                $settings[] = [
                    'title' => __('Prevent double stock import options', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Prevents import options under Zettle purchases and Products from Zettle to be shown if the other has been enabled', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_hide_double_import_options',
                ];

                $settings[] = [
                    'title' => __('Text to use "Any" variation', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('When a product variation is set to have the value "Any xxx", this must be translated to a string in Zettle. ', 'woo-izettle-integration'),
                    'id' => 'izettle_any_text_selection',
                    'default' => '',
                    'options' => array(
                        '' => sprintf(__('Use "%s" (translated)', 'woo-izettle-integration'), __('Any', 'woo-izettle-integration')),
                        'untranslated' => __('Update "Any" (untranslated)', 'woo-izettle-integration'),
                        'alternate' => __('Use an alternate text', 'woo-izettle-integration'),
                    ),
                ];

                $settings[] = [
                    'title' => __('Alternate "Any" name', 'woo-izettle-integration'),
                    'type' => 'text',
                    'desc' => __('Set an alternative "Any" text here.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_alternate_any_text',
                ];

                $settings[] = [
                    'title' => __('Use the old Zettle inventory API', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Will use the old Zettle API to sync the inventory back and forth from Zettle. This will stop working after May 31st 2023.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_use_old_inventory_api',
                ];

                $settings[] = [
                    'title' => __('Use New US tax settings', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('The plugin will use the new way of syncing products to and from Zettle. This is only relevant for US based stores.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_use_new_us_tax_settings',
                ];

                $settings[] = [
                    'title' => __('Use improved UUID matching', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Use a new and improved function to match Zettle and WooCommerce products using UUIDs', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_use_new_product_matching',
                ];

                //izettle_sophisticated_manual_sync
                $settings[] = [
                    'title' => __('Use improved manual import', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('The plugin will take the Automatic import settings into consideration when doing manual syncs.', 'woo-izettle-integration'),
                    'default' => 'yes',
                    'id' => 'izettle_sophisticated_manual_sync',
                ];

                //zettle_product_category_export_filter_v2
                $settings[] = [
                    'title' => __('Use new product category export filter', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Use a new and improved category function to filter products to be exported to Zettle', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_product_category_export_filter_v2',
                ];

                $settings[] = [
                    'title' => __('Set custom low stock notification', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable custom low stock notification for products. This will override the default low stock notification settings in Zettle.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_set_custom_low_stock_notification',
                ];

                $settings[] = [
                    'title' => __('Low stock amount', 'woo-izettle-integration'),
                    'type' => 'number',
                    'desc' => __('Set the low stock amount for products. This will be used when the custom low stock notification is enabled.', 'woo-izettle-integration'),
                    'default' => '1',
                    'id' => 'izettle_low_stock_amount',
                ];

                $settings[] = [
                    'title' => __('Low stock notification', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable low stock notification for products. This will be used when the custom low stock notification is enabled.', 'woo-izettle-integration'),
                    'default' => 'yes',
                    'id' => 'izettle_low_stock_notification',
                ];

                $settings[] = [
                    'title' => __('Do not use Zettle tab categories', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Will not use the categories selected in the Zettle tab when exporting categories to Zettle', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_turn_off_product_zettle_categories',
                ];

                $settings[] = [
                    'title' => __('Do not sync virtual products', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Do not sync virtual products to Zettle', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_do_not_sync_virtual',
                ];

                $settings[] = [
                    'title' => __('Skip importing attributes for existing products', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Skip importing attributes when syncing a product from Zettle except for when the product is created', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_ignore_attributes_existing_products',
                ];

                $settings[] = [
                    'title' => __('Allow conversion from simple to variable products', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Allow products that have been converted from simple to variable products in Zettle to be imported into WooCommerce.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_convert_simple_to_variable',
                ];

                $settings[] = [
                    'title' => __('Product status when created', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('When a WooCommerce product is created from Zettle it should be set to this status.', 'woo-izettle-integration'),
                    'id' => 'izettle_set_products_to_status',
                    'default' => 'publish',
                    'options' => get_post_statuses(),
                ];

                $settings[] = [
                    'title' => __('Enable comments processing', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable the possibility to enter customer name, customer email and customer phone in any comment on a order row in Zettle. The data can be comma-separated and "email = comment including an @", "phone = a string with only numbers". Anything else will be interpreted as customer name and can separated with space like "Firstname" "Lastname", a single string will be interpretad as "Company".', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_process_purchase_comments',
                ];

                $settings[] = [
                    'title' => __('Enable comments order id', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable the possibility to enter an order id that should be updated by the purchase in any comment on a order row in Zettle. The order id should have a # sign as a prefix. The WooCommerce order will be overwritten by the purchase from Zettle and set to "Completed"', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_process_purchase_order_id',
                ];

                $settings[] = [
                    'title' => __('Enable comments customer id', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable the possibility to enter a customer id that should be used in the purchase in any comment on a order row in Zettle.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_process_purchase_customer_id',
                ];

                $settings[] = [
                    'title' => __('Enable comments order id on free amount', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable the possibility to enter an order id in the free amount section in Zettle that should be updated by the purchase. The WooCommerce order will be set to paid and set to "Completed"', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'zettle_process_purchase_order_id_free_amount',
                ];

                $settings[] = [
                    'title' => __('Enable comments to order item meta', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enable the possibility to save any order row comment in Zettle as a metadata field on the order item in Woo.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_add_comment_to_meta',
                ];

                $settings[] = [
                    'title' => __('Scheduler time limit', 'woo-izettle-integration'),
                    'type' => 'number',
                    'desc' => __('By default, Action Scheduler will only process actions for a maximum of 30 seconds in each request. This time limit minimises the risk of a script timeout on unknown hosting environments, some of which enforce 30 second timeouts. If you know your host supports longer than this time limit for web requests, you can increase this time limit. This allows more actions to be processed in each request and reduces the lag between processing each queue, greatly speeding up the processing rate of scheduled actions.', 'woo-izettle-integration'),
                    'default' => 30,
                    'id' => 'izettle_action_scheduler_time_limit',
                ];

                $settings[] = [
                    'title' => __('Scheduler batch size', 'woo-izettle-integration'),
                    'type' => 'number',
                    'desc' => __('By default, Action Scheduler will claim a batch of 10 actions. This small batch size is because the default time limit is only 30 seconds; however, if you know your actions are processing very quickly, e.g. taking microseconds not seconds, or that you have more than 30 second available to process each batch, increasing the batch size can slightly improve performance.', 'woo-izettle-integration'),
                    'default' => 10,
                    'id' => 'izettle_action_scheduler_batch_size',
                ];

                $settings[] = [
                    'title' => __('Queue webhook calls', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Some sites do have performance issues if there are a large number of purchases or other changes in Zettle. Check this option to queue changes being made in Zettle.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_queue_webhook_calls',
                ];

                $settings[] = [
                    'title' => __('Dont queue admin updates', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Product updates from WooCommerce will not be queued if the user is admin.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_do_not_queue_admin_updates',
                ];

                //Create setting for deleting variants from Zettle
                $settings[] = [
                    'title' => __('Delete variants from Zettle', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Delete variants in WooCommerce when deleted in Zettle.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_delete_variants',
                ];

                $settings[] = [
                    'title' => __('Permanent delete from Zettle', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Permanently delete products in WooCommerce when deleted from Zettle.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_force_delete_in_woocommerce',
                ];

                if (1 == get_option('izettle_product_sync_model')) {
                    $settings[] = [
                        'title' => __('Force daily product export', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Force a full export to Zettle daily in addition to realtime updates.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_product_sync_model_force_daily',
                    ];

                    $possibletimes = array(
                        1 => '01:00',
                        2 => '02:00',
                        3 => '03:00',
                        4 => '04:00',
                        5 => '05:00',
                        6 => '06:00',
                        7 => '07:00',
                        8 => '08:00',
                        9 => '09:00',
                        10 => '10:00',
                        11 => '11:00',
                        12 => '12:00',
                        13 => '13:00',
                        14 => '14:00',
                        15 => '15:00',
                        16 => '16:00',
                        17 => '17:00',
                        18 => '18:00',
                        19 => '19:00',
                        20 => '20:00',
                        21 => '21:00',
                        22 => '22:00',
                        23 => '23:00',
                    );

                    if (get_option('izettle_product_sync_model_force_daily') == 'yes') {
                        $settings[] = [
                            'title' => __('Sync at time', 'woo-izettle-integration'),
                            'type' => 'select',
                            'desc' => __('Select at what time the product sync should be performed', 'woo-izettle-integration'),
                            'id' => 'izettle_product_sync_model_force_daily_time',
                            'default' => '',
                            'options' => $possibletimes,
                        ];
                    }
                }

                if ('yes' === get_option('izettle_extra_admin')) {
                    $settings[] = [
                        'title' => __('Enable UUID edit', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Enable the possibility to edit UUID on products.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'zettle_enable_uuid_edit',
                    ];

                    $settings[] = [
                        'title' => __('Zettle webhook priority', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('Change the priority of incoming webhooks from Zettle.', 'woo-izettle-integration'),
                        'default' => '10',
                        'id' => 'izettle_webhook_priority',
                    ];

                    $settings[] = [
                        'title' => __('Allow Zettle metadata deletion', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Enable the possibility to delete the metadata on Zettle products through a bulk action.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_allow_metadata_deletion',
                    ];

                    $settings[] = [
                        'title' => __('Send requests through service', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => __('Send the requests to Zettle through the BjornTech service. To be used when instructed by BjornTech', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_send_through_service',
                    ];

                    $settings[] = [
                        'title' => __('Alternate webhook url', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('Do not change unless instructed by BjornTech', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'bjorntech_alternate_webhook_url',
                    ];

                    $settings[] = [
                        'title' => __('Alternate service url', 'woo-izettle-integration'),
                        'type' => 'text',
                        'desc' => __('Do not change unless instructed by BjornTech', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_alternate_service_url',
                    ];
                }

                $settings[] = [
                    'title' => __('Enable admin options', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Enables additional admin options. Only enable if instructed by Bjorntech.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_extra_admin',
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_advanced_options',
                ];

                $settings[] = [
                    'title' => __('Data to be exported to Zettle web', 'woo-izettle-integration'),
                    'type' => 'title',
                    'desc' => __('Enable what data to be exported to the Zettle web.', 'woo-izettle-integration'),
                    'id' => 'izettle_export_web',
                ];

                $settings[] = [
                    'title' => __('Variation images', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Variation images can not be seen in the Zettle app. Use this only if you are using the Zettle Web', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_variation_images',
                ];

                $settings[] = [
                    'title' => __('Online sales"', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('The product in Zettle will be set as "online sales". To be used if you are using the Zettle shop.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_online_sales_active',
                ];

                $settings[] = [
                    'title' => __('Description', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Select what field should be used to update the online sales description', 'woo-izettle-integration'),
                    'id' => 'izettle_online_sales_description',
                    'default' => '',
                    'options' => array(
                        '' => __('Do not update', 'woo-izettle-integration'),
                        'description' => __('Update from description', 'woo-izettle-integration'),
                        'short_description' => __('Update from short description', 'woo-izettle-integration'),
                    ),
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_export_web',
                ];


                $settings[] = [
                    'title' => __('Product data to be imported from Zettle web', 'woo-izettle-integration'),
                    'type' => 'title',
                    'desc' => __('Select what data to import from Zettle web', 'woo-izettle-integration'),
                    'id' => 'izettle_import_create_web'
                ];

                $settings[] = [
                    'title' => __('Description', 'woo-izettle-integration'),
                    'type' => 'select',
                    'desc' => __('Import the Zettle online sales description to the description or short description field (can be done only if izettle web is used).', 'woo-izettle-integration'),
                    'id' => 'izettle_import_description',
                    'default' => '',
                    'options' => array(
                        '' => __('Do not import', 'woo-izettle-integration'),
                        'description' => __('Import to the description', 'woo-izettle-integration'),
                        'short_description' => __('Import to short description', 'woo-izettle-integration'),
                    )
                ];

                $settings[] = [
                    'title' => __('Additional images', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Import additional images from Zettle to the product.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_import_additional_images'
                ];

                $settings[] = [
                    'title' => __('Variant images', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Import variant image from Zettle to the product variation', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_import_variant_images'
                ];

                $settings[] = [
                    'title' => __('Weight', 'woo-izettle-integration'),
                    'type' => 'checkbox',
                    'desc' => __('Import product weight.', 'woo-izettle-integration'),
                    'default' => '',
                    'id' => 'izettle_import_weight'
                ];

                $settings[] = [
                    'type' => 'sectionend',
                    'id' => 'izettle_import_create_web',
                ];
            } else {
                $settings = array(
                    array(
                        'title' => __('General settings', 'woo-izettle-integration'),
                        'type' => 'title',
                        'id' => 'izettle_connection_options',
                    ),
                    array(
                        'title' => __('User e-mail', 'woo-izettle-integration'),
                        'type' => 'email',
                        'desc' => __('The user mail to where we send the confirmation e-mail and other information.', 'woo-izettle-integration'),
                        'default' => '',
                        'id' => 'izettle_username',
                    ),
                    array(
                        'title' => __('Enable logging', 'woo-izettle-integration'),
                        'type' => 'checkbox',
                        'desc' => sprintf(__('Logging is useful when troubleshooting. You can find the logs <a href="%s">here</a>', 'woo-izettle-integration'), WC_IZ()->logger->get_admin_link()),
                        'default' => '',
                        'id' => 'izettle_logging',
                    ),
                    array(
                        'type' => 'sectionend',
                        'id' => 'izettle_connection_options',
                    ),
                );

                $options = array(
                    '' => __('Standard', 'woocommerce'),
                );

                $tax_mapping_settings = array();

                if (wc_tax_enabled()) {

                    $tax_classes = WC_Tax::get_tax_classes();

                    if (!empty($tax_classes)) {
                        foreach ($tax_classes as $class) {
                            $options[sanitize_title($class)] = esc_html($class);
                        }
                    }

                    if (count($options) > 1) {

                        $tax_mapping_settings[] = [
                            'title' => __('Tax mapping', 'woo-izettle-integration'),
                            'type' => 'title',
                            'desc' => __('If the site/Zettle are using more than one Tax-rate you need to map the Tax-rates here. If left blank the plugin will try to map the tax class by itself.', 'woo-izettle-integration'),
                            'id' => 'izettle_tax_class_mapping',
                        ];

                        foreach ($options as $key => $option) {
                            $tax_mapping_settings[] = [
                                'title' => sprintf(__('%s', 'woo-izettle-integration'), $option),
                                'type' => 'number',
                                'default' => '',
                                'desc' => sprintf(__('Enter the tax rate in Zettle that corresponds to %s in WooCommerce', 'woo-izettle-integration'), $option),
                                'id' => 'izettle_tax_class_mapping_' . $key,
                            ];
                        };

                        $tax_mapping_settings[] = [
                            'type' => 'sectionend',
                            'id' => 'izettle_tax_class_mapping',
                        ];
                    }
                }

                $settings = array_merge($settings, $tax_mapping_settings);
            }
            return $settings;
        }
    }

    return new WC_Settings_Page_iZettle();
}
