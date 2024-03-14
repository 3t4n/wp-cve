<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Currency Exchange Rage Widget.
 * Displays currencies dropdown list.
 */
class Currency_List_Widget extends \WC_Widget {

    public function __construct() {
        $this->widget_id = 'woo_exchange_rate_widget';
        $this->widget_name = __('Woo Exchange Rage plugin widget', 'woo-exchange-rate');
        $this->widget_cssclass = 'woo_exchage_rate currency_list';
        $this->widget_description = __("Display currency switcher (as dropdown list).", 'woo-exchange-rate');

        parent::__construct();
    }

    /**
     * Output widget
     *
     * @see WP_Widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        $this->widget_start($args, $instance);

        $list_data = array();
        $currencies = Exchange_Rate_Model::get_instance()->select(array('currency_code'));

        if (!$currencies) {
            return;
        }

        $wc_all_currencies = get_woocommerce_currencies();
        foreach ($currencies as $row) {
            $code = $row['currency_code'];
            $list_data[$code] = $wc_all_currencies[$code] . ' - ' . get_woocommerce_currency_symbol($code);
        }

        $settings[] = array(
            'name' => __('Currency', 'woocommerce'),
            'id' => 'wooer_currency_code',
            'type' => 'select',
            'options' => $list_data,
            'default' => Currency_Manager::get_currency_code(),
            'class' => 'woo-currency-select'
        );

        $settings[] = array('type' => 'sectionend', 'id' => 'woo-exchange-rate');

        // Output widget
        echo '<div class="currency_wrapper">';
        \WC_Admin_Settings::output_fields($settings);
        echo '</div>';
        
        $this->widget_end($args);
    }

}
