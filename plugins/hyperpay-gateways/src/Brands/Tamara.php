<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use Hyperpay\Gateways\Main;
use WC_Order;


class Tamara extends DefaultGateway
{


  public $trans_mode = 'EXTERNAL';

  public $server_to_server = true;

  public $isDynamicCheck = true;
  public $description, $configurations;


  /**
   * should be lower case and unique
   * @var string $id 
   */
  public $id = "hyperpay_tamara";

  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "Tamara";

  /**
   * Description of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "Tamara Plugin for Woocommerce";


  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
    "TAMARA" => "Tamara",
  ];


  public function __construct()
  {
    parent::__construct();
    add_filter('woocommerce_available_payment_gateways', [$this, 'conditional_payment_gateways'], 10, 1);
  }

  public function configuration($country, $amount)
  {

    $url = $this->testMode ? 'https://tamara-dev.hyperpay.com' : 'https://tamara.hyperpay.com';

    $data = [
      "country" => $country,
      "order_value" => [
        "amount" => $amount,
        "currency" => $this->currency
      ]
    ];

    $res = wp_remote_post("$url/api/tamara-description/{$this->entityId}", ['body' => $data]);
    $configurations = json_decode(wp_remote_retrieve_body($res), true);

    $locale = substr(get_locale(), 0, 2);
    if (isset($configurations['available_payment_labels'][0]["description_$locale"])) {
      $this->description = $configurations['available_payment_labels'][0]["description_$locale"];
    }

    return $configurations;
  }



  public function canMakePayment()
  {
    if (
      !isset($_POST['billingData']['country']) ||
      !isset($_POST['cartTotals']['total_price']) ||
      !isset($_POST['cartTotals']['currency_minor_unit'])
    ) {
      return wp_send_json([
        "canMakePayment" => false,
      ]);
    }


    $country = sanitize_text_field($_POST['billingData']['country']);
    $amount = sanitize_text_field(($_POST['cartTotals']['total_price']) / 100 ** ($_POST['cartTotals']['currency_minor_unit']));
    $this->configurations = $this->configuration($country, $amount);

    if (!isset($this->configurations['available_payment_labels']) || !count($this->configurations['available_payment_labels'])) {
      return wp_send_json([
        "canMakePayment" => false,
        "details" => $this->configurations["message"] ?? $this->configurations
      ]);
    }


    return  wp_send_json([
      "canMakePayment" => true,
      "description" => $this->description,
    ]);;
  }


  /**
   * check if the order have the minimum requirements
   * to display Tamara
   *  
   * @param array $available_gateways
   *
   * @return array $available_gateways
   */

  public function conditional_payment_gateways($available_gateways)
  {
    if (!is_admin() && is_checkout()) {
      $country = WC()->customer->get_billing_country();
      $amount = WC()->cart->total;
      $this->configurations = $this->configuration($country, $amount);

      if (!(isset($this->configurations['available_payment_labels']) && count($this->configurations['available_payment_labels']))) {
        unset($available_gateways['hyperpay_tamara']);
      }
    }

    return $available_gateways;
  }

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

    $cart_index = 0;
    foreach ($woocommerce->cart->get_cart() as $cart_item) {

      $data["cart.items[$cart_index].merchantItemId"] = "By_Hyperpay" . $cart_item['data']->get_id();
      $data["cart.items[$cart_index].type"] = $cart_item['data']->get_type() ?? 'N/A';
      $data["cart.items[$cart_index].name"] = $cart_item['data']->get_title();
      $data["cart.items[$cart_index].totalAmount"] = number_format((float)$cart_item['data']->get_price(), 2, '.', '');
      $data["cart.items[$cart_index].quantity"] = $cart_item['quantity'];
      $data["cart.items[$cart_index].sku"] = rand(111111, 999999);
      $cart_index++;
    }
    $amount = number_format($order->get_total(), 2, '.', '');
    $country = $order->get_billing_country();

    $configurations = $this->configuration($country, $amount);
    $data["customParameters[discount]"] = 00.00;
    $data["customParameters[tamara_payment_type]"] = $configurations['available_payment_labels'][0]['payment_type'];
    $data["customParameters[instalments]"] = $configurations['available_payment_labels'][0]['instalment'];
    $data["taxAmount"] = 00.00;

    return ['body' => $data];
  }


  public function iconSrc()
  {
    $locale = substr(get_locale(), 0, 2);

    $img = HYPERPAY_PLUGIN_DIR .  '/src/assets/images/default.png';

    if (file_exists(Main::ROOT_PATH . "/assets/images/TAMARA-logo_$locale.svg")) {
      $img = HYPERPAY_PLUGIN_DIR . "/src/assets/images/TAMARA-logo_$locale.svg";
    }

    return [$img];
  }


  /**
   * Set Tamara terms and conditions 
   * based on Tamara API configuration 
   * @param string $icon
   * @param string $id
   * 
   * @return string $icon
   */

  public function set_icons($icon, $id)
  {

    if ($id == $this->id) {

      $img = $this->iconSrc();
      $icons = "<img width='48.33' style='border:unset !important' class='hyperpay_gateways_logo' src='$img[0]'>";

      return $icons .
        "
         </label>
             <div class='payment_box terms_zoodpay payment_method_hyperpay_zoodpay'>
              <p>{$this->description}</p>
             </div>";
    }
    return $icon;
  }
}
