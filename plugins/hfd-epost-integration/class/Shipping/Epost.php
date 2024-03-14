<?php
/**
 * Created by PhpStorm.
 * Date: 6/4/18
 * Time: 6:06 PM
 */

namespace Hfd\Woocommerce\Shipping;

class Epost extends \WC_Shipping_Method
{
    const METHOD_ID = 'betanet_epost';

    /** @var string cost passed to [fee] shortcode */
    protected $fee_cost = '';

    /**
     * Constructor.
     *
     * @param int $instanceId
     */
    public function __construct($instanceId = 0)
    {
        $this->id = self::METHOD_ID;
        $this->instance_id = absint($instanceId);
        $this->method_title = __('Epost', 'hfd-integration');
        $this->method_description = __('Epost', 'hfd-integration');
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
        );
        $this->init();

        add_action( 'woocommerce_update_options_shipping_'.$this->id, array($this, 'process_admin_options'));+
		add_filter( 'woocommerce_package_rates', array( $this, 'epost_free_shipping_over_defined_price' ) );
    }

    /**
     * init user set variables.
     */
    public function init()
    {
        /* @var \Hfd\Woocommerce\Setting $setting */
        $this->instance_form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'hfd-integration'),
                'type' => 'checkbox',
                'label' => __('Enable this shipping method', 'hfd-integration'),
                'default' => 'yes',
            ),
            'title' => array(
                'title' => __('Epost', 'hfd-integration'),
                'type' => 'text',
                'default' => __('Epost', 'hfd-integration'),
                'desc_tip' => true
            ),
            'shipping_fee' => array(
                'title' => __('Shipping fee', 'hfd-integration'),
                'type' => 'number',
                'default' => 0
            ),
			'free_shipping_over' => array(
                'title' => __( 'Free shipping over', 'hfd-integration'),
                'type' => 'number',
                'default' => 0
            ),
        );

        $this->enabled = $this->get_option('enabled');
        $this->title = $this->get_option('title');
//        $this->instance_form_fields = include( 'includes/settings-flat-rate.php' );
//        $this->title = $setting->get('title');
//        $this->tax_status = $this->get_option('tax_status');
        $this->cost = $this->get_option('shipping_fee');
        $this->free_shipping_over = $this->get_option('free_shipping_over');
//        $this->type = $this->get_option('type', 'class');
    }

    /**
     * calculate_shipping function.
     * @param array $package (default: array())
     */

    public function calculate_shipping($package = array())
    {
        $rate = array(
            'id' => $this->id . $this->instance_id,
            'label' => $this->title,
            'cost' => $this->instance_settings['shipping_fee'],
        );

        $this->add_rate($rate);

//        do_action('woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate);
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isEpost($method)
    {
        return substr($method, 0, strlen(self::METHOD_ID)) == self::METHOD_ID;
    }
	
	public function epost_free_shipping_over_defined_price( $rates ){
		global $woocommerce;
		$free_shipping_over = $this->get_option('free_shipping_over');
		if( $free_shipping_over ){
			$cart_total = floatval( preg_replace( '#[^\d.]#', '', WC()->cart->cart_contents_total ) );
			if( $cart_total >= $free_shipping_over ){
				foreach( $rates as $rate_id => $rate ) {
					if( $this->id == $rate->method_id ){
						$rate->set_cost( 0 );
						$rates[$rate_id] = $rate;
						break;
					}
				}
			}
		}
		return $rates;
	}
}