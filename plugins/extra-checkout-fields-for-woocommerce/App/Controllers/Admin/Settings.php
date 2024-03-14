<?php

namespace ECFFW\App\Controllers\Admin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Models\Checkout\DefaultFields;
use ECFFW\App\Views\Admin\SettingsPage;
use ECFFW\App\Helpers\WPML;

class Settings
{
    /**
     * Settings construct.
     */
    public function __construct() 
    {
        add_action('admin_menu', array($this, 'addMenu'));
        
        if (isset($_GET['page']) && $_GET['page'] == 'ecffw_editor') {
            remove_all_filters('admin_notices');
            add_filter('woocommerce_screen_ids', array($this, 'manageScreen'), 10, 1);
            add_filter('admin_footer_text', function() { return ECFFW_PLUGIN_NAME; });
            add_filter('update_footer', function() { return 'Version ' . ECFFW_PLUGIN_VERSION; }, 999);
        }

        add_filter('plugin_action_links_' . plugin_basename(ECFFW_PLUGIN_FILE), array($this, 'links'));

        self::update();
    }

    /**
     * Add Menu in WooCommerce Menu.
     */
    public function addMenu()
    {   
        global $submenu;

        if (isset($submenu['woocommerce'])) {
            add_submenu_page(
                'woocommerce',
                __("Checkout Field Editor", 'extra-checkout-fields-for-woocommerce'),
                __("Checkout Field Editor", 'extra-checkout-fields-for-woocommerce'),
                'manage_woocommerce',
                'ecffw_editor',
                array($this, 'page')
            );
        }
    }

    /**
     * Manage screen.
     */
    public function manageScreen($screen_ids)
    {
        $screen = get_current_screen();
        $screen->remove_help_tabs();
        $screen_ids[] = $screen->id;

        return $screen_ids;
    }

    /**
     * Settings Page.
     */
    public function page()
    {
        if (!empty($_POST['save'])) {
            self::check(); // to check nonce

            update_option(ECFFW_SETTINGS_KEY, self::save());

            $args = apply_filters('ecffw_settings_save_args', ['saved' => 'true']);

            wp_redirect(add_query_arg($args));
        }

        if (!empty($_POST['reset'])) {
            self::check(); // to check nonce

            update_option(ECFFW_SETTINGS_KEY, self::reset());

            $args = apply_filters('ecffw_settings_reset_args', ['reset' => 'true']);

            wp_redirect(add_query_arg($args));
        }

        self::message(); // to display message

        new SettingsPage(self::data());
    }

    /**
     * Get Current Page.
     * 
     * @return string page
     */
    public static function getPage()
    {
        return 'admin.php?page=ecffw_editor';
    }

    /**
     * Settings Links in Plugins Page.
     */
    public static function links($links)
    {
        $page = self::getPage();
        $manage_fields_link = '<a href="' . $page . '">Manage Fields</a>';
        $settings_link = '<a href="' . $page . '&tab=settings">Settings</a>';

        array_unshift($links, $manage_fields_link, $settings_link);
        
        return $links; 
    }

    /**
     * Settings Tabs.
     * 
     * @return array tabs
     */
    public static function tabs()
    {
        return apply_filters('ecffw_settings_tabs', [
            'billing' => __('Billing section', 'extra-checkout-fields-for-woocommerce'),
            'shipping' => __('Shipping section', 'extra-checkout-fields-for-woocommerce'),
            'order' => __('Order section', 'extra-checkout-fields-for-woocommerce'),
            'custom' => __('Custom section', 'extra-checkout-fields-for-woocommerce'),
            'settings' => __('Settings', 'extra-checkout-fields-for-woocommerce')
        ]);
    }

    /**
     * Get Current Tab.
     * 
     * @param string default
     * @return string tab
     */
    public static function getTab($default = 'billing')
    {
        $tabs = self::tabs();
        if (isset($_GET['tab']) && !empty($_GET['tab'])) {
            $current = sanitize_text_field($_GET['tab']);
            if (array_key_exists($current, $tabs)) {
                return $current;
            }
        }
        
        return $default;
    }

