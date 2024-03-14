<?php

/**
 * Class responsible for handling our Ajax requests from the checkout page.
 *
 * Author:          Uriahs Victor
 * Created on:      27/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */
namespace Lpac_DPS\Controllers\Checkout_Page\Ajax;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Lpac_DPS\Controllers\Checkout_Page\BaseCheckoutPageController ;
use  Lpac_DPS\Helpers\Functions ;
use  Lpac_DPS\Helpers\Utilities ;
use  Lpac_DPS\Models\Plugin_Settings\Scheduling ;
/**
 * Class Handlers.
 *
 * Class responsible for methods that deal with Ajax logic happening on the checkout page.
 *
 * @package Lpac_DPS\Controllers\Checkout_Page\Ajax
 */
class Handlers extends BaseCheckoutPageController
{
    /**
     * Drop passed time slots from array if the "to" time in the time slot has passed.
     *
     * @param string $date
     * @param array  $time_slots
     * @return array
     * @since 1.1.0
     */
    private function dropPassedTimeSlots( string $date, array $time_slots ) : array
    {
        $today = Functions::getCurrentStoreDate();
        if ( $today !== $date ) {
            // If the timeslots pulled are not for the current day.
            return $time_slots;
        }
        $current_date_time = Functions::getCurrentUTCDateTime();
        $filtered = array_filter( $time_slots, function ( $slot ) use( $current_date_time ) {
            $from = $slot['time_range']['from'] ?? '';
            $to = ( $slot['time_range']['to'] ?: $from );
            // If no to time set then fall back to from time since it would most likely have a value.
            if ( empty($from) && empty($to) ) {
                return false;
            }
            $cutoff_utc_date_time = Utilities::getUTCFromTime( $to, 'Y-m-d H:i:s' );
            return Utilities::timeIsLessThan( $current_date_time, $cutoff_utc_date_time );
        } );
        return $filtered;
    }
    
    /**
     * Drop times that are about to expire based on "buffer" value set in DPS settings.
     *
     * @param string $the current date
     * @param array  $time_slots
     * @return array
     * @since 1.2.0
     */
    private function dropExpiringTimeSlots( string $date, array $time_slots, int $order_placement_buffer ) : array
    {
        $today = Functions::getCurrentStoreDate();
        if ( $date !== $today ) {
            return $time_slots;
        }
        $padding = $order_placement_buffer;
        $padded_date_time = Utilities::addMinutesToCurrentUTCDateTime( $padding );
        $filtered = array_filter( $time_slots, function ( $time_slot ) use( $padded_date_time ) {
            $to_time = ( $time_slot['time_range']['to'] ?: $time_slot['time_range']['from'] );
            $utc_to_time = Utilities::getUTCFromTime( $to_time, 'Y-m-d H:i:s' );
            return !Utilities::timeIsGreaterThanOrEqualTo( $padded_date_time, $utc_to_time );
            // return time_slot if its not beyond our padded time.
        } );
        return $filtered;
    }
    
    /**
     * Ajax controller method for lpac_dps_get_times.
     *
     * @return void
     * @since 1.0.0
     */
    public function getTimesAjaxHandler() : void
    {
        $date_data = array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['selectedDate'] ?? array() ) );
        if ( empty($date_data) ) {
            wp_send_json_error( 'Date data empty.', 500 );
        }
        $date = sanitize_text_field( $date_data['date'] );
        $day_of_the_week = $this->days_of_the_week[sanitize_text_field( $date_data['dayIndex'] )] ?? '';
        $order_type = sanitize_text_field( $date_data['orderType'] );
        $scheduling_model = new Scheduling( $order_type );
        $time_slots_nested_repeater = $scheduling_model->getSavedTimeslots();
        $all_times = array_column( $time_slots_nested_repeater, 'time_slots', 'day_of_the_week' );
        $times_for_day = $all_times[$day_of_the_week] ?? array();
        
        if ( empty($times_for_day) ) {
            wp_send_json_success( 'set_manually' );
            // Allow customer to set their delivery or pickup time manually.
        }
        
        // Drop passed time slots if option enabled.
        if ( $scheduling_model->dropPassedTimeSlots() ) {
            $times_for_day = $this->dropPassedTimeSlots( $date, $times_for_day );
        }
        $order_placement_buffer = $scheduling_model->getOrderPlacementBuffer();
        // Add buffer for order placement if a value is set and it's not 0.
        if ( !empty($order_placement_buffer) ) {
            $times_for_day = $this->dropExpiringTimeSlots( $date, $times_for_day, $order_placement_buffer );
        }
        $time_slots = '';
        
        if ( empty($times_for_day) ) {
            $time_slots = "<option value=''>" . __( 'No available time slots', 'delivery-and-pickup-scheduling-for-woocommerce' ) . '</option>';
            wp_send_json_success( $time_slots );
        }
        
        foreach ( $times_for_day as $index => $data ) {
            $from = $data['time_range']['from'];
            $to = $data['time_range']['to'];
            $from_to = Utilities::createTimeSlotDisplayText( $from, $to );
            
            if ( !empty($to) ) {
                $time_slots .= "<option value='{$from_to}'>" . $from_to . '</option>';
            } else {
                $time_slots .= "<option value='{$from}'>" . $from . '</option>';
            }
        
        }
        wp_send_json_success( $time_slots );
    }

}