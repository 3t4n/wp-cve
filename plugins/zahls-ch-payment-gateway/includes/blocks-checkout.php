<?php


use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use ZahlsPaymentGateway\Util\CartUtil;


final class WC_Zahls_Blocks_Support extends AbstractPaymentMethodType
{
    /**
     * Name of the payment method.
     *
     * @var string
     */
    protected $name = 'zahls';
    protected $settings;
    protected $zahls_gateway;

    /**
     * Initializes the payment method type.
     */
    public function initialize()
    {
        $this->settings = get_option('woocommerce_zahls_settings', []);
        $this->zahls_gateway = new WC_Zahls_Gateway();
    }

    /**
     * Returns if this payment method should be active. If false, the scripts will not be enqueued.
     *
     * @return boolean
     */
    public function is_active()
    {
        $payment_gateways_class = WC()->payment_gateways();
        $payment_gateways = $payment_gateways_class->payment_gateways();

        return $payment_gateways['zahls']->is_available();
    }

    /**
     * Returns an array of scripts/handles to be registered for this payment method.
     *
     * @return array
     */
    public function get_payment_method_script_handles()
    {

		$parent_directory_path = ZAHLS_PLUGIN_DIR;
		$plugin_url = ZAHLS_PLUGIN_URL;
        $indexJsPath = $plugin_url . '/assets/js/index.js';
        $asset_path = $parent_directory_path . '/assets/js/index.asset.php';
		$version = ZAHLS_VERSION;
        $dependencies = [];
        if (file_exists($asset_path)) {
            $asset = require $asset_path;
            $version = is_array($asset) && isset($asset['version'])
                ? $asset['version']
                : $version;
            $dependencies = is_array($asset) && isset($asset['dependencies'])
                ? $asset['dependencies']
                : $dependencies;
        }
        wp_register_script(
            'wc-zahls-blocks-integration',
            $indexJsPath,
            $dependencies,
            $version,
            true
        );
        wp_set_script_translations(
            'wc-zahls-blocks-integration',
            'woocommerce'
        );
        return ['wc-zahls-blocks-integration'];
    }

    /**
     * Returns an array of key=>value pairs of data made available to the payment methods script.
     *
     * @return array
     */

    public function get_payment_method_data()
{
    
    $is_subscription = false;

    
    if($this->get_setting("subscriptions_enabled")  === 'yes'){
        // Check if the cart contains a subscription
        $paymentMethodChangeGET = !empty($_GET['change_payment_method']) ? $_GET['change_payment_method'] : null;
        if(CartUtil::isSubscription(WC()->cart, $paymentMethodChangeGET) == true){
        $is_subscription = true;
    }}


    

    return [
        'title' => $is_subscription ? $this->get_setting('subscriptions_title') : $this->get_setting('title'),
        'description' => $is_subscription ? $this->get_setting('subscriptions_user_desc') : $this->get_setting('description'),
        'supports' => $this->get_supported_features(),
        'icons' => $this->get_payment_icons(),
    ];
}

    /**
     * Returns an array of supported features.
     *
     * @return string[]
     */
    public function get_supported_features()
    {
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        return $payment_gateways['zahls']->supports;
    }

    /**
     * Returns an array of payment icons.
     *
     * @return array
     */
    public function get_payment_icons() 
    {

$paymentMethodChangeGET = !empty($_GET['change_payment_method']) ? $_GET['change_payment_method'] : null;

 if($this->get_setting("subscriptions_enabled")  === 'yes' and CartUtil::isSubscription(WC()->cart, $paymentMethodChangeGET) == true){
    $logos = $this->get_setting( 'subscription_logos' );
}else{
        $logos = $this->get_setting( 'logos' );
}



        $logo_array = [];

        if(is_array($logos)){
            foreach ($logos as $logo) {


                $logo_array[] = [
                    'src' => WC_HTTPS::force_https_url(plugins_url('cardicons/card_' . $logo . '.svg', dirname(__FILE__))),
                    'alt' => $logo,
                    'id' => $logo,
                    'class' => 'onetime',
                ];
            }
        }
    
        return $logo_array;
    }
}
