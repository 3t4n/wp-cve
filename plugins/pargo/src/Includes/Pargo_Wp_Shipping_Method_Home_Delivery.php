<?php

namespace PargoWp\Includes;

use WC_Shipping_Method;

class Pargo_Wp_Shipping_Method_Home_Delivery extends WC_Shipping_Method
{

    public const PARGO_API_ENDPOINTS = [
        'staging' => 'https://api.staging.pargo.co.za',
        'production' => 'https://api.pargo.co.za'
    ];

    /**
     * Constructor for your shipping class
     *
     * @access public
     * @return void
     */
    public function __construct($instance_id = 0)
    {
        $this->id = 'wp_pargo_home';
        $this->method_title = __('Pargo Home Delivery', 'woocommerce');
        $this->method_description = __('Home Delivery Shipping Method for Pargo', 'woocommerce');
        // Availability & Countries
        $this->availability = 'including';
        $this->countries = array(
            'ZA', //South Africa
            'EG'
        );
        //Woocommerce 3 support
        $this->instance_id = absint($instance_id);
        $this->enabled = $this->settings['enabled'] ?? 'yes';
        $this->title = $this->settings['title'] ?? __('Pargo Home Delivery', 'woocommerce');

        $this->supports = array(
            'settings',
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal'
        );

        $this->init();
    }

    /**
     * Init your settings
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->init_settings();
        $this->init_form_fields();
        $this->init_instance_settings();
        $this->init_instance_form_fields();

        // Save settings in admin if you have any defined
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * Define settings field for Pargo shipping
     * @return void
     */
    public function init_form_fields()
    {
        /* Unused weight setting for v3.0.9, will be used in v3.1 */
        /*
        'weight' => array(
            'title' => __('Weight (kg)', 'woocommerce'),
            'type' => 'number',
            'description' => __('Maximum allowed weight per item to use for Pargo delivery', 'woocommerce'),
            'id' => 'weight',
            'default' => 15
        ),
        */
        $this->form_fields = array(
            'pargo_description' => array(
                'title' => __('Method Description', 'woocommerce'),
                'type' => 'text',
                'default' => __(
                    'When it suits you best',
                    'woocommerce'
                ),
            ),
            'home_use_backend_shipping' => array(
	            'title' => __('Use Backend Shipping', 'woocommerce'),
	            'type' => 'checkbox',
	            'description' => __('Disabling this prevents orders being sent to Pargo for shipping fulfilment. You will need to manually create these orders in myPargo or create orders via a Pargo API enabled partner.', 'woocommerce'),
	            'default' => __('yes', 'woocommerce')
            ),
            'home_enable_free_shipping' => array(
                'title' => __('Enable Free Shipping', 'woocommerce'),
                'type' => 'checkbox',

                'default' => 'yes'
            ),
            'home_free_shipping_amount' => array(
                'title' => __('Free Shipping Amount', 'woocommerce'),
                'type' => 'number',
                'description' => __('Set the minimum amount for free shipping', 'woocommerce'),
                'default' => 299
            ),
            'pargo_door_cost' => array(
                'title' => __('No Weight Shipping Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __(
                    'This controls the cost of Pargo delivery without product weight settings.',
                    'woocommerce'
                ),
                'default' => __('85', 'woocommerce')
            ),
            'pargo_door_cost_5' => array(
                'title' => __('5kg Shipping to Home Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to home for 0-5kg items.', 'woocommerce'),
                'default' => __('70', 'woocommerce')
            ),
            'pargo_door_cost_10' => array(
                'title' => __('10kg Shipping to Home Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to home for 5-10kg items.', 'woocommerce'),
                'default' => __('85', 'woocommerce')
            ),
            'pargo_door_cost_15' => array(
                'title' => __('15kg Shipping to Home Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to home for 10-15kg items.', 'woocommerce'),
                'default' => __('100', 'woocommerce')
            ),
        );
    }