    /**
     * Settings Data.
     * 
     * @return array data
     */
    public static function data()
    {
        return apply_filters('ecffw_settings_data', [
            'page' => self::getPage(),
            'tabs' => self::tabs(),
            'tab' => self::getTab(),
            'settings' => self::get(),
        ]);
    }

    /**
     * Check Settings Nonce.
     */
    public static function check()
    {
        if (empty($_POST['ecffw_settings_nonce']) || !wp_verify_nonce($_POST['ecffw_settings_nonce'], ECFFW_SETTINGS_KEY)) {
            die(__('Action failed! Please refresh the page and retry.', 'extra-checkout-fields-for-woocommerce'));
        }
    }

    /**
     * Display Message.
     */
    public static function message()
    {
        if (!empty($_GET['saved']) && !empty($_GET['tab']) && $_GET['tab'] == "settings") {
            echo '<div id="message" class="notice notice-success is-dismissible"><p><strong>' . __('Settings have been saved.', 'extra-checkout-fields-for-woocommerce') . '</strong></p></div>';
        } elseif (!empty($_GET['reset']) && !empty($_GET['tab']) && $_GET['tab'] == "settings") {
            echo '<div id="message" class="notice notice-success is-dismissible"><p><strong>' . __('Settings reset successfully.', 'extra-checkout-fields-for-woocommerce') . '</strong></p></div>';
        } elseif (!empty($_GET['saved'])) {
            echo '<div id="message" class="notice notice-success is-dismissible"><p><strong>' . __('Fields have been saved.', 'extra-checkout-fields-for-woocommerce') . '</strong></p></div>';
        } elseif (!empty($_GET['reset'])) {
            echo '<div id="message" class="notice notice-success is-dismissible"><p><strong>' . __('Default fields restored successfully.', 'extra-checkout-fields-for-woocommerce') . '</strong></p></div>';
        }

        do_action('ecffw_settings_message');
    }

    /**
     * Default Settings.
     */
    public static function default()
    {
        $settings = [];

        $billing_fields = DefaultFields::billing();
        $shipping_fields = DefaultFields::shipping();
        $order_fields = DefaultFields::order();

        $settings['version'] = null;

        $settings['billing_fields_json'] = json_encode($billing_fields);
        $settings['shipping_fields_json'] = json_encode($shipping_fields);
        $settings['order_fields_json'] = json_encode($order_fields);
        $settings['custom_fields_json'] = '[]';

        $settings['custom_fields_heading'] = 'Other details';
        $settings['custom_fields_position'] = 'after_order_notes';

        $settings['form_builder_editonadd'] = true;
        $settings['form_builder_warning'] = true;
        $settings['form_builder_control'] = 'right';

        return apply_filters('ecffw_settings_default', $settings);
    }

    /**
     * Get Settings.
     */
    public static function get() {
        return get_option(ECFFW_SETTINGS_KEY, self::default());
    }

