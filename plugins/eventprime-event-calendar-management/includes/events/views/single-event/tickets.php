<?php $all_tickets = $args->event->all_tickets_data;
$event_offers = $args->event->all_offers_data;
$buy_ticket_text = ep_global_settings_button_title('Buy Tickets');
$get_ticket_now_text = ep_global_settings_button_title('Get Tickets Now');
$checkout_text = ep_global_settings_button_title('Checkout');
$add_details_and_checkout_text = ep_global_settings_button_title('Add Details & Checkout');
$sold_out_text = ep_global_settings_button_title('Sold Out');
$booking_allowed = ( ! empty( $args->event->em_enable_booking ) && $args->event->em_enable_booking == 'bookings_on' ? 1 : 0 );
$is_event_expired = check_event_has_expired( $args->event );
?>
<div class="ep-box-col-4 ep-position-relative" id="ep-sl-right-area">
    <?php do_action( 'ep_event_detail_before_ticket_block', $args->event );
    if( ( ! empty( $args->event->em_enable_booking ) && $args->event->em_enable_booking != 'bookings_off' ) || ( ep_get_global_settings( 'show_qr_code_on_single_event' ) == 1 ) ) {?>
        <div class="ep-box-row ep-border ep-rounded"><?php
            if( ! empty( $args->event->em_enable_booking ) && $args->event->em_enable_booking != 'bookings_off' ) {
                if( $args->event->post_status == 'publish' || ( ! empty( $all_tickets ) && count( $all_tickets ) > 0 ) ) {
                    if( ! $is_event_expired ) {?>
                        <div class="ep-box-col-12 ep-px-4 ep-pt-3">
                            <span class="ep-fs-4"><?php echo esc_html( $buy_ticket_text ); ?> </span>
                        </div><?php
                    }?>
                    <div class="ep-box-col-12 ep-p-4">
                        <div class="ep-mb-3 ep-position-relative">
                            <?php do_action( 'ep_event_detail_right_event_dates_section', $args->event );?>
                        </div>
                        <!-- Ticket Price -->
                        <?php if( $args->event->em_enable_booking == 'bookings_on' ) {?>
                            <div id="ep_single_event_ticket_price">
                                <?php if( ! empty( $args->event->ticket_price_range ) ) {
                                    if( isset( $args->event->ticket_price_range['multiple'] ) && $args->event->ticket_price_range['multiple'] == 1 ) {
                                        if( $args->event->ticket_price_range['min'] == $args->event->ticket_price_range['max'] ) {?>
                                            <h6 class="ep-fs-6"><?php
                                                if( $args->event->ticket_price_range['min'] ){
                                                    echo esc_html( ep_price_with_position( $args->event->ticket_price_range['min'] ) );
                                                } else{
                                                    ep_show_free_event_price( $args->event->ticket_price_range['min'] );
                                                }?>
                                            </h6><?php
                                        } else{?>
                                            <h6 class="ep-fs-6 ep-fw-bold"><?php 
                                                esc_html_e( 'Starting from', 'eventprime-event-calendar-management' );
                                                echo ' '.esc_html( ep_price_with_position( $args->event->ticket_price_range['min'] ) );?>
                                            </h6>
                                            <h6 class="ep-fs-6"><?php
                                                echo esc_html( ep_price_with_position ( $args->event->ticket_price_range['min'] ) . ' - ' . ep_price_with_position( $args->event->ticket_price_range['max'] ) );?>
                                            </h6><?php
                                        }
                                    } else{?>
                                        <h6 class="ep-fs-6"><?php
                                            if( $args->event->ticket_price_range['price'] ){
                                                echo esc_html( ep_price_with_position( $args->event->ticket_price_range['price'] ) );
                                            } else{
                                                esc_html_e( 'Free', 'eventprime-event-calendar-management' ); 
                                            }?>
                                        </h6><?php
                                    }
                                }?>
                            </div><?php
                        }?>
                        <!-- Ticket Price End -->
                        <!-- Button -->
                        <div class="ep-mt-3 d-grid gap-2 d-md-block" id="ep_single_event_ticket_now_wrapper"><?php 
                            if( $args->event->em_enable_booking == 'external_bookings' ) {
                                $url = $args->event->em_custom_link;
                                $new_window = '';
                                if( ! empty( $args->event->em_custom_link_new_browser ) ) {
                                    $new_window = 'target=_blank';
                                }?>
                                <a href="<?php echo esc_url( $url );?>" class="ep-btn ep-btn-dark ep-box-w-100 ep-mb-2 ep-py-2" <?php echo esc_attr( $new_window );?>>
                                    <?php echo esc_html( $get_ticket_now_text ); ?>
                                </a><?php
                            } else if( $is_event_expired ) {?>
                                <div class="ep-btn-light ep-box-w-100 ep-mb-2 ep-py-2">
                                    <?php esc_html_e( 'This event has ended', 'eventprime-event-calendar-management' );?>
                                </div><?php
                            } else if( EventM_Factory_Service::ep_is_event_sold_out( $args->event ) ) {?>
                                <div class="ep-btn-light ep-text-danger ep-box-w-100 ep-mb-2 ep-py-2">
                                    <?php echo esc_html( $sold_out_text ); ?>
                                </div><?php
                            } else{
                                if( ! empty( $args->event->ticket_price_range ) ) {
                                    $ticket_button_style = '';
                                    $invite_only_event = get_post_meta( $args->event->em_id, 'em_rsvp_invite_only_event', true );
                                    if( ! empty( $invite_only_event ) ) {
                                        $ticket_button_style = 'style=display:none;';
                                    }?>
                                    <button type="button" id="ep_single_event_ticket_now_btn" class="ep-btn ep-btn-dark ep-box-w-100 ep-mb-2 ep-py-2" ep-modal-open="ep_single_event_page_ticket_modal" <?php echo esc_attr( $ticket_button_style );?>>
                                        <?php echo esc_html( $get_ticket_now_text ); ?>
                                    </button><?php
                                }
                            }?>
                        </div>
                    </div>
                    <!-- Offers -->
                    <?php if( ! empty( $booking_allowed ) && ! empty( $event_offers['all_show_offers'] ) && count( $event_offers['all_show_offers'] ) > 0 ) {?>
                        <div class="ep-box-col-12 ep-border-top"></div>
                        <div class="ep-box-col-12 ep-offers-section ep-p-4">
                            <h3 class="ep-fs-5 mb-2">
                                <?php esc_html_e( 'Available Offers', 'eventprime-event-calendar-management' );?>
                            </h3>
                            <div id="ep_single_event_available_offers">
                                <?php
                                if( ! empty( $event_offers ) && ! empty( $event_offers['all_show_offers'] ) && count( $event_offers['all_show_offers'] ) > 0 ) { ?>
                                    <div class="ep-text-small ep-bg-success ep-bg-opacity-10 ep-p-2 ep-text-success ep-rounded ep-mt-3">
                                        <?php esc_html_e( 'Offers are applied in the next step.', 'eventprime-event-calendar-management' );?>
                                    </div>
                                    <div id="ep_single_event_offers_list">
                                        <?php $count = 1;
                                        foreach( $event_offers['all_show_offers'] as $offer ) {
                                            $display = '';
                                            if( count( $event_offers['all_show_offers'] ) > 3 && $count > 3 ) {
                                                $display = 'style=display:none;';
                                            }?>
                                            <div class="ep-my-2 ep-py-2 ep-text-small ep-event-offer-single" id="ep_event_offer_num<?php echo esc_attr( $count );?>" <?php echo esc_attr( $display );?>>
                                                <div class="ep-fw-bold ep-text-uppercase ep-mb-1 ep-text-small">
                                                    <span class="ep-fs-5 material-icons-outlined offer-icon ep-align-bottom ep-text-warning ep-mr-1">local_offer</span>
                                                    <?php echo esc_html( $offer->em_ticket_offer_name );?>
                                                </div>
                                                <?php if( ! empty( $offer->em_ticket_offer_description ) ) {?>
                                                    <div class="ep-offer-desc ep-content-truncate ep-content-truncate-line-3 ep-text-small ep-mb-1">
                                                        <?php echo esc_html( $offer->em_ticket_offer_description );?>
                                                    </div><?php
                                                }?>
                                                <div class="ep-text-small ep-text-muted">
                                                    <?php 
                                                    $offer_date = EventM_Factory_Service::get_offer_date( $offer, $args->event );
                                                    if( ! empty( $offer_date ) ) {
                                                        echo esc_html( $offer_date );
                                                    }?>
                                                </div>
                                            </div><?php
                                            $count++;
                                        }
                                        if( count( $event_offers['all_show_offers'] ) > 3 ) {?>
                                            <div class="ep-more-offer ep-text-center" id="ep_show_more_event_offers">
                                                <span class="material-icons-outlined ep-bg-light ep-px-2 ep-rounded-5 ep-cursor">more_horiz</span>
                                            </div><?php
                                        }?>
                                    </div><?php
                                } else{ ?>
                                    <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                                        <?php esc_html_e( 'No offer available.', 'eventprime-event-calendar-management' );?>
                                    </div><?php
                                }?>
                            </div>
                        </div><?php
                    }
                }
            }

            do_action( 'ep_event_detail_after_ticket_block', $args );
            
            if( ep_get_global_settings( 'show_qr_code_on_single_event' ) == 1 ) {
                if( ! $is_event_expired && ( ! empty( $all_tickets ) && count( $all_tickets ) > 0 ) || $args->event->em_enable_booking == 'external_bookings' ) {?>
                    <div class="ep-box-col-12 ep-border-top"></div><?php
                }?>
                <div class="ep-box-col-12 ep-qr-code-section ep-p-4 mx-auto ep-text-center">
                    <div class="ep-fs-4">
                        <?php esc_html_e( 'Scan QR Code', 'eventprime-event-calendar-management' );?>
                    </div>
                    <?php $qr_code = EventM_Factory_Service::get_event_qr_code( $args->event );
                    if( ! empty( $qr_code ) ) {?>
                        <img src="<?php echo esc_url( $qr_code );?>" style="max-width:50%;" class="ep-mt-2 ep-mx-auto" id="ep_single_event_qr_code"><?php
                    }?>
                </div><?php
            }?>
        </div><?php
    }
    // Age Group
    if( ! empty( $args->event->event_type_details ) && ! empty( $args->event->event_type_details->em_age_group )  && empty(ep_get_global_settings('hide_age_group_section'))) {?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-mt-3 ep-border-left ep-border-3 ep-border-warning">
                <div class="ep-fw-bold">
                    <?php esc_html_e( 'Age Group', 'eventprime-event-calendar-management' );?>
                </div>
            </div>
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-transparent-border"> 
                <div class="ep-event-age-group ep-mt-1">
                    <?php if( $args->event->event_type_details->em_age_group == 'all' ) {
                        esc_html_e( ucfirst( $args->event->event_type_details->em_age_group ), 'eventprime-event-calendar-management' );
                    } else if( $args->event->event_type_details->em_age_group == 'parental_guidance' ) {
                        esc_html_e( 'All ages but parental guidance', 'eventprime-event-calendar-management' );
                    } else if( $args->event->event_type_details->em_age_group == 'custom_group' ) {
                        if( ! empty( $args->event->event_type_details->em_custom_group ) ) {
                            echo esc_html( $args->event->event_type_details->em_custom_group );
                        }
                    }?>
                </div>
            </div>
        </div><?php
    }
    // Attendee Note
    if( ! empty( $args->event->em_audience_notice ) && empty( ep_get_global_settings('hide_note_section') ) ) {?>
        <div class="ep-box-row ep-mt-3">
            <div class="ep-box-col-12 ep-mt-3 ep-border-left ep-border-3 ep-border-warning">
                <div class="ep-fw-bold">
                    <?php esc_html_e( 'Attendee Note', 'eventprime-event-calendar-management' );?>
                </div>
            </div>
            <div class="ep-box-col-12"> 
                <div class="ep-event-note ep-mt-1">
                    <?php echo esc_html( $args->event->em_audience_notice );?>
                </div>
            </div>
        </div> <?php
    }?>
    
