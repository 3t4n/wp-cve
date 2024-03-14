<?php
/**
 * Scheduled Order Schedule Summary Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/scheduled-order-schedule-summary-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_schedule_summary', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;


/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_order_schedule_summary_skin', array(
'container'             => '',
'header'                => '',
'content'               => '',
'notice'                => '',
'subnotice'             => '',
));

$item_count = autoship_get_item_count( $autoship_order );

// Next Date.
$next_date        =  autoship_get_formatted_local_date ( $autoship_order['nextOccurrenceUtc'] );

// Header Notices & SubNotices
$notice_format = '%1$s <mark class="order-date">%2$s</mark>';

$notice = 'Active' == $autoship_order['status'] || 'Processing' == $autoship_order['status'] ?
sprintf( $notice_format,  __( 'Next occurrence is on', 'autoship' ), $next_date ):
sprintf( $notice_format,  __( 'Next occurrence would be on', 'autoship' ), $next_date );

$subnotice = 'Active' == $autoship_order['status'] || 'Processing' == $autoship_order['status'] ?
sprintf( __( '<mark class="order-quantity">%1$d</mark> item(s) currently scheduled for <mark class="order-frequency">Every %2$s %3$s</mark>', 'autoship' ), $item_count, $autoship_order['frequency'], strtolower( $autoship_order['frequencyType'] ) ):
sprintf( __( '<mark class="order-quantity">%1$d</mark> item(s) currently paused but originally scheduled for <mark class="order-frequency">Every %2$s %3$s</mark>', 'autoship' ), $item_count, $autoship_order['frequency'], strtolower( $autoship_order['frequencyType'] ) );

$notice     = apply_filters( 'autoship_order_details_summary_notice',
                              $notice, $next_date, autoship_get_scheduled_order_status_nicename( $autoship_order['status'] ),
                              $autoship_order );

$subnotice  = apply_filters( 'autoship_order_details_summary_subnotice',
                              $subnotice, $next_date, autoship_get_scheduled_order_status_nicename( $autoship_order['status'] ),
                              $autoship_order );



?>

    <div class="<?php echo $skin['container']; ?> <?php echo apply_filters( 'autoship_view_scheduled_order_summary_template_classes', 'autoship-scheduled-order-summary', $autoship_order ); ?>">

      <?php do_action( 'autoship_before_autoship_scheduled_order_summary', $autoship_order['status'], $autoship_order ); ?>

      <h3 class="<?php echo $skin['header']; ?>"><?php echo __('Status', 'autoship' );?>: <mark class="order-status"><?php echo autoship_get_scheduled_order_status_nicename( $autoship_order['status'] ); ?></mark></h3>

      <div class="schedule-summary <?php echo $skin['content']; ?>">
        <p class="notice <?php echo $skin['notice']; ?>"><?php echo $notice; ?></p>
        <p class="subnotice <?php echo $skin['subnotice']; ?>"><?php echo $subnotice; ?></p>
      </div>

    	<?php do_action( 'autoship_after_autoship_scheduled_order_summary', $autoship_order['status'], $autoship_order ); ?>

    </div>