    /**
     * Save Settings.
     */
    public static function save()
    {
        $settings = self::get();
        $default_settings = self::default();
        
        $allowed = array(
            'a' => array(
                'href' => array()
            )
        );

        if (isset($_POST['ecffw-form-builder-json']) && !empty($_POST['ecffw-form-builder-json'])) {
            $form_builder_json = stripslashes(wp_kses($_POST['ecffw-form-builder-json'], $allowed));
            if (empty($form_builder_json)) $form_builder_json = '[]';

            if (isset($_GET['tab']) && in_array($_GET['tab'], ['shipping', 'order', 'custom'])) {
                $settings[$_GET['tab'] . '_fields_json'] = $form_builder_json;
            } else {
                $settings['billing_fields_json'] = $form_builder_json;
            }

            // Translate data
            $data = json_decode($form_builder_json);
            $fields = is_array($data) ? $data : array($data);
            self::runTranslation($fields);
        }

        if (isset($_GET['tab']) && $_GET['tab'] == 'settings') {
            $settings['custom_fields_heading'] = sanitize_text_field($_POST['ecffw-custom-fields-heading']);
            
            $position = sanitize_text_field($_POST['ecffw-custom-fields-position']);
            $positions = [
                'checkout_before_customer_details',
                'checkout_after_customer_details',
                'before_checkout_billing_form',
                'after_checkout_billing_form',
                'before_checkout_registration_form',
                'after_checkout_registration_form',
                'before_checkout_shipping_form',
                'after_checkout_shipping_form',
                'before_order_notes',
                'after_order_notes',
                'checkout_before_order_review_heading',
                'checkout_before_order_review',
                'checkout_after_order_review'
            ];
            if (in_array($position, $positions))
                $settings['custom_fields_position'] = $position;
            else
                $settings['custom_fields_position'] = $default_settings['custom_fields_position'];

            
            $settings['form_builder_editonadd'] = isset($_POST['ecffw-form-builder-editonadd']);
            $settings['form_builder_warning'] = isset($_POST['ecffw-form-builder-warning']);
            
            $control = sanitize_text_field($_POST['ecffw-form-builder-control']);
            if (in_array($control, ['left', 'right']))
                $settings['form_builder_control'] = $control;
            else
                $settings['form_builder_control'] = $default_settings['form_builder_control'];
        }

        return apply_filters('ecffw_settings_save', $settings);
    }

    /**
     * Reset Settings.
     */
    public static function reset()
    {
        $current_settings = self::get();
        $default_settings = self::default();

        if (isset($_GET['tab']) && $_GET['tab'] == 'settings') {
            $settings = $default_settings;
            $settings['billing_fields_json'] = $current_settings['billing_fields_json'];
            $settings['shipping_fields_json'] = $current_settings['shipping_fields_json'];
            $settings['order_fields_json'] = $current_settings['order_fields_json'];
            $settings['custom_fields_json'] = $current_settings['custom_fields_json'];
        } else {
            $settings = $current_settings;
            if (isset($_GET['tab']) && in_array($_GET['tab'], ['shipping', 'order', 'custom'])) {
                $settings[$_GET['tab'] . '_fields_json'] = $default_settings[$_GET['tab'] . '_fields_json'];
            } else {
                $settings['billing_fields_json'] = $default_settings['billing_fields_json'];
            }
        }

        return apply_filters('ecffw_settings_reset', $settings);
    }

    /**
     * Update Settings.
     */
    public static function update()
    {
        if (!get_option(ECFFW_SETTINGS_KEY)) {
            update_option(ECFFW_SETTINGS_KEY, self::default());
        } else {
            $settings = get_option(ECFFW_SETTINGS_KEY);
            if ($settings['version'] != ECFFW_PLUGIN_VERSION) {
                $settings['version'] = ECFFW_PLUGIN_VERSION;
                $default_settings = self::default();
                foreach ($default_settings as $key => $value) {
                    if (!isset($settings[$key])) {
                        $settings[$key] = $value;
                    }
                }
                update_option(ECFFW_SETTINGS_KEY, $settings);
            }
        }
    }

    /**
     * Run Translation for Fields label, placeholder, description and options.
     * 
     * @param array fields
     * @return void
     */
    public static function runTranslation($fields)
    {
        if (WPML::stringTranslationIsActive()) {
            foreach ($fields as $field) {
                if (isset($field->label)) {
                    WPML::registerString('Label: ' . $field->label, $field->label);
                }
                if (isset($field->placeholder)) {
                    WPML::registerString('PlaceHolder: ' . $field->placeholder, $field->placeholder);
                }
                if (isset($field->description)) {
                    WPML::registerString('Description: ' . $field->description, $field->description);
                }
                if (isset($field->values)) {
                    foreach ($field->values as $option) {
                        if (isset($option->label)) {
                            WPML::registerString('Option: ' . $option->label, $option->label);
                        }
                    }
                }
            }
        }
    }
}
