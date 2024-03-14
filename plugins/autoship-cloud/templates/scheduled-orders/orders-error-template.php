<?php
/**
 * Scheduled Orders Error Template
 *
 * Displays the Error Information when the API Call fails on the Scheduled Orders Screen.
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/orders-error-template.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'autoship_include_scheduled_orders_error', true, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_orders_error_skin', array(
'container'             => !is_wp_error( $autoship_orders ) ? 'no-error' : '',
'content'               => '',
'header'                => '',
'notice'                => '',
'subnotice'             => '',
));

do_action( 'autoship_before_schedule_orders_error', $autoship_orders, $customer_id, $autoship_customer_id );

?>

<div class="<?php echo $skin['container'];?> <?php echo apply_filters('autoship_scheduled_orders_error_template_classes', 'autoship-scheduled-orders-template autoship-error-template', $customer_id, $autoship_customer_id );?> ">

  <div class="autoship-error <?php echo $skin['content'];?>">

  	<?php do_action( 'autoship_scheduled_orders_error_content', $customer_id, $autoship_customer_id, $autoship_orders ); ?>

  </div>

</div>

<?php
do_action( 'autoship_after_schedule_orders_error', $customer_id, $autoship_customer_id );
