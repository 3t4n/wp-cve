<?php

defined('ABSPATH') or exit;

/**
 * This class controls the UI side of the Checkout page in Woocommerce
 */
class Montonio_Payments_Checkout
{
    protected $_payment_handle_style;
    protected $_payment_handle_extra_css;
    protected $_payment_logos_list;
    protected $_preferred_country;
    protected $_is_non_default_description = false;
    protected $_always_show_description = false;
    protected $_place_order_instructions;
    protected $_currency;

    protected $regions = array(
        'EE' => 'Estonia',
        'FI' => 'Finland',
        'LV' => 'Latvia',
        'LT' => 'Lithuania',
        'PL' => 'Poland'
    );
    
    /**
     * The description shown at checkout
     *
     * @var string
     */
    protected $_description;
    
    /**
     * Banks to show at checkout
     * (configured in Montonio_Payments_Settings)
     *
     * @var Object
     */
    protected $_payment_methods;
    
    public function get_description_html()
    {
        if (is_object($this->get_payment_methods()) && count((array) $this->get_payment_methods()) > 0) {
            $payment_methods = $this->filter_payment_methods_by_currency(
                $this->object_to_array($this->get_payment_methods()),
                $this->get_currency()
            );

            switch ($this->get_payment_handle_style()) {
                case 'list_logos':
                    return $this->get_html_list_logos($payment_methods);
                case 'grid_logos':
                    return $this->get_html_grid_logos($payment_methods);
                default:
                    return $this->get_default_description();
            }
        } else {
            return $this->get_default_description();
        }
    }

    function filter_payment_methods_by_currency($payment_methods, $currency) {
        return array_filter($payment_methods, function($country) use ($currency) {
            return array_search($currency, $country['supported_currencies']) !== false;
        });
    }
    
    // =========================================================================
    // Style and script for non-default description
    // =========================================================================
    
    public function register_payment_handle_style($enqueueMode)
    {
        if ($this->_is_non_default_description) {
            if ($enqueueMode == 'echo') {
                echo '<link rel="stylesheet" href="'. WC_MONTONIO_PLUGIN_URL . '/payments/assets/css/payment-handle/' . $this->_payment_handle_style . '.css">';
            } else {
                wp_enqueue_style(
                    'montonio-payments-payment-handle-style',
                    WC_MONTONIO_PLUGIN_URL . '/payments/assets/css/payment-handle/' . $this->_payment_handle_style . '.css'
                );
            }

            wp_add_inline_style('montonio-payments-payment-handle-style', $this->get_payment_handle_extra_css());
        }
    }

    public function register_payment_handle_script($enqueueMode)
    {
        if (in_array($this->get_payment_handle_style(), array('grid_logos', 'list_logos'))) {
            if ($enqueueMode == 'echo') {
                echo '<script type="text/javascript" src="'. WC_MONTONIO_PLUGIN_URL . '/payments/assets/js/montonio-payment-handle.js"></script>';
            } else {
                wp_enqueue_script(
                    'montonio-payments-payment-handle-script',
                    WC_MONTONIO_PLUGIN_URL . '/payments/assets/js/montonio-payment-handle.js',
                    array('jquery')
                );
            }
        }
    }
    
    // =========================================================================
    // Checkout HTML types
    // =========================================================================
    
    protected function get_default_description()
    {
        return $this->get_description(); // TODO: Translations
    }
    
    protected function get_html_list_logos($regions)
    {
        $defaultDesc = '';
        if ($this->get_always_show_description()) {
            $defaultDesc = $this->get_default_description() . '<br class="mon-br" /> <br class="mon-br" />';
        }

        $preselectedAspsp = '<input type="hidden" name="montonio_payments_preselected_aspsp" id="montonio_payments_preselected_aspsp">';
        $description = (count($regions) > 1) ? $this->get_dropdown_html($regions) : '';
        $description .= '<ul id="montonio-payments-description" class="montonio-aspsp-ul montonio-aspsp-list-logos">';

        $defaultCountryToDisplay = array_keys($regions)[0];
        foreach ($regions as $region => $value) {
            if ($region === $this->get_preferred_country()) {
                $defaultCountryToDisplay = $region;
            }
        }
        
        foreach ($regions as $r => $list) {
            foreach ($list['payment_methods'] as $key => $value) {
                $description .= '<li data-aspsp="' . $value['code'] . '" class="aspsp-region-'. $r .' montonio-aspsp-li montonio-aspsp '. ($r == $defaultCountryToDisplay ? '' : 'montonio-hidden') .'"><img class="montonio-aspsp-li-img" src="' . $value['logo_url'] . '"></li>';
            }
        }

        $description .= '</ul>';
        $description .= $this->get_instructions_html();

        $this->_is_non_default_description = true;
        return $defaultDesc . $preselectedAspsp . $description;
    }
    
