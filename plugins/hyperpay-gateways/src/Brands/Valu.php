<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class Valu extends DefaultGateway
{

  public $trans_mode = 'EXTERNAL';

  /**
   * should be lower case and unique
   * @var string $id 
   */
  public $id = "hyperpay_valu";

  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "Valu";

  /**
   * Description of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "Valu Plugin for Woocommerce";


  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
    "VALU" => "Valu",
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
      "customer.mobile" =>  $order->get_billing_phone(),
    ];

    $cart_index = 0;
    foreach ($woocommerce->cart->get_cart() as $cart_item) {

      $data["cart.items[$cart_index].name"] = $cart_item['data']->get_title();
      $data["cart.items[$cart_index].price"] = number_format((float)$cart_item['data']->get_price(), 2, '.', '');
      $data["cart.items[$cart_index].quantity"] = $cart_item['quantity'];
      !get_the_post_thumbnail_url($cart_item['product_id']) ?: $data["cart.items[$cart_index].description"] = get_the_post_thumbnail_url($cart_item['product_id']);
      $categories[][][] = strip_tags($cart_item['data']->get_categories());
      $cart_index++;
    }


    $data["customParameters[categories]"] = json_encode($categories);
    $data["customParameters[vendorName]"] = get_bloginfo('name');


    return ['body' => $data];
  }
}
