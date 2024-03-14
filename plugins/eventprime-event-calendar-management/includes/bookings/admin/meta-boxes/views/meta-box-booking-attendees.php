<?php
/**
 * Booking meta box html
 */
defined( 'ABSPATH' ) || exit;
$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
$booking_id = $post->ID;
$post_meta = get_post_meta( $booking_id );
$booking = $this->get_booking_cache( $booking_id );
if( empty( $booking ) ) {
    $booking = $booking_controller->load_booking_detail( $booking_id );
}?>

<div class="panel-wrap ep_event_metabox">
    <?php if( ! empty( $booking->em_attendee_names ) && count( $booking->em_attendee_names ) > 0 ) {?>
        <div class="ep-border-bottom">
            <div class="ep-py-3 ep-ps-3 ep-fw-bold ep-text-uppercase ep-text-small">
                <?php esc_html_e( 'Attendees', 'eventprime-event-calendar-management' );?>
            </div>
        </div>
        <?php $booking_attendees_field_labels = array();
        if( isset( $booking->em_old_ep_booking ) && ! empty( $booking->em_old_ep_booking ) ) {
            $table_head = array_keys( $booking->em_attendee_names[0] );?>
            <div class="ep-p-4">
                <table class="ep-table ep-table-hover ep-text-small ep-table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <?php foreach( $table_head as $labels ){?>
                                <th scope="col">
                                    <?php echo esc_html( $labels );?>
                                </th><?php
                            }?>
                            <!-- <th scope="col"></th> -->
                        </tr>
                    </thead>
                    <tbody class=""><?php $att_count = 1;
                        foreach( $booking->em_attendee_names as $key => $attendee_data ) {
                            $table_data = array_values( $attendee_data );?>
                            <tr>
                                <th scope="row" class="py-3"><?php echo esc_html( $att_count );?></th><?php
                                foreach( $table_data as $data ) {?>
                                    <td class="py-3"><?php echo esc_html( $data );?></td><?php
                                }
                                $att_count++;?>
                            </tr><?php
                        }?>
                    </tbody>
                </table>
            </div><?php
        } else{
            if( isset($booking->em_attendee_names[0]) && is_string( $booking->em_attendee_names[0] ) ) {?>
                <div class="ep-p-4">
                    <div class="ep-mb-3 ep-fw-bold ep-text-small">
                        <?php echo esc_html( EventM_Factory_Service::get_ticket_name_by_id( $ticket_id ) );?>
                    </div>
                    <table class="ep-table ep-table-hover ep-text-small ep-table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">
                                    <?php echo esc_html__( 'Name', 'eventprime-event-calendar-management' );?>
                                </th>
                            </tr>
                        </thead>
                        <tbody class=""><?php $att_count = 1;
                            foreach( $booking->em_attendee_names as $booking_attendees ) {?>
                                <tr>
                                    <th scope="row" class="py-3"><?php echo esc_html( $att_count );?></th>
                                    <td class="py-3"><?php echo esc_html( $booking_attendees );?></td>
                                </tr><?php
                                $att_count++;
                            }?>
                        </tbody>
                    </table>
                </div><?php
            } else{
                $is_new_format = $att_count = 1;
                $em_allow_edit_booking = $booking_controller->check_booking_eligible_for_edit( $booking->em_event );
                foreach( $booking->em_attendee_names as $ticket_id => $attendee_data ) {
                    $first_key = array_keys( $attendee_data )[0];
                    if( isset( $attendee_data[$first_key] ) ) {
                        $booking_attendees_field_labels = ep_get_booking_attendee_field_labels( $attendee_data[$first_key] );
                    } else{
                        $att_key_array = array();
                        foreach( $attendee_data as $att_key => $att_value ) {
                            $att_key_array[] = $att_key;
                        }
                        $booking_attendees_field_labels = array_unique( $att_key_array );
                        $is_new_format = 0;
                    }?>
                    <div class="ep-p-4">
                        <div class="ep-mb-3 ep-fw-bold ep-text-small">
                        <?php echo esc_html( EventM_Factory_Service::get_ticket_name_by_id( $ticket_id ) );?>
                        </div>
                        <table class="ep-table ep-table-hover ep-text-small ep-table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <?php foreach( $booking_attendees_field_labels as $label_key => $labels ) {?>
                                        <th scope="col">
                                            <?php echo esc_html__( $labels, 'eventprime-event-calendar-management' );?>
                                        </th><?php
                                    }
                                    if( ! empty( $em_allow_edit_booking ) ) {?>
                                        <th scope="col">&nbsp;</th><?php
                                    }?>
                                </tr>
                            </thead>
                            <tbody class=""><?php $att_count = 1;
                                foreach( $attendee_data as $att_key => $booking_attendees ) {?>
                                    <tr>
                                        <td scope="row" class="py-3"><?php echo esc_html( $att_count );?></td><?php 
                                        $booking_attendees_val = ( is_array( $booking_attendees ) ? array_values( $booking_attendees ) : $booking_attendees );
                                        foreach( $booking_attendees_field_labels as $label_key => $labels ){?>
                                            <td class="py-3"><?php
                                                if( $is_new_format == 0 ) {
                                                    $at_val = $booking_attendees_val;
                                                } else{
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
                                                }
                                                echo esc_html( $at_val );?>
                                            </td><?php
                                        }
                                        /* if( ! empty( $em_allow_edit_booking ) ) {?>
                                            <td>
                                                <a href="javascript:void(0);" class="ep-admin-edit-booking-attendee" data-attendee_val="<?php echo json_encode( $booking_attendees_val );?>" data-event_id="<?php echo esc_attr( $booking->em_event );?>" data-ticket_id="<?php echo esc_attr( $ticket_id );?>" data-ticket_key="<?php echo esc_attr( $att_key );?>" data-booking_id="<?php echo esc_attr( $booking->em_id );?>">
                                                    <span class="material-icons-round ep-fs-6">edit</span>
                                                </a>
                                            </td><?php
                                        } */?>
                                    </tr><?php
                                    $att_count++;
                                }?>
                            </tbody>
                        </table>
                    </div><?php
                }
            }
        }
    }?>