</div>
<?php if( ! empty( $booking_allowed ) && $args->event->post_status == 'publish' && ! $is_event_expired ) {
    if( ! empty( $all_tickets ) && count( $all_tickets ) > 0 ) {
        $ticket_booking_start = $visible_ticket = 0;
        $all_event_bookings = EventM_Factory_Service::get_event_booking_by_event_id( $args->event->em_id, true );
        $em_event_seating_type = 'standing';
        if( ! empty( $args->event->venue_details ) && ! empty( $args->event->venue_details->em_type ) ) {
            $em_event_seating_type = $args->event->venue_details->em_type;
        }
        $live_seat_modal = ( $em_event_seating_type == 'seats' ) ? 'ep-ls-ticket-modal-view' : '';
        $ep_modal_xl = ( $em_event_seating_type == 'seats' ) ? 'ep-modal-xxl' : 'ep-modal-xl';
        ?>
        <div class="ep-modal ep-modal-view <?php echo esc_attr( $live_seat_modal );?>" id="ep-event-ticket-checkout-modal" ep-modal="ep_single_event_page_ticket_modal" style="display: none;">
            <div class="ep-modal-overlay" ep-modal-close="ep_single_event_page_ticket_modal"></div>
            <div class="ep-modal-dialog ep-modal-dialog-centered <?php echo esc_attr( $ep_modal_xl );?>">
                <div class="ep-modal-content">
                    <div class="ep-modal-body"> 
                        <div class="ep-box-row">
                            <input type="hidden" name="ep_event_booking_event_id" id="ep_event_booking_event_id" value="<?php echo base64_encode( $args->event->em_id );?>" />
                            <?php
                            if( $em_event_seating_type == 'seats' ) {
                                $active_extension = EP()->extensions;
                                if( ! empty( $active_extension ) && in_array( 'live_seating', $active_extension ) ) {
                                    do_action( 'ep_event_live_seating_tickets', $args->event );
                                } else{?>
                                    <div class="ep-box-col-12">
                                        <div class="ep-text-small ep-mt-3 ep-mb-2">
                                            <span class="ep-text-small ep-text-danger ep-bg-success ep-bg-opacity-10 ep-px-2 ep-py-2 ep-rounded-1">
                                                <?php echo esc_html_e( 'The event is related with the Seating', 'eventprime-event-calendar-management' );?>
                                            </span>
                                        </div>
                                    </div><?php
                                }
                            } else{?>
                                <div class="ep-box-col-8 ep-box-col-sm-8 ep-py-3" id="ep_event_ticket_modal_left">
                                    <div class="ep-event-ticket-wrap ep-box-w-100 ep-overflow-auto">
                                    <?php foreach( $all_tickets as $ticket ) {
                                        $check_ticket_visibility = EventM_Factory_Service::check_for_ticket_visibility( $ticket, $args->event );
                                        if( ! empty( $check_ticket_visibility['status'] ) ){
                                            $visible_ticket = 1;$ticket_disabled = 0;$ticket_disabled_class = $cursor_class = '';
                                            if( $check_ticket_visibility['message'] == 'disabled' ) {
                                                $ticket_disabled = 1;
                                                $cursor_class = 'ep-pe-none';
                                                $ticket_disabled_class = 'ep-ticket-disabled';
                                            }
                                            $ticket_offers_data = $offer_applied_data = array();
                                            if( ! empty( $ticket->offers ) ){
                                                $ticket_offers_data = json_decode( $ticket->offers );
                                                $offer_applied_data = EventM_Factory_Service::get_event_offer_applied_data( $ticket_offers_data, $ticket, $args->event->em_id );
                                            }
                                            if( ! empty( $ticket->name ) ) {
                                                $ticket->name = esc_html( stripslashes( $ticket->name ) );
                                            }
                                            if( ! empty( $ticket->description ) ) {
                                                $ticket->description = esc_html( stripslashes( $ticket->description ) );
                                            }?>
                                            <div class="ep-box-row ep-mb-3 ep-box-w-100 ep-border-bottom <?php echo esc_attr( $ticket_disabled_class );?>" id="ep_single_modal_ticket_<?php echo absint( $ticket->id );?>" data-ticket_id="<?php echo absint( $ticket->id );?>" data-ticket_data='<?php echo json_encode( $ticket );?>'>
                                                <div class="ep-box-col-12 ep-fs-5 ep-fw-bold ep-d-flex ep-align-items-center ep-event-ticket-modal-ticket-name"> 
                                                    <?php if( ! empty( $ticket->icon ) ) {
                                                        $ticket_icon_url = wp_get_attachment_url( $ticket->icon );?>
                                                        <img src="<?php echo esc_url( $ticket_icon_url );?>" style="max-width:100px;max-height: 100px"><span>&nbsp;&nbsp;<?php
                                                    }
                                                    echo esc_html( stripslashes( $ticket->name ) );?> </span>
                                                </div>
                                                <div class="ep-box-col-12 ep-text-small ep-event-ticket-modal-event-type">
                                                    <?php if( ! empty( $ticket->category_id ) ) {?>
                                                        <span class="material-icons-outlined ep-fs-6 ep-align-middle">folder</span> 
                                                        <?php echo esc_html( EventM_Factory_Service::get_ticket_category_name( $ticket->category_id, $args->event ) );?> 
                                                        <span class="border-end border-2 mx-2"></span><?php
                                                    }?>
                                                    <span class="material-icons-outlined ep-fs-6 ep-align-middle ep-event-ticket-modal-ticket-capacity-icon">groups</span>
                                                    <span class="ep-event-ticket-modal-ticket-capacity-icon"><?php esc_html_e( 'Capacity', 'eventprime-event-calendar-management' );?>: <?php echo absint( $ticket->capacity );?></span>
                                                </div>
                                                <?php if( ! empty( $ticket->description ) ) {?>
                                                    <div class="ep-box-col-12 ep-ticket-description ep-text-small ep-py-2 ep-event-ticket-modal-ticket-description">
                                                        <span class="ep-ticket-description-text">
                                                            <?php echo esc_html( stripslashes( $ticket->description ) );?>
                                                        </span> 
                                                        <a href="#" class="ep-show-more" style="display:none"> <?php esc_html_e( 'more', 'eventprime-event-calendar-management' );?></a> 
                                                        <a href="#" class="ep-show-less" style="display:none"><?php esc_html_e( 'less', 'eventprime-event-calendar-management' );?></a> 
                                                    </div><?php
                                                }?>
                                                <div class="ep-box-col-12"><?php 
                                                    $check_ticket_available = EventM_Factory_Service::check_for_ticket_available_for_booking( $ticket, $args->event );
                                                    if( $check_ticket_available['status'] == 'not_started' ) {?>
                                                        <div class="ep-text-small ep-mt-3 ep-mb-2">
                                                            <span class="ep-text-small ep-text-success ep-bg-success ep-bg-opacity-10 ep-px-2 ep-py-2 ep-rounded-1">
                                                                <?php echo esc_html( $check_ticket_available['message'] );?>
                                                            </span>
                                                        </div><?php
                                                    } elseif( $check_ticket_available['status'] == 'off' ) {?>
                                                        <div class="ep-text-small ep-mt-3 ep-mb-2">
                                                            <span class="ep-text-small ep-text-danger ep-bg-success ep-bg-opacity-10 ep-px-2 ep-py-2 ep-rounded-1">
                                                                <?php echo esc_html( $check_ticket_available['message'] );?>
                                                            </span>
                                                        </div><?php
                                                    } else{
                                                        $ticket_booking_start = 1;$ticket_sold_out = 0;
                                                        $remaining_caps = $ticket->capacity;
                                                        $booked_tickets_data = $all_event_bookings['tickets'];
                                                        if( ! empty( $booked_tickets_data ) ) {
                                                            if( isset( $booked_tickets_data[$ticket->id] ) && ! empty( $booked_tickets_data[$ticket->id] ) ) {
                                                                $booked_ticket_qty = absint( $booked_tickets_data[$ticket->id] );
                                                                if( $booked_ticket_qty > 0 ) {
                                                                    $remaining_caps = $ticket->capacity - $booked_ticket_qty;
                                                                    if( $remaining_caps < 1 ) {
                                                                        $ticket_sold_out = 1;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $max_caps = absint( $ticket->capacity );
                                                        if( absint( $ticket->max_ticket_no ) > 0 ) {
                                                            $max_caps = absint( $ticket->max_ticket_no );
                                                        }
                                                        if( ! empty( $remaining_caps ) && $remaining_caps < $max_caps ) {
                                                            $max_caps = $remaining_caps;
                                                        }
                                                        if( $ticket->show_remaining_tickets == 1 ) {?>
                                                            <div class="ep-text-small ep-text-white ep-mt-2 ep-event-ticket-modal-ticket-left">
                                                                <span class="ep-bg-danger ep-py-1 ep-px-2 ep-rounded-1 ep-text-smalll">
                                                                    <?php 
                                                                    if( $ticket_sold_out == 1 ) {
                                                                        echo esc_html( $sold_out_text );
                                                                    } else{
                                                                        //echo absint( $remaining_caps ) . ' ';
                                                                        $ticket_left_label = ep_global_settings_button_title( 'Tickets Left' );
                                                                        
                                                                        if( empty( $ticket_left_label ) ) {
                                                                            printf(esc_html__( '%d tickets left!', 'eventprime-event-calendar-management' ),absint( $remaining_caps ));
                                                                        }
                                                                        else
                                                                        {
                                                                             printf(esc_html__( '%d %s', 'eventprime-event-calendar-management' ),absint( $remaining_caps ),esc_html($ticket_left_label));
                                                                        }
                                                                        
                                                                    }?>
                                                                </span>
                                                            </div><?php
                                                        }?>
                                                        <div class="ep-my-3 ep-event-ticket-modal-ticket-price">
                                                            <span class="ep-fs-5 ep-fw-bold" id="ep_ticket_price_<?php echo absint( $ticket->id );?>" data-row_ticket_price="<?php echo esc_attr( $ticket->price );?>">
                                                                <?php 
                                                                if( $ticket->price ) {
                                                                    echo ep_price_with_position( $ticket->price );
                                                                } else{
                                                                    ep_show_free_event_price( $ticket->price );
                                                                }?>
                                                            </span>
                                                            <?php if( ! empty( $event_offers['applicable_offers'][$ticket->id] ) && count( $event_offers['applicable_offers'][$ticket->id] ) > 0 && empty( $ticket_sold_out ) ) {?>
                                                                <em class="ms-2 fw-normal ep-text-dark ep-text-small">
                                                                    <?php echo absint( count( $event_offers['applicable_offers'][$ticket->id] ) );?> 
                                                                    <?php esc_html_e( 'Offers Applied', 'eventprime-event-calendar-management' );?>
                                                                </em><?php
                                                            }?>
                                                        </div>
                                                        <?php if( empty( $ticket_sold_out ) ) {?>
                                                            <div class="ep-btn-group btn-group-sm ep-mb-2 ep-select-event-ticket-number <?php echo esc_attr( $cursor_class );?>" role="group">
                                                                <?php 
                                                                if( ! empty( $ticket_disabled ) ) {?>
                                                                    <button type="button" class="ep-btn ep-btn-outline-dark ep-px-3 ep-ticket-disabled-action" data-parent_id="<?php echo absint( $ticket->id );?>" data-dis_reason="<?php echo esc_attr( $check_ticket_visibility['reason'] );?>"> - </button>
                                                                        <input type="number" class="ep-btn ep-btn-outline-dark ep-px-3" value="0" disabled>
                                                                    <button type="button" class="ep-btn ep-btn-outline-dark ep-px-3" data-parent_id="<?php echo absint( $ticket->id );?>" data-dis_reason="<?php echo esc_attr( $check_ticket_visibility['reason'] );?>"> + </button><?php
                                                                } else{?>
                                                                    <button type="button" class="ep-btn ep-btn-outline-dark ep-px-3 ticket_minus" data-parent_id="<?php echo absint( $ticket->id );?>"> - </button>
                                                                        <input type="number" name="em_ticket_qty_<?php echo absint( $ticket->id );?>" id="ep_event_ticket_qty_<?php echo absint( $ticket->id );?>" class="ep-btn ep-btn-outline-dark ep-px-3" min="0" max="<?php echo absint( $max_caps );?>" value="0" data-min_allowed="<?php echo absint( $ticket->min_ticket_no );?>" readonly>
                                                                    <button type="button" class="ep-btn ep-btn-outline-dark ep-px-3 ticket_plus" data-parent_id="<?php echo absint( $ticket->id );?>"> + </button><?php
                                                                }?>
                                                            </div>
                                                            <div class="ep-error-message" id="em_ticket_qty_error_<?php echo absint( $ticket->id );?>"></div><?php
                                                        }
                                                    }?>
                                                </div>
                                                <div class="ep-box-col-12 ep-text-small ep-mt-2 ep-text-muted">
                                                    <?php
                                                    if( absint( $ticket->min_ticket_no ) > 0 ) {?>
                                                        <div class="ep-ticket-min-qty ep-text-small ep-d-inline-flex ep-mr-3 ep-align-items-center ep-event-ticket-modal-ticket-min-quantity">
                                                            <span class="material-icons-outlined ep-fs-6 ep-align-top ep-mr-1">task_alt</span> <?php esc_html_e( 'Min Qnty', 'eventprime-event-calendar-management' );?>: <?php echo absint( $ticket->min_ticket_no );?>
                                                        </div><?php
                                                    }
                                                    if( absint( $ticket->max_ticket_no ) > 0 ) {?>
                                                        <div class="ep-ticket-max-qty ep-text-small ep-d-inline-flex ep-mr-3 ep-align-items-center ep-event-ticket-modal-ticket-max-quantity">
                                                            <span class="material-icons-outlined ep-fs-6 ep-align-top ep-mr-1">task_alt</span> <?php esc_html_e( 'Max Qnty', 'eventprime-event-calendar-management' );?>: <?php echo absint( $ticket->max_ticket_no );?>
                                                        </div><?php
                                                    }?>
                                                </div>
                                                <?php
                                                if( ! empty( $event_offers['show_ticket_offers'][$ticket->id] ) && count( $event_offers['show_ticket_offers'][$ticket->id] ) > 0 ) { ?>
                                                    <div class="ep-box-col-12 ep-mt-3 ep-mb-1 ep-text-small ep-mb-2">
                                                        <span class="ep-fw-bold">
                                                            <?php esc_html_e( 'Available Offers', 'eventprime-event-calendar-management' );?>
                                                        </span>
                                                    </div>
                                                    <div class="ep-ticket-offers-wrapper ep-box-col-12 ep-d-flex ep-text-small ep-event-ticket-modal-ticket-offers">
                                                        <?php 
                                                        $off = 1;
                                                        foreach( $event_offers['show_ticket_offers'][$ticket->id] as $offer ) {?>
                                                            <div class="ep-text-small ep-d-inline-flex ep-border ep-rounded ep-p-2 ep-flex-column ep-mr-2 ep-box-w-25 ep-position-relative <?php if( ! empty( $offer->em_ticket_offer_type ) && $offer->em_ticket_offer_type == 'volume_based' ){ echo esc_html( 'em_ticket_volumn_based_offer' );}?>" data-offer_data='<?php echo json_encode( $offer );?>' id="ep_single_ticket_offer_<?php echo esc_attr( $ticket->id );?>_<?php echo esc_attr( $offer->uid );?>">
                                                                <div class="ep-fw-bold ep-mb-1 ep-text-small"><?php echo esc_html( $offer->em_ticket_offer_name );?></div>
                                                                <div class="ep-text-small ep-mb-1 ep-content-truncate">
                                                                    <?php if( ! empty( $offer->em_ticket_offer_description ) ) {
                                                                        echo esc_html( $offer->em_ticket_offer_description );
                                                                    }?>
                                                                </div>
                                                                <?php 
                                                                $offer_date = EventM_Factory_Service::get_offer_date( $offer, $args->event );
                                                                if( ! empty( $offer_date ) ) {?>
                                                                    <div class="ep-text-small ep-text-muted ep-mt-2 ep-di-flex ep-items-start">
                                                                        <span class="material-icons-outlined ep-fs-6 ep-text-small ep-align-top ep-mt-1 ep-mr-1">schedule</span> <?php echo esc_html( $offer_date );?>
                                                                    </div><?php
                                                                }
                                                                $ticket_offer_applied_style = 'top:-10px; right:-7px;';
                                                                if( empty( $event_offers['applicable_offers'][$ticket->id] ) || empty( $event_offers['applicable_offers'][$ticket->id][$offer->uid] ) ) { 
                                                                    $ticket_offer_applied_style .= 'display: none;';
                                                                }?>
                                                                <span class="ep-rounded-5 ep-position-absolute ep-bg-white ep-text-warning ep-fs-3 ep-event-offer-applied" style='<?php echo esc_attr( $ticket_offer_applied_style );?>' id="ep_event_offer_<?php echo esc_attr( $ticket->id );?>_<?php echo esc_attr( $off );?>">
                                                                    <span class="material-icons-outlined ep-align-top">done</span>
                                                                </span>
                                                            </div><?php
                                                            $off++;
                                                        }?>
                                                    </div><?php
                                                }?>
                                            </div><?php
                                        }
                                    }
                                    if( $visible_ticket == 0 ){
                                        if( $check_ticket_visibility['message'] == 'require_login' && !is_user_logged_in() ) {?>
                                            <div class="ep-box-row ep-mb-2">
                                                <div class="ep-box-col-12 ep-fs-5 ep-fw-bold">
                                                    <span class="ep-text-small ep-text-success ep-bg-warning ep-bg-opacity-10 ep-px-2 ep-py-2 ep-rounded-1">
                                                        <?php esc_html_e( 'To view the tickets, you need to login.', 'eventprime-event-calendar-management' );?>&nbsp;
                                                        <a href="javascript:void(0);" id="ep_tickets_show_login"><?php esc_html_e( 'Click here to login', 'eventprime-event-calendar-management' );?></a>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ep-box-row ep-py-3" id="ep_tickets_need_login" style="display:none;">
                                                <div class="ep-box-col-8 ep-fs-6 ep-text-small">
                                                    <?php echo do_shortcode( '[em_login redirect="reload"]' );?>
                                                </div>
                                            </div><?php
                                        }
                                    }?>
                                    </div>
                                </div>
                                <div class="ep-box-col-4 ep-box-col-sm-4" id="ep_event_ticket_modal_right">
                                    <?php 
                                    $total_price = 0;
                                    if ( has_post_thumbnail( $args->event->em_id ) ){ ?>
                                        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $args->event->em_id ), 'large' ); ?>
                                        <div id="ep_event_ticket_modal_right_image">
                                            <img src="<?php echo esc_url( $image[0] );?>" alt="<?php echo esc_attr( $args->event->name ); ?>" class="ep-rounded" style="max-width:100%;" />
                                        </div><?php
                                    }?>
                                    <div class="ep-fs-5 ep-fw-bold ep-mt-2 ep-text-truncate" id="ep_event_ticket_modal_right_event_name"> <?php echo esc_html( $args->event->name ); ?> </div>
                                    <?php if( ! empty( $args->event->em_venue ) ) {
                                        $event_venue = EventM_Factory_Service::ep_get_venue_by_id( $args->event->em_venue );?>
                                        <div class="ep-text-small ep-text-muted" id="ep_event_ticket_modal_right_venue_name">
                                            <span class="material-icons-outlined ep-fs-6 ep-align-middle">place</span> <?php echo esc_html( $event_venue->name );?>
                                        </div><?php
                                    }
                                    if( ! empty( $args->event->em_start_date ) && ep_show_event_date_time( 'em_start_date', $args->event ) ) {?>
                                        <div class="ep-text-small mb-3 text-muted ep-d-flex ep-align-items-center" id="ep_event_ticket_modal_right_date_time">
                                            <span class="material-icons-outlined ep-fs-6 ep-align-middle ep-mr-1">event</span> 
                                            <?php echo esc_html( ep_timestamp_to_date( $args->event->em_start_date, 'd M, Y', 1 ) );
                                            if( ! empty( $args->event->em_start_time ) && ep_show_event_date_time( 'em_start_time', $args->event ) ) {
                                                echo ', ' . esc_html( ep_convert_time_with_format( $args->event->em_start_time ) );
                                            }?>
                                        </div><?php
                                    }?>
                                    <div id="ep_event_booking_ticket" data-ticket_options=""></div>
                                    <div class="ep-text-small ep-my-2 ep-rounded ep-p-2">
                                        <?php if( $args->event->em_fixed_event_price > 0 ) {
                                            $total_price += $args->event->em_fixed_event_price;?>
                                            <div class="ep-box-row" id="ep_event_ticket_modal_right_fixed_fee">
                                                <div class="ep-box-col-6"> <?php esc_html_e( 'Event Fees', 'eventprime-event-calendar-management' );?> </div>
                                                <div class="ep-box-col-6 ep-text-end" id="ep_event_fixed_price" data-fixed_price="<?php echo esc_attr( $args->event->em_fixed_event_price );?>"> <?php echo esc_html( ep_price_with_position( $args->event->em_fixed_event_price ) );?> </div>
                                            </div><?php
                                        }?>
                                        <div class="ep-box-row" id="ep_event_ticket_modal_right_total">
                                            <div class="ep-box-col-6 ep-fw-bold mt-2"> <?php esc_html_e( 'Total', 'eventprime-event-calendar-management' );?> </div>
                                            <div class="ep-box-col-6 ep-text-end ep-fw-bold mt-2" id="ep_ticket_price_total"><?php echo esc_html( ep_price_with_position( $total_price ) );?></div>
                                        </div>
                                    </div>
                                    <div class="ep-mt-3">
                                        <div class="ep-error-message" id="ep_single_event_before_checkout_error_msg"></div>
                                        <?php if( $ticket_booking_start == 1 ) {?>
                                            <form action="<?php echo esc_url( get_permalink( ep_get_global_settings( 'booking_page' ) ) ); ?>" method="post" name="ep_event_booking" id="ep_event_booking_form">
                                                <input type="hidden" name="ep_event_booking_data" id="ep_event_booking_data" value="" />
                                                <input type="hidden" name="ep_event_offer_data" id="ep_event_offer_data" value="" />

                                                <?php
                                                if(is_user_logged_in())
                                                {
                                                    $current_user = wp_get_current_user();
                                                    $current_user_role = (array) $current_user->roles;
                                                    ?>
                                                    <input type="hidden" name="ep_current_user_role" value="<?php echo esc_attr( $current_user_role[0] );?>" />
                                                <?php 
                                                } 
                                                ?>
                                                
                                                <button type="button" class="ep-btn ep-btn-warning ep-py-2 ep-box-w-100" id="ep_single_event_checkout_btn" disabled>
                                                    <?php if( ! empty( $args->event->em_event_checkout_attendee_fields ) ) {
                                                        echo esc_html( $add_details_and_checkout_text );
                                                    } else{
                                                        echo esc_html( $checkout_text );
                                                    }?>
                                                </button>
                                                <?php do_action('ep_after_event_ticket_book_button', $args->event->em_id);?>
                                            </form><?php
                                        }?>
                                        <a href="javascript:void(0);" ep-modal-close="ep_single_event_page_ticket_modal">
                                            <button type="button" class="ep-btn ep-btn-dark ep-py-2 ep-box-w-100 ep-mt-2" id="ep_event_close_ticket_modal"><?php esc_html_e( 'Close', 'eventprime-event-calendar-management' );?></button>
                                        </a>
                                    </div>
                                </div><?php
                            }?>
                        </div>
                    </div>
                </div>
            </div>
        </div><?php
    }
}?>