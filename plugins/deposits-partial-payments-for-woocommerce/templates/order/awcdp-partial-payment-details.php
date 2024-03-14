<?php
/**
 * Order details Summary
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!$order = wc_get_order($order_id)) {
    return;
}

?>

<p class="awcdp_deposits_summary_title"><?php esc_html_e('Partial payment details', 'deposits-partial-payments-for-woocommerce') ?></p>
<table class="woocommerce-table  awcdp_deposits_summary">
  <thead>
    <tr>
        <th ><?php esc_html_e('ID', 'deposits-partial-payments-for-woocommerce'); ?></th>
        <th ><?php esc_html_e('Payment', 'deposits-partial-payments-for-woocommerce'); ?></th>
        <th><?php esc_html_e('Amount', 'deposits-partial-payments-for-woocommerce'); ?></th>
        <th><?php esc_html_e('Status', 'deposits-partial-payments-for-woocommerce'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($schedule as $timestamp => $payment){
        $title = '';
        if(isset($payment['title'])){
          $title  = $payment['title'];
        } else {
          if(!is_numeric($timestamp)){
            if($timestamp === 'unlimited'){
              $title = esc_html__('Future Payments', 'deposits-partial-payments-for-woocommerce');
            } elseif($timestamp === 'deposit'){
              $title = esc_html__('Deposit', 'deposits-partial-payments-for-woocommerce');
            } else {
              $title = $timestamp;
            }
          } else {
            $title =  date_i18n(wc_date_format(),$timestamp);
          }
      }
      $title = apply_filters('awcdp_partial_payment_title',$title,$payment);
      $payment_order = false;
      if(isset($payment['id']) && !empty($payment['id'])) $payment_order = wc_get_order($payment['id']);
      if(!$payment_order) continue;
      $payment_id = $payment_order ? $payment_order->get_order_number(): '-';
      $status = $payment_order ? wc_get_order_status_name($payment_order->get_status()) : '-';
      $amount = $payment_order ? $payment_order->get_total() : $payment['total'];
      $price_args = array('currency' => $payment_order->get_currency());
      ?>
      <tr>
          <td> <?php echo wp_kses_post( $payment_id); ?> </td>
          <td> <?php echo wp_kses_post( $title); ?> </td>
          <td> <?php echo wp_kses_post( wc_price($amount,$price_args)); ?> </td>
          <td> <?php echo wp_kses_post( $status); ?> </td>
      </tr>
    <?php
    }
    ?>
    </tbody>
</table>


		  <?php 
		  /*
			$balance_text = esc_html__('Make balance payment :', 'deposits-partial-payments-for-woocommerce');
			$balance_text = apply_filters('awcdp_balance_payment_text',$balance_text);
		  
			$actions = wc_get_account_orders_actions( $order );
			if ( ! empty( $actions ) ) {
				foreach ( $actions as $key => $action ) {
					if( $key == 'pay' ){
						echo '<div class="awcdp_balance_pay ">';
						echo '<p>' . $balance_text . '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a> </p>';
						echo '</div>';
					}
				}
			}
			*/
		  ?> 

