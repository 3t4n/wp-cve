<div class="emagic">
    <div class="ep-booking-container ep-box-wrap">
        <div class="ep-box-row">
            <?php if( ! empty( $args->em_id ) ) {
                $user = wp_get_current_user();
                $roles = (array) $user->roles;
                if( ! empty( $user->ID ) || ( isset( $args->em_order_info['guest_booking'] ) && $args->em_order_info['guest_booking'] == 1 ) ) {
                    if( $user->ID != $args->em_user && empty( $args->em_order_info['guest_booking'] ) ) {
                        if( ! in_array( 'administrator', $roles, true ) ) {?>
                            <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                                <?php esc_html_e( 'No booking found!', 'eventprime-event-calendar-management' );?>
                            </div><?php
                            exit();
                        }
                    }?>
                    <div class="ep-box-col-12 ep-fs-2 ep-my-5 ep-text-center">
                        <?php esc_html_e( 'Thank you! Here are your booking details', 'eventprime-event-calendar-management' );?>
                    </div>

                    <div class="ep-box-col-6 ep-text-start ep-mb-3 ep-pl-0 ep-event-link-container">
                        <a href="<?php echo esc_url( $args->event_data->event_url );?>" target="_blank" class="ep-event-link">
                            <button type="button" class="ep-btn ep-btn-outline-dark">
                                <span class="material-icons-outlined ep-align-middle">chevron_left</span>
                                <?php esc_html_e( 'Event Page', 'eventprime-event-calendar-management' );?>
                            </button>
                        </a>
                    </div>

                    <div class="ep-box-col-6 ep-text-end ep-mb-3 ep-pr-0 ep-account-link-container">
                        <a href="<?php echo esc_url( get_permalink( ep_get_global_settings( 'profile_page' ) ) );?>" target="_blank" class="ep-account-link">
                            <button type="button" class="ep-btn ep-btn-dark">
                                <?php esc_html_e( 'Account area', 'eventprime-event-calendar-management' );?>
                                <span class="material-icons-outlined ep-align-middle">chevron_right</span>
                            </button>
                        </a>
                    </div>

                    <div class="ep-box-col-12 ep-border ep-rounded ep-bg-white">
                        <div class="ep-box-row ep-border-bottom ep-text-small">
                            <div class="ep-box-col-12 ep-ps-4 ep-py-4">
                                <span class="ep-text-uppercase">
                                    <span class="ep-fw-bold">
                                        <?php esc_html_e( 'Booking ID', 'eventprime-event-calendar-management' );?>
                                    </span>
                                    <?php echo ': #' . esc_html( $args->em_id );?>
                                </span>
                                <?php if( $args->em_status == 'completed' ) {?>
                                    <span class="ep-text-white ep-small ep-rounded-1 ep-py-1 ep-px-2 ep-bg-success ep-ml-1 ep-align-top">
                                        <?php esc_html_e( 'Confirmed', 'eventprime-event-calendar-management' );?>
                                    </span><?php
                                } else {?>
                                    <span class="ep-text-white ep-small ep-rounded-1 ep-py-1 ep-px-2 ep-bg-warning ep-ml-1 ep-align-top">
                                        <?php esc_html_e( EventM_Constants::$status[ $args->em_status ], 'eventprime-event-calendar-management' );?>
                                    </span><?php
                                }?>
                            </div>
                        </div>

                        <div class="ep-box-row">
                            <div class="ep-box-col-6 ep-py-4">
                                <div class="ep-fs-4 ep-fw-bold ep-ps-4">
                                    <span><?php echo esc_html( $args->event_data->name );?></span>
                                    <a href="<?php echo esc_url( $args->event_data->event_url );?>" target="_blank">
                                        <span class="material-icons-outlined align-middle ep-fs-5 ep-text-primary">open_in_new</span>
                                    </a>
                                </div>
                                <?php if( !empty( $args->event_data->venue_details ) && !empty( $args->event_data->venue_details->em_address ) && ! empty( $args->event_data->venue_details->em_display_address_on_frontend ) ) {?>
                                    <div class="ep-ps-4 ep-text-muted ep-text-small">
                                        <?php echo esc_html( $args->event_data->venue_details->em_address );?>
                                    </div><?php
                                }
                                if( ! empty( $args->event_data->em_start_date ) && ep_show_event_date_time( 'em_start_date', $args->event_data ) ) {?>
                                    <div class="ep-ps-4 ep-text-muted ep-text-small">
                                        <span>
                                            <?php echo esc_html( ep_timestamp_to_date( $args->event_data->em_start_date, 'dS M Y', 1 ) );
                                            if( ! empty( $args->event_data->em_start_time ) && ep_show_event_date_time( 'em_start_time', $args->event_data ) ) {
                                                echo ', ' . esc_html( ep_convert_time_with_format( $args->event_data->em_start_time ) );
                                            }?>
                                        </span>
                                    </div><?php
                                }

                                // User data
                                if( ! empty( $user ) && $user->ID && ! empty( $user->data ) ) {?>
                                    <div class="ep-text-small ep-mt-4">
                                        <div>
                                            <span class="ep-mr-2 ep-fw-bold">
                                                <?php 
                                                if( ! empty( $user->first_name ) ) {
                                                    esc_html_e( 'User Name', 'eventprime-event-calendar-management' );
                                                } else{
                                                    esc_html_e( 'Username', 'eventprime-event-calendar-management' );
                                                }?>:
                                            </span>
                                            <span><?php 
                                                if( ! empty( $user->first_name ) ) {
                                                    echo esc_html( $user->first_name );
                                                    if( ! empty( $user->last_name ) ) {
                                                        echo ' ' . esc_html( $user->last_name );
                                                    }
                                                } else{
                                                    echo esc_html( $user->data->user_login );
                                                }?>
                                            </span>
                                        </div>
                                        <div>
                                            <span class="ep-mr-2 ep-fw-bold">
                                                <?php esc_html_e( 'User Email', 'eventprime-event-calendar-management' );?>:
                                            </span>
                                            <span><?php echo esc_html( $user->data->user_email );?></span>
                                        </div>
                                    </div><?php
                                } else{
                                    if( ! empty( $args->em_order_info ) ) {?>
                                        <div class="ep-text-small ep-mt-4"><?php
                                            if( ! empty( $args->em_order_info['user_name'] ) ) {?>
                                                <div>
                                                    <span class="ep-mr-2 ep-fw-bold">
                                                        <?php esc_html_e( 'Username', 'eventprime-event-calendar-management' );?>:
                                                    </span>
                                                    <span><?php echo esc_html( $args->em_order_info['user_name'] );?></span>
                                                </div><?php
                                            }
                                            if( ! empty( $args->em_order_info['user_email'] ) ) {?>
                                                <div>
                                                    <span class="ep-mr-2 ep-fw-bold">
                                                        <?php esc_html_e( 'User Email', 'eventprime-event-calendar-management' );?>:
                                                    </span>
                                                    <span><?php echo esc_html( $args->em_order_info['user_email'] );?></span>
                                                </div><?php
                                            }
                                            if( ! empty( $args->em_order_info['user_phone'] ) ) {?>
                                                <div>
                                                    <span class="ep-mr-2 ep-fw-bold">
                                                        <?php esc_html_e( 'User Phone', 'eventprime-event-calendar-management' );?>:
                                                    </span>
                                                    <span><?php echo esc_html( $args->em_order_info['user_phone'] );?></span>
                                                </div><?php
                                            }?>
                                        </div><?php
                                    }
                                }?>
                                
                                <?php 
                                // hook to show any fees from extension on the booking
                                do_action( 'ep_booking_detail_show_booking_ticket_data', $args );?>
                            </div>
                            <div class="ep-box-col-3 ep-py-4 ep-text-small">
                                <div class="ep-ps-4">
                                    <span class="ep-mr-2 ep-fs-5">
                                        <?php esc_html_e( 'Paid', 'eventprime-event-calendar-management' );?>:
                                    </span>
                                    <span class="ep-fs-5 ep-mr-2">
                                        <?php echo esc_html( ep_price_with_position( $args->em_order_info['booking_total'] ) );?>
                                    </span>
                                </div>
                                <div class="ep-text-small ep-text-muted">
                                    <div class="ep-text-small">
                                        <span class="ep-mr-2">
                                            <?php esc_html_e( 'Tickets Price', 'eventprime-event-calendar-management' );?>:
                                        </span>
                                        <span><?php echo esc_html( ep_get_booking_tickets_total_price( $args->em_order_info['tickets'] ) );?></span>
                                    </div>
                                    <?php $additional_fees = 0;
                                    $additional_fees = ep_calculate_order_total_additional_fees( $args->em_order_info['tickets'] );
                                    if( ! empty( $additional_fees ) ) {?>
                                        <div class="ep-text-small">
                                            <span class="ep-mr-2"><?php esc_html_e( 'Additional Fees', 'eventprime-event-calendar-management' );?>:</span>
                                            <span><?php echo esc_html( $additional_fees );?></span>
                                        </div><?php
                                    }
                                    if( isset( $args->em_order_info['event_fixed_price'] ) && ! empty( $args->em_order_info['event_fixed_price'] ) ) { ?>
                                        <div class="ep-text-small">
                                            <span class="ep-me-2"><?php esc_html_e( 'Event Fees', 'eventprime-event-calendar-management' );?>:</span>
                                            <span><?php echo esc_html( ep_price_with_position( $args->em_order_info['event_fixed_price'] ) );?></span>
                                        </div><?php
                                    }?>

                                    <?php 
                                    // hook to show any fees from extension on the booking
                                    do_action( 'ep_booking_detail_show_fee_data', $args );?>

                                    <?php
                                    $offer_price = 0;
                                    $offer_price = ep_calculate_order_total_offer_price( $args->em_order_info['tickets'] );
                                    if( ! empty( $offer_price ) ) {?>
                                        <div class="ep-text-small">
                                            <span class="ep-mr-2"><?php esc_html_e( 'Offers', 'eventprime-event-calendar-management' );?>:</span><span><?php echo '-' . esc_html( $offer_price );?></span>
                                        </div><?php
                                    }?>

                                    <?php 
                                    // hook to show any discount from extension on the booking
                                    do_action( 'ep_booking_detail_show_discount_data', $args );?>
                                    
                                </div>
                                <div class="ep-text-small ep-mt-4">
                                    <div class="">
                                        <span class="ep-mr-2">
                                            <?php esc_html_e( 'Booking Status', 'eventprime-event-calendar-management' );?>:
                                        </span>
                                        <?php if( $args->em_status == 'completed' ) {?>
                                            <span class="ep-text-success">
                                                <?php esc_html_e( 'Confirmed', 'eventprime-event-calendar-management' );?>
                                            </span><?php
                                        } else {?>
                                            <span class="ep-text-success">
                                                <?php esc_html_e( EventM_Constants::$status[ $args->em_status ], 'eventprime-event-calendar-management' );?>
                                            </span><?php
                                        }?>
                                    </div>
                                    <div class="">
                                        <span class="ep-mr-2"><?php esc_html_e( 'Payment Status', 'eventprime-event-calendar-management' );?>:</span>
                                        <span class="ep-text-success">
                                            <?php echo ( ! empty( $args->em_payment_log ) && ( ! empty( $args->em_payment_log['payment_status'] ) ) ?  ( ( isset( $args->em_payment_log['offline_status'] ) && ! empty( $args->em_payment_log['offline_status'] ) ) ? esc_html( ucfirst( $args->em_payment_log['offline_status'] ) ) : esc_html( ucfirst( $args->em_payment_log['payment_status'] ) ) ) : '' );?>
                                        </span>
                                    </div>
                                    <div class="">
                                        <span class="ep-mr-2">
                                            <?php esc_html_e( 'Booking ID', 'eventprime-event-calendar-management' );?>:
                                        </span>
                                        <span><?php echo '#' . esc_html( $args->em_id );?></span>
                                    </div>
                                    <div class="">
                                        <span class="ep-mr-2">
                                            <?php esc_html_e( 'Payment Method', 'eventprime-event-calendar-management' );?>:
                                        </span>
                                        <span>
                                            <?php echo ( ! empty( $args->em_payment_log ) && ( ! empty( $args->em_payment_log['payment_gateway'] ) ) ? esc_html( ucfirst( $args->em_payment_log['payment_gateway'] ) ) : '' );?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="ep-box-col-3 ep-p-4 ep-text-small">
                                <div class="ep-pt-4">
                                    <?php 
                                    $gcal_starts = $gcal_ends = $gcal_details = $location = $calendar_url = '';
                                    $gcal_starts = ep_convert_event_date_time_to_timestamp( $args->event_data, 'start' );
                                    if( ! empty( $gcal_starts ) ) {
                                        $gcal_ends = ep_convert_event_date_time_to_timestamp( $args->event_data, 'end' );
                                    }
                                    $gcal_details = urlencode( wp_kses_post( $args->event_data->description ) );
                                    $calendar_url = 'https://www.google.com/calendar/event?action=TEMPLATE&text=' . urlencode( esc_attr( $args->event_data->name ) ) . '&dates=' . gmdate( 'Ymd\\THi00\\Z', esc_attr( $gcal_starts ) ) . '/' . gmdate('Ymd\\THi00\\Z', esc_attr( $gcal_ends ) ) . '&details=' . esc_attr( $gcal_details );
                                    if ( ! empty( $args->event_data->venue_details->em_address ) ) {
                                        $location = urlencode( $args->event_data->venue_details->em_address );
                                        if( ! empty( $location ) ) {
                                            $calendar_url .= '&location=' . esc_attr( $location );
                                        }
                                    }
                                  
                                    if( ! empty( $gcal_starts ) && ! empty( $gcal_ends ) ) {?>
                                        <div class="ep-text-small ep-cursor ep-d-flex ep-align-items-center ep-mb-1">
                                            <a class="em-events-gcal em-events-button ep-di-flex ep-align-items-center ep-lh-0" href="<?php echo esc_url( $calendar_url );?>" target="_blank">
                                                <img class="ep-google-calendar-add ep-fs-6 ep-align-middle" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/gcal.png' ); ?>" style="height: 18px;" />
                                                <?php esc_html_e( 'Add To Calendar','eventprime-event-calendar-management' ); ?>
                                            </a>
                                        </div><?php
                                    }?>
                                            <div class="ep-text-small ep-cursor ep-d-flex ep-align-items-center ep-mb-1">
                                                <a href="javascript:void(0)" id="ep_event_ical_export" data-event_id="<?php echo esc_attr($args->event_data->em_id); ?>">
                                                    <span class="material-icons-outlined ep-fs-6 ep-align-middle ep-lh-0 ep-mr-1">event</span>
                                                    <?php esc_html_e('+ iCal Export', 'eventprime-event-calendar-management'); ?>
                                                </a>
                                            </div>
                                    <?php if( ! empty( $args->event_data->venue_details ) && ! empty( $args->event_data->venue_details->em_address ) ) {?>
                                        <div class="ep-text-small ep-cursor ep-d-flex ep-align-items-center ep-mb-1">
                                            <a target="_blank" href="https://www.google.com/maps?saddr=My+Location&daddr=<?php echo urlencode( $args->event_data->venue_details->em_address ); ?>" >
                                                <span class="material-icons-outlined ep-fs-6 ep-align-middle ep-lh-0 ep-mr-1">directions_car</span>
                                                <?php esc_html_e( 'Directions','eventprime-event-calendar-management' ); ?>
                                            </a>
                                        </div><?php
                                    }?>

                                    <?php echo do_action('ep_booking_confirmation_actions_lists',$args );?>
                                    
                                    <?php if( ! empty( $args->event_data->organizer_details ) && count( $args->event_data->organizer_details ) > 0 ) {
                                        $org_email = '';
                                        foreach( $args->event_data->organizer_details as $or_detail ) {
                                            if( ! empty( $or_detail->em_organizer_emails ) ) {
                                                $org_email = $or_detail->em_organizer_emails[0];
                                                break;
                                            }
                                        }
                                        if( ! empty( $org_email ) ) {?>
                                            <div class="ep-text-small ep-cursor ep-d-flex ep-align-items-center ep-mb-1">
                                                <a href="mailto:<?php echo esc_html( $org_email );?>">
                                                    <span class="material-icons-outlined ep-fs-6 ep-align-middle ep-lh-0 ep-mr-1">work_outline</span>
                                                    <?php esc_html_e('Contact Organizer','eventprime-event-calendar-management'); ?>
                                                </a>
                                            </div><?php
                                        }
                                    }
                                    if( ! empty( $args->event_data->em_allow_cancellations ) && 1 == $args->event_data->em_allow_cancellations && ( 'completed' == $args->em_status || 'pending' == $args->em_status ) ) {?>
                                        <div class="ep-text-small ep-cursor ep-text-danger ep-d-flex ep-align-items-center ep-mb-1" ep-modal-open="ep_booking_cancellation_modal">
                                            <span class="material-icons-outlined ep-fs-6 align-middle ep-lh-0 ep-mr-2">block</span>
                                            <?php esc_html_e('Cancel Booking','eventprime-event-calendar-management'); ?>
                                        </div><?php
                                    }?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Message from payment gateway -->
                        <?php do_action( 'ep_payment_method_custom_message', $args->em_id );?>
                        
                    </div>
                    
                    <?php if( ! empty( $args->em_attendee_names ) && count( $args->em_attendee_names ) > 0 ) {?>
                        <div class="ep-box-col-12 ep-border ep-rounded ep-mt-5 ep-bg-white" id="ep_booking_detail_attendees_container">
                            <div class="ep-box-row ep-border-bottom">
                                <div class="ep-box-col-12 ep-py-4 ep-ps-4 ep-fw-bold ep-text-uppercase ep-text-small">
                                    <?php esc_html_e( 'Attendees', 'eventprime-event-calendar-management' );?>
                                </div>
                            </div>
                            <?php $booking_attendees_field_labels = array();
                            foreach( $args->em_attendee_names as $ticket_id => $attendee_data ) {
                                $first_key = array_keys( $attendee_data )[0];
                                $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[$first_key] );?>
                                <div class="ep-box-row">
                                    <div class="ep-box-col-12 ep-p-4">
                                        <div class="ep-mb-3 ep-fw-bold ep-text-small">
                                            <?php echo esc_html( get_event_ticket_name_by_id_event( $ticket_id, $args->event_data ) );?>
                                        </div>
                                        <table class="ep-table ep-table-hover ep-text-small ep-table-borderless ep-ml-4 ep-text-start">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <?php foreach( $booking_attendees_field_labels as $label_key => $labels ) {?>
                                                        <th scope="col">
                                                            <?php echo esc_html__( $labels, 'eventprime-event-calendar-management' );?>
                                                        </th><?php
                                                    }?>

                                                    <?php do_action( 'ep_booking_detail_attendee_table_header' );?>
                                                </tr>
                                            </thead>
                                            <tbody class=""><?php $att_count = 1;
                                                foreach( $attendee_data as $booking_attendees ) {?>
                                                    <tr>
                                                        <th scope="row" class="py-3"><?php echo esc_html( $att_count );?></th>
                                                        <?php 
                                                        $booking_attendees_val = array_values( $booking_attendees );
                                                        foreach( $booking_attendees_field_labels as $label_key => $labels ){?>
                                                            <td class="py-3"><?php
                                                                $formated_val = ep_get_slug_from_string( $labels );
                                                                $at_val = '---';
                                                                foreach( $booking_attendees_val as $key => $baval ) {
                                                                    if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                                                        $at_val = $baval[$formated_val];
                                                                        if( is_array( $at_val ) ) {
                                                                            $at_val = implode( ', ', $at_val );
                                                                        }
                                                                        break;
                                                                    }
                                                                }
                                                                if( empty( $at_val ) ) {
                                                                    $formated_val = strtolower( $labels );
                                                                    foreach( $booking_attendees_val as $key => $baval ) {
                                                                        if( isset( $baval[$formated_val] ) && ! empty( $baval[$formated_val] ) ) {
                                                                            $at_val = $baval[$formated_val];
                                                                            if( is_array( $at_val ) ) {
                                                                                $at_val = implode( ', ', $at_val );
                                                                            }
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                echo esc_html( $at_val );?>
                                                            </td><?php
                                                        }?>

                                                        <?php do_action( 'ep_booking_detail_attendee_table_data', $booking_attendees_val, $ticket_id, $args->em_id );?>
                                                        
                                                    </tr><?php
                                                    $att_count++;
                                                }?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><?php
                            }?>
                        </div><?php
                    }
                    // booking data
                    if( ! empty( $args->em_booking_fields_data ) && count( $args->em_booking_fields_data ) > 0 ) {
                        foreach( $args->em_booking_fields_data as $booking_fields ) {
                            $formated_val = ep_get_slug_from_string( $booking_fields['label'] );?>
                            <div class="ep-box-col-12 ep-border ep-rounded ep-mt-5 ep-bg-white ep_booking_detail_booking_fields_container">
                                <div class="ep-box-row ep-border-bottom">
                                    <div class="ep-box-col-12 ep-py-4 ep-ps-4 ep-fw-bold ep-text-uppercase ep-text-small">
                                        <?php echo esc_html( $booking_fields['label'] );?>
                                    </div>
                                </div>
                                <div class="ep-box-row">
                                    <div class="ep-box-col-12 ep-p-4">
                                        <div class="ep-mb-3 ep-fw-bold ep-text-small">
                                            <?php 
                                            if( ! empty( $booking_fields[$formated_val] ) ) {
                                                if( is_array( $booking_fields[$formated_val] ) ) {
                                                    echo implode( ', ', $booking_fields[$formated_val] );
                                                } else{
                                                    echo esc_html( $booking_fields[$formated_val] );
                                                }
                                            }?>
                                        </div>
                                    </div>
                                </div>
                            </div><?php
                        }
                    }?>

                    <?php do_action('ep_front_user_booking_details_custom_data', $args );

                } else{?>
                    <div class="ep-box-col-12 ep-fs-2 ep-my-5 ep-text-center">
                        <?php esc_html_e( 'To view the tickets, you need to login.', 'eventprime-event-calendar-management' );?>&nbsp;
                    </div>
                    <div class="ep-box-row ep-py-3">
                        <div class="ep-box-col-12 ep-fs-6 ep-text-small ep-border ep-rounded ep-p-3">
                            <?php echo do_shortcode( '[em_login redirect="reload"]' );?>
                        </div>
                    </div><?php
                }
            } else{?>
                <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
                    <?php esc_html_e( 'No booking detail found!', 'eventprime-event-calendar-management' );?>
                </div><?php
            }?>
        </div>

        <div class="ep-modal ep-modal-view" id="ep_event_booking_cancellation_action" ep-modal="ep_booking_cancellation_modal" style="display: none;" data-booking_id='<?php if( ! empty( $args->em_id ) ) { echo esc_attr( json_encode( $args->em_id ) ); }?>'>
            <div class="ep-modal-overlay" ep-modal-close="ep_booking_cancellation_modal"></div>
            <div class="ep-modal-wrap ep-modal-l">
                <div class="ep-modal-content">
                    <div class="ep-modal-body"> 
                        <div class="ep-box-row">
                            <div class="ep-box-col-12 ep-py-3">
                                <?php esc_html_e( 'Are you sure you want to cancel this booking?', 'eventprime-event-calendar-management' );?>
                            </div>
                        </div>
                        <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right" id="ep_modal_buttonset">
                            <div class="ep-loader" id="ep_event_booking_cancellation_loader" style="display:none;"></div>
                            <button type="button" class="button ep-mr-3 ep-modal-close ep-booking-cancel-modal-close" ep-modal-close="ep_booking_cancellation_modal">
                                <?php esc_html_e( 'Cancel', 'eventprime-event-calendar-management' );?>
                            </button>
                            <button type="button" class="button button-primary button-large" id="ep_event_booking_cancel_booking">
                                <?php esc_html_e( 'Ok', 'eventprime-event-calendar-management' );?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>