<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class UrPay extends DefaultGateway
{

  public $trans_mode = 'EXTERNAL';

  /**
   * should be lower case and unique
   * @var string $id 
   */
  public $id = "hyperpay_urpay";
  
  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "UrPay";

  /**
   * Description of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "UrPay Plugin for Woocommerce";


  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
		"URPAY" => "Urpay",
    ];


}
