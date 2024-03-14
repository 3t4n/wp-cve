<?php

namespace PlatiOnlinePO6\Inc\Admin;

use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Recurrence;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


/**
 * @link              https://plati.online
 * @since             6.1.0
 * @package           PlatiOnlinePO6
 *
 */
class Admin_Recurrence
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * The text domain of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_text_domain The text domain of this plugin.
     */
    private $plugin_text_domain;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param string $plugin_text_domain The text domain of this plugin.
     *
     * @since       1.0.0
     *
     */
    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */

    public function plationline_create_plationline_tab_for_product($tabs)
    {
        $tabs['plationline'] = array(
            'label' => __('PlatiOnline', 'plationline'),
            'target' => 'plationline_create_recurrence_checkbox_for_product',
            'class' => array('show_if_simple', 'show_if_variable', 'hide_if_subscription', 'hide_if_variable-subscription'),
        );
        return $tabs;
    }

    function plationline_create_recurrence_checkbox_for_product()
    {
        echo '<div id="plationline_create_recurrence_checkbox_for_product" class="panel woocommerce_options_panel">';
        echo '<div class="options_group">';
        $args = array(
            'id' => '_plationline_enable_recurrence',
            'wrapper_class' => 'show_if_simple show_if_variable',
            'label' => __('This product supports PlatiOnline Recurring payments', 'plationline'),
            'desc_tip' => false,
            'description' => __('If set, this product can be bought using recurrence if all cart products support this feature.', 'plationline'),
        );
        \woocommerce_wp_checkbox($args);
        echo '</div></div>';
    }

    public function plationline_save_recurrence_checkbox_for_product($post_id)
    {
        $product = \wc_get_product($post_id);
        $product->update_meta_data('_plationline_enable_recurrence', \wc_bool_to_string(!empty($_POST['_plationline_enable_recurrence'])));
        $product->save();
    }

    public function plationline_recurrence_restrict_post_deletion($order_id)
    {
        $type = get_post_type($order_id);
        if ($type == 'shop_order') {
            $order = \wc_get_order(absint($order_id));
            $plationline_transaction_type = $order->get_meta('_plationline_transaction_type');
            $po_recurrence = new WC_PlatiOnline_Recurrence();
            if (in_array($plationline_transaction_type, array_keys($po_recurrence::$po_recurrence_types))) {
                $url = admin_url('post.php?post=' . absint($order_id) . '&action=edit');
                wp_die(__('PlatiOnline recurrent master or slave orders cannot be deleted', 'plationline') . '. ' . \sprintf('<a href="%s"><b>%s</b></a>', $url, __('Return to order page', 'plationline')));
            }
        }
    }
}
