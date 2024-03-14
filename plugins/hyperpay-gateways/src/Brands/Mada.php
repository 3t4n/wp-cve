<?php
namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class Mada extends DefaultGateway
{    
    /**
     * should be lower case and unique
     * @var string $id 
     */
    public $id = 'hyperpay_mada';
    
    /**
     * The title which appear next to gateway on setting page 
     * @var string $method_title
     */
    public $method_title = 'mada'; 

    /**
     * Description of gateways which will appear next to title
     * @var string $method_description
     */
    public $method_description = 'Mada Plugin for Woocommerce';


    /**
     * 
     * the Brands supported by the gateway
     * @var array $supported_brands
     */
    protected $supported_brands = [
        'MADA' => 'Mada',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->title = __('mada debit card', 'hyperpay-payments');
        $this->brands = ['MADA'];
    }


    public function renderPaymentForm(WC_Order $order, $result)
    {
        parent::renderPaymentForm($order,$result);
        wp_enqueue_style('hyperpay_mada_style', HYPERPAY_PLUGIN_DIR . '/src/assets/css/mada.css');
    }

}
