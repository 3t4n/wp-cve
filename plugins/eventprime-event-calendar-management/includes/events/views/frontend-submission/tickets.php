<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/tickets.php
 *
 */
?>
<?php if( isset( $args->fes_event_booking ) && ! empty( $args->fes_event_booking ) ){?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
        <div class="ep-box-wrap">
            <div class="ep-box-row">
                <div class="ep-box-col-12">
                <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3"><?php esc_html_e('Event Tickets', 'eventprime-event-calendar-management');?></div>
                </div>
            </div>
            <div class="ep-box-row ep-border ep-bg-light ep-rounded ep-p-3">
                <div class="ep-box-col-12 ep-mb-3" id="ep-how-to-book">
                    <strong><?php esc_html_e('How do you wish to handle ticket bookings for this event?', 'eventprime-event-calendar-management'); ?></strong>
                </div>
                <div class="ep-box-col-12">
                    <div class="ep-form-check ep-form-check-inline ep-mb-3">
                        <input class="ep-form-check-input" type="radio" name="em_enable_booking" id="ep-bookings-off" value="bookings_off" checked="checked" >
                        <label class="ep-form-check-label" for="ep-bookings-off">
                            <?php esc_html_e('Turn bookings off', 'eventprime-event-calendar-management'); ?>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Users will not be able to book for this event. Useful when the event is for informative purposes.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </label>
                    </div>
                    <div class="ep-form-check ep-form-check-inline ep-mb-3">
                        <input class="ep-form-check-input" type="radio" name="em_enable_booking" id="ep-bookings-on" value="bookings_on" <?php echo isset($args->event->em_enable_booking) && $args->event->em_enable_booking == 'bookings_on' ? 'checked="checked"' : '';?>>
                        <label class="ep-form-check-label" for="ep-bookings-on">
                            <?php esc_html_e('Turn bookings on', 'eventprime-event-calendar-management'); ?>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Users will be able to book tickets for this event on your website using a checkout process.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </label>
                    </div>
                    <?php if( isset( $args->fes_event_link ) && ! empty( $args->fes_event_link ) ){?>
                        <div class="ep-form-check ep-form-check-inline ep-mb-3">
                            <input class="ep-form-check-input" type="radio" name="em_enable_booking" id="ep-external-bookings" value="external_bookings" <?php echo isset($args->event->em_enable_booking) && $args->event->em_enable_booking == 'external_bookings' ? 'checked="checked"' : '';?>>
                            <label class="ep-form-check-label" for="ep-external-bookings">
                                <?php esc_html_e('Take users to an external URL', 'eventprime-event-calendar-management'); ?>
                            </label>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Users will be able to book tickets for this event but will be redirected to a third-party site for checkout.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </div><?php
                    }?>
                </div>
            </div> 
        </div>
    </div><?php 
}?>

<?php if( isset( $args->fes_event_link ) && ! empty( $args->fes_event_link ) ){?>
    <div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1" id="ep-bookings-url" style="<?php if(isset($args->event) && $args->event->em_enable_booking == 'external_bookings') {echo 'display:block;';}else{ echo 'display: none;';} ?>">
        <div class="ep-box-row ep-p-3">
            <div class="ep-box-col-12">
                <label class="ep-form-label">
                    <?php esc_html_e( 'URL', 'eventprime-event-calendar-management');?> <em><?php esc_html_e( '(Required)', 'eventprime-event-calendar-management' );?></em>
                </label>
                <input type="url" class="ep-form-control ep-box-w-50" name="em_custom_link" id="ep_event_custom_link" value="<?php if(isset($args->event)){ echo esc_attr( $args->event->em_custom_link ); }?>">
                <div class="ep-text-muted ep-text-small">
                    <?php esc_html_e( 'The external URL where you wish to take the user for completing the booking.', 'eventprime-event-calendar-management' );?>
                </div>
                <div class="ep-error-message" id="em_custom_link_error_message"></div>
            </div>    
            <div class="ep-box-col-12 ep-mt-3">
                <div class="ep-form-check ep-form-check-inline ">
                    <input class="ep-form-check-input" name="em_custom_link_new_browser" type="checkbox" value="1" id="flex-check-default" <?php if( isset($args->event) && $args->event->em_custom_link_new_browser == 1 ) { echo 'checked="checked"';} ?> >
                    <label class="ep-form-check-label" for="flex-check-default">
                        <?php esc_html_e( 'Open in a new browser tab', 'eventprime-event-calendar-management' );?>
                    </label>
                </div>
            </div>
        </div>
    </div><?php 
}?>

