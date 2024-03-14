<?php

/*
 * Get Forms list
 */
// use AmeliaBooking\Domain\Entity\Entities;
function adfoin_amelia_get_forms( $form_provider )
{
    if ( $form_provider != 'amelia' ) {
        return;
    }
    $triggers = array(
        'appointmentBookingRegular'       => __( 'Appointment Booking Completed', 'advanced-form-integration' ),
        'eventBookingRegular'             => __( 'Event Booking Completed', 'advanced-form-integration' ),
        'appointmentBookingTimeUpdated'   => __( 'Appointment Booking Rescheduled (Amelia Pro)', 'advanced-form-integration' ),
        'eventBookingTimeUpdated'         => __( 'Event Booking Rescheduled (Amelia Pro)', 'advanced-form-integration' ),
        'appointmentBookingCanceled'      => __( 'Appointment Booking Canceled (Amelia Pro)', 'advanced-form-integration' ),
        'eventBookingCanceled'            => __( 'Event Booking Canceled (Amelia Pro)', 'advanced-form-integration' ),
        'appointmentBookingStatusUpdated' => __( 'Appointment Status Changed (Amelia Pro)', 'advanced-form-integration' ),
        'eventBookingStatusUpdated'       => __( 'Event Booking Status Changed (Amelia Pro)', 'advanced-form-integration' ),
    );
    if ( has_action( 'AmeliaBookingTimeUpdated' ) ) {
    }
    return $triggers;
}

/*
 * Get form fields
 */
function adfoin_amelia_get_form_fields( $form_provider, $form_id )
{
    if ( $form_provider != 'amelia' ) {
        return;
    }
    $fields = array();
    
    if ( 'appointmentBookingRegular' == $form_id || 'appointmentBookingTimeUpdated' == $form_id || 'appointmentBookingCanceled' == $form_id || 'appointmentBookingStatusUpdated' == $form_id ) {
        $fields['appointment_id'] = __( 'Appointment ID', 'advanced-form-integration' );
        $fields['appointment_booking_start'] = __( 'Appointment Booking Start', 'advanced-form-integration' );
        $fields['appointment_booking_end'] = __( 'Appointment Booking End', 'advanced-form-integration' );
        $fields['appointment_status'] = __( 'Appointment Status', 'advanced-form-integration' );
        $fields['service_id'] = __( 'Service ID', 'advanced-form-integration' );
        $fields['service_name'] = __( 'Service Name', 'advanced-form-integration' );
        $fields['price'] = __( 'Price', 'advanced-form-integration' );
        $fields['customer_timezone'] = __( 'Customer Timezone', 'advanced-form-integration' );
    }
    
    
    if ( 'eventBookingRegular' == $form_id || 'eventBookingTimeUpdated' == $form_id || 'eventBookingCanceled' == $form_id || 'eventBookingStatusUpdated' == $form_id ) {
        $fields['event_id'] = __( 'Event ID', 'advanced-form-integration' );
        $fields['event_name'] = __( 'Event Name', 'advanced-form-integration' );
        $fields['event_price'] = __( 'Event Price', 'advanced-form-integration' );
        $fields['event_max_capacity'] = __( 'Event Max Capacity', 'advanced-form-integration' );
        $fields['event_status'] = __( 'Event Status', 'advanced-form-integration' );
        $fields['total_persons'] = __( 'Total Persons', 'advanced-form-integration' );
    }
    
    $fields['coupon_id'] = __( 'Coupon ID', 'advanced-form-integration' );
    $fields['customer_id'] = __( 'Customer ID', 'advanced-form-integration' );
    $fields['customer_first_name'] = __( 'Customer First Name', 'advanced-form-integration' );
    $fields['customer_last_name'] = __( 'Customer Last Name', 'advanced-form-integration' );
    $fields['customer_email'] = __( 'Customer Email', 'advanced-form-integration' );
    $fields['customer_phone'] = __( 'Customer Phone', 'advanced-form-integration' );
    $fields['location_name'] = __( 'Location Name', 'advanced-form-integration' );
    $fields['location_description'] = __( 'Location Description', 'advanced-form-integration' );
    $fields['location_address'] = __( 'Location Address', 'advanced-form-integration' );
    return $fields;
}

add_action(
    'AmeliaBookingAddedBeforeNotify',
    'adfoin_amelia_booking_added',
    10,
    2
);
add_action(
    'AmeliaBookingTimeUpdated',
    'adfoin_amelia_booking_time_updated',
    10,
    3
);
add_action(
    'AmeliaBookingCanceled',
    'adfoin_amelia_booking_canceled',
    10,
    3
);
add_action(
    'AmeliaBookingStatusUpdated',
    'adfoin_amelia_booking_status_updated',
    10,
    3
);
function adfoin_amelia_booking_added( $reservation, $container )
{
    adfoin_amelia_triggered( $reservation, $container, 'BookingAddedRegular' );
}

function adfoin_amelia_booking_time_updated( $reservation, $bookings, $container )
{
    adfoin_amelia_triggered( $reservation, $container, 'BookingTimeUpdated' );
}

function adfoin_amelia_booking_canceled( $reservation, $bookings, $container )
{
    adfoin_amelia_triggered( $reservation, $container, 'BookingCanceled' );
}

function adfoin_amelia_booking_status_updated( $reservation, $bookings, $container )
{
    adfoin_amelia_triggered( $reservation, $container, 'BookingStatusUpdated' );
}

