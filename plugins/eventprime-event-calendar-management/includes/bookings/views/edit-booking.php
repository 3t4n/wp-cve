<?php if( empty( $args->ep_nonce_verified ) || empty( $args->booking_data ) ) {?>
    <div class="ep-alert ep-alert-warning ep-mt-3">
        <?php esc_html_e( 'No data found.', 'eventprime-event-calendar-management' ); ?>
    </div><?php
} else{?>
    <div class="emagic ep-position-relative" id="ep_event_edit_booking_page">
        <?php do_action( 'ep_add_loader_section' );?>
        <div class="ep-box-wrap">
            <h2>
                <?php echo apply_filters( 'ep_event_edit_booking_page_title', esc_html__( 'Edit Booking', 'eventprime-event-calendar-management' ) );?>
            </h2>
            
            <div class="ep-box-row ep-mt-5 ep-mb-3">
                <div class="ep-box-col-2 ep-pr-2 ep-border-right ep-border-warning ep-border-3 ep-lh-0 ep-box-col-sm-2 ep-box-col-xsm-2">
                    <img class="ep-checkout-img-icon ep-rounded-1" src="<?php echo esc_url( $args->booking_data->event_data->image_url );?>" alt="<?php echo esc_html( $args->booking_data->event_data->name );?>" style="max-width:100%;">
                </div>
                <div class="ep-box-col-10 ep-text-start ep-lh-1 ep-box-col-sm-10 ep-box-col-xsm-10">
                    <div class="ep-fs-3 ep-fw-bold ep-mb-2"><?php echo esc_html( $args->booking_data->event_data->name );?></div>
                    <div class="ep-fs-6">
                        <div class="ep-d-inline-flex ep-align-items-center">
                            <?php echo esc_html( $args->booking_data->event_data->fstart_date );?>, <?php echo esc_html( ep_convert_time_with_format( $args->booking_data->event_data->em_start_time ) );?>
                            <?php if( ! empty( $args->booking_data->event_data->venue_details  ) && ! empty( $args->booking_data->event_data->venue_details->name ) ) {?>
                                <span class="material-icons-round ep-text-warning">arrow_right</span><?php
                            }?>
                        </div>
                        <div class="ep-d-inline-flex ep-text-muted">
                            <?php if( ! empty( $args->booking_data->event_data->venue_details ) && ! empty( $args->booking_data->event_data->venue_details->name ) ) {
                                echo esc_html( $args->booking_data->event_data->venue_details->name );
                            }?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ep-box-row ep-mb-3">
                <form name="checkout_form" class="needs-validation ep-box-col-12" novalidate="" id="ep_event_edit_booking_form">
                    <input type="hidden" name="ep_event_booking_id" value="<?php echo esc_attr( $args->booking_data->em_id );?>" />
                    <input type="hidden" name="ep_event_booking_user_id" value="<?php echo esc_attr( get_current_user_id() );?>" />
                    <div class="ep-box-row ep-g-5">
                        <div class="ep-box-col-8 ep-text-small ep-col-order-1">
                            <!-- Attendees Info Section -->
                            <?php if( ! empty( $args->booking_data->em_attendee_names ) ) {?>
                                <div id="ep_event_booking_attendee_section">
                                    <div class="ep-mb-3">
                                        <?php esc_html_e( 'Please update the attendees details below:', 'eventprime-event-calendar-management' );?>
                                    </div>
                                    <?php $ticket_num = $num = 1;
                                    $em_event_checkout_attendee_fields = ( ! empty( $args->booking_data->event_data->em_event_checkout_attendee_fields ) ? $args->booking_data->event_data->em_event_checkout_attendee_fields : array() );
                                    //$em_event_checkout_fixed_fields = ( ! empty( $args->event->em_event_checkout_fixed_fields ) ? $args->event->em_event_checkout_fixed_fields : array() );
                                    $attendees_data = $args->booking_data->em_attendee_names;
                                    foreach( $attendees_data as $ticket_id => $ticket_data ) {
                                        $event_ticket_data = EventM_Factory_Service::get_event_ticket_by_id( $ticket_id );
                                        if( ! empty( $event_ticket_data ) ) {
                                            $event_ticket_name = $event_ticket_data->name;
                                            $total_ticket_qty_count = count( $ticket_data );
                                            for( $q = 1; $q <= $total_ticket_qty_count; $q ++ ) {?>
                                                <div class="ep-event-booking-attendee ep-mb-3">
                                                    <div class="ep-event-booking-attendee-head ep-box-row ep-overflow-hidden ep-border ep-rounded-top  ep-mb-0">
                                                        <div class="ep-box-col-12 ep-py-3 ep-d-flex ep-justify-content-between">
                                                            <span class="ep-fs-6 ep-fw-bold">
                                                                <?php $ticket = ep_global_settings_button_title( 'Ticket' );
                                                                if( empty( $ticket ) ) {
                                                                    $ticket = esc_html__( 'Ticket', 'eventprime-event-calendar-management' ); 
                                                                }
                                                                echo $ticket; echo ' ' . esc_html( $ticket_num );?>
                                                            </span>
                                                            <span class="material-icons-round ep-align-bottom ep-bg-light ep-cursor ep-rounded-circle ep-ml-5 ep-event-attendee-handler">expand_more</span>
                                                        </div>
                                                    </div>
                                                    <div class="ep-event-booking-attendee-section ep-box-row ep-border ep-border-top-0 ep-rounded-bottom ">
                                                        <div class="ep-box-col-3 ep-text-small ep-ps-4 ep-d-flex ep-align-items-center">
                                                            <div class="ep-p-2">
                                                                <div>
                                                                    <?php esc_html_e( 'Type:', 'eventprime-event-calendar-management' );?>&nbsp;
                                                                    <strong><?php echo esc_html( $event_ticket_name );?></strong>
                                                                </div>
                                                                <?php if( $event_ticket_data->category_id && $event_ticket_data->category_id > 0 ) {?>
                                                                    <div>
                                                                        <?php esc_html_e( 'Category:', 'eventprime-event-calendar-management' );?>&nbsp;
                                                                        <strong><?php echo esc_html( EventM_Factory_Service::get_ticket_category_name( $event_ticket_data->category_id, $args->booking_data->event_data ) );?></strong>
                                                                    </div><?php
                                                                }?>
                                                                <div>
                                                                    <?php esc_html_e( 'Attendee:', 'eventprime-event-calendar-management' );?>&nbsp;
                                                                    <strong><?php echo esc_html( $q );?></strong>&nbsp;
                                                                    <?php echo '('. esc_html__( 'of', 'eventprime-event-calendar-management' ). ' ' .esc_html( $total_ticket_qty_count ). ')';?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                        <div class="ep-box-col-9 ep-p-3">
                                                            <?php $at_field_num = 1;$match_find = 0;
                                                            foreach( $ticket_data as $single_ticket_sec ) {
                                                                if( $at_field_num == $q ) {
                                                                    $match_find = 1;
                                                                    foreach( $single_ticket_sec as $key => $ticket_attendee_data ) {
                                                                        if( $key == 'name' ) {
                                                                            foreach( $ticket_attendee_data as $key_field => $attendee_field_data ) {
                                                                                if( $key_field == 'first_name' ) {?>
                                                                                    <div class="ep-mb-3">
                                                                                        <label for="name" class="form-label ep-text-small">
                                                                                            <?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );
                                                                                            if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) ) {?>
                                                                                                <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                            }?>
                                                                                        </label>
                                                                                        <input name="ep_booking_attendee_fields[<?php echo esc_attr( $event_ticket_data->id );?>][<?php echo esc_attr( $num );?>][name][first_name]" type="text" class="ep-form-control" 
                                                                                            value="<?php echo esc_html( $attendee_field_data );?>"
                                                                                            id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_name_first_name" 
                                                                                            placeholder="<?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );?>"
                                                                                            <?php if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) ) { echo 'required="required"'; }?>
                                                                                        >
                                                                                        <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_name_first_name_error"></div>
                                                                                    </div><?php
                                                                                }
                                                                                if( $key_field == 'middle_name' ) {?>
                                                                                    <div class="ep-mb-3">
                                                                                        <label for="name" class="form-label ep-text-small">
                                                                                            <?php esc_html_e( 'Middle Name', 'eventprime-event-calendar-management' );
                                                                                            if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) ) {?>
                                                                                                <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                            }?>
                                                                                        </label>
                                                                                        <input name="ep_booking_attendee_fields[<?php echo esc_attr( $event_ticket_data->id );?>][<?php echo esc_attr( $num );?>][name][middle_name]" type="text" class="ep-form-control" 
                                                                                            value="<?php echo esc_html( $attendee_field_data );?>"
                                                                                            id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_name_middle_name" 
                                                                                            placeholder="<?php esc_html_e( 'Middle Name', 'eventprime-event-calendar-management' );?>"
                                                                                            <?php if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) ) { echo 'required="required"'; }?>
                                                                                        >
                                                                                        <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_name_middle_name_error"></div>
                                                                                    </div><?php
                                                                                }
                                                                                if( $key_field == 'last_name' ) {?>
                                                                                    <div class="ep-mb-3">
                                                                                        <label for="name" class="form-label ep-text-small">
                                                                                            <?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );
                                                                                            if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) ) {?>
                                                                                                <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                            }?>
                                                                                        </label>
                                                                                        <input name="ep_booking_attendee_fields[<?php echo esc_attr( $event_ticket_data->id );?>][<?php echo esc_attr( $num );?>][name][last_name]" type="text" class="ep-form-control" 
                                                                                            value="<?php echo esc_html( $attendee_field_data );?>"
                                                                                            id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_name_last_name" 
                                                                                            placeholder="<?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );?>"
                                                                                            <?php if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) ) { echo 'required="required"'; }?>
                                                                                        >
                                                                                        <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_name_last_name_error"></div>
                                                                                    </div><?php
                                                                                }
                                                                            }
                                                                        } else{
                                                                            $checkout_require_fields = array();
                                                                            $core_field_types = array_keys( ep_get_core_checkout_fields() );
                                                                            if( isset( $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] ) && ! empty( $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] ) ) {
                                                                                $checkout_require_fields = $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'];
                                                                            }
                                                                            $checkout_fields = EventM_Factory_Service::get_checkout_field_by_id( $key );
                                                                            $input_name = ep_get_slug_from_string( $ticket_attendee_data['label'] );
                                                                            if( in_array( $checkout_fields->type, $core_field_types ) ) {?>
                                                                                <div class="ep-mb-3">
                                                                                    <label for="name" class="form-label ep-text-small">
                                                                                        <?php echo esc_html( $ticket_attendee_data['label'] );
                                                                                        if( in_array( $checkout_fields->id, $checkout_require_fields ) ) {?>
                                                                                            <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                        }?>
                                                                                    </label>
                                                                                    <input name="ep_booking_attendee_fields[<?php echo esc_attr( $event_ticket_data->id );?>][<?php echo esc_attr( $num );?>][<?php echo esc_attr( $checkout_fields->id );?>][label]" type="hidden" value="<?php echo esc_attr( $ticket_attendee_data['label'] );?>">
                                                                                    <input name="ep_booking_attendee_fields[<?php echo esc_attr( $event_ticket_data->id );?>][<?php echo esc_attr( $num );?>][<?php echo esc_attr( $checkout_fields->id );?>][<?php echo esc_attr( $input_name );?>]" 
                                                                                        value="<?php echo esc_html( $ticket_attendee_data[$input_name] );?>"
                                                                                        type="<?php echo esc_attr( $checkout_fields->type );?>" 
                                                                                        class="ep-form-control" 
                                                                                        id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_<?php echo esc_attr( $checkout_fields->id );?>_<?php echo esc_attr( $input_name );?>" 
                                                                                        placeholder="<?php echo esc_attr( $ticket_attendee_data['label'] );?>"
                                                                                        <?php if( in_array( $checkout_fields->id, $checkout_require_fields ) ) { echo 'required="required"'; } ?>
                                                                                    >
                                                                                    <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $event_ticket_data->id );?>_<?php echo esc_attr( $num );?>_<?php echo esc_attr( $checkout_fields->id );?>_<?php echo esc_attr( $input_name );?>_error"></div>
                                                                                </div><?php
                                                                            } else{
                                                                                $checkout_field_data = array( 'fields' => $checkout_fields, 'tickets' => $event_ticket_data, 'checkout_require_fields' => $checkout_require_fields, 'num' => $num, 'value' => $ticket_attendee_data[$input_name] );
                                                                                do_action( 'ep_event_advanced_checkout_fields_section', $checkout_field_data );
                                                                            }
                                                                        }
                                                                    }
                                                                    $num++;
                                                                }
                                                                if( $match_find == 1 ){
                                                                    break;
                                                                }
                                                                $at_field_num++;
                                                            }?>
                                                        </div>
                                                    </div>
                                                </div><?php
                                                $ticket_num++;
                                            }
                                        }
                                    }?>
                                </div><?php
                            }?>
                            <!-- Attendees info section End -->
                        </div>
                    </div>
                    
                    <div class="ep-box-row">
                        <?php wp_nonce_field( 'ep_update_event_booking', 'ep_update_event_booking_nonce' );?>
                        <div class="ep-box-col-1">
                            <a href="<?php echo esc_url( ep_get_custom_page_url( 'profile_page' ) );?>">
                                <button type="button" class="ep-btn ep-btn-dark">
                                    <?php esc_html_e( 'Cancel', 'eventprime-event-calendar-management' );?>
                                </button>
                            </a>
                        </div>
                        <div class="ep-box-col-2">
                            <button type="button" class="ep-btn ep-btn-warning ep-mb-2" id="ep_event_update_booking_button">
                            <?php esc_html_e( 'Update Booking', 'eventprime-event-calendar-management' );?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div><?php
}?>