<!-- Booking Options Buttons Area --> 
<div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1" id="ep-bookings-options" style="display: none;">
    <div class="ep-box-row ep-p-3 ep-border ep-bg-light ep-rounded ep-m-3">
        <div class="ep-box-col-4">
            <div class="ep-meta-box-section">
                <div class="ep-meta-box-title">
                    <?php esc_html_e('One-Time Event Fee', 'eventprime-event-calendar-management'); ?>
                </div>
                <div class="ep-meta-box-data">
                    <div class="ep-event-booking-one-time-fee">
                        <input type="number" min="0" name="em_fixed_event_price" id="em_fixed_event_price" placeholder="<?php esc_html_e('One-Time Event Fee', 'eventprime-event-calendar-management');?>" value="<?php //echo esc_attr( $em_fixed_event_price ); ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="ep-box-col-4">
            <div class="ep-meta-box-section ep-form-check ep-mt-4">
                <div class="ep-meta-box-data">
                    <label class="ep-event-booking-hide-status">
                        <input type="checkbox" name="em_hide_booking_status" id="em_hide_booking_status" value="1" <?php //if( $em_hide_booking_status == 1 ) { echo 'checked="checked"'; } ?> >
                        <?php esc_html_e('Hide Booking Status', 'eventprime-event-calendar-management'); ?>
                    </label>
                </div>
            </div>
        </div>
        <!-- Allow booking cancellation option -->
        <div class="ep-box-col-4">
            <div class="ep-meta-box-section ep-form-check ep-mt-4">
                <div class="ep-meta-box-data">
                    <label class="ep-event-booking-hide-status">
                        <input type="checkbox" name="em_allow_cancellations" id="em_allow_cancellations" value="1" <?php //if( $em_allow_cancellations == 1 ) { echo 'checked="checked"'; } ?> >
                        <?php esc_html_e( 'Allow Cancellations', 'eventprime-event-calendar-management' ); ?>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="ep-box-row ep-p-3">
        <div class="ep-box-col-12 ep-d-flex ep-content-center">
            <button type="button" class="ep-btn ep-btn-dark ep-button-large ep-m-3 ep-open-modal" ep-modal-open="ep_fes_event_category_modal" id="ep_event_open_category_modal" title="<?php esc_html_e( 'Add Tickets Category', 'eventprime-event-calendar-management' );?>">
                <?php esc_html_e( 'Add Tickets Category', 'eventprime-event-calendar-management' );?>
            </button>        
            <button type="button" class="ep-btn ep-btn-dark ep-button-large ep-m-3 ep-open-modal" ep-modal-open="ep_fes_event_ticket_modal" id="ep_event_open_ticket_modal" title="<?php esc_html_e( 'Add Tickets', 'eventprime-event-calendar-management' );?>">
                <?php esc_html_e( 'Add Ticket Type', 'eventprime-event-calendar-management' );?>
            </button>
        </div>
    </div>
    <!--Existing Tickets-->
    <div id="ep_existing_tickets_category_list ep-box-col-12">
        <input type="hidden" name="em_ticket_category_data" id="ep_ticket_category_data" value="" />
        <input type="hidden" name="em_ticket_category_delete_ids" id="ep_ticket_category_delete_ids" value="" />
        <div class="ep-box-row ep-p-3" id="ep_existing_tickets_list">
            
        </div>
    </div>

    <div id="ep_existing_individual_tickets_list">
        <input type="hidden" name="em_ticket_individual_data" id="ep_ticket_individual_data" value="" />
        <input type="hidden" name="em_ticket_individual_delete_ids" id="ep_ticket_individual_delete_ids" value="" />
    </div>
    <!--Existing Tickets Ends-->
