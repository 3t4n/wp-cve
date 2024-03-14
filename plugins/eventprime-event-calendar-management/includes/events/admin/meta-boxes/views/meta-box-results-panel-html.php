<?php
/**
 * Event result panel html.
 */
defined('ABSPATH') || exit;
$date_format = 'Y-m-d';
if( ! empty( ep_get_global_settings( 'datepicker_format' ) ) ) {
    $datepicker_format = explode( '&', ep_get_global_settings( 'datepicker_format' ) );
    if( ! empty( $datepicker_format ) ) {
        $date_format = $datepicker_format[1];
    }
}
$ep_selected_result_page    = get_post_meta( $post->ID, 'ep_select_result_page', true );
$ep_result_start_from_type = get_post_meta( $post->ID, 'ep_result_start_from_type', true );
$ep_result_start_date = get_post_meta( $post->ID, 'ep_result_start_date', true );

$ep_result_start_time =  get_post_meta( $post->ID, 'ep_result_start_time', true );
$ep_result_start_days =  get_post_meta( $post->ID, 'ep_result_start_days', true );
$ep_result_start_days_option =  get_post_meta( $post->ID, 'ep_result_start_days_option', true );
$ep_result_start_event_option =  get_post_meta( $post->ID, 'ep_result_start_event_option', true );
$pages = ep_get_all_pages_list();
?>
<div id="ep_event_results_data" class="panel ep_event_options_panel">
    <div class="ep-box-wrap ep-my-3">
        <div class="ep-box-row ep-mb-3 ep-items-end">
            <div class="ep-box-col-6 ep-meta-box-data">
                <label class="ep-form-label"><?php esc_html_e( 'Select Result Page', 'eventprime-event-calendar-management' ); ?>
                    <span id="ep-start-date-hidden" class="material-icons ep-text-muted" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-result-start-date">
                    <select name="ep_select_result_page" required class="ep-form-control">
                        <option value=""><?php echo esc_html( 'Please Select', 'eventprime-event-calendar-management' );?></option><?php
                        if( count( $pages ) ) {
                            foreach( $pages as $page_id => $page_title ) {
                                if( $ep_selected_result_page == $page_id ) {?>
                                    <option value="<?php echo esc_attr( $page_id ); ?>" selected><?php echo esc_html( $page_title ); ?></option><?php
                                }else{?>
                                    <option value="<?php echo esc_attr( $page_id ); ?>"><?php echo esc_html( $page_title ); ?></option><?php
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="ep-box-col-6 ep-mt-3">
            <div class="ep-box-row">
                <div class="ep-box-col-12">
                    <label class="ep-form-check-label" for="ep_result_allow_until">
                        <?php esc_html_e( 'Show Result From', 'eventprime-result' );?>
                    </label>
                    <select class="ep-form-control ep_result_start_from_type" id="ep_result_start_from_type" name="ep_result_start_from_type">
                        <option value="" hidden><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                        <option value="custom_date" <?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type == 'custom_date' ? 'selected' : '' );?>><?php esc_html_e( 'Custom Date', 'eventprime-event-calendar-management' );?></option>
                        <option value="event_date" <?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type == 'event_date' ? 'selected' : '' );?>><?php esc_html_e( 'Event Date', 'eventprime-event-calendar-management' );?></option>
                        <option value="relative_date" <?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type == 'relative_date' ? 'selected' : '' );?>><?php esc_html_e( 'Relative Date', 'eventprime-event-calendar-management' );?></option>
                    </select>
                </div>
                <div class="ep-box-col-6 ep-mt-3 ep_result_start_from_type_options ep_result_start_from_type_custom_date" style="<?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type != 'custom_date' ? 'display: none;' : '' );?>" >
                    <label class="ep-form-label">
                        <?php esc_html_e( 'Choose Date', 'eventprime-event-calendar-management' );?>
                    </label>
                    <input type="text" class="ep-form-control ep_metabox_custom_date_picker" name="ep_result_start_date" id="ep_result_start_date" data-start="event_start" value="<?php echo ( ! empty( $ep_result_start_date ) ? esc_attr( ep_timestamp_to_date( $ep_result_start_date ) ) : '' );?>">
                </div>
                <div class="ep-box-col-6 ep-mt-3 ep_result_start_from_type_options ep_result_start_from_type_custom_date" style="<?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type != 'custom_date' ? 'display: none;' : '' );?>">
                    <label class="ep-form-label">
                        <?php esc_html_e( 'Choose Time', 'eventprime-event-calendar-management' );?>
                    </label>
                    <input type="text" class="ep-form-control epTimePicker" name="ep_result_start_time" id="ep_result_start_time" value="<?php echo ( ! empty( $ep_result_start_time ) ? esc_attr( $ep_result_start_time ) : '' );?>">
                </div> 
                <div class="ep-box-col-6 ep-mt-3 ep_result_start_from_type_options ep_result_start_from_type_relative_date" style="<?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type != 'relative_date' ? 'display: none;' : '' );?>">
                    <label class="ep-form-label">
                        <?php esc_html_e( 'Enter Days', 'eventprime-event-calendar-management' );?>
                    </label>
                    <input type="number" class="ep-form-control" name="ep_result_start_days" id="ep_result_start_days" min="0" value="<?php echo ( ! empty( $ep_result_start_days ) ? absint( $ep_result_start_days ) : '' );?>">
                </div>
                <div class="ep-box-col-6 ep-mt-3 ep_result_start_from_type_options ep_result_start_from_type_relative_date" style="<?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type != 'relative_date' ? 'display: none;' : '' );?>">
                    <label class="ep-form-label">
                        <?php esc_html_e( 'Days Option', 'eventprime-event-calendar-management' );?>
                    </label>
                    <select class="ep-form-control" name="ep_result_start_days_option" id="ep_result_start_days_option">
                        <option value="before" <?php echo ( ! empty( $ep_result_start_days_option ) && $ep_result_start_days_option == 'before' ? 'selected' : '' );?>><?php esc_html_e( 'Days Before', 'eventprime-event-calendar-management');?></option>
                        <option value="after" <?php echo ( ! empty( $ep_result_start_days_option ) && $ep_result_start_days_option == 'after' ? 'selected' : '' );?>><?php esc_html_e( 'Days After', 'eventprime-event-calendar-management');?></option>
                    </select>
                </div>
                <div class="ep-box-col-12 ep-mt-3 ep_result_start_from_type_options ep_result_start_from_type_event_date ep_result_start_from_type_relative_date" style="<?php echo ( ! empty( $ep_result_start_from_type ) && $ep_result_start_from_type != 'relative_date' && $ep_result_start_from_type != 'event_date' ? 'display: none;' : '' );?>">
                    <label class="ep-form-label">
                        <?php esc_html_e( 'Event Option', 'eventprime-event-calendar-management' );?>
                    </label>
                    <select class="ep-form-control" name="ep_result_start_event_option" id="ep_result_start_event_option">
                    <?php $existing_cat_data = self::get_ticket_booking_event_date_options( $post->ID );
                            if( ! empty( $existing_cat_data ) ) {
                                foreach( $existing_cat_data as $key => $option ) {?>
                                    <option value="<?php echo esc_attr( $key );?>" <?php if( $ep_result_start_event_option == $key ){ echo 'selected'; } ?>><?php echo esc_html( $option );?></option><?php
                                }
                            }?>
                    </select>
                </div>
            </div>
        </div>
     
    </div>
</div>