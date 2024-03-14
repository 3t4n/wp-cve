<?php

/**
 * Class responsible for outputting content on the thank you page.
 *
 * Author:          Uriahs Victor
 * Created on:      22/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */
namespace Lpac_DPS\Views\Frontend;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Functions ;
use  Lpac_DPS\Models\Plugin_Settings\Localization as LocalizationSettings ;
use  Lpac_DPS\Views\BaseView ;
/**
 * Class responsible for methods that act on the order details page.
 *
 * @package Lpac_DPS\Views\Frontend
 */
class OrderDetails extends BaseView
{
    /**
     * The order id.
     *
     * @var int
     * @since 1.0.0
     */
    private  $order_id ;
    /**
     * Delivery/Pickup details markup for displaying.
     *
     * Possible order types are "delivery" and "pickup"
     *
     * @return array|null
     * @since 1.0.0
     */
    private function getOrderDateTimeDetails() : ?array
    {
        $order = wc_get_order( $this->order_id );
        $order_type = $order->get_meta( 'lpac_dps_order_type' );
        if ( empty($order_type) ) {
            return null;
        }
        $date = $order->get_meta( "lpac_dps_{$order_type}_date" );
        $date_formatted = '';
        if ( !empty($date) ) {
            $date_formatted = Functions::getFormattedDate( $date );
        }
        $time = $order->get_meta( "lpac_dps_{$order_type}_time" );
        return array(
            'date' => $date_formatted,
            'time' => $time,
        );
    }
    
    /**
     * Output our order details on the respective pages (thank you page and customer view order page).
     *
     * @return void
     * @since 1.0.0
     */
    public function outputDeliveryPickupDetails() : void
    {
        if ( !function_exists( 'is_wc_endpoint_url' ) ) {
            return;
        }
        // If this isn't the order received page shown after a purchase, or the view order page shown on the user account, then bail.
        if ( !is_wc_endpoint_url( 'view-order' ) && !is_wc_endpoint_url( 'order-received' ) ) {
            return;
        }
        global  $wp ;
        if ( is_wc_endpoint_url( 'order-received' ) ) {
            $this->order_id = (int) $wp->query_vars['order-received'];
        }
        if ( is_wc_endpoint_url( 'view-order' ) ) {
            $this->order_id = (int) $wp->query_vars['view-order'];
        }
        if ( empty($this->order_id) ) {
            return;
        }
        // Assign order details to property.
        $order = wc_get_order( $this->order_id );
        $order_type = $order->get_meta( 'lpac_dps_order_type' );
        if ( empty($order_type) ) {
            return;
        }
        $date_time = $this->getOrderDateTimeDetails();
        ?>
		<div class='lpac-dps-order-details-container' style='margin-bottom: 20px;'>
			<h2 class='woocommerce-order-details__title'><?php 
        echo  ( 'delivery' === $order_type ? esc_html( LocalizationSettings::getOrderDetailsPageDeliveryDetailsText() ) : esc_html( LocalizationSettings::getOrderDetailsPagePickupDetailsText() ) ) ;
        ?></h2>
			<div class='lpac-dps-order-details-contents' style='font-size: 18px'>
				<?php 
        
        if ( !empty($date_time['date']) ) {
            $date_text = ( 'delivery' === $order_type ? LocalizationSettings::getOrderDetailsPageDeliveryDateText() : LocalizationSettings::getOrderDetailsPagePickupDateText() );
            echo  '<p>' . esc_html( $date_text ) . "&nbsp;<span class='dashicons dashicons-calendar-alt' style='vertical-align: text-top'></span>:&nbsp;" . esc_html( $date_time['date'] ?? '' ) . '<p>' ;
        }
        
        
        if ( !empty($date_time['time']) ) {
            $time_text = ( 'delivery' === $order_type ? LocalizationSettings::getOrderDetailsPageDeliveryTimeText() : LocalizationSettings::getOrderDetailsPagePickupTimeText() );
            echo  '<p>' . $time_text . "&nbsp;<span class='dashicons dashicons-clock' style='vertical-align: text-top'></span>:&nbsp;" . esc_html( $date_time['time'] ?? '' ) . '</p>' ;
        }
        
        ?>
			</div>
		</div>
		<?php 
    }

}