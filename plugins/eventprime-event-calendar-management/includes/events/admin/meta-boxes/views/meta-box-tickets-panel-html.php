<?php
/**
 * Event tickets panel html
 */
defined( 'ABSPATH' ) || exit;
$em_enable_booking = get_post_meta( $post->ID, 'em_enable_booking', true );
$extensions = EP()->extensions;
$show_tickets = $show_message = '';
$event_has_ticket = 0;
$is_event_expired = check_event_has_expired( $single_event_data );?>
<div id="ep_event_ticket_data" class="panel ep_event_options_panel">
    <div class="ep-box-wrap">
        <?php if( $is_event_expired ) {
            $show_tickets = '';$show_message = 1;?>
            <div class="ep-box-row ep-p-3" id="ep_show_event_expire_warning">
                <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                    <strong><?php esc_html_e( 'This event has ended.', 'eventprime-event-calendar-management' ); ?></strong>
                </div>
            </div><?php
        }
        if( empty( $single_event_data->em_enable_booking ) || $single_event_data->em_enable_booking !== 'bookings_on' ) {
            $show_tickets = '';$show_message = 1;?>
            <div class="ep-box-row ep-p-3" id="ep_event_booking_not_enabled_warning">
                <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                    <strong><?php esc_html_e( 'The event booking is not enabled.', 'eventprime-event-calendar-management' ); ?></strong>
                </div>
            </div><?php
        }
        if( empty( $show_tickets ) ) {
            $show_tickets = 'style=display:none;';
        }?>

        <!-- Ticket Options Buttons Area --> 
        <div id="ep-event-tickets-options" <?php echo esc_attr( $show_tickets );?>>
            <div class="ep-box-row ep-p-3">
                <div class="ep-box-col-12 ep-d-flex ep-content-center">
                    <button type="button" class="button button-large ep-m-3 ep-open-modal"  data-id="ep-ticket-category-modal" id="ep_event_open_category_modal" title="<?php esc_html_e( 'Add Tickets Category', 'eventprime-event-calendar-management' );?>">
                        <?php esc_html_e( 'Add Tickets Category', 'eventprime-event-calendar-management' );?>
                    </button>        
                    <button type="button" class="button button-large ep-m-3 ep-open-modal" data-id="ep_event_ticket_tier_modal" id="ep_event_open_ticket_modal" title="<?php esc_html_e( 'Add Tickets', 'eventprime-event-calendar-management' );?>">
                        <?php esc_html_e( 'Add Ticket Type', 'eventprime-event-calendar-management' );?>
                    </button>
                </div>
                <!--Existing Tickets-->
                <div id="ep_existing_tickets_category_list ep-box-col-12">
                    <?php 
                    $existing_cat_data = self::get_existing_category_lists( $post->ID );
                    $ep_ticket_category_data = ( ! empty( $existing_cat_data ) ? json_encode($existing_cat_data) : '' );?>
                    <input type="hidden" name="em_ticket_category_data" id="ep_ticket_category_data" value="<?php echo esc_attr( $ep_ticket_category_data );?>" />
                    <input type="hidden" name="em_ticket_category_delete_ids" id="ep_ticket_category_delete_ids" value="" />
                
                    <div class="ep-box-row ep-p-3" id="ep_existing_tickets_list">
                        <?php if( !empty( $existing_cat_data ) && count( $existing_cat_data ) > 0 ) {
                            foreach( $existing_cat_data as $key => $cat_data ) {
                                $cat_row_data = json_encode($cat_data);
                                $row_key = $key + 1;
                                $cat_row_id = 'ep_ticket_cat_section'. $row_key; ?>
                                <div class="ep-box-col-12 ep-p-3 ep-border ep-rounded ep-mb-3 ep-bg-white ep-shadow-sm ui-state-default ep-cat-list-class" id="<?php echo esc_attr( $cat_row_id );?>" data-cat_row_data="<?php echo esc_attr( $cat_row_data );?>">
                                    <div class="ep-box-row ep-mb-3 ep-items-center">
                                        <div class="ep-box-col-1">
                                            <span class="ep-ticket-cat-sort material-icons ep-cursor-move text-muted" data-parent_id="<?php echo esc_attr( $cat_row_id );?>" >drag_indicator</span>
                                        </div>
                                        <div class="ep-box-col-5">
                                            <h4 class="ep-cat-name"><?php echo esc_html( $cat_data->name );?></h4>
                                        </div>
                                        <div class="ep-box-col-4">
                                            <h4 class="ep-cat-capacity">
                                                <?php echo esc_html__( 'Capacity', 'eventprime-event-calendar-management' ) . ': '. esc_html( $cat_data->capacity );?>
                                            </h4>
                                        </div>
                                        <div class="ep-box-col-1">
                                            <a href="javascript:void(0)" class="ep-ticket-cat-edit ep-text-primary" data-parent_id="<?php echo esc_attr( $cat_row_id );?>" title="<?php esc_html_e( 'Edit Category', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Edit', 'eventprime-event-calendar-management' );?></a>
                                        </div>
                                        <div class="ep-box-col-1">
                                            <a href="javascript:void(0)" class="ep-ticket-cat-delete ep-item-delete" data-parent_id="<?php echo esc_attr( $cat_row_id );?>" title="<?php esc_html_e( 'Delete Category', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Delete', 'eventprime-event-calendar-management' );?></a>
                                        </div>
                                    </div>
                                
                                    <div class="ep-ticket-category-section" data-parent_category_id="<?php echo esc_attr( $row_key );?>">
                                        <?php 
                                        $existing_cat_ticket_data = self::get_existing_category_ticket_lists( $post->ID, $cat_data->id );
                                        if( !empty( $existing_cat_ticket_data ) ) {
                                            $tic_row = 1;$event_has_ticket = 1;
                                            foreach( $existing_cat_ticket_data as $ticket ) {
                                                $icon_url = '';
                                                if( ! empty( $ticket->icon ) ) {
                                                    $icon_url = wp_get_attachment_url( $ticket->icon );
                                                }
                                                $ticket->icon_url = $icon_url;
                                                $ticket_row_data = json_encode( $ticket );
                                                $ticket_row_id = '';
                                                $ticket_row_id = 'ep_cat_' . $row_key . '_ticket' . $tic_row;?>
                                                <div class="ep-box-row ep-tickets-cate-ticket-row" id="<?php echo esc_attr( $ticket_row_id );?>" data-ticket_row_data="<?php echo esc_attr( $ticket_row_data );?>">
                                                    <div class="ep-box-col-12">
                                                        <div class="ep-box-row ep-border ep-rounded ep-ml-2 ep-my-1 ep-mr-2 ep-bg-white ep-items-center ui-state-default">
                                                            <div class="ep-box-col-1 ep-p-3">
                                                                <span class="ep-ticket-row-sort material-icons ep-cursor-move ep-text-muted" data-parent_id="<?php echo esc_attr( $ticket_row_id );?>">drag_indicator</span>
                                                            </div>
                                                            <div class="ep-box-col-3 ep-p-3">
                                                                <?php echo esc_html( $ticket->name );?>
                                                            </div>
                                                            <div class="ep-box-col-2 ep-p-3">
                                                                <span><?php echo esc_html( ep_price_with_position( $ticket->price ) );?></span>
                                                            </div>
                                                            <div class="ep-box-col-3 ep-p-3">
                                                                <span>
                                                                    <?php echo esc_html__( 'Capacity', 'eventprime-event-calendar-management' ) . ' ' . esc_html( $ticket->capacity ) . '/' . esc_html( $cat_data->capacity );?>
                                                                </span>
                                                            </div>
                                                            <?php do_action( 'ep_event_tickets_action_icons', $post->ID, $ticket->id );?>
                                                            <div class="ep-box-col-1 ep-p-3">
                                                                <a href="javascript:void(0)" class="ep-ticket-row-edit ep-text-primary ep-cursor" data-parent_id="<?php echo esc_attr( $ticket_row_id );?>" data-parent_category_id="<?php echo esc_attr( $cat_row_id );?>" title="<?php esc_html_e( 'Edit Ticket', 'eventprime-event-calendar-management' );?>">Edit</a>
                                                            </div>
                                                            <div class="ep-box-col-1 ep-p-3">
                                                                <span class="ep-ticket-row-delete ep-text-danger ep-cursor" data-parent_id="<?php echo esc_attr( $ticket_row_id );?>" title="<?php esc_html_e( 'Delete Ticket', 'eventprime-event-calendar-management' );?>">Delete</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><?php
                                                $tic_row++;
                                            }
                                        }?>
                                    </div><?php
                                    $total_caps = self::get_category_tickets_capacity( $existing_cat_ticket_data );
                                    if( $total_caps < $cat_data->capacity ){?>
                                        <div class="ep_category_add_tickets_button" id="ep_category_add_tickets_button_<?php echo esc_attr( $cat_row_id );?>">
                                            <button type="button" class="button button-large ep-m-3 ep-open-category-ticket-modal" data-id="ep_event_ticket_tier_modal" data-parent_id="<?php echo esc_attr( $cat_row_id );?>"><?php esc_html_e( 'Add Tickets', 'eventprime-event-calendar-management' );?></button>
                                        </div><?php
                                    }?>
                                </div><?php
                            }
                        }?>
                    </div>
                </div>
                <div id="ep_existing_individual_tickets_list">
                    <?php $get_existing_individual_ticket_lists = self::get_existing_individual_ticket_lists( $post->ID );
                    $ep_ticket_data = ( ! empty( $get_existing_individual_ticket_lists ) ? $get_existing_individual_ticket_lists : array() );
                    //$em_ticket_individual_data = ( ! empty( $get_existing_individual_ticket_lists ) ? json_encode( $get_existing_individual_ticket_lists ) : '' );?>
                    <input type="hidden" name="em_ticket_individual_data" id="ep_ticket_individual_data" value="" />
                    <input type="hidden" name="em_ticket_individual_delete_ids" id="ep_ticket_individual_delete_ids" value="" />
                    <?php echo do_action('ep_ticket_additional_hidden_fields');?>
                    <div class="ep-ticket-category-section">
                        <?php if( !empty( $ep_ticket_data ) ) {
                            $tic_row = 1;$event_has_ticket = 1;
                            foreach( $ep_ticket_data as $ticket ) {
                                $icon_url = '';
                                if( ! empty( $ticket->icon ) ) {
                                    $icon_url = wp_get_attachment_url( $ticket->icon );
                                }
                                $ticket->icon_url = $icon_url;
                                $ticket_row_data = json_encode( $ticket );
                                $ticket_row_id = 'ep_event_ticket_row' . $tic_row;?>
                                <div class="ep-box-row ep-tickets-indi-ticket-row" id="<?php echo esc_attr( $ticket_row_id );?>" data-ticket_row_data="<?php echo esc_attr( $ticket_row_data );?>">
                                    <div class="ep-box-col-12">
                                        <div class="ep-box-row ep-border ep-rounded ep-ml-2 ep-my-1 ep-mr-2 ep-bg-white ep-items-center ui-state-default">
                                            <div class="ep-box-col-1 ep-p-3">
                                                <span class="ep-ticket-row-sort material-icons ep-cursor-move ep-text-muted" data-parent_id="<?php echo esc_attr( $ticket_row_id );?>">drag_indicator</span>
                                            </div>
                                            <div class="ep-box-col-3 ep-p-3">
                                                <?php echo esc_html( $ticket->name );?>
                                            </div>
                                            <div class="ep-box-col-2 ep-p-3">
                                                <span><?php echo esc_html( ep_price_with_position( $ticket->price, '', false ) );?></span>
                                            </div>
                                            <div class="ep-box-col-3 ep-p-3">
                                                <span>
                                                    <?php echo esc_html__( 'Capacity', 'eventprime-event-calendar-management' ) . ' ' . esc_html( $ticket->capacity );?>
                                                </span>
                                            </div>
                                            <?php do_action( 'ep_event_tickets_action_icons', $post->ID, $ticket->id );?>
                                            <div class="ep-box-col-1 ep-p-3">
                                                    <a href="javascript:void(0)" class="ep-ticket-row-edit ep-text-primary ep-cursor" data-parent_id="<?php echo esc_attr( $ticket_row_id );?>" title="<?php esc_html_e( 'Edit Ticket', 'eventprime-event-calendar-management' );?>">Edit</a>
                                            </div>
                                            <div class="ep-box-col-1 ep-p-3">
                                                <a href="javascript:void(0)" class="ep-ticket-row-delete  ep-text-danger ep-cursor" data-parent_id="<?php echo esc_attr( $ticket_row_id );?>" title="<?php esc_html_e( 'Delete Ticket', 'eventprime-event-calendar-management' );?>">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div><?php
                                $tic_row++;
                            }
                        }?>
                    </div>
                </div>
                <!--Existing Tickets Ends-->
            </div>
        </div>
    
        <!--Add Ticket Category Modal-->
        <div id="ep-ticket-category-modal" class="ep-modal-view" style="display: none;">
            <div class="ep-modal-overlay ep-modal-overlay-fade-in"></div>  
            <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out">
                <div class="ep-modal-body">
                    <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                        <h3 class="ep-modal-title ep-px-3 ">
                            <?php esc_html_e('Add Tickets Category', 'eventprime-event-calendar-management'); ?>
                        </h3>
                        <a href="#" class="ep-modal-close close-popup" data-id="ep-ticket-category-modal">&times;</a>
                    </div>
                    <div class="ep-modal-content-wrapep-box-wrap">
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
                                    <input type="number" class="ep-form-control" name="em_ticket_category_capacity" min="1" id="ep_ticket_category_capacity">
                                    <div class="ep-text-muted ep-text-small">
                                        <?php esc_html_e('Combined capacity or inventory of the tickets you wish to include in this tickets category should not exceed this number.', 'eventprime-event-calendar-management'); ?>
                                    </div>
                                    <div id="ep_ticket_category_capacity_error" class="ep-error-message"></div>
                                </div> 
                            </div>

                        <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right">
                            <button type="button" class="button ep-mr-3 ep-modal-close close-popup" data-id="ep-ticket-category-modal"><?php esc_html_e('Close', 'eventprime-event-calendar-management'); ?></button>
                            <button type="button" class="button button-primary button-large" id="ep_save_ticket_category"><?php esc_html_e('Add', 'eventprime-event-calendar-management'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Add Ticket Category Modal-->
        
        <!-- Add  Ticket Tier Modal -->   
        <div id="ep_event_ticket_tier_modal" class="ep-modal-view" style="display: none;">
            <input type="hidden" name="em_ticket_category_id" value="" />
            <input type="hidden" name="em_ticket_id" value="" />
            <input type="hidden" name="em_ticket_parent_div_id" value="" />
            <div class="ep-modal-overlay ep-modal-overlay-fade-in"></div>
            <div class="popup-content ep-modal-wrap ep-modal-lg ep-modal-out">
                <div class="ep-modal-body">    
                    <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                        <h3 class="ep-modal-title ep-px-3"><?php esc_html_e( 'Add Ticket Type', 'eventprime-event-calendar-management' );?></h3>
                        <a href="#" class="ep-modal-close close-popup" data-id="ep_event_ticket_tier_modal">&times;</a>
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

                            <div class="ep-box-col-12 ep-mt-3">
                                <label class="ep-form-label">
                                    <!--<?php esc_html_e( 'Icon', 'eventprime-event-calendar-management' );?> -->
                                </label>
                                <input type="hidden" name="icon" id="ep_event_ticket_icon">
                                <button type="button" class="upload_offer_icon_button button">
                                    <?php esc_html_e( 'Upload Icon', 'eventprime-event-calendar-management' );?>
                                </button>
                                <div class="ep-text-muted ep-text-small">
                                    <?php esc_html_e('A small icon or an image representative of the ticket type.', 'eventprime-event-calendar-management'); ?>
                                </div>
                                <div id="ep_event_ticket_icon_image"></div>
                            </div>                

                            <div class="ep-box-col-5 ep-mt-3">
                                <label class="ep-form-label">
                                    <?php esc_html_e( 'Quantity/ Inventory', 'eventprime-event-calendar-management' );?>
                                </label>
                                <input type="number" class="ep-form-control" min="0" name="capacity" id="ep_event_ticket_qty">
                                <div class="ep-error-message" id="ep_event_ticket_qty_error"></div>
                                <span id="ep_ticket_remaining_capacity" data-max_ticket_label="<?php esc_html_e( 'Remaining Seats', 'eventprime-event-calendar-management' );?>"></span>
                            </div>                

                            <div class="ep-box-col-5 ep-mt-3">
                                <label class="ep-form-label">
                                    <?php esc_html_e( 'Price ( per ticket )', 'eventprime-event-calendar-management' );?>
                                </label>
                                <input type="number" class="ep-form-control" name="price" id="ep_event_ticket_price" min="0.00" step="0.01">
                            </div>                

                            <div class="ep-box-col-2 ep-mt-3 ep-d-flex ep-items-end ep-pb-2">
                                <span class="ep-fw-bold"><?php 
                                    $selected_currency = ep_get_global_settings( 'currency' );
                                    if( empty( $selected_currency ) ) {
                                        $selected_currency = 'USD';
                                    }
                                    echo esc_html( $selected_currency );
                                ?></span>
                                <span class="ep-color-primary ep-border-bottom ep-border-opacity-50 ep-ml-2 ep-text-small"><a target="_blank" href="<?php echo esc_url(add_query_arg(array('tab' => 'payments'), admin_url().'edit.php?post_type=em_event&page=ep-settings'));?>"><?php esc_html_e( 'change?', 'eventprime-event-calendar-management' );?></a></span>
                            </div>
                            
                            <div class="ep-box-col-12 ep-mt-3">
                                <button type="button" class="button button-primary button-large" id="add_more_additional_ticket_fee"><?php esc_html_e( 'Add Additional Fee', 'eventprime-event-calendar-management' );?></button>
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
                                            <?php $existing_cat_data = self::get_ticket_booking_event_date_options( $post->ID );
                                            if( ! empty( $existing_cat_data ) ) {
                                                foreach( $existing_cat_data as $key => $option ) {?>
                                                    <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $option );?></option><?php
                                                }
                                            }?>
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
                                            <?php $existing_cat_data = self::get_ticket_booking_event_date_options( $post->ID );
                                            if( ! empty( $existing_cat_data ) ) {
                                                foreach( $existing_cat_data as $key => $option ) {?>
                                                    <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $option );?></option><?php
                                                }
                                            }?>
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

                            <div class="ep-box-col-12 ep-mt-3">
                                <label class="ep-form-check-label" for="ep-ticket-template">
                                    <?php esc_html_e( 'Tickets Template', 'eventprime-event-calendar-management');?>
                                </label>
                                <select id="ep-event-ticket-template" class="ep-form-control" name="em_ticket_template" disabled>
                                    <?php do_action( 'ep_event_get_ticket_template_options' );?>
                                </select>
                                <div class="ep-text-muted ep-text-small">
                                    <?php esc_html_e( 'Templates allow you to design custom ticket styles which are sent to the users as PDF for printing.', 'eventprime-event-calendar-management'); 
                                    if( ! in_array( 'event_tickets', $extensions ) ) {
                                        echo '<br>';
                                        esc_html_e( 'To use ticket templates, please install', 'eventprime-event-calendar-management' );?>
                                        <a href="<?php echo esc_url( admin_url('edit.php?post_type=em_event&page=ep-extensions') );?>" target="_blank"><?php echo esc_html( 'Event Tickets extension' );?></a><?php
                                    }?>
                                </div>
                            </div>
                            <?php echo do_action('em_ticket_additional_fields');?>
                        </div>

                        <!-- Tabs -->
                        <div class="ep-box-row ep-p-3">
                            <div class="ep-box-col-12 ep-mt-3">
                                <div id="accordion" class="ep-accordion-wrap">
                                    <h3 class="ep-accordion-header-item"><?php esc_html_e( 'Visibility', 'eventprime-event-calendar-management');?></h3>
                                    <div>
                                        <div class="ep-box-row">
                                            <div class="ep-box-col-12 ep-mt-3">
                                                <label class="ep-form-label"><?php esc_html_e( 'Visible To', 'eventprime-event-calendar-management');?></label>
                                                <select class="ep-form-control" name="em_tickets_user_visibility" id="ep_tickets_user_visibility">
                                                    <option value="public"><?php esc_html_e( 'Everyone', 'eventprime-event-calendar-management');?></option>
                                                    <option value="all_login"><?php esc_html_e( 'Logged In Users', 'eventprime-event-calendar-management');?></option>
                                                    <option value="user_roles"><?php esc_html_e( 'Specific User Roles', 'eventprime-event-calendar-management');?></option>
                                                    <!-- <option value="user-groups">Logged In Users -> Group Members</option> -->
                                                </select>
                                            </div>

                                            <div class="ep-box-col-12 ep-mt-3" id="ep_ticket_visibility_user_roles_select" style="display:none;">
                                                <!-- <label class="ep-form-label"><?php esc_html_e( 'Roles', 'eventprime-event-calendar-management');?></label> -->
                                                <select name="em_ticket_visibility_user_roles[]" id="ep_ticket_visibility_user_roles" multiple="multiple" class="ep-form-control ep_user_roles_options">
                                                    <?php foreach( ep_get_all_user_roles() as $key => $role ){?>
                                                        <option value="<?php echo esc_attr( $key );?>">
                                                            <?php echo $role;?>
                                                        </option><?php
                                                    }?>
                                                </select>
                                            </div>

                                            <div class="ep-box-col-12 ep-mt-3">
                                                <label><?php esc_html_e( 'For Ineligible Users', 'eventprime-event-calendar-management');?></label>
                                                <div class="ep-form-check ep-mt-2">
                                                    <input class="ep-form-check-input" type="radio" name="em_ticket_for_invalid_user" value="hidden" id="ep_ticket_for_hidden_user">
                                                    <label class="ep-form-check-label" for="ep_ticket_for_hidden_user">
                                                        <?php esc_html_e( 'Hide Tickets', 'eventprime-event-calendar-management');?>
                                                    </label>
                                                </div>

                                                <div class="ep-form-check">
                                                    <input class="ep-form-check-input" type="radio" name="em_ticket_for_invalid_user" value="disabled" id="ep_ticket_for_disabled_user" checked>
                                                    <label class="ep-form-check-label" for="ep_ticket_for_disabled_user">
                                                        <?php esc_html_e( 'Show Tickets as Disabled/ Greyed Out', 'eventprime-event-calendar-management');?>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <h3 class="ep-accordion-header-item"><?php esc_html_e( 'Offers', 'eventprime-event-calendar-management');?></h3>
                                    <div>
                                        <div class="ep-box-col-12 ep-my-3 ep-px-2">
                                            <strong><?php esc_html_e( 'Add New Offer', 'eventprime-event-calendar-management');?></strong>
                                        </div>
                                        
                                        <div class="ep-box-row ep-border ep-rounded ep-p-3 ep-m-2 ep-bg-light" id="ep_event_ticket_offer_wrapper">
                                            <div class="ep-box-col-12">
                                                <label class="ep-form-label"><?php esc_html_e( 'Name', 'eventprime-event-calendar-management');?></label>
                                                <input type="text" class="ep-form-control" name="em_ticket_offer_name" id="ep_ticket_offer_name">
                                                <div class="ep-text-muted ep-text-small">
                                                    <?php esc_html_e('Examples: Weekend Offer, Early-bird Discount etc. Offer name is visible on the frontend.', 'eventprime-event-calendar-management'); ?>
                                                </div>
                                                <div class="ep-error-message" id="ep_event_offer_name_error"></div>
                                            </div>

                                            <div class="ep-box-col-12 ep-mt-3">
                                                <label class="ep-form-label"><?php esc_html_e( 'Description', 'eventprime-event-calendar-management');?></label>
                                                <textarea class="ep-form-control" name="em_ticket_offer_description"></textarea>
                                                <div class="ep-text-muted ep-text-small">
                                                    <?php esc_html_e('Offer Description is visible on the frontend.', 'eventprime-event-calendar-management'); ?>
                                                </div>
                                            </div>

                                            <div class="ep-box-col-12 ep-mt-3">
                                                <div class="ep-form-check ep-form-check-inline">
                                                    <input class="ep-form-check-input" type="checkbox" name="em_ticket_show_offer_detail" value="1" id="show-offer-details">
                                                    <label class="ep-form-check-label" for="show-offer-details">
                                                        <?php esc_html_e( 'Show this offer in the offers section of the event', 'eventprime-event-calendar-management');?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="ep-box-col-6 ep-mt-3">
                                                <div class="ep-box-row" >
                                                    <div class="ep-box-col-12">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Offer Start', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <select class="ep-form-control ep_offer_start_booking_type" name="em_offer_start_booking_type">
                                                            <option value="custom_date"><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                                                            <option value="event_date"><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                                                            <option value="relative_date"><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                                                        </select>
                                                    </div>
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_start_booking_options ep_offer_start_booking_custom_date">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Choose Date', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <input type="text" class="ep-form-control ep_metabox_custom_date_picker" name="em_offer_start_booking_date" id="ep_offer_start_booking_date" data-start="booking_start" data-end="booking_end">
                                                    </div>
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_start_booking_options ep_offer_start_booking_custom_date">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Choose Time', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <input type="text" class="ep-form-control epTimePicker" name="em_offer_start_booking_time" id="ep_offer_start_booking_time">
                                                    </div> 
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_start_booking_options ep_offer_start_booking_relative_date" style="display:none;">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Enter Days', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <input type="number" class="ep-form-control" name="em_offer_start_booking_days" min="0">
                                                    </div>
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_start_booking_options ep_offer_start_booking_relative_date" style="display:none;">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Days Option', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <select class="ep-form-control" name="em_offer_start_booking_days_option">
                                                            <option value="before"><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                                            <option value="after"><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                                        </select>
                                                    </div>
                                                    <div class="ep-box-col-12 ep-mt-3 ep_offer_start_booking_options ep_offer_start_booking_event_date ep_offer_start_booking_relative_date" style="display:none;">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Event Option', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <select class="ep-form-control" name="em_offer_start_booking_event_option">
                                                            <?php $existing_cat_data = self::get_ticket_booking_event_date_options( $post->ID );
                                                            if( ! empty( $existing_cat_data ) ) {
                                                                foreach( $existing_cat_data as $key => $option ) {?>
                                                                    <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $option );?></option><?php
                                                                }
                                                            }?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ep-box-col-6 ep-mt-3">
                                                <div class="ep-box-row">
                                                    <div class="ep-box-col-12">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Offer Ends', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <select class="ep-form-control ep_offer_ends_booking_type" name="em_offer_ends_booking_type">
                                                            <option value="custom_date"><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                                                            <option value="event_date"><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                                                            <option value="relative_date"><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                                                        </select>
                                                    </div>
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_ends_booking_options ep_offer_ends_booking_custom_date">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Choose Date', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <input type="text" class="ep-form-control ep_metabox_custom_date_picker" name="em_offer_ends_booking_date" id="ep_offer_ends_booking_date" data-start="booking_start" data-end="booking_end">
                                                    </div>
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_ends_booking_options ep_offer_ends_booking_custom_date">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Choose Time', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <input type="text" class="ep-form-control epTimePicker" name="em_offer_ends_booking_time" id="ep_offer_ends_booking_time">
                                                    </div> 
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_ends_booking_options ep_offer_ends_booking_relative_date" style="display:none;">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Enter Days', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <input type="number" class="ep-form-control" name="em_offer_ends_booking_days" min="0">
                                                    </div>
                                                    <div class="ep-box-col-6 ep-mt-3 ep_offer_ends_booking_options ep_offer_ends_booking_relative_date" style="display:none;">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Days Option', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <select class="ep-form-control" name="em_offer_ends_booking_days_option">
                                                            <option value="before"><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                                                            <option value="after"><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                                                        </select>
                                                    </div>
                                                    <div class="ep-box-col-12 ep-mt-3 ep_offer_ends_booking_options ep_offer_ends_booking_event_date ep_offer_ends_booking_relative_date" style="display:none;">
                                                        <label class="ep-form-label">
                                                            <?php esc_html_e( 'Event Option', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                        <select class="ep-form-control" name="em_offer_ends_booking_event_option">
                                                            <?php $existing_cat_data = self::get_ticket_booking_event_date_options( $post->ID );
                                                            if( ! empty( $existing_cat_data ) ) {
                                                                foreach( $existing_cat_data as $key => $option ) {?>
                                                                    <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $option );?></option><?php
                                                                }
                                                            }?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ep-box-col-6 ep-mt-3">
                                                <label class="ep-form-label"><?php esc_html_e( 'Offer Type', 'eventprime-event-calendar-management');?></label>
                                                <select id="ep_ticket_offer_type" class="ep-form-control" name="em_ticket_offer_type">
                                                    <option value="seat_based"><?php esc_html_e( 'Admittance Based', 'eventprime-event-calendar-management');?></option>
                                                    <option value="role_based"><?php esc_html_e( 'User Role Based', 'eventprime-event-calendar-management');?></option>
                                                    <option value="volume_based"><?php esc_html_e( 'Volume Based', 'eventprime-event-calendar-management');?></option>
                                                </select>
                                            </div>
                                            
                                            <div class="ep-box-col-12 ep-mt-3">
                                                <div class="ep-box-row">
                                                    <div class="ep-box-col-12 offer-fields ep-seat-based-offer-wrapper" id="ep_ticket_offer_seat_based">
                                                        <div class="ep-box-row">
                                                            <div class="ep-box-col-6">
                                                                <label class="ep-form-label"><?php esc_html_e( 'Select Admittance Order', 'eventprime-event-calendar-management');?></label>
                                                                <select class="ep-form-control" name="em_ticket_offer_seat_option">
                                                                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management');?></option>
                                                                    <option value="first"><?php esc_html_e( 'First', 'eventprime-event-calendar-management');?></option>
                                                                    <option value="last"><?php esc_html_e( 'Last', 'eventprime-event-calendar-management');?></option>
                                                                </select>
                                                            </div>
                                                            <div class="ep-box-col-6">
                                                                <label class="ep-form-label"><?php esc_html_e( 'Enter Number', 'eventprime-event-calendar-management');?></label>
                                                                <input type="number" min="0" class="ep-form-control" name="em_ticket_offer_seat_number" placeholder="<?php esc_html_e( 'Count', 'eventprime-event-calendar-management');?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="offer-fields ep-box-col-6" id="ep_ticket_offer_role_based" style="display: none;">
                                                        <label class="ep-form-label"><?php esc_html_e( 'Select Roles', 'eventprime-event-calendar-management');?></label>
                                                        <select name="em_ticket_offer_user_roles" id="em_ticket_offer_user_roles" multiple="multiple" class="ep-form-control ep_user_roles_options">
                                                            <?php foreach( ep_get_all_user_roles() as $key => $role ){?>
                                                                <option value="<?php echo esc_attr( $key );?>">
                                                                    <?php echo $role;?>
                                                                </option><?php
                                                            }?>
                                                        </select>
                                                    </div>

                                                    <div class="offer-fields ep-box-col-6" id="ep_ticket_offer_volume_based" style="display: none;">
                                                        <label class="ep-form-label"><?php esc_html_e( 'Enter Number', 'eventprime-event-calendar-management');?></label>
                                                        <input type="number" class="ep-form-control" name="em_ticket_offer_volumn_count" placeholder="<?php esc_html_e( 'Minimum Number of Tickets', 'eventprime-event-calendar-management');?>">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="ep-box-col-6 ep-mt-3" id="ep_offer_discount_type">
                                                <label class="ep-form-label">
                                                    <?php esc_html_e( 'Select Discount Type', 'eventprime-event-calendar-management' );?>
                                                </label>
                                                <select class="ep-form-control" name="em_ticket_offer_discount_type" id="ep_ticket_offer_discount_type">
                                                    <option value=""><?php esc_html_e( 'Select Discount Type', 'eventprime-event-calendar-management');?></option>
                                                    <option value="flat"><?php esc_html_e( 'Flat Discount', 'eventprime-event-calendar-management');?></option>
                                                    <option value="percentage"><?php esc_html_e( 'Percentage', 'eventprime-event-calendar-management');?></option>
                                                </select>
                                                <div class="ep-error-message" id="ep_ticket_offer_discount_type_error"></div>
                                            </div>

                                            <div class="ep-box-col-6 ep-mt-3" id="ep_offer_discount">
                                                <label class="ep-form-label">
                                                    <?php esc_html_e( 'Enter Discount', 'eventprime-event-calendar-management' );?>
                                                </label>
                                                <input type="number" min="0" class="ep-form-control" name="em_ticket_offer_discount" id="ep_ticket_offer_discount" placeholder="<?php esc_html_e( 'Enter Discount', 'eventprime-event-calendar-management');?>">
                                                <div class="ep-error-message" id="ep_ticket_offer_discount_error"></div>
                                            </div>

                                            <div class="ep-box-col-12 ep-mt-3">
                                                <button type="button" class="button button-primary button-large" id="ep_ticket_add_offer"><?php esc_html_e( 'Add Offer', 'eventprime-event-calendar-management');?></button>
                                                <div class="ep-error-message ep-lh-lg ep-mt-3" id="ep_ticket_offer_not_save_error"></div>
                                            </div>

                                        </div>

                                        <div class="ep-box-row ep-my-4 ep-mx-0">
                                            <div class="ep-box-col-6">
                                                <label class="ep-form-label"><?php esc_html_e( 'How to Handle Multiple Offers?', 'eventprime-event-calendar-management');?></label>
                                                <select class="ep-form-control" name="multiple_offers_option" id="em_multiple_offers_option">
                                                    <option value="stack_offers"><?php esc_html_e( 'Stack offers', 'eventprime-event-calendar-management');?></option>
                                                    <option value="first_offer"><?php esc_html_e( 'First one that applies', 'eventprime-event-calendar-management');?></option>
                                                </select>
                                            </div>
                                            <div class="ep-box-col-6">
                                                <label class="form-label"><?php esc_html_e( 'Max Cumulative Discount', 'eventprime-event-calendar-management');?></label>
                                                <input type="number" min="0" class="ep-form-control" name="multiple_offers_max_discount" id="em_multiple_offers_max_discount">
                                            </div>
                                        </div>

                                        <div class="ep-box-row ep-mt-4" id="ep_ticket_offers_wrapper" style="display: none;">
                                            <div class="ep-box-col-12 ep-my-3">
                                                <strong><?php esc_html_e( 'Added Offers', 'eventprime-event-calendar-management');?></strong>
                                            </div>
                                            <input type="hidden" name="offers" id="ep_event_ticket_offers" value="" />
                                            <div class="ep-box-col-12" id="ep_existing_offers_list"></div>
                                        </div>  
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        <!-- Modal Wrap  End --> 
                        <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right">
                            <button type="button" class="button ep-mr-3 ep-modal-close close-popup" data-id="ep_event_ticket_tier_modal"><?php esc_html_e( 'Close', 'eventprime-event-calendar-management');?></button>
                            <button type="button" class="button button-primary button-large" id="ep_save_ticket_tier"><?php esc_html_e( 'Save changes', 'eventprime-event-calendar-management');?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add  Ticket Tier Modal End --> 
        <input type="hidden" name="ep_event_has_ticket" id="ep_event_has_ticket" value="<?php echo absint( $event_has_ticket );?>" >

        <?php do_action( 'ep_event_after_admin_tickets_section' );?>
        <?php if( empty( $show_message ) ) {?>
            <div class="ep-box-row ep-p-3" id="ep_event_booking_disabled_warning" style="display: none;">
                <div class="ep-alert ep-alert-warning ep-mt-3 ep-py-2">
                    <strong><?php esc_html_e( 'The event booking is not enabled.', 'eventprime-event-calendar-management' ); ?></strong>
                </div>
            </div><?php
        }?>
    </div>