</div>
<!-- Category Modal -->
<div class="ep-modal ep-modal-view" id="ep-fes-event-category-modal" ep-modal="ep_fes_event_category_modal" style="display: none;">
    <div class="ep-modal-overlay" ep-modal-close="ep_fes_event_category_modal"></div>
    <div class="ep-modal-wrap ep-modal-xl">
        <div class="ep-modal-content">
            <div class="ep-modal-body">
                <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                    <h3 class="ep-modal-title ep-px-3 ">
                        <?php esc_html_e('Add Tickets Category', 'eventprime-event-calendar-management'); ?>
                    </h3>
                    <a href="#" class="ep-modal-close close-popup" ep-modal-close="ep_fes_event_category_modal" data-id="ep-fes-event-category-modal">&times;</a>
                </div>
                <div class="ep-modal-content-wrap">
                    <div class="ep-box-wrap">
                        <div class="ep-box-row ep-p-3 ep-box-w-75">
                            <div class="ep-box-col-12">
                                <label class="ep-form-label">
                                    <?php esc_html_e('Tickets Category', 'eventprime-event-calendar-management'); ?>
                                </label>
                                <input type="text" class="ep-form-control" name="em_ticket_category_name" id="ep_ticket_category_name">
                                <div class="ep-text-muted ep-text-small">
                                    <?php esc_html_e('Category name will be visible to users while selecting tickets.', 'eventprime-event-calendar-management'); ?>
                                </div>
                                <div id="ep_ticket_category_name_error" class="ep-error-message"></div>
                            </div> 

                            <div class="ep-box-col-12 ep-mt-3">
                                <label class="ep-form-label">
                                    <?php esc_html_e('Total Quantity/Inventory', 'eventprime-event-calendar-management'); ?>
                                </label>
                                <input type="number" class="ep-form-control" name="em_ticket_category_capacity" id="ep_ticket_category_capacity">
                                <div class="ep-text-muted ep-text-small">
                                    <?php esc_html_e('Combined capacity or inventory of the tickets you wish to include in this tickets category should not exceed this number.', 'eventprime-event-calendar-management'); ?>
                                </div>
                                <div id="ep_ticket_category_capacity_error" class="ep-error-message"></div>
                            </div> 
                        </div>
                    </div>
                    
                    <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right">
                        <button type="button" class="ep-btn ep-btn-dark ep-mr-3 ep-modal-close close-popup" ep-modal-close="ep_fes_event_category_modal" data-id="ep-ticket-category-modal"><?php esc_html_e('Close', 'eventprime-event-calendar-management'); ?></button>
                        <button type="button" class="ep-btn ep-btn-dark ep-button-primary button-large" id="ep_save_ticket_category"><?php esc_html_e('Add', 'eventprime-event-calendar-management'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Modal -->
<div class="ep-modal ep-modal-view" id="ep-fes-event-ticket-modal" ep-modal="ep_fes_event_ticket_modal" style="display: none;">
    <input type="hidden" name="em_ticket_category_id" value="" />
    <input type="hidden" name="em_ticket_id" value="" />
    <input type="hidden" name="em_ticket_parent_div_id" value="" />
    <div class="ep-modal-overlay" ep-modal-close="ep_fes_event_ticket_modal"></div>
    <div class="ep-modal-wrap ep-modal-xl">
        <div class="ep-modal-content">
            <div class="ep-modal-body">    
                <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                    <h3 class="ep-modal-title ep-px-3"><?php esc_html_e( 'Add New Ticket Type', 'eventprime-event-calendar-management' );?></h3>
                    <a href="#" class="ep-modal-close close-popup" ep-modal-close="ep_fes_event_ticket_modal" data-id="ep-fes-event-ticket-modal">&times;</a>
                </div>
                <div class="ep-modal-content-wrap ep-box-wrap">
                    <div class="ep-box-row ep-p-3 ep-box-w-75">
                        <div class="ep-box-col-12">
                            <label class="ep-form-label">
                                <?php esc_html_e( 'Name', 'eventprime-event-calendar-management' );?>
                            </label>
                            <input type="text" class="ep-form-control" name="name" id="ep_event_ticke_name">
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Ticket names are visible to the user on the frontend.', 'eventprime-event-calendar-management'); ?>
                            </div>
                            <div class="ep-error-message" id="ep_event_ticket_name_error"></div>
                        </div> 

                        <div class="ep-box-col-12 ep-mt-3">
                            <label class="ep-form-label">
                                <?php esc_html_e( 'Description', 'eventprime-event-calendar-management' );?>
                            </label>
                            <textarea class="ep-form-control" name="description" id="ep_event_ticke_description"></textarea>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Ticket description are visible to the user on the frontend during ticket selection.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </div>               

                        <div class="ep-box-col-6 ep-mt-3">
                            <label class="ep-form-label">
                                <?php esc_html_e( 'Quantity/ Inventory', 'eventprime-event-calendar-management' );?>
                            </label>
                            <input type="number" class="ep-form-control" min="0" name="capacity" id="ep_event_ticket_qty">
                            <div class="ep-error-message" id="ep_event_ticket_qty_error"></div>
                            <span id="ep_ticket_remaining_capacity" data-max_ticket_label="<?php esc_html_e( 'Remaining Seats', 'eventprime-event-calendar-management' );?>"></span>
                        </div>                

                        <div class="ep-box-col-6 ep-mt-3">
                            <label class="ep-form-label">
                                <?php esc_html_e( 'Price ( per ticket )', 'eventprime-event-calendar-management' );?>
                            </label>
                            <input type="number" class="ep-form-control" name="price" id="ep_event_ticket_price" min="0.00" step="0.01">
                        </div>                

                        <div class="ep-box-col-12 ep-mt-3">
                            <button type="button" class="ep-btn ep-btn-dark ep-button-large" id="add_more_additional_ticket_fee"><?php esc_html_e( 'Add Additional Fee', 'eventprime-event-calendar-management' );?></button>
                        </div>

                        <div class="ep-additional-ticket-fee-wrapper ep-box-w-100" id="ep_additional_ticket_fee_wrapper"></div>

                        <div class="ep-box-col-12 ep-mt-2">
                            <div class="ep-form-check">
                                <input class="ep-form-check-input" type="checkbox" name="show_remaining_tickets" value="1" id="ep_show_remaining_tickets">
                                <label class="ep-form-check-label" for="ep_show_remaining_tickets">
                                    <?php esc_html_e( 'Show tickets remaining to the users', 'eventprime-event-calendar-management' );?>
                                </label>
                            </div>
                        </div>

                        <div class="ep-box-col-6 ep-mt-3">
                            <div class="ep-box-row">
                                <div class="ep-box-col-12">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Tickets Available From', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <select class="ep-form-control ep_ticket_start_booking_type" id="ep_ticket_start_booking_type" name="em_ticket_start_booking_type">
                                        <option value="custom_date"><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                                        <option value="event_date"><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                                        <option value="relative_date"><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                                    </select>
                                </div>
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_start_booking_options ep_ticket_start_booking_custom_date">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Choose Date', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <input type="text" class="ep-form-control ep_metabox_custom_date_picker" name="em_ticket_start_booking_date" id="ep_ticket_start_booking_date" data-start="today" data-end="event_end">
                                </div>
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_start_booking_options ep_ticket_start_booking_custom_date">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Choose Time', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <input type="text" class="ep-form-control epTimePicker" name="em_ticket_start_booking_time" id="ep_ticket_start_booking_time">
                                </div> 
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_start_booking_options ep_ticket_start_booking_relative_date" style="display:none;">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Enter Days', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <input type="number" class="ep-form-control" name="em_ticket_start_booking_days" id="ep_ticket_start_booking_days" min="0">
                                </div>
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_start_booking_options ep_ticket_start_booking_relative_date" style="display:none;">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Days Option', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <select class="ep-form-control" name="em_ticket_start_booking_days_option" id="ep_ticket_start_booking_days_option">
                                        <option value="before"><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                        <option value="after"><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                    </select>
                                </div>
                                <div class="ep-box-col-12 ep-mt-3 ep_ticket_start_booking_options ep_ticket_start_booking_event_date ep_ticket_start_booking_relative_date" style="display:none;">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Event Option', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <select class="ep-form-control" name="em_ticket_start_booking_event_option" id="ep_ticket_start_booking_event_option">
                                        <option value="event_start">
                                            <?php esc_html_e( 'Event Start', 'eventprime-event-calendar-management' );?>
                                        </option>
                                        <option value="event_ends">
                                            <?php esc_html_e( 'Event Ends', 'eventprime-event-calendar-management' );?>
                                        </option>
                                    </select>
                                    <div class="ep-error-message" id="em_ticket_start_booking_event_option_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-col-6 ep-mt-3">
                            <div class="ep-box-row">
                                <div class="ep-box-col-12">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Tickets Available Till', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <select class="ep-form-control ep_ticket_ends_booking_type" id="ep_ticket_ends_booking_type" name="em_ticket_ends_booking_type">
                                        <option value="custom_date"><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                                        <option value="event_date"><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                                        <option value="relative_date"><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                                    </select>
                                </div>
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_ends_booking_options ep_ticket_ends_booking_custom_date">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Choose Date', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <input type="text" class="ep-form-control ep_metabox_custom_date_picker" name="em_ticket_ends_booking_date" id="ep_ticket_ends_booking_date" data-start="today" data-end="event_end">
                                </div>
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_ends_booking_options ep_ticket_ends_booking_custom_date">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Choose Time', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <input type="text" class="ep-form-control epTimePicker" name="em_ticket_ends_booking_time" id="ep_ticket_ends_booking_time">
                                </div> 
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_ends_booking_options ep_ticket_ends_booking_relative_date" style="display:none;">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Enter Days', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <input type="number" class="ep-form-control" name="em_ticket_ends_booking_days" id="ep_ticket_ends_booking_days" min="0">
                                </div>
                                <div class="ep-box-col-6 ep-mt-3 ep_ticket_ends_booking_options ep_ticket_ends_booking_relative_date" style="display:none;">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Days Option', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <select class="ep-form-control" name="em_ticket_ends_booking_days_option" id="ep_ticket_ends_booking_days_option">
                                        <option value="before"><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                        <option value="after"><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                    </select>
                                </div>
                                <div class="ep-box-col-12 ep-mt-3 ep_ticket_ends_booking_options ep_ticket_ends_booking_event_date ep_ticket_ends_booking_relative_date" style="display:none;">
                                    <label class="ep-form-label">
                                        <?php esc_html_e( 'Event Option', 'eventprime-event-calendar-management' );?>
                                    </label>
                                    <select class="ep-form-control" name="em_ticket_ends_booking_event_option" id="ep_ticket_ends_booking_event_option">
                                        <option value="event_start">
                                            <?php esc_html_e( 'Event Start', 'eventprime-event-calendar-management' );?>
                                        </option>
                                        <option value="event_ends">
                                            <?php esc_html_e( 'Event Ends', 'eventprime-event-calendar-management' );?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="ep-box-col-12 ep-mt-3">
                            <div class="ep-form-check">
                                <input class="ep-form-check-input" type="checkbox" name="show_ticket_booking_dates" value="1" id="ep_show_ticket_booking_dates">
                                <label class="ep-form-check-label" for="ep_show_ticket_booking_dates">
                                    <?php esc_html_e( 'Show tickets availability dates on the frontend', 'eventprime-event-calendar-management');?>
                                </label>
                            </div>
                        </div>

                        <div class="ep-box-col-6 ep-mt-3">
                            <label class="ep-form-check-label" for="ep_min_ticket_no">
                                <?php esc_html_e( 'Minimum Tickets Per Order', 'eventprime-event-calendar-management');?>
                            </label>
                            <input type="number" id="ep_min_ticket_no" class="ep-form-control" min="0" name="min_ticket_no">
                            <div class="ep-error-message" id="ep_event_ticket_min_ticket_error"></div>
                        </div> 

                        <div class="ep-box-col-6 ep-mt-3">
                            <label class="ep-form-check-label" for="ep_max_ticket_no">
                                <?php esc_html_e( 'Maximum Tickets Per Order', 'eventprime-event-calendar-management');?>
                            </label>
                            <input type="number" id="ep_max_ticket_no" class="ep-form-control" min="0" name="max_ticket_no">
                            <div class="ep-error-message" id="ep_event_ticket_max_ticket_error"></div>
                        </div>
                    </div>
                    <!-- Modal Wrap  End --> 
                    <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right">
                        <button type="button" class="ep-btn ep-btn-dark ep-mr-3 ep-modal-close close-popup" ep-modal-close="ep_fes_event_ticket_modal" data-id="ep_event_ticket_tier_modal"><?php esc_html_e( 'Close', 'eventprime-event-calendar-management');?></button>
                        <button type="button" class="ep-btn ep-btn-dark ep-button-large" id="ep_save_ticket_tier"><?php esc_html_e( 'Save changes', 'eventprime-event-calendar-management');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>