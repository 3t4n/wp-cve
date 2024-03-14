<?php
/**
 * Event tickets panel html
 */
defined( 'ABSPATH' ) || exit;
$em_enable_booking          = get_post_meta( $post->ID, 'em_enable_booking', true );
$em_custom_link             = get_post_meta( $post->ID, 'em_custom_link', true );
$em_custom_link_new_browser = get_post_meta( $post->ID, 'em_custom_link_new_browser', true );
$em_fixed_event_price       = get_post_meta( $post->ID, 'em_fixed_event_price', true );
$em_hide_booking_status     = get_post_meta( $post->ID, 'em_hide_booking_status', true );
$em_allow_cancellations     = get_post_meta( $post->ID, 'em_allow_cancellations', true );
$em_allow_edit_booking      = get_post_meta( $post->ID, 'em_allow_edit_booking', true );
$em_edit_booking_date_data  = get_post_meta( $post->ID, 'em_edit_booking_date_data', true );
$extensions = EP()->extensions;
?>
<div id="ep_event_booking_data" class="panel ep_event_options_panel">
    <div class="ep-box-wrap">
        <?php if( check_event_has_expired( $single_event_data ) ) {?>
            <div class="ep-box-row ep-p-3">
                <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                    <strong><?php esc_html_e( 'This event has ended.', 'eventprime-event-calendar-management' ); ?></strong>
                </div>
            </div><?php
        } else{?>
            <div class="ep-box-row ep-p-3 ep-border ep-bg-light ep-rounded ep-m-3">
                <div class="ep-box-col-12 ep-mb-3" id="ep-how-to-book">
                    <strong><?php esc_html_e('How do you wish to handle ticket bookings for this event?', 'eventprime-event-calendar-management'); ?></strong>
                </div>
                <div class="ep-box-col-12">
                    <div class="ep-form-check ep-form-check-inline ep-mb-3">
                        <input class="ep-form-check-input" type="radio" name="em_enable_booking" id="ep-bookings-off" value="bookings_off" <?php if( empty( esc_attr( $em_enable_booking ) ) || 'bookings_off' == esc_attr( $em_enable_booking ) ) { echo 'checked="checked"'; }?> >
                        <label class="ep-form-check-label" for="ep-bookings-off">
                            <?php esc_html_e('Turn bookings off', 'eventprime-event-calendar-management'); ?>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Useful for event calendars and listings where bookings are not required.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="ep-box-col-12">
                    <div class="ep-form-check ep-form-check-inline ep-mb-3">
                        <input class="ep-form-check-input" type="radio" name="em_enable_booking" id="ep-bookings-on" value="bookings_on" <?php if( 'bookings_on' == esc_attr( $em_enable_booking ) ) { echo 'checked="checked"'; }?> >
                        <label class="ep-form-check-label" for="ep-bookings-on">
                            <?php esc_html_e('Turn bookings on', 'eventprime-event-calendar-management'); ?>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Users will be able to buy and manage tickets for this event on your website. Also use for free events.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="ep-box-col-12">
                    <div class="ep-form-check ep-form-check-inline ep-mb-3">
                        <input class="ep-form-check-input" type="radio" name="em_enable_booking" id="ep-external-bookings" value="external_bookings" <?php if( 'external_bookings' == esc_attr( $em_enable_booking ) ) { echo 'checked="checked"'; }?> >
                        <label class="ep-form-check-label" for="ep-external-bookings">
                            <?php esc_html_e('Third-party bookings', 'eventprime-event-calendar-management'); ?>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Users will be redirected to a third-party ticket booking website defined by you.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </label>
                    </div>
                </div>
            </div>    
            
            <!---External Bookings-->
            <div class="ep-box-row ep-p-3" id="ep-bookings-url">
                <div class="ep-box-col-12">
                    <label class="ep-form-label">
                        <?php esc_html_e( 'URL', 'eventprime-event-calendar-management');?> <em><?php esc_html_e( '(Required)', 'eventprime-event-calendar-management' );?></em>
                    </label>
                    <input type="url" class="ep-form-control ep-box-w-50" name="em_custom_link" id="ep_event_custom_link" value="<?php echo esc_attr( $em_custom_link );?>">
                    <div class="ep-text-muted ep-text-small">
                        <?php esc_html_e( 'Third-party URL where users can buy tickets for this event.', 'eventprime-event-calendar-management' );?>
                    </div>
                    <div class="ep-error-message" id="em_custom_link_error_message"></div>
                </div>    
                <div class="ep-box-col-12 ep-mt-3">
                    <div class="ep-form-check ep-form-check-inline ">
                        <input class="ep-form-check-input" name="em_custom_link_new_browser" type="checkbox" value="1" id="flex-check-default" <?php if( $em_custom_link_new_browser == 1 ) { echo 'checked="checked"';} ?> >
                        <label class="ep-form-check-label" for="flex-check-default">
                            <?php esc_html_e( 'Open in a new browser tab', 'eventprime-event-calendar-management' );?>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Booking Options Buttons Area --> 
            <div id="ep-bookings-options">
                <?php do_action( 'ep_event_admin_ticket_booking_options', $post->ID );?>

                <!-- Edit booking options -->
                <div class="ep-box-row ep-p-3 ep-border ep-bg-light ep-rounded ep-m-3" id="ep_edit_booking_options">
                    <div class="ep-box-col-12">
                        <div class="ep-meta-box-section">
                            <div class="ep-form-check ep-form-check-inline ">
                                <label class="ep-form-check-label" for="ep_allow_edit_booking">
                                    <input class="ep-form-check-input" name="em_allow_edit_booking" type="checkbox" value="1" id="ep_allow_edit_booking" <?php if( !empty( $em_allow_edit_booking ) ) { echo esc_attr( 'checked' ); }?> >
                                    <?php esc_html_e( 'Allow Booking Modification', 'eventprime-event-calendar-management' );?>
                                </label>
                            </div>
                            <div class="ep-text-muted ep-text-small"><?php esc_html_e( 'Check this to allow users to make modifications to their bookings from user account area. Users can change attendee details or remove attendees from their bookings.', 'eventprime-event-calendar-management' );?></div>
                        </div>
                    </div>
                    <div class="ep-box-col-12" id="ep_edit_booking_date_options" <?php if( empty( $em_allow_edit_booking ) ) { echo esc_attr( 'style=display:none;' ); }?>>
                        <div class="ep-meta-box-section">
                            <div class="ep-box-col-6 ep-mt-3">
                                <div class="ep-box-row">
                                    <div class="ep-box-col-12">
                                        <label class="ep-form-check-label">
                                            <?php esc_html_e( 'Allow Until', 'eventprime-event-calendar-management' );?>
                                        </label>
                                        <select class="ep-form-control ep_edit_booking_date_type" id="ep_edit_booking_date_type" name="em_edit_booking_date_type">
                                            <option value="custom_date" <?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) && $em_edit_booking_date_data['em_edit_booking_date_type'] == 'custom_date' ? 'selected' : '' );?>><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                                            <option value="event_date" <?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) && $em_edit_booking_date_data['em_edit_booking_date_type'] == 'event_date' ? 'selected' : '' );?>><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                                            <option value="relative_date" <?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) && $em_edit_booking_date_data['em_edit_booking_date_type'] == 'relative_date' ? 'selected' : '' );?>><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                                        </select>
                                    </div>
                                    <div class="ep-box-col-6 ep-mt-3 ep_edit_booking_date_type_options ep_edit_booking_date_type_custom_date" style="<?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) && $em_edit_booking_date_data['em_edit_booking_date_type'] != 'custom_date' ? 'display: none;' : '' );?>">
                                        <label class="ep-form-label">
                                            <?php esc_html_e( 'Choose Date', 'eventprime-event-calendar-management' );?>
                                        </label>
                                        <input type="text" class="ep-form-control ep_metabox_custom_date_picker" name="em_edit_booking_date_date" id="ep_edit_booking_date_date" data-end="event_end" value="<?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_date'] ) ? esc_attr( ep_timestamp_to_date( $em_edit_booking_date_data['em_edit_booking_date_date'] ) ) : '' );?>">
                                    </div>
                                    <div class="ep-box-col-6 ep-mt-3 ep_edit_booking_date_type_options ep_edit_booking_date_type_custom_date" style="<?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) && $em_edit_booking_date_data['em_edit_booking_date_type'] != 'custom_date' ? 'display: none;' : '' );?>">
                                        <label class="ep-form-label">
                                            <?php esc_html_e( 'Choose Time', 'eventprime-event-calendar-management' );?>
                                        </label>
                                        <input type="text" class="ep-form-control epTimePicker" name="em_edit_booking_date_time" id="ep_edit_booking_date_time" value="<?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_time'] ) ? esc_attr( $em_edit_booking_date_data['em_edit_booking_date_time'] ) : '' );?>">
                                    </div> 
                                    <div class="ep-box-col-6 ep-mt-3 ep_edit_booking_date_type_options ep_edit_booking_date_type_relative_date" style="<?php echo ( empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) || $em_edit_booking_date_data['em_edit_booking_date_type'] != 'relative_date' ? 'display: none;' : '' );?>">
                                        <label class="ep-form-label">
                                            <?php esc_html_e( 'Enter Days', 'eventprime-event-calendar-management' );?>
                                        </label>
                                        <input type="number" class="ep-form-control" name="em_edit_booking_date_days" id="ep_edit_booking_date_days" min="0" value="<?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_days'] ) ? absint( $em_edit_booking_date_data['em_edit_booking_date_days'] ) : '' );?>">
                                    </div>
                                    <div class="ep-box-col-6 ep-mt-3 ep_edit_booking_date_type_options ep_edit_booking_date_type_relative_date" style="<?php echo ( empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) || $em_edit_booking_date_data['em_edit_booking_date_type'] != 'relative_date' ? 'display: none;' : '' );?>">
                                        <label class="ep-form-label">
                                            <?php esc_html_e( 'Days Option', 'eventprime-event-calendar-management' );?>
                                        </label>
                                        <select class="ep-form-control" name="em_edit_booking_date_days_option" id="ep_edit_booking_date_days_option">
                                            <option value="before" <?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_days_option'] ) && $em_edit_booking_date_data['em_edit_booking_date_days_option'] == 'before' ? 'selected' : '' );?>><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                            <option value="after" <?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_days_option'] ) && $em_edit_booking_date_data['em_edit_booking_date_days_option'] == 'after' ? 'selected' : '' );?>><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                        </select>
                                    </div>
                                    <div class="ep-box-col-12 ep-mt-3 ep_edit_booking_date_type_options ep_edit_booking_date_type_event_date ep_edit_booking_date_type_relative_date" style="<?php echo ( empty( $em_edit_booking_date_data['em_edit_booking_date_type'] ) || $em_edit_booking_date_data['em_edit_booking_date_type'] == 'custom_date' ? 'display: none;' : '' );?>">
                                        <label class="ep-form-label">
                                            <?php esc_html_e( 'Event Option', 'eventprime-event-calendar-management' );?>
                                        </label>
                                        <select class="ep-form-control" name="em_edit_booking_date_event_option" id="ep_edit_booking_date_event_option">
                                            <?php $existing_cat_data = self::get_ticket_booking_event_date_options( $post->ID );
                                            if( ! empty( $existing_cat_data ) ) {
                                                foreach( $existing_cat_data as $key => $option ) {?>
                                                    <option value="<?php echo esc_attr( $key );?>" <?php echo ( ! empty( $em_edit_booking_date_data['em_edit_booking_date_event_option'] ) && $em_edit_booking_date_data['em_edit_booking_date_event_option'] == $key ? 'selected' : '' );?>><?php echo esc_html( $option );?></option><?php
                                                }
                                            }?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="ep-text-muted ep-text-small ep-mt-3"><?php esc_html_e( 'Choose until when users will be allowed to edit their bookings. After this date, no editing will be allowed.', 'eventprime-event-calendar-management' );?></div>
                        </div>
                    </div>
                </div>

                <div class="ep-box-row ep-p-3 ep-border ep-bg-light ep-rounded ep-m-3">
                    <div class="ep-box-col-12">
                        <div class="ep-meta-box-section">
                            <div class="ep-meta-box-title">
                                <?php esc_html_e('One-Time Event Fee', 'eventprime-event-calendar-management'); ?>
                            </div>
                            <div class="ep-meta-box-data">
                                <div class="ep-event-booking-one-time-fee">
                                    <input type="number" min="0" name="em_fixed_event_price" id="em_fixed_event_price" placeholder="<?php esc_html_e('0', 'eventprime-event-calendar-management');?>" value="<?php echo esc_attr( $em_fixed_event_price ); ?>">
                                </div>
                            </div>
                            <div class="ep-text-muted ep-text-small">
                                <?php esc_html_e('Fixed fee (per booking) for this event which is added to total ticket price during checkout.', 'eventprime-event-calendar-management'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ep-box-row ep-p-3 ep-border ep-bg-light ep-rounded ep-m-3">
                    <div class="ep-box-col-6">
                        <div class="ep-meta-box-section ep-form-check ep-mt-2">
                            <div class="ep-meta-box-data ep-form-check ep-form-check-inline">
                                    <input type="checkbox" class="ep-form-check-input" name="em_hide_booking_status" id="em_hide_booking_status" value="1" <?php if( $em_hide_booking_status == 1 ) { echo 'checked="checked"'; } ?> >
                                    <label class="ep-event-booking-hide-status">
                                    <?php esc_html_e('Hide Booking Status', 'eventprime-event-calendar-management'); ?>
                                    <div class="ep-text-muted ep-text-small">
                                        <?php esc_html_e('Hide current booking status for this event on events listing view.', 'eventprime-event-calendar-management'); ?>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Allow booking cancellation option -->
                    <div class="ep-box-col-6">
                        <div class="ep-meta-box-section ep-form-check ep-mt-2">
                            <div class="ep-meta-box-data ep-form-check ep-form-check-inline">
                                    <input type="checkbox" class="ep-form-check-input" name="em_allow_cancellations" id="em_allow_cancellations" value="1" <?php if( $em_allow_cancellations == 1 ) { echo 'checked="checked"'; } ?> >
                                    <label class="ep-event-booking-hide-status ">
                                    <?php esc_html_e( 'Allow Cancellations', 'eventprime-event-calendar-management' ); ?>
                                    <div class="ep-text-muted ep-text-small">
                                        <?php esc_html_e('Allow users to cancel confirmed bookings for this event.', 'eventprime-event-calendar-management'); ?>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div><?php
        }?>
    </div>
</div>