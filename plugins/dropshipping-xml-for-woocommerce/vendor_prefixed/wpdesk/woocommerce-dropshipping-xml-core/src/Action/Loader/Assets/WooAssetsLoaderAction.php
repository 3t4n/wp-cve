<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Loader\Assets;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
/**
 * Class WooAssetsLoaderAction, loads woocommerce assets.
 */
class WooAssetsLoaderAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Conditional\Conditional
{
    const INTEGRATION_SCREEN_IDS = ['dropshipping-import_page_dropshipping_xml_import'];
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var PluginHelper
     */
    private $plugin_helper;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $helper)
    {
        $this->config = $config;
        $this->request = $request;
        $this->plugin_helper = $helper;
    }
    public function isActive() : bool
    {
        $is_plugin_page = $this->plugin_helper->is_plugin_page($this->request->get_param('get.page')->getAsString(), $this->request->get_param('get.action')->getAsString());
        return $is_plugin_page && ($this->request->get_param('get.action')->getAsString() === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig::ACTION_MAPPER || $this->request->get_param('get.action')->getAsString() === \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Config\MenuConfig::ACTION_OPTIONS);
    }
    public function hooks()
    {
        \add_filter('woocommerce_screen_ids', array($this, 'update_woo_screen_ids'), 10, 1);
        \add_action('admin_enqueue_scripts', array($this, 'load_woocommerce_scripts'), 90);
    }
    public function update_woo_screen_ids($screen_ids)
    {
        return \array_merge($screen_ids, self::INTEGRATION_SCREEN_IDS);
    }
    public function load_woocommerce_scripts()
    {
        $suffix = \true === $this->config->get_param('plugin.development')->get() ? '' : '.min';
        $version = $this->config->get_param('plugin.version')->get();
        \wp_enqueue_media();
        \wp_register_script('wc-admin-product-meta-boxes', \WC()->plugin_url() . '/assets/js/admin/meta-boxes-product' . $suffix . '.js', array('wc-admin-meta-boxes', 'media-models'), $version);
        \wp_register_script('wc-admin-variation-meta-boxes', \WC()->plugin_url() . '/assets/js/admin/meta-boxes-product-variation' . $suffix . '.js', array('wc-admin-meta-boxes', 'serializejson', 'media-models'), $version);
        \wp_enqueue_script('wc-admin-product-meta-boxes');
        \wp_enqueue_script('wc-admin-variation-meta-boxes');
        \wp_localize_script('wc-admin-variation-meta-boxes', 'woocommerce_admin_meta_boxes_variations', $this->get_variation_meta_boxes_params());
        \wp_localize_script('wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $this->get_meta_boxes_params());
    }
    private function get_meta_boxes_params()
    {
        return [
            'remove_item_notice' => \__('Are you sure you want to remove the selected items?', 'woocommerce'),
            'i18n_select_items' => \__('Please select some items.', 'woocommerce'),
            'i18n_do_refund' => \__('Are you sure you wish to process this refund? This action cannot be undone.', 'woocommerce'),
            'i18n_delete_refund' => \__('Are you sure you wish to delete this refund? This action cannot be undone.', 'woocommerce'),
            'i18n_delete_tax' => \__('Are you sure you wish to delete this tax column? This action cannot be undone.', 'woocommerce'),
            'remove_item_meta' => \__('Remove this item meta?', 'woocommerce'),
            'remove_attribute' => \__('Remove this attribute?', 'woocommerce'),
            'name_label' => \__('Name', 'woocommerce'),
            'remove_label' => \__('Remove', 'woocommerce'),
            'click_to_toggle' => \__('Click to toggle', 'woocommerce'),
            'values_label' => \__('Value(s)', 'woocommerce'),
            'text_attribute_tip' => \__('Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce'),
            'visible_label' => \__('Visible on the product page', 'woocommerce'),
            'used_for_variations_label' => \__('Used for variations', 'woocommerce'),
            'new_attribute_prompt' => \__('Enter a name for the new attribute term:', 'woocommerce'),
            'calc_totals' => \__('Recalculate totals? This will calculate taxes based on the customers country (or the store base country) and update totals.', 'woocommerce'),
            'copy_billing' => \__('Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce'),
            'load_billing' => \__("Load the customer's billing information? This will remove any currently entered billing information.", 'woocommerce'),
            'load_shipping' => \__("Load the customer's shipping information? This will remove any currently entered shipping information.", 'woocommerce'),
            'featured_label' => \__('Featured', 'woocommerce'),
            'prices_include_tax' => \esc_attr(\get_option('woocommerce_prices_include_tax')),
            'tax_based_on' => \esc_attr(\get_option('woocommerce_tax_based_on')),
            'round_at_subtotal' => \esc_attr(\get_option('woocommerce_tax_round_at_subtotal')),
            'no_customer_selected' => \__('No customer selected', 'woocommerce'),
            'plugin_url' => \WC()->plugin_url(),
            'ajax_url' => \admin_url('admin-ajax.php'),
            'order_item_nonce' => \wp_create_nonce('order-item'),
            'add_attribute_nonce' => \wp_create_nonce('add-attribute'),
            'save_attributes_nonce' => \wp_create_nonce('save-attributes'),
            'calc_totals_nonce' => \wp_create_nonce('calc-totals'),
            'get_customer_details_nonce' => \wp_create_nonce('get-customer-details'),
            'search_products_nonce' => \wp_create_nonce('search-products'),
            'grant_access_nonce' => \wp_create_nonce('grant-access'),
            'revoke_access_nonce' => \wp_create_nonce('revoke-access'),
            'add_order_note_nonce' => \wp_create_nonce('add-order-note'),
            'delete_order_note_nonce' => \wp_create_nonce('delete-order-note'),
            'calendar_image' => \WC()->plugin_url() . '/assets/images/calendar.png',
            'post_id' => '',
            'base_country' => \WC()->countries->get_base_country(),
            'currency_format_num_decimals' => \wc_get_price_decimals(),
            'currency_format_symbol' => \get_woocommerce_currency_symbol(''),
            'currency_format_decimal_sep' => \esc_attr(\wc_get_price_decimal_separator()),
            'currency_format_thousand_sep' => \esc_attr(\wc_get_price_thousand_separator()),
            'currency_format' => \esc_attr(\str_replace(array('%1$s', '%2$s'), array('%s', '%v'), \get_woocommerce_price_format())),
            // For accounting JS.
            'rounding_precision' => \wc_get_rounding_precision(),
            'tax_rounding_mode' => \wc_get_tax_rounding_mode(),
            'product_types' => \array_unique(\array_merge(array('simple', 'grouped', 'variable', 'external'), \array_keys(\wc_get_product_types()))),
            'i18n_download_permission_fail' => \__('Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce'),
            'i18n_permission_revoke' => \__('Are you sure you want to revoke access to this download?', 'woocommerce'),
            'i18n_tax_rate_already_exists' => \__('You cannot add the same tax rate twice!', 'woocommerce'),
            'i18n_delete_note' => \__('Are you sure you wish to delete this note? This action cannot be undone.', 'woocommerce'),
            'i18n_apply_coupon' => \__('Enter a coupon code to apply. Discounts are applied to line totals, before taxes.', 'woocommerce'),
            'i18n_add_fee' => \__('Enter a fixed amount or percentage to apply as a fee.', 'woocommerce'),
        ];
    }
    private function get_variation_meta_boxes_params()
    {
        return ['post_id' => '', 'plugin_url' => \WC()->plugin_url(), 'ajax_url' => \admin_url('admin-ajax.php'), 'woocommerce_placeholder_img_src' => \wc_placeholder_img_src(), 'add_variation_nonce' => \wp_create_nonce('add-variation'), 'link_variation_nonce' => \wp_create_nonce('link-variations'), 'delete_variations_nonce' => \wp_create_nonce('delete-variations'), 'load_variations_nonce' => \wp_create_nonce('load-variations'), 'save_variations_nonce' => \wp_create_nonce('save-variations'), 'bulk_edit_variations_nonce' => \wp_create_nonce('bulk-edit-variations'), 'i18n_link_all_variations' => \esc_js(\__('Are you sure you want to link all variations?', 'woocommerce')), 'i18n_enter_a_value' => \esc_js(\__('Enter a value', 'woocommerce')), 'i18n_enter_menu_order' => \esc_js(\__('Variation menu order (determines position in the list of variations)', 'woocommerce')), 'i18n_enter_a_value_fixed_or_percent' => \esc_js(\__('Enter a value (fixed or %)', 'woocommerce')), 'i18n_delete_all_variations' => \esc_js(\__('Are you sure you want to delete all variations? This cannot be undone.', 'woocommerce')), 'i18n_last_warning' => \esc_js(\__('Last warning, are you sure?', 'woocommerce')), 'i18n_choose_image' => \esc_js(\__('Choose an image', 'woocommerce')), 'i18n_set_image' => \esc_js(\__('Set variation image', 'woocommerce')), 'i18n_variation_added' => \esc_js(\__('variation added', 'woocommerce')), 'i18n_variations_added' => \esc_js(\__('variations added', 'woocommerce')), 'i18n_no_variations_added' => \esc_js(\__('No variations added', 'woocommerce')), 'i18n_remove_variation' => \esc_js(\__('Are you sure you want to remove this variation?', 'woocommerce')), 'i18n_scheduled_sale_start' => \esc_js(\__('Sale start date (YYYY-MM-DD format or leave blank)', 'woocommerce')), 'i18n_scheduled_sale_end' => \esc_js(\__('Sale end date (YYYY-MM-DD format or leave blank)', 'woocommerce')), 'i18n_edited_variations' => \esc_js(\__('Save changes before changing page?', 'woocommerce')), 'i18n_variation_count_single' => \esc_js(\__('%qty% variation', 'woocommerce')), 'i18n_variation_count_plural' => \esc_js(\__('%qty% variations', 'woocommerce')), 'variations_per_page' => \absint(\apply_filters('woocommerce_admin_meta_boxes_variations_per_page', 15))];
    }
}
