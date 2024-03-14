<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class Tabby extends DefaultGateway
{


  public $server_to_server = true;

  public $trans_mode = 'EXTERNAL';

  /**
   * should be lower case and unique
   * @var string $id 
   */
  public $id = "hyperpay_tabby";

  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "Tabby";

  /**
   * Description of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "Tabby Plugin for Woocommerce";


  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
    "TABBY" => "Tabby",
  ];



  /**
   * to set extra parameter on requested data to connector 
   * just uncomment the function below
   * 
   * @param object $order
   * @return array 
   */

  public function setExtraData(WC_Order $order): array
  {
    global $woocommerce;

    $data = [
      "customer.mobile" => $order->get_billing_phone()
    ];

    if ($this->testMode) {
      $data["customer.email"] =  "card.success@tabby.ai";
      $data["customer.mobile"] =  "0500000001";
    }



    $cart_index = 0;

    foreach ($woocommerce->cart->get_cart() as $cart_item) {
      $data["cart.items[$cart_index].name"] = $cart_item['data']->get_title();
      $data["cart.items[$cart_index].price"] = number_format((float)$cart_item['data']->get_price(), 2, '.', '');
      $data["cart.items[$cart_index].quantity"] = $cart_item['quantity'];
      $data["cart.items[$cart_index].sku"] =  rand(111111, 99999);

      $cart_index++;
    }

    return ['body' => $data];
  }
}
