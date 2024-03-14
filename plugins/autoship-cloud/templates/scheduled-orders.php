<?php
/**
 * Main Schedule Orders iframe and app Template
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders.php
*/
defined( 'ABSPATH' ) || exit;
?>

<div class="autoship-container">

	<?php
  /**
   * Pre Scheduled Orders APP and iFrame hook.
   *
   * @hooked autoship_scheduled_orders_header_wp_notices_display - 9
   * @hooked autoship_scheduled_orders_custom_html_header_display - 10
   * @hooked autoship_non_hosted_scheduled_order_error_display - 11
   */
  do_action( 'autoship_before_autoship_scheduled_orders', $customer_id, $autoship_customer_id ); ?>

    <iframe src="<?php echo esc_attr( $app_url ); ?>" class="autoship-scheduled-orders-iframe" frameborder="0"></iframe>

	<?php do_action( 'autoship_after_autoship_scheduled_orders', $customer_id, $autoship_customer_id ); ?>

</div>