</div>

<div id="ep_event_booking_turn_off_modal" class="ep-modal-view" style="display: none;">
    <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_event_booking_turn_off_modal"></div>
    <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out"> 
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                <h3 class="ep-modal-title ep-px-3">
                    <?php esc_html_e( 'No ticket found', 'eventprime-event-calendar-management' ); ?>
                </h3>
                <a href="#" class="ep-modal-close close-popup" data-id="ep_event_booking_turn_off_modal">&times;</a>
            </div> 
            <div class="ep-modal-content-wrap"> 
                <div class="ep-box-wrap">
                    <div class="ep-box-row ep-p-3 ep-box-w-75 ep-event-booking-field-manager">
                        <div class="ep-box-col-12 form-field">
                            <?php esc_html_e( 'You have not added any tickets for this event. Therefore, bookings for this event will be turned off.', 'eventprime-event-calendar-management' );?>
                        </div>
                    </div>
                </div>
                <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right" id="ep_modal_buttonset">
                    <span class="spinner ep-mr-2 ep-mb-2 ep-text-end" id="ep_event_booking_turn_off_loader"></span>
                    <button type="button" class="button ep-mr-3 ep-modal-close close-popup" data-id="ep_event_booking_turn_off_modal" id="ep_event_booking_turn_off_cancel" title="<?php echo esc_attr( 'Cancel', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Cancel', 'eventprime-event-calendar-management'); ?></button>
                    <button type="button" class="button button-primary button-large" id="ep_event_booking_turn_off_continue" title="<?php echo esc_attr( 'Ok', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Ok', 'eventprime-event-calendar-management'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>