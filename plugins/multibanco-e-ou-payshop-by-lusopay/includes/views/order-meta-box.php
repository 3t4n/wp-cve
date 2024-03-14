<?php
$order = new WC_Order_Lusopay( $post->ID );
echo '<p>';

$payment_method = $order->lp_order_get_payment_method();
//$payment_method_title = $order->lp_get_payment_method_title();
//$order_total = $order->lp_order_get_total();

switch ($payment_method) {
  case 'lusopaygateway':
    $multibanco = new WC_Lusopaygateway;
    echo $multibanco->get_lp_template_mb_order_details($post->ID);
  break;

  case 'lusopay_payshop':
    $payshop = new WC_Lusopay_PS;
    echo $payshop->get_lp_template_ps_order_details($post->ID);
  break;

  case 'lusopay_mbway':
    $mbway = new WC_Lusopay_MBWAY;
    echo $mbway->get_lp_template_mbway_order_details($post->ID);
  break;

  default:
  echo __('No details available', 'lusopaygateway');
  break;
}
echo '</p>';