    protected function get_html_grid_logos($regions)
    {
        $defaultDesc = '';
        if ($this->get_always_show_description()) {
            $defaultDesc = $this->get_default_description() . '<br class="mon-br" /> <br class="mon-br" />';
        }

        $preselectedAspsp = '<input type="hidden" name="montonio_payments_preselected_aspsp" id="montonio_payments_preselected_aspsp">';
        
        $description = (count($regions) > 1) ? $this->get_dropdown_html($regions) : '';
        $description .= '<div id="montonio-payments-description" class="montonio-aspsp-grid montonio-aspsp-grid-logos">';

        $defaultCountryToDisplay = array_keys($regions)[0];
        foreach ($regions as $region => $value) {
            if ($region === $this->get_preferred_country()) {
                $defaultCountryToDisplay = $region;
            }
        }

        foreach ($regions as $r => $list) {
            foreach ($list['payment_methods'] as $key => $value) {
                $description .= '<div class="aspsp-region-'. $r .' montonio-aspsp-grid-item montonio-aspsp '. ($r == $defaultCountryToDisplay ? '' : 'montonio-hidden') .'" data-aspsp="' . $value['code']
                . '"><img class="montonio-aspsp-grid-item-img" src="' . $value['logo_url'] . '"></div>';
            }
        }

        $description .= '</div>';
        $description .= $this->get_instructions_html();
            
        $this->_is_non_default_description = true;
        return $defaultDesc . $preselectedAspsp . $description;
    }

    protected function get_dropdown_html($regions) {
        $html = '<select class="montonio-payments-country-dropdown" name="montonio_payments_preselected_country">';
        foreach ($regions as $r => $list) {
            $html .= '<option '. ($r == $this->get_preferred_country() ? 'selected="selected"' : '') .' value="'. $r .'">'. $this->regions[$r] .'</option>';
        }
        $html .= '</select>';

        return $html;
    }

    protected function get_instructions_html() {
        $html = '<div class="montonio-place-order-instructions-wrapper"><span class="montonio-place-order-instructions">'. $this->get_place_order_instructions() .'</span></div>';
        return $html;
    }
    
    // =========================================================================
    // Getters and setters
    // =========================================================================
    
    /**
     * @return object
     */
    public function get_payment_handle_style()
    {
        return $this->_payment_handle_style;
    }
    
    /**
     * @param object $payment_handle_style
     */
    public function set_payment_handle_style($payment_handle_style)
    {
        $this->_payment_handle_style = $payment_handle_style;
    }
    
    /**
     * @return object
     */
    public function get_payment_handle_extra_css()
    {
        return $this->_payment_handle_extra_css;
    }
    
    /**
     * @param object $payment_handle_extra_css
     */
    public function set_payment_handle_extra_css($payment_handle_extra_css)
    {
        $this->_payment_handle_extra_css = $payment_handle_extra_css;
    }

    public function get_payment_methods()
    {
        return $this->_payment_methods;
    }
    
    /**
     * JSON-decode bankList
     *
     * @param object $payment_handle_style
     */
    public function set_payment_methods($banklistJson)
    {
        $this->_payment_methods = json_decode($banklistJson);
    }
    
    public function set_description($description) {
        $this->_description = $description;
    }
    
    public function get_description() {
        return $this->_description;
    }

    public function set_preferred_country($country) {
        $this->_preferred_country = $country;
    }

    public function get_preferred_country() {
        return $this->_preferred_country;
    }

    public function set_regions($regions) {
        $this->regions = $regions;
    }

    public function get_regions() {
        return $this->regions;
    }

    public function set_place_order_instructions($place_order_instructions) {
        $this->_place_order_instructions = $place_order_instructions;
    }

    public function get_place_order_instructions() {
        return $this->_place_order_instructions;
    }

    public function set_always_show_description($always_show_description) {
        $this->_always_show_description = $always_show_description;
    }

    public function get_always_show_description() {
        return $this->_always_show_description;
    }

    public function set_currency($currency) {
        $this->_currency = $currency;
    }

    public function get_currency() {
        return $this->_currency;
    }

    private function object_to_array($obj) {
        //only process if it's an object or array being passed to the function
        if(is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach($ret as &$item) {
                //recursively process EACH element regardless of type
                $item = $this->object_to_array($item);
            }
            return $ret;
        }
        //otherwise (i.e. for scalar values) return without modification
        else {
            return $obj;
        }
    }
}