</div>


<div class="ep-modal ep-modal-view" id="ep_edit_admin_booking_modal_container" ep-modal="ep_edit_admin_booking_modal_container" style="display: none;">
    <div class="ep-modal-overlay" ep-modal-close="ep_edit_admin_booking_modal_container"></div>
    <div class="popup-content ep-modal-wrap ep-modal-sm">
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                <h3 class="ep-modal-title ep-px-3">
                    <?php echo esc_html__('Edit Attendee', 'eventprime-eventprime-event-calendar-management');?>
                </h3>
                <a href="#" class="ep-modal-close close-popup" data-id="ep_edit_admin_booking_modal_container">&times;</a>
            </div>
            <div class="ep-modal-content-wrap"> 
                <div class="ep-box-wrap">
                    <div class="ep-box-row ep-p-3 ep-box-w-75">
                        <input type="hidden" name="em_event_id" id="ep_event_id" value="<?php echo esc_attr( $booking->em_event );?>">
                        <input type="hidden" name="em_booking_id" id="ep_booking_id" value="<?php echo esc_attr( $booking->em_id );?>">
                        <div id="ep_admin_edit_booking_load_attendee_data"></div>
                    </div>
                </div>
                <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right" id="ep_modal_buttonset">
                    <span class="ep-error-message ep-box-col-9 ep-mr-2 ep-mb-2 ep-text-end" id="ep_edit_admin_booking_error"></span>
                    <button type="button" class="button ep-mr-3 ep-modal-close" ep-modal-close="ep_edit_admin_booking_modal_container">
                        <?php esc_html_e( 'Cancel', 'eventprime-eventprime-event-calendar-management' );?>
                    </button>
                    <button type="button" class="button button-primary button-large" id="ep_update_edit_admin_booking">
                        <?php esc_html_e( 'Send', 'eventprime-eventprime-event-calendar-management' );?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>