function adfoin_amelia_triggered( $reservation, $container, $action )
{
    $integration = new Advanced_Form_Integration_Integration();
    
    if ( 'BookingAddedRegular' == $action ) {
        if ( 'appointment' == $reservation['type'] ) {
            $connections = $integration->get_by_trigger( 'amelia', 'appointmentBookingRegular' );
        }
        if ( 'event' == $reservation['type'] ) {
            $connections = $integration->get_by_trigger( 'amelia', 'eventBookingRegular' );
        }
    } else {
        $type = ( isset( $reservation['type'] ) ? $reservation['type'] . $action : '' );
        $connections = $integration->get_by_trigger( 'amelia', $type );
    }
    
    if ( !is_array( $connections ) || count( $connections ) <= 0 ) {
        return;
    }
    $posted_data = array();
    $booking_application_service = $container->get( 'application.booking.booking.service' );
    
    if ( 'BookingAddedRegular' == $action ) {
        if ( 'appointment' == $reservation['type'] ) {
            $reservation_entity = $booking_application_service->getReservationEntity( $reservation['appointment'] );
        }
        if ( 'event' == $reservation['type'] ) {
            $reservation_entity = $booking_application_service->getReservationEntity( $reservation['event'] );
        }
    } else {
        $reservation_entity = $booking_application_service->getReservationEntity( $reservation );
    }
    
    $reservation_data = $reservation_entity->toArray();
    $booking = ( isset( $reservation_data['bookings'] ) && is_array( $reservation_data['bookings'] ) & count( $reservation_data['bookings'] ) > 0 ? end( $reservation_data['bookings'] ) : '' );
    $location = ( isset( $reservation_data['location'] ) ? $reservation_data['location'] : '' );
    
    if ( $booking ) {
        $booking_entity = $booking_application_service->getBookingEntity( $booking );
        $booking_data = $booking_entity->toArray();
        $custom_fields = ( isset( $booking_data['customFields'] ) ? json_decode( $booking_data['customFields'], true ) : '' );
        $customer = ( isset( $booking_data['customer'] ) ? $booking_data['customer'] : '' );
    }
    
    
    if ( 'appointment' == $reservation['type'] ) {
        $provider = ( isset( $reservation_data['provider'] ) ? $reservation_data['provider'] : '' );
        $service = ( isset( $reservation_data['service'] ) ? $reservation_data['service'] : '' );
        $posted_data['appointment_id'] = $reservation_data['id'];
        $posted_data['appointment_booking_start'] = $reservation_data['bookingStart'];
        $posted_data['appointment_booking_end'] = $reservation_data['bookingEnd'];
        $posted_data['appointment_status'] = $reservation_data['status'];
        $posted_data['service_id'] = ( isset( $service['id'] ) ? $service['id'] : '' );
        $posted_data['service_name'] = ( isset( $service['name'] ) ? $service['name'] : '' );
        $posted_data['service_description'] = ( isset( $service['description'] ) ? $service['description'] : '' );
        $posted_data['coupon_id'] = ( isset( $booking_data['couponId'] ) ? $booking_data['couponId'] : '' );
        $posted_data['price'] = ( isset( $booking_data['price'] ) ? $booking_data['price'] : '' );
        $posted_data['employee_id'] = ( isset( $provider['id'] ) ? $provider['id'] : '' );
        $posted_data['employee_first_name'] = ( isset( $provider['firstName'] ) ? $provider['firstName'] : '' );
        $posted_data['employee_last_name'] = ( isset( $provider['lastName'] ) ? $provider['lastName'] : '' );
        $posted_data['employee_email'] = ( isset( $provider['email'] ) ? $provider['email'] : '' );
    }
    
    
    if ( 'event' == $reservation['type'] ) {
        $posted_data['event_id'] = $reservation_data['id'];
        $posted_data['event_name'] = $reservation_data['name'];
        $posted_data['event_description'] = $reservation_data['description'];
        $posted_data['event_price'] = $reservation_data['price'];
        $posted_data['event_deposit'] = $reservation_data['deposit'];
        $posted_data['event_max_capacity'] = $reservation_data['maxCapacity'];
        $posted_data['event_status'] = $reservation_data['status'];
        $posted_data['total_persons'] = $booking_data['persons'];
        $posted_data['coupon_id'] = $booking_data['couponId'];
    }
    
    $posted_data['customer_id'] = ( isset( $customer['id'] ) ? $customer['id'] : '' );
    $posted_data['customer_first_name'] = ( isset( $customer['firstName'] ) ? $customer['firstName'] : '' );
    $posted_data['customer_last_name'] = ( isset( $customer['lastName'] ) ? $customer['lastName'] : '' );
    $posted_data['customer_email'] = ( isset( $customer['email'] ) ? $customer['email'] : '' );
    $posted_data['customer_phone'] = ( isset( $customer['phone'] ) ? $customer['phone'] : '' );
    $posted_data['customer_timezone'] = ( isset( $customer['timeZone'] ) ? $customer['timeZone'] : '' );
    $posted_data['location_name'] = ( isset( $location['name'] ) ? $location['name'] : '' );
    $posted_data['location_description'] = ( isset( $location['description'] ) ? $location['description'] : '' );
    $posted_data['location_address'] = ( isset( $location['address'] ) ? $location['address'] : '' );
    $job_queue = get_option( 'adfoin_general_settings_job_queue' );
    foreach ( $connections as $record ) {
        $action_provider = $record['action_provider'];
        
        if ( $job_queue ) {
            as_enqueue_async_action( "adfoin_{$action_provider}_job_queue", array(
                'data' => array(
                'record'      => $record,
                'posted_data' => $posted_data,
            ),
            ) );
        } else {
            call_user_func( "adfoin_{$action_provider}_send_data", $record, $posted_data );
        }
    
    }
    return $posted_data;
}
