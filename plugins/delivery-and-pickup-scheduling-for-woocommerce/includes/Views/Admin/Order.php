<?php

/**
 * Houses Order related View methods.
 *
 * Author:          Uriahs Victor
 * Created on:      17/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */
namespace Lpac_DPS\Views\Admin;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Functions ;
use  Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController ;
use  Automattic\WooCommerce\Utilities\OrderUtil ;
/**
 * Class Order.
 */
class Order
{
    /**
     * The order id.
     *
     * @var int
     * @since 1.2.0
     */
    private  $order_id ;
    /**
     * Output our delivery data to the metabox.
     *
     * @return void
     * @since 1.0.0
     */
    public function output_order_details_metabox_content() : void
    {
        $order = wc_get_order( $this->order_id );
        $order_type = $order->get_meta( 'lpac_dps_order_type' );
        $date = $order->get_meta( "lpac_dps_{$order_type}_date" );
        $date_formatted = '';
        if ( !empty($date) ) {
            $date_formatted = Functions::getFormattedDate( $date );
        }
        $time = $order->get_meta( "lpac_dps_{$order_type}_time" );
        ?>
		<div style='text-align: center; font-weight: 500'>
		<?php 
        ?>
		<?php 
        
        if ( !empty($date_formatted) ) {
            ?>
			<p><span class='dashicons dashicons-calendar-alt'></span> <?php 
            echo  esc_html( $date_formatted ) ;
            ?></p>
		<?php 
        }
        
        ?>
		<?php 
        
        if ( !empty($time) ) {
            ?>
			<p style='border-bottom: 1px dashed #e3e3e3; padding-bottom: 10px;'><span class='dashicons dashicons-clock'></span> <?php 
            echo  esc_html( $time ) ;
            ?></p>
		<?php 
        }
        
        ?>
		<p id='lpac-dps-countdown-timer' style='font-size: 20px; margin: 10px 0;'></p>
		</div>
		<?php 
    }
    
    /**
     * Create our metabox to hold the delivery/pickup details.
     *
     * @return void
     * @since 1.0.0
     */
    public function create_metabox() : void
    {
        
        if ( Functions::usingHPOS() === false ) {
            $order_id = get_the_ID();
        } else {
            $order_id = $_GET['id'] ?? '';
        }
        
        if ( empty($order_id) ) {
            return;
        }
        $this->order_id = $order_id;
        if ( 'shop_order' !== OrderUtil::get_order_type( $order_id ) ) {
            return;
        }
        $screen = ( wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order' );
        $order = wc_get_order( $order_id );
        $order_type = $order->get_meta( 'lpac_dps_order_type' );
        
        if ( 'delivery' !== $order_type && 'pickup' !== $order_type ) {
            return;
            // Something is wrong, bail (check order type value saved for order).
        }
        
        $title = ( 'delivery' === $order_type ? __( 'Delivery details', 'delivery-and-pickup-scheduling-for-woocommerce' ) : __( 'Pickup details', 'delivery-and-pickup-scheduling-for-woocommerce' ) );
        add_meta_box(
            'lpac_dps_metabox',
            $title,
            array( $this, 'output_order_details_metabox_content' ),
            $screen,
            'side',
            'high'
        );
    }

}