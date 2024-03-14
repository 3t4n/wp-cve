<div class="emagic">
    <div class="ep-calendar-view-admin wrap ">
        <div class="ep-admin-calendar-loader" style="display: none;"></div>
        <?php wp_nonce_field( 'ep-admin-calendar-action' );?>
        <div class="ep_calendar-wrap">
            <div id="ep_event_calendar" class="ep-event-calendar"></div>
        </div>
    </div>
    <!----- New Event Popup ------->
    <div id="ep-new-event-modal" class="ep-admin-calendar-popup">
        <div class="ep-new-event-modal-container ep-modal-view" id="calendarPopup" style="display: none">
            <div class="ep-modal-overlay ep-modal-overlay-fade-in"> </div>
            <div class="popup-content ep-modal-wrap-calendar ep-modal-wrap ep-modal-x-sm ep-modal-out">
                <form  name="calendarForm" class="ep-modal-body" id="ep-calendar-event-create-form">
                    <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                        <h3 class="ep-modal-title ep-px-3"><?php esc_html_e( 'Add New Event', 'eventprime-event-calendar-management' ); ?></h3>
                        <span class="ep-modal-close" onclick="jQuery('#calendarPopup').hide();">&times;</span>
                    </div>
                    <div class="ep-box-wrap ep-my-3">
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <label class="ep-form-label"><?php esc_html_e( 'Event Title', 'eventprime-event-calendar-management' ); ?></label>
                                <div class="eminput">
                                    <input type="hidden" name="event_id" id="ep-calendar-event-id">
                                    <input type="text" name="title" id="ep-event-title" class="ep-form-control regular-text" placeholder="<?php esc_html_e( 'Add Event Title', 'eventprime-event-calendar-management' ); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-6">
                                <label class="ep-form-label"><?php esc_html_e( 'Start Date', 'eventprime-event-calendar-management' ); ?></label>
                                <div class="eminput">
                                    <input type="text" name="start_date" class="ep-form-control" id="calendar_start_date" />
                                </div>  
                            </div>
                            <div class="ep-box-col-6"> 
                                <label class="ep-form-label"><?php esc_html_e( 'Start Time (optional)', 'eventprime-event-calendar-management' ); ?></label>
                                <div class="eminput">
                                    <input type="text" class="ep-form-control epTimePicker" name="start_time" id="calendar_start_time"/>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-6">
                                <div class="emlabel"><?php esc_html_e( 'End Date', 'eventprime-event-calendar-management' ); ?></div>
                                <div class="eminput">
                                    <input type="text" name="end_date" class="ep-form-control" id="calendar_end_date" />
                                </div>
                            </div>
                            <div class="ep-box-col-6">
                                <div class="emlabel"><?php esc_html_e( 'End Time (optional)', 'eventprime-event-calendar-management' ); ?></div>
                                <div class="eminput">
                                    <input type="text" name="end_time" class="ep-form-control epTimePicker" id="calendar_end_time"/>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <div class="eminput">
                                    <label><input type="checkbox" name="em_all_day" id="ep-calendar-all-day" value="1"><?php esc_html_e( 'All Day', 'eventprime-event-calendar-management' ); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3" id="ep-calendar-booking-row">
                            <div class="ep-box-col-12">
                                <label>
                                    <input type="checkbox" name="em_enable_booking" id="ep-calendar-enable-booking" value="1"><?php esc_html_e( 'Enable Bookings', 'eventprime-event-calendar-management' ); ?>
                                </label>
                                <div class="ep-event-popup-notice ep-mt-2" id="ep-calendar-event-booing-helptext" style="display:none;">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    <?php esc_html_e( 'Bookings will open immediately and close once the event begins. You can set custom booking dates from the Event Settings.', 'eventprime-event-calendar-management' ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3" id="ep-calendar-enable-booking-child" style="display:none">
                            <div class="ep-box-col-6" id="ep-calendar-enable-booking-capacity-child">
                                <div class="eminput">
                                    <span class=""><?php esc_html_e( 'Quantity/ Inventory', 'eventprime-event-calendar-management' ); ?></span>
                                    <input name="em_ticket_capacity" class="ep-form-control" id="calendar_ticket_capacity" type="number" min="0" value="" disabled>
                                </div>
                            </div>
                            <div class="ep-box-col-6">
                                <div class="eminput">
                                    <span class=""> <?php esc_html_e( 'Price ( per ticket )', 'eventprime-event-calendar-management' ); ?></span>
                                    <input name="em_ticket_price" class="ep-form-control" id="calendar_booking_price" type="number" min="0" value="" disabled>
                                    <?php if ( ! em_is_payment_gateway_enabled() ) { ?>
                                        <div class="ep-event-popup-notice ep-mt-2">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            <?php esc_html_e(
                                                sprintf(
                                                    'Configure payment gateway from %sGlobal Settings%s before adding booking price here. Payment gateway is not required for 0 booking price.',
                                                    '<a href="' . add_query_arg('page', 'ep-settings', admin_url().'edit.php?post_type=em_events') . '" target="_blank">',
                                                    '</a>'
                                                ),
                                                'eventprime-event-calendar-management');
                                            ?>
                                        </div><?php 
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <?php do_action('event_magic_popup_custom_settings');?>
                        
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <div class="emlabel"><?php esc_html_e( 'Select Event Type', 'eventprime-event-calendar-management' ); ?></div>
                                <div class="eminput">
                                    <select name="event_type" class="ep-form-control regular-text" id="ep-calendar-event-type">
                                        <option value=""><?php esc_html_e('Select Event Type', 'eventprime-event-calendar-management'); ?></option>
                                        <?php if( ! empty( $event_types ) ) {
                                            foreach ( $event_types as $event_type ) {?>
                                                <option value="<?php echo esc_attr( $event_type['id'] ); ?>"><?php echo esc_attr( $event_type['name'] ); ?></option><?php
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <div class="emlabel"><?php esc_html_e( 'Select Venue', 'eventprime-event-calendar-management' ); ?></div>
                                <div class="eminput">
                                    <select name="venue" class="ep-form-control regular-text" id="ep-calendar-venue">
                                        <option value=""><?php esc_html_e( 'Select Event Site', 'eventprime-event-calendar-management' ); ?></option>
                                        <?php if( ! empty( $venues ) ) {
                                            foreach ( $venues as $venue ) {?>
                                                <option value="<?php echo esc_attr( $venue['id'] ); ?>"><?php echo esc_attr( $venue['name'] ); ?></option><?php
                                            }
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <label><?php esc_html_e( 'Feature Image','eventprime-event-calendar-management' ); ?></label>
                                <div class="ep-featured-image-wrap ep-mt-2">
                                    <div class="ep-featured-image">
                                        <img src="" />
                                    </div>
                                    <input type="button" class="button ep-admin-calendar-event-image" value="<?php esc_html_e( 'Upload', 'eventprime-event-calendar-management' ); ?>" />
                                    <input type="button" class="button ep-admin-calendar-event-image-remove" style="display:none;" value="<?php esc_html_e( 'Remove', 'eventprime-event-calendar-management' ); ?>" />
                                    <input type="hidden" name="ep_featured_image_id" id="ep_featured_image_id" />
                                </div>
                            </div>
                        </div>
                        <div class="ep-box-row ep-mb-3">
                            <div class="ep-box-col-12">
                                <div class="emlabel"><?php esc_html_e( 'Status', 'eventprime-event-calendar-management' ); ?></div>
                                <div class="eminput">
                                    <?php $status = array(
                                        'publish' => esc_html( 'Active', 'eventprime-event-calendar-management' ),
                                        'draft'   => esc_html( 'Draft', 'eventprime-event-calendar-management' ),
                                    );?>
                                    <select name="status" class="ep-form-control regular-text" id="ep-calendar-status">
                                        <?php foreach( $status as $key => $status ) {?>
                                            <option value="<?php echo esc_attr( $key );?>"><?php echo esc_attr( $status );?></option><?php
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right">
                        <span class="ep-calendar-event-error ep-error-message ep-box-col-5 ep-mr-2 ep-mb-2 ep-text-end" style="display:none"></span>
                        <button type="button" class="button ep-mr-3 ep-modal-close close-popup"><?php esc_html_e( 'Close', 'eventprime-event-calendar-management' ); ?></button>
                        <button type="button" id="ep-admin-calendar-event-submit" class="button button-primary button-large" ><?php esc_html_e( 'Save', 'eventprime-event-calendar-management' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>