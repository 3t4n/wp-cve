<?php
if (!defined('ABSPATH')) {
    exit;
}

if ($order && $order->get_type() != AWCDP_POST_TYPE ) {
    $payment_schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
    if (!is_array($payment_schedule) || empty($payment_schedule)) {
      ?>
      <div>
        <h4><?php esc_html_e('No payment schedule found.', 'deposits-partial-payments-for-woocommerce'); ?></h4>
      </div>
      <?php
    } else {
      ?>
      <table style="width:100%; text-align:left;" >
        <thead>
          <tr>
            <th><?php esc_html_e('ID', 'deposits-partial-payments-for-woocommerce'); ?> </th>
            <th><?php esc_html_e('Payment', 'deposits-partial-payments-for-woocommerce'); ?> </th>
            <th><?php esc_html_e('Payment method', 'deposits-partial-payments-for-woocommerce'); ?> </th>
            <th><?php esc_html_e('Amount', 'deposits-partial-payments-for-woocommerce'); ?> </th>
            <th><?php esc_html_e('Status', 'deposits-partial-payments-for-woocommerce'); ?> </th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($payment_schedule as $timestamp => $payment) {
            $date = '';
            if (isset($payment['title'])) {
              $date = $payment['title'];
            } else {
              if (!is_numeric($timestamp)) {
                $date = '-';
              } else {
                $date = date_i18n(wc_date_format(), $timestamp);
              }
            }
            $date = apply_filters('awcdp_partial_payment_title', $date, $payment);
            $payment_order = false;
            if (isset($payment['id']) && !empty($payment['id'])) $payment_order = wc_get_order($payment['id']);
            if (!$payment_order) continue;
            $gateway = $payment_order ? $payment_order->get_payment_method_title() : '-';
            $payment_id = $payment_order ? '<a href="' . esc_url($payment_order->get_edit_order_url()) . '">' . $payment_order->get_order_number() . '</a>' : '-';
            $status = $payment_order ? wc_get_order_status_name($payment_order->get_status()) : '-';
            $amount =  $payment_order->get_total() - $payment_order->get_total_refunded();
            $price_args = array('currency' => $payment_order->get_currency());
            ?>
            <tr>
              <td><?php echo wp_kses_post( $payment_id); ?></td>
              <td><?php echo wp_kses_post( $date); ?></td>
              <td><?php echo wp_kses_post( $gateway); ?></td>
              <td><?php echo wp_kses_post( wc_price($amount, $price_args)); ?></td>
              <td><?php echo wp_kses_post( $status); ?></td>
              <td><a href="<?php echo esc_url($payment_order->get_edit_order_url()); ?>" class="button" > <?php esc_html_e('View', 'deposits-partial-payments-for-woocommerce'); ?> </a></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
      <?php
    }
}


?>

<script>
jQuery(document).ready(function () {

    function reload_payments_metabox() {
        jQuery('#awcdp_deposits_partial_payments').block({
          message: null,
          overlayCSS: { background: '#fff', opacity: 0.6 }
        });

        var data = {
          action: 'awcdp_reload_payments_metabox',
          order_id: woocommerce_admin_meta_boxes.post_id,
          security: '<?php echo wp_create_nonce('awcdp-deposits-partial-payments-refresh'); ?>'
        };

        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: data,
            type: 'POST',
            success: function (response) {
              if (response.success) {
                jQuery('#awcdp_deposits_partial_payments div.inside').empty().append(response.data.html);
                jQuery('#woocommerce-order-items').unblock();
                jQuery('#awcdp_deposits_partial_payments').unblock();
              }
            }
        });
    }

    jQuery(document.body).on ('order-totals-recalculate-complete', function(){
      window.setTimeout(function(){
        reload_payments_metabox();
      },1500);
    });

});
</script>
