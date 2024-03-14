<?php

namespace Hyperpay\Gateways\Brands;

use Hyperpay\Gateways\App\DefaultGateway;
use WC_Order;


class Hypercash extends DefaultGateway
{

  public $trans_mode = 'EXTERNAL';

  /**
   * should be lower case and uniqe
   * @var string $id 
   */
  public $id = "hyperpay_hypercash";

  /**
   * The title which appear next to gateway on setting page 
   * @var string $method_title
   */
  public $method_title = "Hyperpay Hypercash Gateway";

  /**
   * Describtion of gateways which will appear next to title
   * @var string $method_description
   */
  public $method_description = "Hypercash Plugin for Woocommerce";


  /**
   * you can overwrite styles options by 
   * uncomment array below
   * 
   * @var array $hyperpay_payment_style
   */

  //    protected  $hyperpay_payment_style = [
  //     "card" => "Card",
  //     "plain" => "Plain"
  // ];

  public $successManualReviewCodePattern = '/^(000\.400\.0|000\.400\.100)/';
  public $successCodePattern = '/^(800\.400\.5|100\.400\.500)/';

  /**
   * 
   * the Brands supported by the gateway
   * @var array $supported_brands
   */
  protected $supported_brands = [
    "HYPERCASH" => "Hypercash",
  ];

  protected $order_id;

  public function __construct()
  {
    parent::__construct();

    $this->order_status = 'on-hold';

    if (!is_order_received_page())
      add_action('woocommerce_order_details_before_order_table', [$this, 'print_invoice_id']);
  }

  public function order_received_text($thanks_text, $order)
  {
    echo "<p> $thanks_text </p>";
    $this->print_invoice_id($order);
  }

  public function print_invoice_id($order)
  {
    $invoice_id = $order->get_meta('invoice_id');
    if ($invoice_id) {
      ?>
        <div class="woocommerce-message"><?php echo __("Invoice ID: ", "hyperpay-payments") . number_format($invoice_id, 0, '.', ' ') ?> </div>
      <?php
    }
  }


}
