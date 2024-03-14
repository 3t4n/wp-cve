<div class="emagic">

<div class="ep-setting-tab-content">
    <h2 class="ep-mt-3"><?php esc_html_e( 'Checkout Fields', 'eventprime-event-calendar-management' );?></h2>
    <a href="javascript:void(0)" class="button ep-open-modal" data-id="ep_event_settings_checkout_fields_container" id="em_add_new_checkout_field" title="<?php esc_html_e( 'Add New Checkout Field', 'eventprime-event-calendar-management' );?>"><?php esc_html_e( 'Add New Field', 'eventprime-event-calendar-management' );?></a>
    <input type="hidden" name="em_setting_type" value="checkout_fields_settings">
</div>

<table class="ep-setting-table-main">
    <tbody>
        <tr>
            <td class="ep-setting-table-wrap" colspan="2">
                <table class="ep-setting-table ep-setting-table-wide ep-setting-checkout-table" cellspacing="0" id="ep_settings_checkout_field_lists">
                    <thead>
                        <tr>
                            <th>
                                <?php esc_html_e('Label', 'eventprime-event-calendar-management'); ?>
                            </th>
                            <th>
                                <?php esc_html_e('Type', 'eventprime-event-calendar-management'); ?>
                            </th>
                            <th>
                                <?php esc_html_e('Created', 'eventprime-event-calendar-management'); ?>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( isset( $options['checkout_fields_data'] ) && count( $options['checkout_fields_data'] ) > 0 ) {
                            foreach ( $options['checkout_fields_data'] as $fields ) {?>
                                <tr id="ep-checkout-field-<?php echo esc_attr( $fields->id) ; ?>">
                                    <td class="em-checkout-field-label">
                                    <?php echo esc_html( $fields->label ); ?>
                                    </td>
                                    <td class="em-checkout-field-type">
                                        <?php echo esc_html( $fields->type ); ?>
                                    </td>
                                    <td><?php 
                                        $format = get_option('date_format').' '.get_option('time_format');
                                        if( ! empty( $format ) ) {
                                            echo esc_html( date( $format, strtotime( $fields->created_at ) ) );
                                        } else{
                                            echo esc_html( $fields->created_at );
                                        } ?>
                                    </td>
                                    <td>
                                        <div class="ep-checkout-field-action">
                                            <a href="javascript:void(0)" class="ep-edit-checkout-field ep-color-primary ep-cursor" id="ep_edit_checkout_field_<?php echo esc_attr( $fields->id ); ?>" data-field_id="<?php echo esc_attr( $fields->id ); ?>" data-field_label="<?php echo esc_attr( $fields->label ); ?>" data-field_type="<?php echo esc_attr( $fields->type ); ?>" title="<?php esc_attr_e( 'Edit Field', 'eventprime-event-calendar-management' ); ?>">
                                               <?php esc_html_e( 'Edit', 'eventprime-event-calendar-management' ); ?> 
                                            </a>
                                            <a href="javascript:void(0)"  class="ep-delete-checkout-field ep-item-delete ep-open-modal ep-ml-3" data-id="ep_event_settings_delete_checkout_field" id="ep_delete_checkout_field_<?php echo esc_attr( $fields->id ); ?>" data-field_id="<?php echo esc_attr( $fields->id ); ?>" data-field_label="<?php echo esc_attr( $fields->label ); ?>" data-field_type="<?php echo esc_attr( $fields->type ); ?>" title="<?php esc_attr_e( 'Delete Field', 'eventprime-event-calendar-management' ); ?>">
                                                <?php esc_html_e( 'Delete', 'eventprime-event-calendar-management' ); ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr><?php
                            }
                        }?>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<div id="ep_event_settings_checkout_fields_container" class="ep-modal-view" title="<?php esc_attr_e( 'Add New Field', 'eventprime-event-calendar-management' );?>" style="display: none;">
    <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_event_settings_checkout_fields_container"></div>
    <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out"> 
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                <h3 class="ep-modal-title ep-px-3">
                    <?php esc_html_e('Add New Field', 'eventprime-event-calendar-management'); ?>
                </h3>
                <a href="#" class="ep-modal-close ep-checkout-field-modal-close" data-id="ep_event_settings_checkout_fields_container">&times;</a>
            </div> 
            <div class="ep-modal-content-wrap"> 
                <div class="ep-box-wrap">
                    <div class="ep-box-row ep-p-3 ep-settings-checkout-field-manager">
                        <input type="hidden" name="em_checkout_field_id" id="em_checkout_field_id" value="">
                        <div class="ep-box-col-12 form-field">
                            <label for="em_checkout_field_label" class="ep-form-label">
                                <?php esc_html_e('Label', 'eventprime-event-calendar-management'); ?>
                                <span class="ep-required-field">*</span>
                            </label>
                            <div class="ep-checkout-field-input" >
                                <input type="text" name="em_checkout_field_label" class="ep-form-control" id="em_checkout_field_label" placeholder="<?php echo esc_attr('Enter Label', 'eventprime-event-calendar-management'); ?>">
                            </div>
                            <div class="ep-error-message" id="em_checkout_field_label_error"></div>
                        </div>
                        <div class="ep-box-col-12 ep-mt-3 form-field" id="ep_settings_checkout_fields_type">
                            <label for="em_checkout_field_type" class="ep-form-label">
                                <?php esc_html_e('Select Type', 'eventprime-event-calendar-management'); ?>
                                <span class="ep-required-field">*</span>
                            </label>
                            <div class="ep-checkout-field-input">
                                <select name="em_checkout_field_type" class="ep-form-control ep-checkout-field-type" id="em_checkout_field_type">
                                    <option value=""><?php esc_html_e('Select Field Type', 'eventprime-event-calendar-management'); ?></option>
                                    <?php foreach ( $options['checkout_field_types'] as $type_key => $type ) { ?>
                                        <option value="<?php echo esc_attr( $type_key ); ?>"><?php echo esc_html( $type ); ?></option><?php }
                                    ?>
                                </select>
                            </div>
                            <div class="ep-error-message" id="em_checkout_field_type_error"></div>
                        </div>
                    </div>
                </div>
                <!-- Modal Wrap Ends: -->
                <div class="ep-modal-footer ep-d-flex ep-items-end ep-content-right" id="ep_modal_buttonset">
                    <button type="button" class="button ep-mr-3 ep-modal-close ep-checkout-field-modal-close close-popup" data-id="ep_event_settings_checkout_fields_container" title="<?php echo esc_attr( 'Close', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Close', 'eventprime-event-calendar-management'); ?></button>
                    <button type="button" class="button button-primary button-large" id="ep_save_checkout_field" title="<?php echo esc_attr( 'Save', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Save', 'eventprime-event-calendar-management'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="ep_event_settings_delete_checkout_field" class="ep-modal-view" title="<?php esc_attr_e( 'Delete Field', 'eventprime-event-calendar-management' );?>" style="display: none;">
    <div class="ep-modal-overlay ep-modal-overlay-fade-in close-popup" data-id="ep_event_settings_delete_checkout_field"></div>
    <div class="popup-content ep-modal-wrap ep-modal-sm ep-modal-out"> 
        <div class="ep-modal-body">
            <div class="ep-modal-titlebar ep-d-flex ep-items-center">
                <h3 class="ep-modal-title ep-px-3">
                    <?php esc_html_e( 'Delete Field', 'eventprime-event-calendar-management' ); ?>
                </h3>
                <a href="#" class="ep-modal-close close-popup" data-id="ep_event_settings_delete_checkout_field">&times;</a>
            </div> 
            <div class="ep-modal-content-wrap"> 
                <div class="ep-box-wrap">
                    <div class="ep-box-row ep-p-3 ep-settings-checkout-field-manager">
                        <input type="hidden" name="em_checkout_field_id_delete" id="em_checkout_field_id_delete" value="">
                        <div class="ep-box-col-12 form-field">
                            <?php esc_html_e( 'Are you sure you want to delete this field?', 'eventprime-event-calendar-management' );?>
                        </div>
                    </div>
                </div>
                <div class="ep-modal-footer ep-mt-3 ep-d-flex ep-items-end ep-content-right" id="ep_modal_buttonset">
                    <button type="button" class="button ep-mr-3 ep-modal-close close-popup" data-id="ep_event_settings_delete_checkout_field" id="em_delete_modal_cancel_button" title="<?php echo esc_attr( 'Cancel', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Cancel', 'eventprime-event-calendar-management'); ?></button>
                    <button type="button" class="button button-primary button-large" id="em_delete_checkout_fields" title="<?php echo esc_attr( 'Delete', 'eventprime-event-calendar-management' ); ?>"><?php esc_html_e('Delete', 'eventprime-event-calendar-management'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>