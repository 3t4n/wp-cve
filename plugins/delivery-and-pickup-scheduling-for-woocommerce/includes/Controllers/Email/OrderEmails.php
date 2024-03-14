<?php

/**
 * File responsible for methods to do with manipulating Emails.
 *
 * Author:          Uriahs Victor
 * Created on:      04/04/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.6
 * @package Controllers
 */
namespace Lpac_DPS\Controllers\Email;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Functions ;
use  Lpac_DPS\Models\Plugin_Settings\GeneralSettings ;
use  Lpac_DPS\Models\Plugin_Settings\Localization ;
/**
 * Class responsible for methods that manipulate Emails.
 *
 * @package Lpac_DPS\Controllers
 * @since 1.0.6
 */
class OrderEmails
{
    /**
     * Show our date and time inside order emails.
     *
     * @param mixed $order
     * @param mixed $sent_to_admin
     * @param mixed $plain_text
     * @param mixed $email
     * @return void
     * @since 1.0.6
     */
    public function addDateTimeToEmails(
        $order,
        $sent_to_admin,
        $plain_text,
        $email
    )
    {
        $allowed_emails = GeneralSettings::getDateTimeIncludedEmails();
        if ( !is_object( $email ) ) {
            return;
        }
        // If the current email ID is not in our list of allowed emails then bail.
        if ( !in_array( $email->id, $allowed_emails ) ) {
            return;
        }
        $order_type = $order->get_meta( 'lpac_dps_order_type' );
        
        if ( 'delivery' === $order_type ) {
            $order_date = $order->get_meta( 'lpac_dps_delivery_date' );
            $order_date = Functions::getFormattedDate( $order_date );
            $order_time = $order->get_meta( 'lpac_dps_delivery_time' );
        } else {
            $order_date = $order->get_meta( 'lpac_dps_pickup_date' );
            $order_date = Functions::getFormattedDate( $order_date );
            $order_time = $order->get_meta( 'lpac_dps_pickup_time' );
        }
        
        $order_type_text = ( $order_type === 'delivery' ? Localization::getEmailsDeliveryText() : Localization::getEmailsPickupText() );
        ?>
		<div style='text-align: center !important'>
			<p><strong><?php 
        echo  esc_html( Localization::getEmailsOrderTypeText() ) ;
        ?>:</strong> <span><?php 
        echo  esc_html( $order_type_text ) ;
        ?></span></p>
			
			<?php 
        ?>

			<?php 
        
        if ( !empty($order_date) ) {
            ?>
			<p><strong><?php 
            echo  esc_html( Localization::getEmailsDateText() ) ;
            ?>:</strong> <span><?php 
            echo  esc_html( $order_date ) ;
            ?></span> </p>
			<?php 
        }
        
        ?>
			<?php 
        
        if ( !empty($order_time) ) {
            ?>
			<p><strong><?php 
            echo  esc_html( Localization::getEmailsTimeText() ) ;
            ?>:</strong> <span><?php 
            echo  esc_html( $order_time ) ;
            ?></span> </p>
			<?php 
        }
        
        ?>
		</div>
		<?php 
    }

}