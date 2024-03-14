<?php
/**
 * Booking meta box html
 */
defined( 'ABSPATH' ) || exit;
$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
$booking_id = $post->ID;
$post_meta = get_post_meta( $booking_id );
$booking = $this->get_booking_cache( $booking_id );
if( empty( $booking ) ) {
    $booking = $booking_controller->load_booking_detail( $booking_id );
}
$order_info = isset( $booking->em_order_info ) ? $booking->em_order_info : array();
$tickets = isset( $order_info['tickets'] ) ? $order_info['tickets'] : array();
$ticket_sub_total = $offers = 0;
?>
<div class="panel-wrap ep_event_metabox">
    <div class="ep-py-3 ep-ps-3 ep-fw-bold ep-text-uppercase ep-text-small"><?php esc_html_e( 'Tickets', 'eventprime-event-calendar-management' );?></div>
    <div class="ep-box-tickets-table">
        <table class="ep-tickets-table ep-table ep-table-hover ep-text-small ep-table-borderless ep-text-start">
            <thead>
                <tr>
                    <th class="ep-ticket-item ep-ticket-type" colspan="2"><?php esc_html_e( 'Ticket Type','eventprime-event-calendar-management' );?></th>
                    <th class="ep-ticket-item ep-ticket-cost"><?php esc_html_e( 'Cost','eventprime-event-calendar-management' );?></th>
                    <th class="ep-ticket-item ep-ticket-qty"><?php esc_html_e( 'Qty','eventprime-event-calendar-management' );?></th>
                    <th  class="ep-ticket-item ep-ticket-fee"><?php esc_html_e( 'Additional fees','eventprime-event-calendar-management' );?></th>
                    <th class="ep-ticket-item ep-ticket-total"><?php esc_html_e( 'Sub Total','eventprime-event-calendar-management' );?></th>
                </tr>
            </thead>
            <?php 
            if( isset( $booking->em_old_ep_booking ) && ! empty( $booking->em_old_ep_booking ) ) {
                if( ! empty( $tickets ) ){?>
                    <tbody>
                        <?php foreach( $tickets as $ticket ){
                            if( ! empty( $ticket->offer ) ) {
                                $offers += $ticket->offer;
                            }?>
                            <tr>
                                <td colspan="2"><?php echo esc_attr( $ticket->name );?></td>
                                <td><?php echo esc_html( ep_price_with_position( $ticket->price ) );?></td>
                                <td><?php echo esc_attr( $ticket->qty );?></td>
                                <td>
                                    <?php $additional_fees = array();
                                    if( isset( $ticket->additional_fee ) ) {
                                        foreach( $ticket->additional_fee as $fees ){
                                            $additional_fees[] = $fees->label.' ('.ep_price_with_position( $fees->price * $ticket->qty) .')';
                                        }
                                    }
                                    if(!empty($additional_fees)){
                                        echo implode(' | ',$additional_fees);
                                    }else{
                                        echo '--';
                                    }?>
                                </td>
                                <td><?php echo esc_html( ep_price_with_position( $ticket->subtotal ) );?></td>
                            </tr><?php 
                            $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;
                        }?>
                    </tbody><?php
                } else if( ! empty( $order_info['order_item_data'] ) ) {?>
                    <tbody>
                        <?php foreach( $order_info['order_item_data'] as $order_item_data ){?>
                            <tr>
                                <td colspan="2"><?php echo esc_attr( $order_item_data->variation_name );?></td>
                                <td><?php echo esc_html( ep_price_with_position( $order_item_data->price ) );?></td>
                                <td><?php echo esc_attr( $order_item_data->quantity );?></td>
                                <td><?php echo '--';?></td>
                                <td><?php echo esc_html( ep_price_with_position( $order_item_data->sub_total ) );?></td>
                            </tr><?php 
                            $ticket_sub_total = $ticket_sub_total + $order_item_data->sub_total;
                        }?>
                    </tbody><?php
                }
            } else{
                if( ! empty( $tickets ) ){?>
                    <tbody>
                        <?php foreach( $tickets as $ticket ){
                            if( ! empty( $ticket->offer ) ) {
                                $offers += $ticket->offer;
                            }?>
                            <tr>
                                <td colspan="2"><?php echo esc_attr( $ticket->name );?></td>
                                <td><?php echo esc_html( ep_price_with_position( $ticket->price ) );?></td>
                                <td><?php echo esc_attr( $ticket->qty );?></td>
                                <td>
                                    <?php $additional_fees = array();
                                    if(isset($ticket->additional_fee)){
                                        foreach($ticket->additional_fee as $fees){
                                            $additional_fees[] = $fees->label.' ('.ep_price_with_position($fees->price * $ticket->qty).')';
                                        }
                                    }
                                    if(!empty($additional_fees)){
                                        echo implode(' | ',$additional_fees);
                                    }else{
                                        echo '--';
                                    }?>
                                </td>
                                <td><?php echo esc_html( ep_price_with_position( $ticket->subtotal ) );?></td>
                            </tr><?php 
                            $ticket_sub_total = $ticket_sub_total + $ticket->subtotal;
                        }?>
                    </tbody><?php
                } else if( ! empty( $order_info['order_item_data'] ) ) {?>
                    <tbody>
                        <?php foreach( $order_info['order_item_data'] as $order_item_data ){?>
                            <tr>
                                <td colspan="2"><?php echo esc_attr( $order_item_data->variation_name );?></td>
                                <td><?php echo esc_html( ep_price_with_position( $order_item_data->price ) );?></td>
                                <td><?php echo esc_attr( $order_item_data->quantity );?></td>
                                <td><?php echo '--';?></td>
                                <td><?php echo esc_html( ep_price_with_position( $order_item_data->sub_total ) );?></td>
                            </tr><?php 
                            $ticket_sub_total = $ticket_sub_total + $order_item_data->sub_total;
                        }?>
                    </tbody><?php
                }
            }?>  
        </table>
    </div>
    <div class="ep-order-data-row ep-order-totals-items">
        <div class="ep-used-coupons">
            <?php if(isset($order_info['coupon_code'])):?>
            <ul class="ep_coupon_list">
                <li><strong><?php esc_html_e('Coupon','eventprime-event-calendar-management');?></strong></li>
                <li class="code">
                    <span><?php echo esc_html($order_info['coupon_code']);?></span>
                </li>
            </ul>
            <?php endif;?>
        </div>
        
        <table class="ep-order-totals ep-table ep-table-hover ep-text-small ep-table-borderless ep-ml-4">
            <tbody>
                <tr>
                    <td class="label"><?php esc_html_e( 'Event Fees:', 'eventprime-event-calendar-management' );?></td>
                    <td width="1%"></td>
                    <td class="ep-ticket-total-amount">
                        <span>
                            <?php if( !empty( $order_info['event_fixed_price'] ) ) {
                                echo esc_html( ep_price_with_position( $order_info['event_fixed_price'] ) );
                            } else{
                                echo esc_html( ep_price_with_position( 0 ) );
                            }?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label"><?php esc_html_e( 'Tickets Subtotal:', 'eventprime-event-calendar-management' );?></td>
                    <td width="1%"></td>
                    <td class="ep-ticket-total-amount">
                        <span><?php echo esc_html( ep_price_with_position( $ticket_sub_total ) );?></span>
                    </td>
                </tr>
                <?php 
                if( ! empty( $offers ) ) {?>
                    <tr>
                        <td class="label"><?php esc_html_e( 'Offers:', 'eventprime-event-calendar-management' );?></td>
                        <td width="1%"></td>
                        <td class="ep-ticket-total-amount"><span>-<?php echo esc_html( ep_price_with_position( $offers ) );?></span></td>
                    </tr><?php
                }
                if( isset( $order_info['coupon_code'] ) ) {?>
                    <tr>
                        <td class="label"><?php esc_html_e( 'Coupon:', 'eventprime-event-calendar-management' );?></td>
                        <td width="1%"></td>
                        <td class="ep-ticket-total-amount"><span>-<?php echo esc_html( ep_price_with_position( $order_info['discount'] ) );?></span></td>
                    </tr><?php 
                }?>
                <tr>
                    <td class="label"><?php esc_html_e( 'Ticket Total:', 'eventprime-event-calendar-management' );?></td>
                    <td width="1%"></td>
                    <td class="ep-ticket-total-amount">
                        <span>
                            <?php echo ep_get_event_booking_total( $booking ); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="clear"></div>
        <div class="clear"></div>
    </div>
</div>
