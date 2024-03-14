<?php
/**
 * Event checkout fields panel html.
 */
defined( 'ABSPATH' ) || exit;
$em_event_checkout_attendee_fields = get_post_meta( $post->ID, 'em_event_checkout_attendee_fields', true );
$em_event_checkout_fields_data = ( ! empty( $em_event_checkout_attendee_fields ) && isset( $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] ) ? $em_event_checkout_attendee_fields['em_event_checkout_fields_data'] : array() );
$em_event_checkout_fields_data_required = ( ! empty( $em_event_checkout_attendee_fields ) && isset( $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] ) ? array_flip( $em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] ) : array() );
$em_event_checkout_fixed_fields = get_post_meta( $post->ID, 'em_event_checkout_fixed_fields', true );
$em_event_checkout_booking_fields = get_post_meta( $post->ID, 'em_event_checkout_booking_fields', true );
$em_event_booking_fields_data = ( ! empty( $em_event_checkout_booking_fields ) && isset( $em_event_checkout_booking_fields['em_event_booking_fields_data'] ) ? $em_event_checkout_booking_fields['em_event_booking_fields_data'] : array() );
$em_event_booking_fields_data_required = ( ! empty( $em_event_checkout_booking_fields ) && isset( $em_event_checkout_booking_fields['em_event_booking_fields_data_required'] ) ? array_flip( $em_event_checkout_booking_fields['em_event_booking_fields_data_required'] ) : array() );?>
<div id="ep_event_checkout_fields_data" class="panel ep_event_options_panel">
    <div class="ep-box-wrap ep-my-3">
        <div class="panel-wrap ep_event_metabox ep-box-wrap ep-p-0">
            <div class="ep-box-row">
                <div class="ep-box-col-12">
                    <ul class="ep_event_checkout_fields_tabs ep-nav-tabs ep-mb-3 ep-m-0 ep-p-0 ep-d-flex ep-justify-content-center">
                        <?php foreach (self::get_ep_event_checkout_field_tabs() as $key => $tab) {?>
                            <?php $active_class = ( $key == 'attendee_fields' ) ? 'ep-tab-active' : '';?>
                            <li class="ep-event-checkout-fields-tab ep-tab-item ep-mx-0 ep-my-0 <?php echo esc_attr($key); ?>_options <?php echo esc_attr($key); ?>_tab <?php echo esc_attr(isset($tab['class']) ? implode(' ', (array) $tab['class']) : '' ); ?> ">
                                <a href="#" class="ep-tab-link <?php echo esc_attr( $active_class );?>"
                                    data-src="<?php echo esc_attr($tab['target']); ?>"><span><?php echo esc_html($tab['label']); ?></span>
                                </a>
                            </li><?php
                        }?>
                    </ul>
                </div>
            </div>
            <div class="ep-box-row">
                <div class="ep-box-col-12">
                    <div class="ep-box-row ep-mb-3 ep-event-checkout-attendee-fields panel ep_event_checkout_fields_panel"
                        id="ep_event_attendee_fields_data">
                        <div class="ep-box-col-12">
                            <div class="ep-text-small ep-alert ep-alert-warning ep-mx-2 ep-my-4">
                                <?php echo esc_html__( 'Attendee fields will be added for each attendee in the checkout form Step 1. For example, you can add fields like Name, Age etc for each person. To create new fields, go to Settings', 'eventprime-event-calendar-management' );?>
                                <span class="material-icons ep-fs-6 ep-align-middle">navigate_next</span>
                                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=checkoutfields' ) );?>" target="_blank"><?php echo esc_html__( 'Checkout Fields', 'eventprime-event-calendar-management' );?></a>
                            </div>
                        </div>
                        <div class="ep-box-col-12">
                            <button type="button" name="em_event_add_attendee_fields" id="em_event_add_attendee_fields"
                                class="button button-large ep-open-modal"
                                data-id="ep_event_checkout_attendee_fields_modal">
                                <?php esc_html_e( 'Add Field', 'eventprime-event-calendar-management' ); ?>
                            </button>
                        </div>

                        <div id="ep_event_checkout_attendee_fields_modal" class="ep-modal-view"
                            title="<?php esc_html_e( 'Add Checkout Fields', 'eventprime-event-calendar-management' );?>"
                            style="display: none;">
                            <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_event_checkout_attendee_fields_modal"></div>
                            <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out">
                                <div class="ep-modal-body">
                                    <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                                        <h3 class="ep-modal-title ep-px-3">
                                            <?php esc_html_e('Add Attendee Fields', 'eventprime-event-calendar-management'); ?>
                                        </h3>
                                        <a href="#" class="ep-modal-close close-popup" data-id="ep_event_checkout_attendee_fields_modal">&times;</a>
                                    </div>

                                    <div class="ep-modal-content-wrap">
                                        <div class="ep-box-wrap">
                                            <div class="ep-box-row ep-checkout-field-essentials ep-pt-3">
                                                <div class="ep-box-col-12">
                                                    <h3 class="ep-fs-6 ep-mb-3">
                                                        <?php esc_html_e('Core Fields', 'eventprime-event-calendar-management'); ?>
                                                    </h3>
                                                </div>
                                                <div class="ep-box-col-12 ep-d-flex ep-items-center ep-meta-box-data">
                                                    <table class="ep-table ep-table-striped ep-attendee-fields-modal-table ep-checkout-field-table">
                                                        <thead>
                                                            <tr>
                                                                <th><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Description', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Include', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Required', 'eventprime-event-calendar-management' ); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php echo self::ep_get_checkout_essentials_fields_rows( $em_event_checkout_attendee_fields, '_popup' ); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="ep-box-row ep-checkout-field-checkout ep-pt-3">
                                                <div class="ep-box-col-12">
                                                    <h3 class="ep-fs-6 ep-mb-3">
                                                        <?php esc_html_e( 'User Created Fields', 'eventprime-event-calendar-management' ); ?>
                                                    </h3>
                                                    <div class="ep-text-small ep-text-muted">
                                                        <?php esc_html_e( 'You can create fields in Settings', 'eventprime-event-calendar-management' );?>
                                                        <span
                                                            class="material-icons ep-fs-6 ep-align-middle">navigate_next</span>
                                                        <?php esc_html_e( 'Checkout Fields', 'eventprime-event-calendar-management' );?>
                                                    </div>
                                                    </p>
                                                </div>
                                                <div class="ep-box-col-12">
                                                    <table class="ep-table ep-table-striped ep-attendee-fields-modal-table ep-checkout-field-table">
                                                        <thead>
                                                            <tr>
                                                                <th><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Description', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Include', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Required', 'eventprime-event-calendar-management' ); ?></th>
                                                                <?php do_action( 'ep_event_checkout_fields_modal_table_header' );?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $settings_controller = EventM_Factory_Service::ep_get_instance('EventM_Admin_Controller_Settings');
                                                            $get_field_data = $settings_controller->ep_get_checkout_fields_data();
                                                            $event_checkout_fields_arr = array();
                                                            foreach ( $get_field_data as $field ) {?>
                                                                <tr title="<?php echo esc_attr( sprintf( esc_html( 'Add %s field', 'eventprime-event-calendar-management' ), esc_attr( strtolower( $field->label ) ) ) ) ;?>">
                                                                    <td><?php echo esc_html( $field->label ); ?></td>
                                                                    <td><?php echo esc_html( $field->type ); ?></td>
                                                                    <td>
                                                                        <input type="checkbox"
                                                                            name="em_event_checkout_field_ids[]"
                                                                            class="em_event_checkout_field_ids"
                                                                            id="em_event_checkout_field_id_<?php echo esc_attr($field->id); ?>"
                                                                            value="<?php echo esc_attr( $field->id ); ?>"
                                                                            data-label="<?php echo esc_attr( $field->label ); ?>"
                                                                            data-type="<?php echo esc_attr( $field->type ); ?>" 
                                                                            <?php if ( in_array( $field->id, $em_event_checkout_fields_data ) ) { echo 'checked="checked"'; } ?>>
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox"
                                                                            name="em_event_checkout_field_required[]"
                                                                            class="em_event_checkout_field_requires"
                                                                            id="ep_event_checkout_field_required_<?php echo esc_attr( $field->id ); ?>"
                                                                            value="1"
                                                                            data-field_id="<?php echo esc_attr( $field->id ); ?>" 
                                                                            <?php if ( ! empty( $em_event_checkout_fields_data_required ) && isset( $em_event_checkout_fields_data_required[$field->id] ) ) {
                                                                                echo 'checked="checked"';
                                                                            } ?>
                                                                            title="<?php echo esc_attr( sprintf( esc_html( 'Require %s field', 'eventprime-event-calendar-management' ), esc_attr( strtolower( $field->label ) ) ) ) ;?>">
                                                                    </td>
                                                                    <?php do_action( 'ep_event_checkout_fields_modal_table_column', $field->id, $field->label, $em_event_checkout_attendee_fields );?>
                                                                    
                                                                </tr><?php
                                                                $event_checkout_fields_arr[$field->id] = $field->label;
                                                            }?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Wrap End -->
                                        <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right">
                                            <span
                                                class="ep-error-message ep-box-col-5 ep-mr-2 ep-mb-2 ep-text-end"></span>
                                            <button type="button" class="button ep-mr-3 ep-modal-close close-popup"
                                                data-id="ep_event_checkout_attendee_fields_modal"><?php esc_html_e('Close', 'eventprime-event-calendar-management'); ?></button>
                                            <button type="button" class="button button-primary button-large"
                                                id="ep_save_checkout_attendee_fields"><?php esc_html_e('Save changes', 'eventprime-event-calendar-management'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendees field container -->
                        <div id="ep_event_checkout_attendee_fields_container" class="ep-mt-3"
                            <?php if( empty( $em_event_checkout_attendee_fields ) ) { echo 'style="display: none;"'; }?>>
                            <?php if( ! empty( $em_event_checkout_attendee_fields ) ) {?>
                            <div class="ep-event-checkout-name-field">
                                <?php
                                if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name'] ) ) {?>
                                    <input type="checkbox" name="em_event_checkout_name" value="1"
                                    class="ep-form-check-input" id="em_event_checkout_name" checked="checked"
                                    style="display:none;"><?php
                                    // first name
                                    if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name'] ) ) {?>
                                        <div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_first_name_top">
                                            <div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">
                                                <input type="checkbox" name="em_event_checkout_name_first_name" value="1"
                                                    id="em_event_checkout_name_first_name" checked="checked"
                                                    style="display:none;">
                                                    <div class="ep-d-inline-block ep-checkout-field-drag"><span
                                                        class="material-icons ep-fs-6">drag_indicator</span>
                                                    </div>
                                                <div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">
                                                    <?php echo esc_html__( 'First Name', 'eventprime-event-calendar-management' );?>
                                                </div>
                                                <div class="ep-d-inline-block ep-mx-auto ep-text-muted">
                                                    <?php echo esc_html__( 'text', 'eventprime-event-calendar-management' );?>
                                                </div>
                                                <div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span
                                                        class="material-icons ep-cursor ep-event-checkout-fields-expand"
                                                        data-id="ep_event_checkout_fields_first_name_expand">expand_more</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ep-box-col-12 ep-event-checkout-fields-expand-section"id="ep_event_checkout_fields_first_name_expand">
                                            <div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">
                                                <div class="ep-event-checkout-selected-fields-attributes">
                                                    <label for="em_event_checkout_name_first_name_required"><?php
                                                        if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) ) {?>
                                                            <input type="checkbox" name="em_event_checkout_name_first_name_required"
                                                            id="em_event_checkout_name_first_name_required" value="1"
                                                            checked="checked"><?php
                                                        } else{?>
                                                            <input type="checkbox" name="em_event_checkout_name_first_name_required" id="em_event_checkout_name_first_name_required" value="1"><?php
                                                        }?>
                                                        <span><?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?></span>
                                                    </label>
                                                </div>
                                                <div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end"
                                                    data-parent-id="ep_event_checkout_fields_first_name_expand">
                                                    <button type="button"
                                                        name="<?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>"
                                                        class="ep-event-checkout-fields-remove button button-large"
                                                        data-main_id="ep_event_checkout_fields_first_name">
                                                        <?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div><?php
                                    }
                                    // middle name
                                    if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name'] ) ) {?>
                                        <div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_middle_name_top">
                                            <div
                                                class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">
                                                <input type="checkbox" name="em_event_checkout_name_middle_name" value="1"
                                                    id="em_event_checkout_name_middle_name" checked="checked"
                                                    style="display:none;">
                                                    <div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>
                                                <div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">
                                                    <?php echo esc_html__( 'Middle Name', 'eventprime-event-calendar-management' );?>
                                                </div>
                                                <div class="ep-d-inline-block ep-mx-auto ep-text-muted">
                                                    <?php echo esc_html__( 'text', 'eventprime-event-calendar-management' );?>
                                                </div>
                                                <div class="ep-field-options-expand ep-d-inline-block ep-ms-auto">
                                                    <span
                                                        class="material-icons ep-cursor ep-event-checkout-fields-expand"
                                                        data-id="ep_event_checkout_fields_middle_name_expand">expand_more</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ep-box-col-12 ep-event-checkout-fields-expand-section" id="ep_event_checkout_fields_middle_name_expand">
                                            <div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">
                                                <div class="ep-event-checkout-selected-fields-attributes">
                                                    <label
                                                        for="em_event_checkout_name_middle_name_required"><?php
                                                        if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) ) {?>
                                                            <input type="checkbox" name="em_event_checkout_name_middle_name_required"
                                                                id="em_event_checkout_name_middle_name_required" value="1"
                                                                checked="checked"><?php
                                                        } else{?>
                                                            <input type="checkbox" name="em_event_checkout_name_middle_name_required"
                                                                id="em_event_checkout_name_middle_name_required" value="1"><?php
                                                        }?>
                                                        <span><?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?></span>
                                                    </label>
                                                </div>
                                                <div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end"
                                                    data-parent-id="ep_event_checkout_fields_middle_name_expand">
                                                    <button type="button"
                                                        name="<?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>"
                                                        class="ep-event-checkout-fields-remove button button-large"
                                                        data-main_id="ep_event_checkout_fields_middle_name">
                                                        <?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div><?php
                                    }
                                    // last name
                                    if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name'] ) ) {?>
                                        <div class="ep-box-col-12 ep-bg-white"
                                            id="ep_event_checkout_fields_last_name_top">
                                            <div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">
                                                <input type="checkbox" name="em_event_checkout_name_last_name" value="1"
                                                    id="em_event_checkout_name_last_name" checked="checked" style="display:none;">
                                                    <div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>
                                                <div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">
                                                    <?php echo esc_html__( 'Last Name', 'eventprime-event-calendar-management' );?>
                                                </div>
                                                <div class="ep-d-inline-block ep-mx-auto ep-text-muted">
                                                    <?php echo esc_html__( 'text', 'eventprime-event-calendar-management' );?></div>
                                                <div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span
                                                    class="material-icons ep-cursor ep-event-checkout-fields-expand"
                                                    data-id="ep_event_checkout_fields_last_name_expand">expand_more</span></div>
                                            </div>
                                        </div>
                                        <div class="ep-box-col-12 ep-event-checkout-fields-expand-section"
                                            id="ep_event_checkout_fields_last_name_expand">
                                            <div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">
                                                <div class="ep-event-checkout-selected-fields-attributes">
                                                    <label
                                                        for="em_event_checkout_name_last_name_required"><?php
                                                        if( ! empty( $em_event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) ) {?>
                                                            <input type="checkbox" name="em_event_checkout_name_last_name_required"
                                                                id="em_event_checkout_name_last_name_required" value="1"
                                                                checked="checked"><?php
                                                        } else{?>
                                                            <input type="checkbox" name="em_event_checkout_name_last_name_required"
                                                                id="em_event_checkout_name_last_name_required" value="1"><?php
                                                        }?>
                                                        <span><?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?></span>
                                                    </label>
                                                </div>
                                                <div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end"
                                                    data-parent-id="ep_event_checkout_fields_last_name_expand">
                                                    <button type="button"
                                                        name="<?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>"
                                                        class="ep-event-checkout-fields-remove button button-large"
                                                        data-main_id="ep_event_checkout_fields_last_name">
                                                        <?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div><?php
                                    }
                                }?>
                            </div><?php
                            if( ! empty( $em_event_checkout_fields_data ) ) {?>
                                <div class="ep-event-checkout-fields ep-box-row"><?php
                                    foreach( $em_event_checkout_fields_data as $field_data ) {
                                        if( isset( $event_checkout_fields_arr[$field_data] ) && ! empty( $event_checkout_fields_arr[$field_data] ) ) {?>
                                            <div class="ep-box-col-12 ep-bg-white" id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>_top">
                                                <div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">
                                                    <input type="checkbox" name="em_event_checkout_fields_data[]"
                                                        id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>"
                                                        value="<?php echo esc_attr( $field_data );?>" checked="checked"
                                                        style="display:none;">
                                                    <div class="ep-d-inline-block ep-checkout-field-drag">
                                                        <span class="material-icons ep-fs-6">drag_indicator</span>
                                                        </div>
                                                    <div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">
                                                        <?php echo esc_html( $event_checkout_fields_arr[$field_data] );?>
                                                    </div>
                                                    <div class="ep-d-inline-block ep-mx-auto ep-text-muted">
                                                        <?php echo $get_field_data[$field_data]->type;?>
                                                    </div>
                                                    <div class="ep-form-check ep-d-inline-flex ep-mx-auto ep-d-none">
                                                        <input class="ep-form-check-input ep-mr-2" type="checkbox" value="" id="">
                                                        <label class="ep-form-check-label" for="flexCheckDefault">
                                                            <?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                    </div>

                                                    <div class="ep-field-options-expand ep-d-inline-block ep-ms-auto">
                                                        <span class="material-icons ep-cursor ep-event-checkout-fields-expand"
                                                            data-id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>_expand">expand_more</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ep-box-col-12 ep-event-checkout-fields-expand-section"
                                                id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>_expand">
                                                <div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">
                                                    <div class="ep-event-checkout-selected-fields-attributes">
                                                        <?php $checked_att_field = ( ! empty( $em_event_checkout_fields_data_required ) && isset( $em_event_checkout_fields_data_required[$field_data] ) ? 'checked="checked"' : '' ) ;?>
                                                        <label
                                                            for="ep_event_checkout_fields_data_required_<?php echo esc_attr( $field_data );?>">
                                                            <input type="checkbox" name="em_event_checkout_fields_data_required[]"
                                                                id="ep_event_checkout_fields_data_required_<?php echo esc_attr( $field_data );?>"
                                                                value="<?php echo esc_attr( $field_data );?>"
                                                                <?php echo esc_attr( $checked_att_field );?>>
                                                            <span><?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?></span>
                                                        </label>
                                                    </div>

                                                    <?php do_action( 'ep_event_checkout_fields_attributes_data', $field_data, $em_event_checkout_attendee_fields );?>
                                                    
                                                    <div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end"
                                                        data-parent-id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>_expand">
                                                        <button type="button"
                                                            name="<?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>"
                                                            class="ep-event-checkout-fields-remove button button-large"
                                                            data-main_id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>">
                                                            <?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div><?php
                                        }
                                    }?>
                                </div><?php
                                }
                            }?>
                        </div>
                    </div>

                    <div class="ep-box-row ep-mb-3 ep-event-checkout-fixed-fields panel ep_event_checkout_fields_panel"
                        id="ep_event_booking_fields_data">
                        <div class="ep-box-col-12">
                            <div class="ep-text-small ep-alert ep-alert-warning ep-mx-2 ep-my-4">
                                <?php echo esc_html__( 'Booking fields are used to gether additional data from the user during checkout form Step 1. These field appear only once, unlike attendee fields. For example, Terms and Conditions, Additional Instructions etc. To create new fields, go to Settings', 'eventprime-event-calendar-management' );?>
                                <span class="material-icons ep-fs-6 ep-align-middle">navigate_next</span>
                                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=em_event&page=ep-settings&tab=checkoutfields' ) );?>" target="_blank"><?php echo esc_html__( 'Checkout Fields', 'eventprime-event-calendar-management' );?></a>
                            </div>
                        </div>
                        <div class="ep-box-col-12">
                            <button type="button" name="em_event_add_fixed_fields" id="em_event_add_fixed_fields"
                                class="button button-large ep-open-modal"
                                data-id="ep_event_checkout_fixed_fields_modal">
                                <?php esc_html_e( 'Add Field', 'eventprime-event-calendar-management' ); ?>
                            </button>
                        </div>

                        <div id="ep_event_checkout_fixed_fields_modal" class="ep-modal-view"
                            title="<?php esc_html_e( 'Add Booking Fields', 'eventprime-event-calendar-management' );?>"
                            style="display: none;">
                            <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_event_checkout_fixed_fields_modal"></div>
                            <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out">
                                <div class="ep-modal-body">
                                    <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                                        <h3 class="ep-modal-title ep-px-3">
                                            <?php esc_html_e('Add Booking Fields', 'eventprime-event-calendar-management'); ?>
                                        </h3>
                                        <a href="#" class="ep-modal-close close-popup"
                                            data-id="ep_event_checkout_fixed_fields_modal">&times;</a>
                                    </div>

                                    <div class="ep-modal-content-wrap">
                                        <div class="ep-box-wrap ep-checkout-field-essentials">
                                            <h2><?php esc_html_e('Use Core Fields', 'eventprime-event-calendar-management'); ?>
                                            </h2>
                                            <div class="ep-box-row ep-p-3 ep-box-w-75">
                                                <div class="ep-box-col-12">
                                                    <?php echo self::ep_get_checkout_fixed_fields( $em_event_checkout_fixed_fields ); ?>
                                                </div>
                                            </div>
                                            
                                            <div class="ep-box-row ep-checkout-field-checkout ep-pt-3">
                                                <div class="ep-box-col-12">
                                                    <h3 class="ep-fs-6 ep-mb-3">
                                                        <?php esc_html_e( 'User Created Fields', 'eventprime-event-calendar-management' ); ?>
                                                    </h3>
                                                    <div class="ep-text-small ep-text-muted">
                                                        <?php esc_html_e( 'You can create fields in Settings', 'eventprime-event-calendar-management' );?>
                                                        <span
                                                            class="material-icons ep-fs-6 ep-align-middle">navigate_next</span>
                                                        <?php esc_html_e( 'Checkout Fields', 'eventprime-event-calendar-management' );?>
                                                    </div>
                                                    </p>
                                                </div>
                                                <div class="ep-box-col-12">
                                                    <table class="ep-table ep-table-striped ep-booking-fields-modal-table ep-checkout-field-table" id="ep_event_checkout_booking_fields_table">
                                                        <thead>
                                                            <tr>
                                                                <th><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Description', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Include', 'eventprime-event-calendar-management' ); ?></th>
                                                                <th><?php esc_html_e( 'Required', 'eventprime-event-calendar-management' ); ?></th>
                                                                <?php //do_action( 'ep_event_checkout_fields_modal_table_header' );?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $settings_controller = EventM_Factory_Service::ep_get_instance('EventM_Admin_Controller_Settings');
                                                            $get_field_data = $settings_controller->ep_get_checkout_fields_data();
                                                            $event_booking_fields_arr = array();
                                                            foreach ( $get_field_data as $field ) {?>
                                                                <tr title="<?php echo esc_attr( sprintf( esc_html( 'Add %s field', 'eventprime-event-calendar-management' ), esc_attr( strtolower( $field->label ) ) ) ) ;?>">
                                                                    <td><?php echo esc_html( $field->label ); ?></td>
                                                                    <td><?php echo esc_html( $field->type ); ?></td>
                                                                    <td>
                                                                        <input type="checkbox"
                                                                            name="em_event_booking_field_ids[]"
                                                                            class="em_event_booking_field_ids"
                                                                            id="em_event_booking_field_id_<?php echo esc_attr($field->id); ?>"
                                                                            value="<?php echo esc_attr( $field->id ); ?>"
                                                                            data-label="<?php echo esc_attr( $field->label ); ?>"
                                                                            data-type="<?php echo esc_attr( $field->type ); ?>" 
                                                                            <?php if ( in_array( $field->id, $em_event_booking_fields_data ) ) { echo 'checked="checked"'; } ?>>
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox"
                                                                            name="em_event_booking_field_required[]"
                                                                            class="em_event_booking_field_requires"
                                                                            id="ep_event_booking_field_required_<?php echo esc_attr( $field->id ); ?>"
                                                                            value="1"
                                                                            data-field_id="<?php echo esc_attr( $field->id ); ?>" 
                                                                            <?php if ( ! empty( $em_event_booking_fields_data_required ) && isset( $em_event_booking_fields_data_required[$field->id] ) ) {
                                                                                echo 'checked="checked"';
                                                                            } ?>
                                                                            title="<?php echo esc_attr( sprintf( esc_html( 'Require %s field', 'eventprime-event-calendar-management' ), esc_attr( strtolower( $field->label ) ) ) ) ;?>">
                                                                    </td>
                                                                    <?php //do_action( 'ep_event_booking_fields_modal_table_column', $field->id, $field->label, $em_event_checkout_booking_fields );?>
                                                                </tr><?php
                                                                $event_booking_fields_arr[$field->id] = $field->label;
                                                            }?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="ep-box-row ep-p-3">
                                                <div class="ep-box-col-12 ep-mt-3 ep-d-flex ep-items-end ep-content-right"
                                                    id="ep_event_fixed_field_dataset">
                                                    <span
                                                        class="ep-error-message ep-box-col-5 ep-mr-2 ep-mb-2 ep-text-end"
                                                        id="ep_event_fixed_field_bottom_error"></span>
                                                    <button type="button"
                                                        class="button ep-mr-3 ep-modal-close close-popup"
                                                        data-id="ep_event_checkout_fixed_fields_modal"><?php esc_html_e('Close', 'eventprime-event-calendar-management'); ?></button>
                                                    <button type="button" class="button button-primary button-large"
                                                        id="ep_save_checkout_fixed_fields"><?php esc_html_e('Save changes', 'eventprime-event-calendar-management'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fixed field container -->
                        <div id="ep_event_checkout_fixed_fields_container" class="ep-mt-3"
                            <?php //if( empty( $em_event_checkout_fixed_fields ) ) { echo 'style="display: none;"'; }?>>
                            <?php if( ! empty( $em_event_checkout_fixed_fields ) ) {
                                if( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_enabled'] ) ) {?>
                                    <div class="ep-event-checkout-fields"><?php 
                                        if( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] ) ) {
                                            $em_event_checkout_fixed_terms_content = '';
                                            if( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] == 'page' ) {
                                                $em_event_checkout_fixed_terms_content = esc_html( get_the_title( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] ) );
                                            } elseif( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] == 'url' ) {
                                                $em_event_checkout_fixed_terms_content = esc_url( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] );
                                            } elseif( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] == 'content' ) {
                                                $em_event_checkout_fixed_terms_content = wp_kses_post( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] );
                                            }?>
                                            <div class="ep-box-row">
                                                <div class="ep-box-col-12 ep-bg-white"id="ep_event_checkout_fields_fixed_terms_top">
                                                    <div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">
                                                        <input type="checkbox" name="em_event_checkout_fixed_terms_enabled" value="1"
                                                            id="em_event_checkout_fixed_terms_enabled" checked="checked"
                                                            style="display:none;">
                                                        <input type="hidden" name="em_event_checkout_fixed_terms_label"
                                                            value="<?php echo esc_attr( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_label'] );?>">
                                                            <div class="ep-d-inline-block ep-checkout-field-drag"><span class="material-icons ep-fs-6">drag_indicator</span></div>
                                                            <div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">
                                                            <?php echo esc_html( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_label'] );?>
                                                        </div>
                                                        <div class="ep-d-inline-block ep-mx-auto ep-text-muted">
                                                            <?php echo esc_html( ucwords( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] ) );?>
                                                        </div>
                                                        <div class="ep-field-options-expand ep-d-inline-block ep-ms-auto"><span
                                                                class="material-icons ep-cursor ep-event-checkout-fields-expand"
                                                                data-id="ep_event_checkout_fields_fixed_terms_expand">expand_more</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ep-box-col-12 ep-event-checkout-fields-expand-section"id="ep_event_checkout_fields_fixed_terms_expand">
                                                    <input type="hidden" name="em_event_checkout_fixed_terms_option"
                                                        value="<?php echo esc_attr( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] );?>"
                                                        style="display:none;">
                                                    <input type="hidden" name="em_event_checkout_fixed_terms_content"
                                                        value="<?php echo $em_event_checkout_fixed_terms_content;?>"
                                                        style="display:none;">
                                                    <div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">
                                                    <div class="ep-event-checkout-selected-fields-attributes">
                                                        <span class="em-event-checkout-fixed-terms-option">
                                                            <?php echo esc_html( ucwords( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] ) ) . ': ';?>
                                                        </span>
                                                        <span class="em-event-checkout-fixed-terms-content">
                                                            <?php echo $em_event_checkout_fixed_terms_content;?>
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end"
                                                        data-parent-id="ep_event_checkout_fields_fixed_terms_expand">
                                                        <button type="button"
                                                            name="<?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>"
                                                            class="ep-event-checkout-fields-remove button button-large"
                                                            data-main_id="ep_event_checkout_fields_fixed_terms">
                                                            <?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>
                                                        </button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div><?php
                                        }?>
                                    </div><?php
                                }
                            }
                            if( ! empty( $em_event_booking_fields_data ) ) {?>
                                <div class="ep-event-checkout-fields ep-box-row"><?php
                                    foreach( $em_event_booking_fields_data as $field_data ) {
                                        if( isset( $event_checkout_fields_arr[$field_data] ) && ! empty( $event_checkout_fields_arr[$field_data] ) ) {?>
                                            <div class="ep-box-col-12 ep-bg-white" id="ep_event_booking_fields_data_<?php echo esc_attr( $field_data );?>_top">
                                                <div class="ep-border ep-rounded-1 ep-p-2 ep-mx-2 ep-mb-2 ep-d-flex ep-align-items-center ep-text-small">
                                                    <input type="checkbox" name="em_event_booking_fields_data[]"
                                                        id="ep_event_checkout_fields_data_<?php echo esc_attr( $field_data );?>"
                                                        value="<?php echo esc_attr( $field_data );?>" checked="checked"
                                                        style="display:none;">
                                                    <div class="ep-d-inline-block ep-checkout-field-drag">
                                                        <span class="material-icons ep-fs-6">drag_indicator</span>
                                                        </div>
                                                    <div class="ep-d-inline-block ep-ml-3 ep-checkout-field-name">
                                                        <?php echo esc_html( $event_checkout_fields_arr[$field_data] );?>
                                                    </div>
                                                    <div class="ep-d-inline-block ep-mx-auto ep-text-muted">
                                                        <?php echo $get_field_data[$field_data]->type;?>
                                                    </div>
                                                    <div class="ep-form-check ep-d-inline-flex ep-mx-auto ep-d-none">
                                                        <input class="ep-form-check-input ep-mr-2" type="checkbox" value="" id="">
                                                        <label class="ep-form-check-label" for="flexCheckDefault">
                                                            <?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?>
                                                        </label>
                                                    </div>

                                                    <div class="ep-field-options-expand ep-d-inline-block ep-ms-auto">
                                                        <span class="material-icons ep-cursor ep-event-booking-fields-expand"
                                                            data-id="ep_event_booking_fields_data_<?php echo esc_attr( $field_data );?>_expand">expand_more</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="ep-box-col-12 ep-event-checkout-fields-expand-section"
                                                id="ep_event_booking_fields_data_<?php echo esc_attr( $field_data );?>_expand">
                                                <div class="checkout-field-options ep-border ep-rounded-1 ep-p-2 ep-py-4 ep-d-flex ep-justify-content-between ep-mx-2 ep-mb-2 ep-text-small">
                                                    <div class="ep-event-checkout-selected-fields-attributes">
                                                        <?php $checked_att_field = ( ! empty( $em_event_booking_fields_data_required ) && isset( $em_event_booking_fields_data_required[$field_data] ) ? 'checked="checked"' : '' ) ;?>
                                                        <label
                                                            for="ep_event_checkout_fields_data_required_<?php echo esc_attr( $field_data );?>">
                                                            <input type="checkbox" name="em_event_booking_fields_data_required[]"
                                                                id="ep_event_checkout_fields_data_required_<?php echo esc_attr( $field_data );?>"
                                                                value="<?php echo esc_attr( $field_data );?>"
                                                                <?php echo esc_attr( $checked_att_field );?>>
                                                            <span><?php echo esc_html__( 'Required', 'eventprime-event-calendar-management' );?></span>
                                                        </label>
                                                    </div>
                                                    <?php //do_action( 'ep_event_booking_fields_attributes_data', $field_data, $em_event_checkout_booking_fields );?>
                                                    <div class="ep-event-checkout-selected-fields-remove ep-mt-auto ep-text-end"
                                                        data-parent-id="ep_event_booking_fields_data_<?php echo esc_attr( $field_data );?>_expand">
                                                        <button type="button"
                                                            name="<?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>"
                                                            class="ep-event-booking-fields-remove button button-large"
                                                            data-main_id="ep_event_booking_fields_data_<?php echo esc_attr( $field_data );?>">
                                                            <?php echo esc_html__( 'Remove', 'eventprime-event-calendar-management' );?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div><?php
                                        }
                                    }?>
                                </div><?php
                            }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>