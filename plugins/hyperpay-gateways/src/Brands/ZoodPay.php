<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class ZoodPay extends DefaultGateway
{
  protected $service_code;

  public $trans_mode = 'EXTERNAL';

  /**
   * should be lower case and unique
   * @var string $id 
   */
  public $id = "hyperpay_zoodpay";

  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "Zoodpay";

  /**
   * Description of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "Zoodpay Plugin for Woocommerce";

  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
    "ZOODPAY" => "Zoodpay",
  ];

  public $min_limit, $max_limit, $instalment_count, $terms;

  public function __construct()
  {
    parent::__construct();
    $service_code['service_code'] = [
      'title' => __('service code', 'hyperpay-payments'),
      'type' => 'text',
      'default' => 'ZPI'
    ];

    /**
     * unset unwanted fields
     */
    unset($this->form_fields['custom_style']);
    unset($this->form_fields['payment_style']);
    unset($this->form_fields['trans_mode']);

    /**
     * add service_code field to position number 6
     */
    $this->form_fields = array_merge(array_slice($this->form_fields, 0, 6), $service_code, array_slice($this->form_fields, 6));

    $this->trans_mode = 'EXTERNAL'; // in test mode should be EXTERNAL to redirect to connector
    $this->service_code = $this->get_option('service_code');

    // call zoodpay API to set payment configurations
    $this->configuration();
    


    add_filter('woocommerce_available_payment_gateways', [$this, 'conditional_payment_gateways'], 10, 1);
  }

  /**
   * Call Zoodpay API to set terms, instalment count and total limits 
   * 
   * @return void
   */
  public function configuration()
  {

    $url = $this->testMode ? 'https://zoodpay-sandbox.hyperpay.com/api/getTerms' : 'https://zoodpay.hyperpay.com/api/getTerms';

    $res = wp_remote_post($url, ['body' => ['entity_id' => $this->entityId]]);
    $res = json_decode(wp_remote_retrieve_body($res), true);


    $this->terms = $res['description'] ?? '';

    $this->instalment_count = $res['instalments'] ?? 1;

    $this->min_limit = $res['min_limit'] ?? 1;
    $this->max_limit = $res['max_limit'] ?? 200;
  }

  /**
   * Check if total order amount is grater than min limit
   * and less than max limit 
   * @param array $available_gateways
   * 
   * @return array $available_gateways
   */

  public function conditional_payment_gateways($available_gateways)
  {

    if(is_checkout()){    

      $total = WC()->cart->total;
      if ($total < $this->min_limit || $total > $this->max_limit)
        unset($available_gateways['hyperpay_zoodpay']);

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
      "customer.mobile" =>  $this->getPhone($order),
      "customParameters[service_code]" => $this->service_code
    ];

    $cart_index = 0;
    foreach ($woocommerce->cart->get_cart() as $cart_item) {
      $data["cart.items[$cart_index].name"] = $cart_item['data']->get_title();
      $data["cart.items[$cart_index].price"] = number_format((float)$cart_item['data']->get_price(), 2, '.', '');
      $data["cart.items[$cart_index].quantity"] = $cart_item['quantity'];
      $categories[][][] = strip_tags($cart_item['data']->get_categories());
      $cart_index++;
    }

    $data["customParameters[categories]"] = json_encode($categories);

    return ['body' => $data];
  }

  /**
   * reformat phone number
   * @param WC_Order
   * @return string $phone
   */
  private function getPhone(WC_Order $order): string
  {
    $phone =  $order->get_billing_phone();

    if (substr($phone, 0, 2) == '07')
      $phone = substr_replace($phone, '962', 0, 1);

    return $phone;
  }

  /**
   * Set Zoodpay tems and conditions 
   * based on Zoodpay API configuration 
   * @param string $icon
   * @param string $id
   * 
   * @return string $icon
   */

  public function set_icons($icon, $id): string
  {
    if ($id == $this->id) {

      global $woocommerce;

      echo "<div id='hyperpayModal' class='modal'>
                <div class='modal-content'>
                  <span class='close'>&times;</span>
                  <p></p>
                </div>
            </div>";

      wp_enqueue_script('hyperpay_script_modal',  __DIR__ . '../src/assets/js/modal.js', ['jquery'], false, true);


      $img = __DIR__ . "../src/assets/images/ZOODPAY-logo.png";
      $icons = "<img class='hyperpay_gateways_logo' src='$img'>";

      $amount = $woocommerce->cart->total;
      $instalment = number_format(round($amount / $this->instalment_count, 2), 2, '.', '');

      return $icons .
        "
        </label>
            <div class='payment_box terms_zoodpay payment_method_hyperpay_zoodpay'>
             <p>{$this->instalment_count} " . __('Instalment of', 'hyperpay-payments') . " <b>$instalment</b>/" . __('mo.', 'hyperpay-payments') . "</p> <a class='woocommerce-privacy-policy-link ' onClick='openModal(`{$this->terms}`)'>" . __('Terms and Conditions', 'hyperpay-payments') . "</a>
            </div>";
    }
    return $icon;
  }
}
