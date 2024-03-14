<?php

/**
 * File responsible for creating methods that affect the WP Admin.
 *
 * Author:          Uriahs Victor
 * Created on:      04/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.6
 * @package Views
 */
namespace Lpac_DPS\Views\Admin;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Functions ;
/**
 * Class responsible for creating methods that affect the Admin view.
 *
 * @package Lpac_DPS\Views\Admin
 * @since 1.0.0
 */
class Admin
{
    /**
     * Adds a fulfillment column to the admin list of columns.
     *
     * @param mixed $columns Current admin columns.
     * @return mixed
     * @since 1.0.6
     */
    public function addDatetimeAdminListColumn( $columns )
    {
        $columns['dps_fulfillment'] = __( 'Fulfillment', 'delivery-and-pickup-scheduling-for-woocommerce' );
        return $columns;
    }
    
    /**
     * Add our content to our custom column.
     *
     * @param mixed    $column Current admin columns.
     * @param WC_Order $order
     * @since v1.0.6
     * @return void
     */
    public function addDatetimeAdminListColumnContent( $column, $order )
    {
        if ( $column !== 'dps_fulfillment' ) {
            return;
        }
        if ( is_int( $order ) === false && is_object( $order ) === false ) {
            return;
        }
        if ( is_int( $order ) ) {
            // When HPOS is on this would be an int
            $order = wc_get_order( $order );
        }
        $order_type = $order->get_meta( 'lpac_dps_order_type' );
        
        if ( 'delivery' === $order_type ) {
            $date = $order->get_meta( 'lpac_dps_delivery_date' );
            $time = $order->get_meta( 'lpac_dps_delivery_time' );
        } else {
            $date = $order->get_meta( 'lpac_dps_pickup_date' );
            $time = $order->get_meta( 'lpac_dps_pickup_time' );
        }
        
        if ( empty($date) && empty($time) ) {
            return;
        }
        // Set our date to the format that admin perfers.
        $formatted_date = apply_filters( 'dps_admin_column_preferred_date_format', Functions::getFormattedDate( $date ), $date );
        ?>
		<div style='text-align: left'>
			<p><strong><?php 
        esc_html_e( 'Type', 'delivery-and-pickup-scheduling-for-woocommerce' );
        ?></strong>: <span><?php 
        echo  esc_html( ucfirst( $order_type ) ) ;
        ?></span></p>
			
			<?php 
        ?>

			<?php 
        
        if ( !empty($formatted_date) ) {
            ?>
				<p style='margin-top: 5px;'><strong><?php 
            esc_html_e( 'Date', 'delivery-and-pickup-scheduling-for-woocommerce' );
            ?></strong>: <span><?php 
            echo  esc_html( $formatted_date ) ;
            ?></span></p>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( !empty($time) ) {
            ?>
				<p style='margin-top: 5px;'><strong><?php 
            esc_html_e( 'Time', 'delivery-and-pickup-scheduling-for-woocommerce' );
            ?></strong>: <span><?php 
            echo  esc_html( $time ) ;
            ?></span></p>
			<?php 
        }
        
        ?>
		</div>
		<?php 
    }

}