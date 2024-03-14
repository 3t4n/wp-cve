<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Log all things!
 *
 * @since 1.0.0
 * @version 1.0.0
 */
class Woocci_Blocks_Support extends AbstractPaymentMethodType {
    /**
     * The gateway instance.
     **/
    private $gateway;
    /**
     * The gateway settings.
     **/
    protected $settings;

    /**
     * Payment method name/id/slug.
     *
     * @var string
     */
    protected $name = 'woocci_zaytech';

    /**
     * Initializes the payment method type.
     */
    public function initialize() {
        $this->settings = get_option( 'woocommerce_woocci_zaytech_settings', [] );
        $this->gateway  = new Woocci_zay_gateway();
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active() {
        return ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'];
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles() {
        $dependencies = [];
        $script_path       = '/build/blocks/blocks.js';
        $script_asset_path = WOOCCI_PLUGIN_PATH . '/build/blocks/blocks.asset.php';
        $script_asset      = file_exists( $script_asset_path )
            ? require( $script_asset_path )
            : array(
                'dependencies' => array(),
                'version'      => WOOCCI_VERSION
            );
        $script_url        = WOOCCI_PLUGIN_URL . $script_path;
        $dependencies = is_array( $script_asset ) && isset( $script_asset['dependencies'] )
            ? $script_asset['dependencies']
            : $dependencies;
        wp_register_script(
            'zaytech-clover-payments-blocks',
            $script_url,
            array_merge( [ 'woocci_scripts' ], $dependencies ),
            $script_asset[ 'version' ],
            true
        );

        if ( function_exists( 'wp_set_script_translations' ) ) {
          //  wp_set_script_translations( 'zaytech-clover-payments-blocks', 'zaytech_woocci', WOOCCI_PLUGIN_PATH . 'languages/' );
        }

        return [ 'zaytech-clover-payments-blocks' ];




    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */
    public function get_payment_method_data() {
        return [
            'title'       => $this->get_setting( 'title' ),
            'description' => $this->get_setting( 'description' ),
            'supports'    => array_filter( $this->gateway->supports, [ $this->gateway, 'supports' ] )
        ];
    }
}
