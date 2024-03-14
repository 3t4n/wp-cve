<?php
$is_recaptcha_enabled = 0;
if( ep_get_global_settings('checkout_reg_google_recaptcha') == 1 && !empty(ep_get_global_settings('google_recaptcha_site_key')) ){
    $is_recaptcha_enabled = 1;?>
    <script src='https://www.google.com/recaptcha/api.js'></script><?php 
}
?>
<?php if( ! empty( $args->tickets ) && ! empty( $args->event ) && ! empty( $args->event->em_id ) ) {?>
    <?php $checkout_text = ep_global_settings_button_title('Checkout'); ?>
    <div class="emagic ep-position-relative" id="ep_event_checkout_page">
        <?php do_action( 'ep_add_loader_section' );?>
        <div class="ep-box-wrap">
            <div class="ep-box-row ep-text-center ep-text-small">
                <div class="ep-box-col-2">
                    <div class="ep-flex-column">
                        <div class="">
                            <span class="material-icons-round ep-bg-warning ep-rounded-circle ep-p-2" id="ep_booking_step1">view_list</span>
                        </div>
                        <div class="ep-fw-bold">
                            <?php esc_html_e( 'Step 1', 'eventprime-event-calendar-management' );?> 
                        </div>
                        <div class="ep-text-small ep-text-muted">
                            <?php esc_html_e( 'Attendee Details', 'eventprime-event-calendar-management' );?> 
                        </div>
                    </div>
                </div>
                <div class="ep-box-col-8 small text-danger">
                    <div id="ep_checkout_timer_section">
                        <span class="ep-text-dark">
                            <?php esc_html_e( 'You have', 'eventprime-event-calendar-management' );?> 
                        </span>
                        <?php $checkout_timer_sec = 260;
                        $checkout_timer_min = ep_get_global_settings( 'checkout_page_timer' );
                        if( $checkout_timer_min > 0 ) {
                            $checkout_timer_sec = $checkout_timer_min * 60;
                        }?>
                        <span class="ep-checkout-time ep-fw-bold"><?php echo absint( $checkout_timer_sec );?></span> <?php esc_html_e( 'seconds', 'eventprime-event-calendar-management' );?>
                        <span class="ep-text-dark">
                            <?php echo esc_html__( 'left to', 'eventprime-event-calendar-management' ).' '.esc_html( $checkout_text );?>
                        </span>
                    </div>
                    <div class="ep-progress ep-bg-success ep-bg-opacity-10" style="height: 3px;">
                        <div class="ep-progress-bar ep-bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="ep-box-col-2">
                    <div class="ep-flex-column">
                        <div class="ep-text-muted" id="ep-booking-step-2">
                            <span class="material-icons-round ep-bg-light ep-rounded-circle ep-p-2" id="ep_booking_step2">shopping_cart</span>
                        </div>
                        <div class="ep-fw-bold ep-text-muted">
                            <?php esc_html_e( 'Step 2', 'eventprime-event-calendar-management' );?>
                        </div>
                        <div class="ep-text-small ep-text-muted">
                            <?php echo esc_html( $checkout_text ). ' '. esc_html__( '& Payment', 'eventprime-event-calendar-management' );?>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="ep-box-row ep-mt-5 ep-mb-3"><?php
                if( ! empty( $args->event->image_url ) ) {?>
                    <div class="ep-box-col-2 ep-pr-2 ep-border-right ep-border-warning ep-border-3 ep-lh-0 ep-box-col-sm-2 ep-box-col-xsm-2">
                        <img class="ep-checkout-img-icon ep-rounded-1" src="<?php echo esc_url( $args->event->image_url );?>" alt="<?php echo esc_html( $args->event->name );?>" style="max-width:100%;">
                    </div><?php
                }?>
                <div class="ep-box-col-10 ep-text-start ep-lh-1 ep-box-col-sm-10 ep-box-col-xsm-10">
                    <div class="ep-fs-3 ep-fw-bold ep-mb-2"><?php echo esc_html( $args->event->name );?></div>
                    <div class="ep-fs-6">
                        <div class="ep-d-inline-flex ep-align-items-center">
                            <?php echo esc_html( $args->event->fstart_date );?>, <?php echo esc_html( ep_convert_time_with_format( $args->event->em_start_time ) );?>
                            <?php if( !empty( $args->event->venue_details  ) ) {?>
                                <span class="material-icons-round ep-text-warning">arrow_right</span><?php
                            }?>
                        </div>
                        <div class="ep-d-inline-flex ep-text-muted">
                            <?php if( !empty( $args->event->venue_details  ) ) {
                                echo esc_html( $args->event->venue_details->name );
                            }?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ep-box-row ep-mb-3">
                <form name="checkout_form" class="needs-validation ep-box-col-12" novalidate="" id="ep_event_checkout_form">
                    <input type="hidden" name="ep_event_booking_ticket_data" value="<?php echo esc_attr( json_encode( $args->tickets ) );?>" />
                    <input type="hidden" name="ep_event_booking_event_id" value="<?php echo esc_attr( $args->event->em_id );?>" />
                    <input type="hidden" name="ep_event_booking_user_id" value="<?php echo esc_attr( get_current_user_id() );?>" />
                    <div class="ep-box-row ep-g-5 ep-flex-row-reverse-md">
                        <!-- Right side Tickets info box -->
                        <div class="ep-box-col-4 ep-col-order-2">
                            <ul class="ep-list-group ep-text-small ep-mx-0 ep-px-0 ep-m-0">

                                <?php do_action( 'ep_event_booking_before_ticket_info', $args ); ?>

                                <?php $total_price = $total_tickets = 0;
                                if( ! empty( $args->tickets ) && count( $args->tickets ) > 0 ) {
                                    foreach( $args->tickets as $tickets ) {
                                        $tic_sub_total = $tickets->price * $tickets->qty;
                                        $total_price += $tic_sub_total;
                                        $total_tickets += $tickets->qty;?>
                                        <li class="ep-list-group-item" aria-current="true">
                                            <span class="ep-fw-bold ep-mr-2">
                                                <?php echo esc_html( $tickets->name );?>
                                            </span>
                                            <span class="ep-text-small">x <?php echo absint( $tickets->qty );?></span>
                                            <div class="ep-box-row ep-text-small">
                                                <div class="ep-box-col-6">
                                                    <?php esc_html_e( 'Base Price', 'eventprime-event-calendar-management' );?>
                                                </div>
                                                <div class="ep-box-col-6 ep-text-end">
                                                    <?php echo esc_html( ep_price_with_position( $tic_sub_total ) );?>
                                                </div>
                                                <?php if( ! empty( $tickets->additional_fee ) && count( $tickets->additional_fee ) > 0 ) {
                                                    foreach( $tickets->additional_fee as $fee ) { 
                                                        $add_price = $fee->price * $tickets->qty;
                                                        $total_price += $add_price;?>
                                                        <div class="ep-box-col-6 ep-text-muted">
                                                            <?php echo esc_html( $fee->label );?>
                                                        </div>
                                                        <div class="ep-box-col-6 ep-text-end ep-text-muted">
                                                            <?php echo esc_html( ep_price_with_position( $add_price ) );?>
                                                        </div><?php
                                                    }
                                                }?>
                                                <!-- Offers -->
                                                <?php if( isset( $tickets->offer ) && ! empty( $tickets->offer ) ) {
                                                    $total_price -= $tickets->offer;?>
                                                    <div class="ep-box-col-6 ep-text-muted">
                                                        <?php esc_html_e( 'Offers', 'eventprime-event-calendar-management' );?>
                                                    </div>
                                                    <div class="ep-box-col-6 ep-text-end ep-text-muted"><?php echo esc_html( '-' . ep_price_with_position( $tickets->offer ) );?></div><?php
                                                }?>

                                                <?php do_action( 'ep_event_booking_after_single_ticket_info', $tickets ); ?>
                                            </div>
                                        </li><?php
                                    }
                                }?>

                                <?php do_action( 'ep_event_booking_after_ticket_info', $args ); ?>

                                <!-- Event Fixed Price -->
                                <?php if( $args->event->em_fixed_event_price && $args->event->em_fixed_event_price > 0 ) {
                                    $total_price += $args->event->em_fixed_event_price;?>
                                    <li class="ep-list-group-item" aria-current="true">
                                        <div class="ep-box-row ep-py-2">
                                            <div class="ep-box-col-6 ep-fw-bold">
                                                <span><?php esc_html_e( 'Event Fee', 'eventprime-event-calendar-management' );?></span>
                                                <span class="material-icons-round ep-fs-6 ep-align-middle ep-text-muted">help_outline</span>
                                            </div>
                                            <div class="ep-box-col-6 ep-text-end">
                                                <?php echo esc_html( ep_price_with_position( $args->event->em_fixed_event_price ) );?>
                                                <input type="hidden" name="ep_event_booking_event_fixed_price" value="<?php echo esc_attr( $args->event->em_fixed_event_price );?>" />
                                            </div>
                                        </div>
                                    </li><?php
                                }?>

                                <?php do_action( 'ep_event_booking_before_ticket_total', $args ); ?>

                                <!-- Total Price -->
                                <li class="ep-list-group-item ep-bg-light" id="ep-booking-total" aria-current="true">
                                    <div class="ep-box-row ep-py-2 ep-fs-5">
                                        <div class="ep-box-col-6 ep-fw-bold">
                                            <?php esc_html_e( 'Total', 'eventprime-event-calendar-management' );?>
                                        </div>
                                        <div class="ep-box-col-6 ep-text-end ep-fw-bold">
                                            <?php 
                                            $total_price = apply_filters( 'ep_event_booking_total_price', $total_price, $args->event->id );
                                            if( $total_price ) {
                                                echo esc_html( ep_price_with_position( $total_price ) );
                                            } else{
                                                ep_show_free_event_price( $total_price );
                                            }?>
                                            <input type="hidden" name="ep_event_booking_total_price" value="<?php echo esc_attr( $total_price );?>" />
                                            <input type="hidden" name="ep_event_booking_total_tickets" value="<?php echo absint( $total_tickets );?>" />
                                        </div>
                                    </div>
                                </li>
                                
                                <?php do_action( 'ep_event_booking_after_ticket_total', $args ); ?>
                            </ul> 
                            
                            <?php do_action( 'ep_event_booking_after_ticket_info_box', $args ); ?>

                            <div class="ep-my-3">
                                <?php do_action( 'ep_event_booking_before_checkout_button', $args ); ?>
                                <?php wp_nonce_field( 'ep_save_event_booking', 'ep_save_event_booking_nonce' );?>
                                <?php if(isset($args->event) && (!isset($args->event->enable_event_wc_checkout) || empty($args->event->enable_event_wc_checkout))){?>
                                <button type="button" class="ep-btn ep-btn-warning ep-box-w-100 ep-mb-2 step1" id="ep_event_booking_checkout_btn" data-active_step="1">
                                    <?php echo esc_html( $checkout_text ); ?>
                                </button>
                                <?php } ?>
                                <a href="<?php echo esc_url( $args->event->event_url );?>">
                                    <button type="button" class="ep-btn ep-btn-dark ep-box-w-100">
                                        <?php esc_html_e( 'Cancel', 'eventprime-event-calendar-management' );?>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <!-- Tickets info Box end -->
                        <!-- Left side box -->
                        <div class="ep-box-col-8 ep-text-small ep-col-order-1">
                            <!-- Attendees Info Section -->
                            <?php if( ! empty( $args->tickets ) && count( $args->tickets ) > 0 ) {?>
                                <div id="ep_event_booking_attendee_section">
                                    <div class="ep-mb-3">
                                        <?php esc_html_e( 'Please enter details of the attendees below:', 'eventprime-event-calendar-management' );?>
                                    </div>
                                    <?php $ticket_num = 1;
                                    $em_event_checkout_attendee_fields = ( ! empty( $args->event->em_event_checkout_attendee_fields ) ? $args->event->em_event_checkout_attendee_fields : array() );
                                    $em_event_checkout_fixed_fields = ( ! empty( $args->event->em_event_checkout_fixed_fields ) ? $args->event->em_event_checkout_fixed_fields : array() );
                                    foreach( $args->tickets as $tickets ) {
                                        if( $tickets->qty && $tickets->qty > 0 ) {
                                            $num = 1;
                                            for( $q = 0; $q < $tickets->qty; $q ++ ) {?>
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
                                                                    <strong><?php echo esc_html( $tickets->name );?></strong>
                                                                </div>
                                                                <?php if( $tickets->category_id && $tickets->category_id > 0 ) {?>
                                                                    <div>
                                                                        <?php esc_html_e( 'Category:', 'eventprime-event-calendar-management' );?>&nbsp;
                                                                        <strong><?php echo esc_html( EventM_Factory_Service::get_ticket_category_name( $tickets->category_id, $args->event ) );?></strong>
                                                                    </div><?php
                                                                }?>
                                                                <div>
                                                                    <?php esc_html_e( 'Attendee:', 'eventprime-event-calendar-management' );?>&nbsp;
                                                                    <strong><?php echo esc_html( $num );?></strong>&nbsp;
                                                                    <?php echo '('. esc_html__( 'of', 'eventprime-event-calendar-management' ). ' ' .esc_html( $tickets->qty ). ')';?>
                                                                </div>

                                                                <?php do_action( 'ep_event_booking_attendee_box_left_info', $tickets, $num );?>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="ep-box-col-9 ep-p-3">
                                                            <?php if( ! empty( $em_event_checkout_attendee_fields ) ) {
                                                                if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name'] ) && ( isset( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name'] ) || isset( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name'] ) || isset( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name'] ) ) ) {
                                                                    // checkout fields for name
                                                                    if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name'] ) ) {?>
                                                                        <div class="ep-mb-3">
                                                                            <label for="name" class="form-label ep-text-small">
                                                                                <?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );
                                                                                if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) ) {?>
                                                                                    <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                }?>
                                                                            </label>
                                                                            <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][name][first_name]" type="text" class="ep-form-control" 
                                                                                id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_first_name" 
                                                                                placeholder="<?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );?>"
                                                                                <?php if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) ) { echo 'required="required"'; }?>
                                                                            >
                                                                            <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_first_name_error"></div>
                                                                        </div><?php
                                                                    }
                                                                    if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name'] ) ) {?>
                                                                        <div class="ep-mb-3">
                                                                            <label for="name" class="form-label ep-text-small">
                                                                                <?php esc_html_e( 'Middle Name', 'eventprime-event-calendar-management' );
                                                                                if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) ) {?>
                                                                                    <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                }?>
                                                                            </label>
                                                                            <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][name][middle_name]" type="text" class="ep-form-control" 
                                                                                id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_middle_name" 
                                                                                placeholder="<?php esc_html_e( 'Middle Name', 'eventprime-event-calendar-management' );?>"
                                                                                <?php if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) ) { echo 'required="required"'; }?>
                                                                            >
                                                                            <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_middle_name_error"></div>
                                                                        </div><?php
                                                                    }
                                                                    if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name'] ) ) {?>
                                                                        <div class="ep-mb-3">
                                                                            <label for="name" class="form-label ep-text-small">
                                                                                <?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );
                                                                                if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) ) {?>
                                                                                    <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                }?>
                                                                            </label>
                                                                            <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][name][last_name]" type="text" class="ep-form-control" 
                                                                                id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_last_name" 
                                                                                placeholder="<?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );?>"
                                                                                <?php if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) ) { echo 'required="required"'; }?>
                                                                            >
                                                                            <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_last_name_error"></div>
                                                                        </div><?php
                                                                    }
                                                                }
                                                                // other checkout fields
                                                                if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] ) && count( $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] ) > 0 ) {
                                                                    $checkout_require_fields = array();
                                                                    $core_field_types = array_keys( ep_get_core_checkout_fields() );
                                                                    if( isset( $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] ) && ! empty( $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] ) ) {
                                                                        $checkout_require_fields = $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'];
                                                                    }
                                                                    foreach( $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] as $fields ) {
                                                                        if( in_array( $fields->type, $core_field_types ) ) {
                                                                            $input_name = ep_get_slug_from_string( $fields->label );?>
                                                                            <div class="ep-mb-3">
                                                                                <label for="name" class="form-label ep-text-small">
                                                                                    <?php echo esc_html( $fields->label );
                                                                                    if( in_array( $fields->id, $checkout_require_fields ) ) {?>
                                                                                        <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                                    }?>
                                                                                </label>
                                                                                <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][<?php echo esc_attr( $fields->id );?>][label]" type="hidden" value="<?php echo esc_attr( $fields->label );?>">
                                                                                <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][<?php echo esc_attr( $fields->id );?>][<?php echo esc_attr( $input_name );?>]" 
                                                                                    type="<?php echo esc_attr( $fields->type );?>" 
                                                                                    class="ep-form-control" 
                                                                                    id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_<?php echo esc_attr( $fields->id );?>_<?php echo esc_attr( $input_name );?>" 
                                                                                    placeholder="<?php echo esc_attr( $fields->label );?>"
                                                                                    <?php if( in_array( $fields->id, $checkout_require_fields ) ) { echo 'required="required"'; } ?>
                                                                                >
                                                                                <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_<?php echo esc_attr( $fields->id );?>_<?php echo esc_attr( $input_name );?>_error"></div>
                                                                            </div><?php
                                                                        } else{
                                                                            $checkout_field_data = array( 'fields' => $fields, 'tickets' => $tickets, 'checkout_require_fields' => $checkout_require_fields, 'num' => $num );
                                                                            do_action( 'ep_event_advanced_checkout_fields_section', $checkout_field_data );
                                                                        }
                                                                    }
                                                                }
                                                            } else{ // show default name field?>
                                                                <div class="ep-mb-3">
                                                                    <label for="name" class="form-label ep-text-small">
                                                                        <?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );
                                                                        if( ! empty( ep_get_global_settings( 'required_booking_attendee_name' ) ) ) {?>
                                                                            <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                                        }?>
                                                                    </label>
                                                                    <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][name][first_name]" type="text" class="ep-form-control" 
                                                                        id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_first_name" 
                                                                        placeholder="<?php esc_html_e( 'First Name', 'eventprime-event-calendar-management' );?>"
                                                                        <?php if( ! empty( ep_get_global_settings( 'required_booking_attendee_name' ) ) ) { echo 'required="required"'; }?>
                                                                    >
                                                                    <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_first_name_error"></div>
                                                                </div>
                                                                <!-- <div class="ep-mb-3">
                                                                    <label for="name" class="form-label ep-text-small">
                                                                        <?php esc_html_e( 'Middle Name', 'eventprime-event-calendar-management' );?>
                                                                    </label>
                                                                    <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][name][middle_name]" type="text" class="ep-form-control" 
                                                                        id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_middle_name" 
                                                                        placeholder="<?php esc_html_e( 'Middle Name', 'eventprime-event-calendar-management' );?>"
                                                                    >
                                                                    <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_middle_name_error"></div>
                                                                </div> -->
                                                                <div class="ep-mb-3">
                                                                    <label for="name" class="form-label ep-text-small">
                                                                        <?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );?>
                                                                    </label>
                                                                    <input name="ep_booking_attendee_fields[<?php echo esc_attr( $tickets->id );?>][<?php echo esc_attr( $num );?>][name][last_name]" type="text" class="ep-form-control" 
                                                                        id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_last_name" 
                                                                        placeholder="<?php esc_html_e( 'Last Name', 'eventprime-event-calendar-management' );?>"
                                                                    >
                                                                    <div class="ep-error-message" id="ep_booking_attendee_fields_<?php echo esc_attr( $tickets->id );?>_<?php echo esc_attr( $num );?>_name_last_name_error"></div>
                                                                </div><?php
                                                            }?>                  
                                                        </div>
                                                    </div>
                                                </div><?php
                                                $num++;$ticket_num++;
                                            }
                                        }
                                    }
                                    // checkout fixed fields
                                    if( ! empty( $em_event_checkout_fixed_fields ) ) {
                                        if( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_enabled'] ) ) {
                                            $term_option = $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'];
                                            $term_content = $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'];?>
                                            <div class="ep-event-booking-attendee-section ep-box-row ep-border ep-rounded ep-mb-4">
                                                <div class="ep-box-col-9 ep-p-3">
                                                    <input name="ep_booking_attendee_fixed_term_field" type="checkbox" id="ep_booking_attendee_fixed_term_field" required="required" value="">
                                                    <label for="ep_booking_attendee_fixed_term_field" class="form-label ep-text-small">
                                                        <?php echo esc_html( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_label'] );?>
                                                    </label>
                                                    <span>
                                                        <?php if( $term_option == 'content' ) {?>
                                                            <a href="javascript:void(0);" ep-modal-open="ep_checkout_attendee_terms_modal">
                                                                <?php esc_html_e( 'Terms & Condition', 'eventprime-event-calendar-management' );?>
                                                            </a>
                                                            <div class="ep-modal ep-modal-view" id="ep-booking-attendee-terms-modal" ep-modal="ep_checkout_attendee_terms_modal" style="display: none;">
                                                                <div class="ep-modal-overlay" ep-modal-close="ep_checkout_attendee_terms_modal"></div>
                                                                <div class="ep-modal-wrap ep-modal-xl">
                                                                    <div class="ep-modal-content">
                                                                        <div class="ep-modal-body"> 
                                                                            <div class="ep-box-row">
                                                                                <div class="ep-box-col-12 ep-py-3">
                                                                                    <?php echo wp_kses_post( $term_content );?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><?php
                                                        } else{
                                                            $term_page_url = ( $term_option == 'page' ) ? get_permalink( $term_content ) : $term_content ;?>
                                                            <a href="<?php echo esc_url( $term_page_url );?>" target="_blank">
                                                                <?php esc_html_e( 'Terms & Condition', 'eventprime-event-calendar-management' );?>
                                                            </a><?php
                                                        }?>
                                                    </span>
                                                    <div class="ep-error-message" id="ep_booking_attendee_fixed_term_field_error"></div>
                                                </div>
                                            </div><?php
                                        }
                                    }
                                    // checkout booking fields container
                                    $em_event_checkout_booking_fields = ( ! empty( $args->event->em_event_checkout_booking_fields ) ? $args->event->em_event_checkout_booking_fields : array() );
                                    if( ! empty( $em_event_checkout_booking_fields['em_event_booking_fields_data'] ) ) {
                                        $booking_require_fields = array();
                                        $core_field_types = array_keys( ep_get_core_checkout_fields() );
                                        if( isset( $em_event_checkout_booking_fields['em_event_booking_fields_data_required'] ) && ! empty( $em_event_checkout_booking_fields['em_event_booking_fields_data_required'] ) ) {
                                            $booking_require_fields = $em_event_checkout_booking_fields['em_event_booking_fields_data_required'];
                                        }
                                        foreach( $em_event_checkout_booking_fields['em_event_booking_fields_data'] as $fields ) {?>
                                            <div class="ep-event-booking-booking-section ep-box-row ep-border ep-rounded ep-mb-4"><?php
                                                if( in_array( $fields->type, $core_field_types ) ) {
                                                    $input_name = ep_get_slug_from_string( $fields->label );?>
                                                    <div class="ep-p-3">
                                                        <label for="name" class="form-label ep-text-small">
                                                            <?php echo esc_html( $fields->label );
                                                            if( in_array( $fields->id, $booking_require_fields ) ) {?>
                                                                <span class="ep-checkout-fields-required"><?php echo esc_html( '*' ); ?></span><?php
                                                            }?>
                                                        </label>
                                                        <input name="ep_booking_booking_fields[<?php echo esc_attr( $fields->id );?>][label]" type="hidden" value="<?php echo esc_attr( $fields->label );?>">
                                                        <input name="ep_booking_booking_fields[<?php echo esc_attr( $fields->id );?>][<?php echo esc_attr( $input_name );?>]" 
                                                            type="<?php echo esc_attr( $fields->type );?>" 
                                                            class="ep-form-control" 
                                                            id="ep_booking_booking_fields_<?php echo esc_attr( $fields->id );?>_<?php echo esc_attr( $input_name );?>" 
                                                            placeholder="<?php echo esc_attr( $fields->label );?>"
                                                            <?php if( in_array( $fields->id, $booking_require_fields ) ) { echo 'required="required"'; } ?>
                                                        >
                                                        <div class="ep-error-message" id="ep_booking_booking_fields_<?php echo esc_attr( $fields->id );?>_<?php echo esc_attr( $input_name );?>_error"></div>
                                                    </div><?php
                                                } else{
                                                    $checkout_field_data = array( 'fields' => $fields, 'tickets' => '', 'checkout_require_fields' => $booking_require_fields, 'num' => '', 'section' => 'booking' );
                                                    do_action( 'ep_event_advanced_checkout_fields_section', $checkout_field_data );
                                                }?>
                                            </div><?php
                                        }
                                    }
                                    do_action( 'ep_front_checkout_addresses_separation_view', $args );
                                    ?>
                                </div><?php
                            }?>
                            <!-- Attendees info section End -->
                        
                            <!-- Checkout Form -->
                            <div id="ep_event_booking_checkout_user_section" style="display: none;">
                                <?php if ( ! is_user_logged_in() ) {
                                    if( ! empty( ep_enabled_guest_booking() ) ) {
                                        do_action( 'ep_checkout_guest_booking_form', $args );
                                    } else{?>
                                        <h4 class="mb-3">
                                            <?php esc_html_e( 'Create Account', 'eventprime-event-calendar-management' );?>
                                        </h4>
                                        <div class="ep-text-dark ep-mb-3 ep-bg-success ep-bg-opacity-10 ep-p-3 ep-border-start ep-border-success ep-border-3 ep-text-small">
                                            <span class="material-icons-round ep-fs-4 ep-align-middle ep-text-success ep-mr-2">account_circle</span>
                                            <span class="ep-fw-bold">
                                                <?php esc_html_e( 'Already have an account?', 'eventprime-event-calendar-management' );?>
                                            </span>
                                            <span class="ep-text-success ep-cursor" id="ep_checkout_login_modal_id" ep-modal-open="ep_checkout_login_modal">
                                                <?php esc_html_e( 'Click here to login', 'eventprime-event-calendar-management' );?>
                                            </span>
                                        </div>
                                        <!-- Checkout registration form -->
                                        <div class="ep-box-row ep-g-3" id="ep_event_checkout_registration_form">
                                            <div class="ep-box-col-6">
                                                <label for="ep_event_checkout_rg_form_first_name" class="ep-form-label">
                                                    <?php echo esc_html( $args->account_form->fname_label );?>
                                                    <span class="text-muted">
                                                        <?php esc_html_e( '(Optional)', 'eventprime-event-calendar-management' );?>
                                                    </span>
                                                </label>
                                                <input type="text" name="ep_rg_field_first_name" class="ep-form-control" id="ep_event_checkout_rg_form_first_name" placeholder="<?php echo esc_attr( $args->account_form->fname_label );?>" value="">
                                                <div class="ep-error-message" id="ep_event_checkout_rg_form_first_name_error"></div>
                                            </div>

                                            <div class="ep-box-col-6">
                                                <label for="ep_event_checkout_rg_form_last_name" class="ep-form-label">
                                                    <?php echo esc_html( $args->account_form->lname_label );?>
                                                    <span class="text-muted">
                                                        <?php esc_html_e( '(Optional)', 'eventprime-event-calendar-management' );?>
                                                    </span>
                                                </label>
                                                <input type="text" name="ep_rg_field_last_name" class="ep-form-control" id="ep_event_checkout_rg_form_last_name" placeholder="<?php echo esc_attr( $args->account_form->lname_label );?>" value="">
                                                <div class="ep-error-message" id="ep_event_checkout_rg_form_last_name_error"></div>
                                            </div>

                                            <div class="ep-box-col-12">
                                                <label for="ep_event_checkout_rg_form_user_name" class="ep-form-label">
                                                    <?php echo esc_html( $args->account_form->username_label );?>
                                                </label>
                                                <div class="ep-input-group ep-has-validation">
                                                    <span class="ep-input-group-text">@</span>
                                                    <input type="text" name="ep_rg_field_user_name" class="ep-form-control" id="ep_event_checkout_rg_form_user_name" placeholder="<?php echo esc_attr( $args->account_form->username_label );?>" required="">
                                                    <div class="ep-error-message" id="ep_event_checkout_rg_form_user_name_error"></div>
                                                </div>
                                            </div>

                                            <div class="ep-box-col-12">
                                                <label for="ep_event_checkout_rg_form_email" class="ep-form-label">
                                                    <?php echo esc_html( $args->account_form->email_label );?></label>
                                                <input type="email" name="ep_rg_field_email" class="ep-form-control" id="ep_event_checkout_rg_form_email" placeholder="<?php echo esc_attr( $args->account_form->email_label );?>">
                                                <div class="ep-error-message" id="ep_event_checkout_rg_form_email_error"></div>
                                            </div>

                                            <div class="ep-box-col-12">
                                                <label for="ep_event_checkout_rg_form_password" class="ep-form-label">
                                                    <?php echo esc_html( $args->account_form->password_label );?>
                                                </label>
                                                <input type="password" name="ep_rg_field_password" class="ep-form-control" id="ep_event_checkout_rg_form_password" placeholder="<?php echo esc_attr( $args->account_form->password_label );?>">
                                                <div class="ep-error-message" id="ep_event_checkout_rg_form_password_error"></div>
                                            </div>
                                            <?php 
                                            if( ep_get_global_settings('checkout_reg_google_recaptcha') == 1 && !empty(ep_get_global_settings('google_recaptcha_site_key')) ){
                                            echo '<div class="ep-box-col-12">
                                                    <div class="g-recaptcha"  data-sitekey="'.ep_get_global_settings('google_recaptcha_site_key').'"></div>
                                                    <div class="ep-error-message" id="ep_event_checkout_rg_form_captcha_error"></div>    
                                            </div>'; 
                                            } ?>
                                        </div><?php
                                    }
                                } else{
                                    $current_user = wp_get_current_user();
                                    if( ! empty( $current_user->ID ) ) {?>
                                        <div class="ep-logged-user ep-py-3 ep-border ep-rounded" style="">
                                            <div class="ep-box-row">
                                                <div class="ep-box-col-12 ep-d-flex ep-align-items-center ">
                                                    <div class="ep-d-inline-flex ep-mx-3">
                                                        <img class="ep-rounded-circle" src="<?php echo esc_url( get_avatar_url( $current_user->ID ) ); ?>" style="height: 32px;">
                                                    </div>
                                                    <div class="ep-d-inline-flex ">
                                                        <span class="ep-mr-1"><?php esc_html_e( 'Logged in as', 'eventprime-event-calendar-management' ); ?></span>
                                                        <span class="ep-fw-bold"><?php echo esc_html( ep_get_current_user_profile_name() ); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><?php
                                    }
                                }?>
                                <?php do_action( 'ep_front_checkout_data_view', $args ); ?>
                            </div>

                            <div id="ep_event_booking_payment_section" style="display: none;">
                                <?php if ( is_user_logged_in() ) {?>
                                    <div class="ep-my-4 ep-border-bottom"></div><?php
                                }?>
                                <!-- <div class="ep-mb-3">
                                    <?php //esc_html_e( 'Please select your payment option below:', 'eventprime-event-calendar-management' );?>
                                </div> -->

                                <div class="ep-my-3 ep-fs-4">
                                    <?php esc_html_e( 'Select Payment Method', 'eventprime-event-calendar-management' );?>
                                </div>
                                <div class="ep-my-3">
                                    <?php if( empty( em_is_payment_gateway_enabled() ) ) {
                                        esc_html_e( 'Payment method is not enabled. Please contact with your site admin for this issue.', 'eventprime-event-calendar-management' );
                                    } else{
                                        // if total price is 0 then payment option will not load
                                        if( empty( $total_price ) ) {?>
                                            <input id="none_payment" name="payment_processor" value="<?php echo esc_html( 'none' );?>" type="hidden"><?php
                                        } else{?>
                                            <div class="ep-booking-payment-option-container ep-book-payment-gateways-radio-buttons ep-border-bottom ep-pb-2 ep-mb-4">
                                                <div class="ep-box-row"> <?php do_action( 'ep_front_checkout_payment_processors', $args );?></div>
                                            </div>
                                            <div class="ep-booking-payment-option-button-container ep-d-flex ep-justify-content-end"><?php
                                                do_action( 'ep_front_checkout_payment_processors_button', $args );?>
                                            </div><?php
                                        }
                                    }?>
                                </div>
                            </div>
                            <!-- Checkout Form End -->
                        </div>
                    </div>
                </form>
            </div>
            <!-- Hook after checkout form ( paypal form ) -->
            <?php do_action( 'ep_front_checkout_form_after', $args ); ?>
        </div>
    </div><?php
} else{?>
    <div class="ep-alert ep-alert-warning ep-mt-3 ep-fs-6">
        <?php esc_html_e( 'No event found for booking!', 'eventprime-event-calendar-management' );?>
    </div><?php
}?>

<div class="ep-modal ep-modal-view" id="ep-event-booking-login-modal" ep-modal="ep_checkout_login_modal" style="display: none;">
    <div class="ep-modal-overlay" ep-modal-close="ep_checkout_login_modal"></div>
    <div class="ep-modal-wrap ep-modal-lg">
        <div class="ep-modal-content">
            <div class="ep-modal-body"> 

             <?php echo do_shortcode( '[em_login show_login_form=1]' );?>

            </div>
        </div>
    </div>
</div>