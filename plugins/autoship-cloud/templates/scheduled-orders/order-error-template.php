<?php
/**
 * Scheduled Order Error Template
 *
 * Displays the Error Information when the API Call fails on the Scheduled Order.
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-error-template.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'autoship_include_scheduled_order_error', true, $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_error_skin', array(
'container'             => '',
'content'               => '',
'header'                => '',
'notice'                => '',
'subnotice'             => '',
));


// Add fall back
if ( empty( $autoship_order ) || !is_wp_error( $autoship_order  ) )
$autoship_order = new WP_Error( 'Order Display Failed', sprintf( __( 'A problem was encountered while trying to display %s #%1$s', 'autoship' ), autoship_translate_text( 'Scheduled Order' ) ,'<mark class="order-number">' . $autoship_order_id . '</mark>' ) );

do_action( 'autoship_before_schedule_order_error', $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id  );
?>

<div class="<?php echo $skin['container']; ?> <?php echo apply_filters('autoship_scheduled_order_error_template_classes', 'autoship-scheduled-order-template autoship-error-template', $autoship_order );?>">

  <div class="autoship-error <?php echo $skin['content']; ?>">

    <h2 class="<?php echo $skin['header'];?>"><?php printf( __( '%s #%1$s', 'autoship' ), autoship_translate_text( 'Scheduled Order' ),
    '<mark class="order-number">' . $autoship_order_id . '</mark>'
    ); ?></h2>

    <h3 class="<?php echo $skin['notice'];?>"><span><?php echo $autoship_order->get_error_code(); ?></span></h3>

  </div>

  <p class="autoship_order_error <?php echo $skin['subnotice'];?>"><?php echo $autoship_order->get_error_message();?></p>

</div>

<?php
do_action( 'autoship_after_schedule_order_error', $autoship_order, $customer_id, $autoship_customer_id, $autoship_order_id  );