    /**
     * Define instance settings form fields
     *
     *
     * @access public
     *
     * @param mixed $package
     *
     * @return void
     */
    public function init_instance_form_fields()
    {
        $this->instance_form_fields = array(
            'pargo_description' => array(
                'title' => __('Method Description', 'woocommerce'),
                'type' => 'text',
                'default' => __(
                    $this->get_option('pargo_description', ''),
                    'woocommerce'
                ),
            ),
            'home_enable_free_shipping' => array(
                'title' => __('Enable Free Shipping', 'woocommerce'),
                'type' => 'checkbox',
                'default' => __($this->get_option('home_enable_free_shipping', '0'), 'woocommerce')
            ),
            'home_free_shipping_amount' => array(
                'title' => __('Free Shipping Amount', 'woocommerce'),
                'type' => 'number',
                'description' => __('Set the minimum amount for free shipping', 'woocommerce'),
                'default' => $this->get_option('home_free_shipping_amount', '0')
            ),
            'pargo_door_cost' => array(
                'title' => __('No Weight Shipping Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __(
                    'This controls the cost of Pargo delivery without product weight settings.',
                    'woocommerce'
                ),
                'default' => __($this->get_option('pargo_door_cost', '0'), 'woocommerce')
            ),
            'pargo_door_cost_5' => array(
                'title' => __('5kg Shipping to Home Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to home for 0-5kg items.', 'woocommerce'),
                'default' => __($this->get_option('pargo_door_cost_5', '0'), 'woocommerce')
            ),
            'pargo_door_cost_10' => array(
                'title' => __('10kg Shipping to Home Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to home for 5-10kg items.', 'woocommerce'),
                'default' => __($this->get_option('pargo_door_cost_10', '0'), 'woocommerce')
            ),
            'pargo_door_cost_15' => array(
                'title' => __('15kg Shipping to Home Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to home for 10-15kg items.', 'woocommerce'),
                'default' => __($this->get_option('pargo_door_cost_15', '0'), 'woocommerce')
            ),
        );

        $this->instance_settings = array(
            'pargo_description' => $this->get_option('pargo_description', ''),
            'pargo_door_cost' => $this->get_option('pargo_door_cost', '0'),
            'home_enable_free_shipping' => $this->get_option('home_enable_free_shipping', '0'),
            'home_free_shipping_amount' => $this->get_option('home_free_shipping_amount', '0'),
            'pargo_door_cost_5' => $this->get_option('pargo_door_cost_5', '0'),
            'pargo_door_cost_10' => $this->get_option('pargo_door_cost_10', '0'),
            'pargo_door_cost_15' => $this->get_option('pargo_door_cost_15', '0'),
        );
    }

    /**
     * Called to calculate shipping rates for this method. Rates can be added using the add_rate() method.
     *
     * @param array $package Package array.
     */
    public function calculate_shipping($package = array())
    {
        $weight = 0;

        [$cost_no_weight, $cost_5, $cost_10, $cost_15] = $this->getWeightCosts();

        foreach ($package['contents'] as $values) {
            $_product = $values['data'];
            if (!empty($_product->get_weight())) {
                $weight += $_product->get_weight() * $values['quantity'];
            }
        }

        $weight = wc_get_weight($weight, 'kg');

        if ($weight == 0) {
            $cost = $cost_no_weight;
        } else if ($weight <= 5) {
            $cost = $cost_5;
        } else if ($weight <= 10) {
            $cost = $cost_10;
        } else {
            $cost = $cost_15;
        }

        $rate = array(
            'id' => $this->id,
            'label' => $this->title . ': ' . $this->get_option('pargo_description'),
            'cost' => $cost,
            'calc_tax' => 'per_item'
        );

        if ($this->get_option('home_enable_free_shipping') === 'yes') {
            $total_cart_amount = (int)WC()->cart->cart_contents_total;

            if ($total_cart_amount >= $this->get_option('home_free_shipping_amount')) {
                $rate['cost'] = 0;
                $rate['label'] = $this->title . ': Free';
            }
        }

        // Register the rate
        $this->add_rate($rate);
    }

    /**
     * @return array
     */
    private function getWeightCosts(): array
    {
        $cost_no_weight = $this->get_option('pargo_door_cost');
        $cost_5 = $this->get_option('pargo_door_cost_5');
        $cost_10 = $this->get_option('pargo_door_cost_10');
        $cost_15 = $this->get_option('pargo_door_cost_15');


        return [$cost_no_weight, $cost_5, $cost_10, $cost_15];
    }
}
