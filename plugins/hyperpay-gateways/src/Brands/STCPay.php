<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class STCPay extends DefaultGateway
{

    public $trans_mode = 'EXTERNAL';

    public $server_to_server = false;

    /**
     * should be lower case and unique
     * @var string $id 
     */
    public $id = 'hyperpay_stcpay';

    /**
     * The title which appear next to gateway on setting page 
     * @var string $method_title
     */
    public $method_title = 'STCPay';

    /**
     * Description of gateways which will appear next to title
     * @var string $method_description
     */
    public $method_description = 'STCPay Plugin for Woocommerce';


    /**
     * 
     * the Brands supported by the gateway
     * @var array $supported_brands
     */
    protected $supported_brands = [
        'STC_PAY' => 'STCPay',
    ];
  
}
