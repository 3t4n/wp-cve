<?php
/**
 * Main Schedule Order Edit Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-template.php
*/
defined( 'ABSPATH' ) || exit;
$skin = apply_filters( 'autoship_scheduled_order_edit_order_template_skin', array(
'container'                 => '',
));

?>

<div class="<?php echo $skin['container']?> <?php echo apply_filters('autoship_edit_scheduled_order_forms_classes', 'autoship-scheduled-order-template', $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id );?>">

  <?php
  /**
   * Pre Scheduled Order Details form hook.
   *
   * @hooked autoship_scheduled_order_header_template_display - 9
   * @hooked autoship_scheduled_order_schedule_summary_template_display - 10
   * @hooked autoship_scheduled_order_schedule_form_display - 11
   */
  do_action( 'autoship_before_scheduled_order_edit', $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id ); ?>

  <?php
  /**
   * Scheduled Order Details form hook.
   *
   * @hooked autoship_scheduled_order_items_form_display - 10
   */
  do_action( 'autoship_scheduled_order_edit', $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id ); ?>

  <?php
  /**
   * Post Scheduled Order Details form hook.
   *
   * @hooked autoship_scheduled_order_shipping_rate_form_display - 8
   * @hooked autoship_scheduled_order_payment_method_summary_display - 9
   * @hooked autoship_scheduled_order_payment_form_display - 10
   * @hooked autoship_scheduled_order_address_template_display - 11
   */
  do_action( 'autoship_after_scheduled_order_edit', $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id ); ?>


</div><!-- .autoship-scheduled-order-template -->
