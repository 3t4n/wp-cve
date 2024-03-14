<?php

/**
 * Methods that handle fees created for timeslots.
 *
 * Author:          Uriahs Victor
 * Created on:      12/12/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.2.2
 * @package Controllers
 */
namespace Lpac_DPS\Controllers\Checkout_Page\Fees;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Helpers\Functions ;
use  Lpac_DPS\Helpers\Logger ;
use  Lpac_DPS\Helpers\Utilities ;
use  Lpac_DPS\Models\Plugin_Settings\Scheduling as SchedulingSettings ;
/**
 * Responsible for creating methods that handle checkout fees based on timeslots.
 *
 * @package Lpac_DPS\Controllers\Checkout_Page\Fees
 * @since 1.2.2
 */
class TimeSlotFees
{
    /**
     * Get the fee set for a timeslot.
     *
     * @param string $order_type
     * @param string $selected_date
     * @param array  $normalized_time_array
     * @return null|array
     * @since 1.2.2
     */
    private function getTimeSlotFeeData( string $order_type, string $selected_date, array $normalized_time_array ) : ?array
    {
        $timeslots = ( new SchedulingSettings( $order_type ) )->getSavedTimeslots();
        $selected_day = strtolower( Functions::getDateTimeFromFormat( $selected_date, 'Y-m-d', 'l' ) );
        // Get the timeslots and fees
        $timeslots_for_day = array();
        foreach ( $timeslots as $key => $timeslot_data ) {
            
            if ( $timeslot_data['day_of_the_week'] === $selected_day ) {
                $timeslots_for_day = $timeslot_data['time_slots'];
                break;
            }
        
        }
        if ( empty($timeslots_for_day) ) {
            return null;
        }
        $selected_from = $normalized_time_array['from'];
        $selected_to = $normalized_time_array['to'];
        $time_slot_data = array();
        foreach ( $timeslots_for_day as $key => $timeslots_for_day_details ) {
            $from = $timeslots_for_day_details['time_range']['from'];
            $to = $timeslots_for_day_details['time_range']['to'];
            if ( empty($to) ) {
                // When timeslots are single times (with no dash)
                
                if ( $from === $selected_from ) {
                    $time_slot_data = $timeslots_for_day[$key];
                    break;
                }
            
            }
            
            if ( $from === $selected_from && $to === $selected_to ) {
                $time_slot_data = $timeslots_for_day_details;
                break;
            }
        
        }
        $time_slot_fee_data = $time_slot_data['time_slot_fee'] ?? '';
        if ( empty($time_slot_fee_data) ) {
            ( new Logger() )->logError( 'There was an issue retrieving the timeslot fee from the database. Method: ' . __METHOD__ . ' Line: ' . __LINE__ );
        }
        $fee_amount = $time_slot_fee_data['fee_amount'];
        return array(
            'fee_amount' => $fee_amount,
            'fee_name'   => $time_slot_fee_data['fee_name'],
        );
    }
    
    /**
     * Set the additional fee at checkout.
     *
     * @return void
     * @since 1.2.2
     */
    public function setAdditionalFee()
    {
        if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
            return;
        }
        if ( !is_checkout() ) {
            return;
        }
        $posted_data = sanitize_text_field( $_POST['post_data'] ?? '' );
        /**
         * Bail for new orders.
         */
        if ( empty($posted_data) && empty(WC()->session->get( 'dps_timeslot_fee_amount' )) ) {
            return;
        }
        $taxable_time_slot_fee = apply_filters( 'lpac_dps_taxable_time_slot_fee', false );
        $time_slot_fee_tax_class = apply_filters( 'lpac_dps_time_slot_fee_tax_class', '' );
        /**
         * This check happens when customer clicks "Place Order" button and ajax runs.
         * $posted_data would be empty but we would have already set the session key for the fee on the checkout page.
         */
        
        if ( empty($posted_data) && !empty(WC()->session->get( 'dps_timeslot_fee_amount' )) ) {
            $fee_name = WC()->session->get( 'dps_timeslot_fee_name' );
            $fee_amount = WC()->session->get( 'dps_timeslot_fee_amount' );
            WC()->cart->add_fee(
                $fee_name,
                $fee_amount,
                $taxable_time_slot_fee,
                $time_slot_fee_tax_class
            );
            WC()->session->set( 'dps_timeslot_fee_amount', false );
            // Set to false so new orders don't show the fee until it is set.
            WC()->session->set( 'dps_timeslot_fee_name', false );
            // Set to false so new orders don't show the fee until it is set.
            return;
        }
        
        $fields = Utilities::normalizePostString( $posted_data );
        $order_type = $fields['lpac_dps_order_type'] ?? '';
        if ( empty($order_type) ) {
            return;
        }
        $scheduling_settings = new SchedulingSettings( $order_type );
        if ( $scheduling_settings->enableTimeslotFees() === false ) {
            return;
        }
        $date_field = 'lpac_dps_' . $order_type . '_date';
        $selected_date = $fields[$date_field] ?? '';
        if ( empty($selected_date) ) {
            return;
        }
        $time_field = 'lpac_dps_' . $order_type . '_time';
        $selected_time = $fields[$time_field] ?? '';
        if ( empty($selected_time) ) {
            return;
        }
        $normalized_time_array = Utilities::normalizePostTimeslot( $selected_time );
        $order_type = WC()->session->get( 'lpac_dps_order_type' );
        $fee_data = $this->getTimeSlotFeeData( $order_type, $selected_date, $normalized_time_array );
        $fee_name = $fee_data['fee_name'] ?? '';
        $fee_amount = $fee_data['fee_amount'] ?? '';
        
        if ( empty($fee_amount) ) {
            WC()->session->set( 'dps_timeslot_fee_amount', false );
            WC()->session->set( 'dps_timeslot_fee_name', false );
            return;
        }
        
        WC()->session->set( 'dps_timeslot_fee_amount', $fee_amount );
        WC()->session->set( 'dps_timeslot_fee_name', $fee_name );
        /**
         * This fee_amount is shown on the checkout page but the actual attaching happens after the Place Order button is clicked
         * And the fee is retrieved from the session and added.
         * This can be seen in the empty( $posted_data ) && ! empty( WC()->session->get( 'dps_timeslot_fee_amount' ) ) logic above.
         */
        WC()->cart->add_fee(
            $fee_name,
            $fee_amount,
            $taxable_time_slot_fee,
            $time_slot_fee_tax_class
        );
    }

}