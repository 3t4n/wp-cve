<?php

namespace PargoWp\Includes;

use WC_Shipping_Method;

/**
 *
 */
class Pargo_Wp_Shipping_Method extends WC_Shipping_Method
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
        parent::__construct($instance_id);
        $this->id = 'wp_pargo';
        $this->method_title = __('Pargo Pickup Point', 'woocommerce');
        $this->method_description = __('Shipping Method for Pargo Pickup', 'woocommerce');
        // Availability & Countries
        $this->availability = 'including';
        $this->countries = array(
            'ZA', //South Africa
            'EG' //Egypt
        );
        $this->instance_id = absint($instance_id);
        $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
        $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Pargo Pickup Point', 'woocommerce');
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
                    'Choose your most convenient Pargo Pickup Point',
                    'woocommerce'
                ),
            ),
            'use_backend_shipping' => array(
	            'title' => __('Use Backend Shipping', 'woocommerce'),
	            'type' => 'checkbox',
	            'description' => __('Disabling this prevents orders being sent to Pargo for shipping fulfilment. You will need to manually create these orders in myPargo or create orders via a Pargo API enabled partner.', 'woocommerce'),
	            'default' => __('yes', 'woocommerce')
            ),
            'enable_free_shipping' => array(
                'title' => __('Enable Free Shipping', 'woocommerce'),
                'type' => 'checkbox',
                'default' => __('no', 'woocommerce')
            ),
            'free_shipping_amount' => array(
                'title' => __('Free Shipping Amount', 'woocommerce'),
                'type' => 'number',
                'description' => __('Set the minimum amount for free shipping', 'woocommerce'),
                'default' => 199
            ),
            'pargo_cost' => array(
                'title' => __('No Weight Shipping Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __(
                    'This controls the cost of Pargo delivery without product weight settings.',
                    'woocommerce'
                ),
                'default' => __('80', 'woocommerce')
            ),
            'pargo_cost_5' => array(
                'title' => __('5kg Shipping to Pickup Point Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to pickup point for 0-5kg items.', 'woocommerce'),
                'default' => __('70', 'woocommerce')
            ),
            'pargo_cost_10' => array(
                'title' => __('10kg Shipping to Pickup Point Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to pickup point for 5-10kg items.', 'woocommerce'),
                'default' => __('103', 'woocommerce')
            ),
            'pargo_cost_15' => array(
                'title' => __('15kg Shipping to Pickup Point Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to pickup point for 10-15kg items.', 'woocommerce'),
                'default' => __('136', 'woocommerce')
            ),
            'pargo_map_display' => array(
                'title' => __('Pargo map display as static widget', 'woocommerce'),
                'type' => 'checkbox',
                'description' => __('Display map as a modal or static widget', 'woocommerce'),
                'default' => __('no', 'woocommerce')
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
            'enable_free_shipping' => array(
                'title' => __('Enable Free Shipping', 'woocommerce'),
                'type' => 'checkbox',
                'default' => __($this->get_option('enable_free_shipping', '0'), 'woocommerce')
            ),
            'free_shipping_amount' => array(
                'title' => __('Free Shipping Amount', 'woocommerce'),
                'type' => 'number',
                'description' => __('Set the minimum amount for free shipping', 'woocommerce'),
                'default' => $this->get_option('free_shipping_amount', '0')
            ),
            'pargo_cost' => array(
                'title' => __('No Weight Shipping Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __(
                    'This controls the cost of Pargo delivery without product weight settings.',
                    'woocommerce'
                ),
                'default' => __($this->get_option('pargo_cost', '0'), 'woocommerce')
            ),
            'pargo_cost_5' => array(
                'title' => __('5kg Shipping to Pickup Point Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to pickup point for 0-5kg items.', 'woocommerce'),
                'default' => __($this->get_option('pargo_cost_5', '0'), 'woocommerce')
            ),
            'pargo_cost_10' => array(
                'title' => __('10kg Shipping to Pickup Point Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to pickup point for 5-10kg items.', 'woocommerce'),
                'default' => __($this->get_option('pargo_cost_10', '0'), 'woocommerce')
            ),
            'pargo_cost_15' => array(
                'title' => __('15kg Shipping to Pickup Point Cost', 'woocommerce'),
                'type' => 'number',
                'description' => __('This controls the cost of Pargo delivery to pickup point for 10-15kg items.', 'woocommerce'),
                'default' => __($this->get_option('pargo_cost_15', '0'), 'woocommerce')
            )
        );
        $this->instance_settings = array(
            'pargo_description' => $this->get_option('pargo_description', ''),
            'pargo_cost' => $this->get_option('pargo_cost', '0'),
            'enable_free_shipping' => $this->get_option('enable_free_shipping', '0'),
            'free_shipping_amount' => $this->get_option('free_shipping_amount', '0'),
            'pargo_cost_5' => $this->get_option('pargo_cost_5', '0'),
            'pargo_cost_10' => $this->get_option('pargo_cost_10', '0'),
            'pargo_cost_15' => $this->get_option('pargo_cost_15', '0'),
        );
    }

    /**
     * calculate_shipping function.
     * WC_Shipping_Method::get_option('pargo_cost_10');
     * @access public
     * @return array
     */
    public function getPargoSettings()
    {
        $pargosetting['pargo_description'] = $this->get_option('pargo_description');
        $pargosetting['pargo_map_token'] = $this->get_option('pargo_map_token');
        $pargosetting['pargo_url_endpoint'] = $this->get_option('pargo_url_endpoint');
        $pargosetting['pargo_buttoncaption'] = $this->get_option('pargo_buttoncaption');
        $pargosetting['pargo_buttoncaption_after'] = $this->get_option('pargo_buttoncaption_after');
        $pargosetting['pargo_map_display'] = $this->get_option('pargo_map_display');

        return $pargosetting;
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

        if ($this->get_option('enable_free_shipping') === 'yes') {
            $total_cart_amount = (int)WC()->cart->cart_contents_total;

            if ($total_cart_amount >= $this->get_option('free_shipping_amount')) {
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
        $cost_no_weight = $this->get_option('pargo_cost');
        $cost_5 = $this->get_option('pargo_cost_5');
        $cost_10 = $this->get_option('pargo_cost_10');
        $cost_15 = $this->get_option('pargo_cost_15');


        return [$cost_no_weight, $cost_5, $cost_10, $cost_15];
    }

	/**
	 * Return the default style sheet for pargo_wp.css
	 *
	 * @return string
	 */
	public function default_styling()
	{
		$styling = "";
		// Backward compatibility for v2
		$v2_styles = [
			[
				'option_key' => "pargo_style_button",
				'class_name' => '.woocommerce button.pargo_style_button',
				'label' => "Sets the style of the button pressed to select a pickup point.",
				'default' => ''
			],
			[
				'option_key' => "pargo_style_title",
				'label' => "Set the style of the selected Pargo Point title.",
				'default' => 'font-size:16px;font-weight:bold;margin-bottom:0px;margin-top:0px;max-width:250px;'
			],
			[
				'option_key' => "pargo_style_desc",
				'label' => "Set the style of the selected Pargo Point line items.",
				'default' => 'font-size:12px;margin-bottom:0px;margin-top:0px;max-width:250px;'
			],
			[
				'option_key' => "pargo_style_image",
				'label' => "Set the style of the selected Pargo Point image.",
				'default' => 'max-width:250px;border:1px solid #EBEBEB;border-radius:2px;'
			]
		];
		foreach ($v2_styles as $option) {
			$styling .= "\n/* " . $option['label'] . " */\n";
			if (isset($option['class_name'])) {
				$styling .= $option['class_name'];
			} else {
				$styling .= "." . $option['option_key'];
			}
			$styling .= " {\n";
			$style = $option['default'];
			if ($this->get_option($option['option_key'])) {
				$style = $this->get_option($option['option_key']);
			}
			$styling .= $style;
			$styling .= "}\n";
		}
		return $styling;
	}
}
