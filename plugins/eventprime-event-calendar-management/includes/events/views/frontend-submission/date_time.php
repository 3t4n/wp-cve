<?php
/**
 * View: Frontend Event Submission
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/events/frontend-submission/date_time.php
 *
 */
?>

<div class="ep-fes-section ep-mb-4 ep-border ep-p-4 ep-shadow-sm ep-rounded-1">
    <div class="ep-box-wrap">
        <div class="ep-box-row">
            <div class="ep-box-col-12">
              <div class="ep-fes-section-title ep-fs-5 ep-fw-bold ep-mb-3">
               <?php esc_html_e( 'Date and Time', 'eventprime-event-calendar-management');?>
              </div>
            </div>
        </div>
        <div class="ep-box-row ep-mb-3 ep-items-end">
            <div class="ep-box-col-3 ep-meta-box-data">
                <label class="ep-form-label ep-di-flex ep-align-items-center"><?php esc_html_e('Start Date', 'eventprime-event-calendar-management'); ?>
                    <span id="ep-start-date-hidden" class="material-icons ep-text-muted ep-text-small ep-ml-2" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-start-date">
                    <input type="text" name="em_start_date" id="em_start_date" class="ep-form-control epDatePicker" autocomplete="off" placeholder="<?php esc_html_e('Start Date', 'eventprime-event-calendar-management'); ?>" value="<?php echo isset($args->event) && !empty($args->event->em_start_date) ? ep_timestamp_to_date( $args->event->em_start_date )  : '';?>">
                </div>
            </div>

            <div class="ep-box-col-3 ep-items-end ep-meta-box-data">
                <label class="ep-form-label ep-di-flex ep-align-items-center"><?php esc_html_e('Start Time (optional)', 'eventprime-event-calendar-management'); ?>
                    <span id="ep-start-time-hidden" class="material-icons ep-text-muted ep-text-small ep-ml-2" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-start-time">
                    <input type="text" id="em_start_time" name="em_start_time" class="ep-form-control epTimePicker" value="<?php echo isset($args->event) && !empty($args->event->em_start_time) ?  $args->event->em_start_time   : '';?>">
                </div>
            </div>
            
            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline">
                  <input class="ep-form-check-input" type="checkbox" name="em_hide_event_start_time" id="ep_hide_event_time" value="1" <?php if( isset($args->event) && absint( $args->event->em_hide_event_start_time ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep_hide_event_time"><?php esc_html_e( 'Hide Start Time', 'eventprime-event-calendar-management' );?></label>
                </div>
            </div>

            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input ep-event-date-check" type="checkbox" name="em_hide_event_start_date" id="ep-hide-start-date" value="1" <?php if( isset($args->event) && absint( $args->event->em_hide_event_start_date ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep-hide-start-date"><?php esc_html_e( 'Hide Start Date', 'eventprime-event-calendar-management' );?></label>
                </div>
            </div> 
        </div>
        
        <!-- All day Event Row-->
        <div class="ep-box-row ep-mb-3">
            <div class="ep-box-col-3 ep-d-flex ep-items-center ep-meta-box-data">
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="checkbox" name="em_all_day" id="em_all_day" value="1" <?php if( isset($args->event) && absint( $args->event->em_all_day ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-label" for="em_all_day"><?php esc_html_e( 'It\'s an all day event', 'eventprime-event-calendar-management' );?></label>
                </div>
            </div>
        </div>

        <!-- All day Event Row Ends:-->
        <div class="ep-box-row ep-py-3 ep-mb-3 ep-items-end">
            <div class="ep-box-col-3 ep-meta-box-data">
                <label class="ep-form-label"> <?php esc_html_e( 'End Date', 'eventprime-event-calendar-management' ); ?>
                    <span id="ep-end-date-hidden" class="material-icons ep-text-muted ep-text-small ep-ml-2" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-end-date">
                    <input type="text" name="em_end_date" id="em_end_date" class="ep-form-control epDatePicker" autocomplete="off" placeholder="<?php esc_html_e('End Date', 'eventprime-event-calendar-management'); ?>" value="<?php echo isset($args->event) && !empty($args->event->em_end_date) ? ep_timestamp_to_date( $args->event->em_end_date )  : '';?>">
                </div>
            </div>
            
            <div class="ep-box-col-3 ep-meta-box-data">
                <label class="ep-form-label"><?php esc_html_e( 'End Time (optional)', 'eventprime-event-calendar-management' ); ?>
                    <span id="ep-end-time-hidden" class="material-icons ep-text-muted ep-text-small ep-ml-2" style="display: none;">visibility_off</span>
                </label>
                <div class="ep-event-start-time">
                    <input type="text" id="em_end_time" name="em_end_time" class="ep-form-control epTimePicker" value="<?php echo isset($args->event) && !empty($args->event->em_end_time) ?  $args->event->em_end_time   : '';?>">                
                </div>
            </div>
            
            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="ep-form-check ep-form-check-inline">
                    <input type="checkbox" name="em_hide_event_end_time" id="ep_hide_event_end_time" value="1" <?php if( isset($args->event) && absint( $args->event->em_hide_event_end_time ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep_hide_event_end_time"><?php esc_html_e( 'Hide End Time', 'eventprime-event-calendar-management' ); ?></label>
                </div>
            </div>

            <div class="ep-box-col-3 ep-d-flex ep-items-center">
                <div class="form-check form-check-inline">
                    <input class="ep-form-check-input date-check" type="checkbox" name="em_hide_end_date" id="ep-hide-end-date" value="1" <?php if( isset($args->event) && absint( $args->event->em_hide_end_date ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep-hide-end-date"><?php esc_html_e( 'Hide End Date', 'eventprime-event-calendar-management' ); ?></label>
                </div>
            </div> 
        </div>

        <!-- Date Placeholder Options -->
        <div class="ep-box-row ep-py-3 ep-mb-3" id="ep-date-note" <?php if(isset($args->event) && !empty( $args->event->em_hide_event_start_date ) ) { echo 'style="display: block;"';}else{ echo 'style="display: none;"'; } ?> >
            <div class="ep-box-12 ep-mb-3">
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="radio" name="em_event_date_placeholder" id="tbd" value="tbd" <?php if( ! empty( $args->event ) && ! empty( $args->event->em_event_date_placeholder ) && esc_attr( $args->event->em_event_date_placeholder ) == 'tbd' ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="tbd" style="width: 25px;">
                        <?php $tbd_icon_file = EP_BASE_URL .'/includes/assets/images/tbd-icon.png';?>
                        <img src="<?php echo esc_url( $tbd_icon_file );?>" width="25" />
                    </label>
                </div>
                <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="radio" name="em_event_date_placeholder" id="ep-date-custom-note" value="custom_note" <?php if( ! empty( $args->event ) && ! empty( $args->event->em_event_date_placeholder ) && esc_attr( $args->event->em_event_date_placeholder ) == 'custom_note' ) { echo 'checked="checked"'; }?> >
                    <label class="form-check-label" for="ep-date-custom-note">
                        <?php esc_html_e( 'Custom Text', 'eventprime-event-calendar-management'); ?>
                    </label>
                </div>
            </div>
            
            <div class="col-md-12" id="ep-date-custom-note-content" style="<?php if( isset($args->event) && esc_attr( $args->event->em_event_date_placeholder ) == 'custom_note' ) { echo 'display:block;'; } else{ echo 'display:none;';} ?>">
                <label class="ep-form-label"><?php esc_html_e( 'Date Placeholder Note', 'eventprime-event-calendar-management'); ?></label>
                <input type="text" class="ep-form-control" name="em_event_date_placeholder_custom_note" value="<?php echo isset($args->event) && isset($args->event->em_event_date_placeholder_custom_note) ? $args->event->em_event_date_placeholder_custom_note : '';?>">
                <span class="ep-text-muted ep-text-small">
                <?php printf( esc_html__( 'Since you chose to hide both the dates, this note will be displayed where date usually appears on the frontned. You can use text like %s etc. here.', 'eventprime-event-calendar-management' ), '<strong>To Be Decided</strong>' ); ?></span>
            </div>
        </div>

        <!-- Hidden Radio Options End -->
      
        <!-- Additional Dates Question Wrapper  --> 
        <div class="ep-box-row ep-py-3">
            <div class="ep-box-col-12 ep-d-fle ep-items-center">
                  <div class="ep-form-check ep-form-check-inline">
                    <input class="ep-form-check-input" type="checkbox" name="em_event_more_dates" id="ep-add-more-dates" value="1" <?php if( absint( isset($args->event) && $args->event->em_event_more_dates ) == 1 ) { echo 'checked="checked"'; }?> >
                    <label class="ep-form-check-label" for="ep-add-more-dates"><?php esc_html_e( 'Add more dates?', 'eventprime-event-calendar-management' ); ?>
                    <span class="ep-text-muted ep-text-small"><?php esc_html_e( '(Additional relevant non-event related dates.)', 'eventprime-event-calendar-management' ); ?></span>
                    </label>
                </div>  
            </div>
        </div>
        
        <!-- Additional Dates --->
        <div class="ep-additional-date-wrapper" id="ep-event-additional-date-wrapper" style="<?php if( isset($args->event) && absint( $args->event->em_event_more_dates ) == 1 ) { echo 'display:block;'; }else{ echo 'display:none;';}?>">
            <!-- Add Data Button -->
            <div class="ep-box-row ep-pb-3">
                <div class="ep-box-col-3 ">
                    <button type="button" class="ep-btn ep-btn-primary" id="add_new_date_field">
                        <?php esc_html_e('Add', 'eventprime-event-calendar-management'); ?>
                    </button>
                </div>
            </div>
            
            <?php $note_count =1;
            if( isset( $args->event ) && isset( $args->event->em_event_add_more_dates ) && ! empty( $args->event->em_event_add_more_dates ) ) {
                foreach( $args->event->em_event_add_more_dates as $event_dates ) {?>
                    <div class=" ep-box-row ep-py-3 ep-mb-3 ep-items-end ep-additional-date-row" id="ep-additional-date-row<?php echo $note_count;?>">
                        <input type="hidden" name="em_event_add_more_dates[<?php echo $note_count;?>][uid]" value="<?php echo $event_dates['uid'];?>">
                        <div class="ep-box-col-3 ep-meta-box-data">
                            <label class="ep-form-label"><?php esc_html_e( 'Date', 'eventprime-event-calendar-management' ); ?></label>
                            <div class="ep-event-start-time">
                                <input type="text" name="em_event_add_more_dates[<?php echo $note_count;?>][date]" class="ep-form-control ep-ad-event-date epDatePicker" autocomplete="off" value="<?php echo isset($event_dates['date']) ? ep_timestamp_to_date( $event_dates['date'] )  : '';?>">                
                            </div>
                        </div>

                        <div class="ep-box-col-3 ep-meta-box-data">
                            <label class="ep-form-label"><?php esc_html_e( 'Time (Optional)', 'eventprime-event-calendar-management' ); ?>
                            </label>
                            <div class="ep-event-start-time">
                                <input type="text" name="em_event_add_more_dates[<?php echo $note_count;?>][time]" class="ep-form-control ep-ad-event-time epTimePicker" autocomplete="off" value="<?php echo isset($event_dates['time']) ? $event_dates['time']  : '';?>">                
                            </div>
                        </div>

                        <div class="ep-box-col-3 ep-meta-box-data">
                            <label class="ep-form-label"><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>
                            </label>
                            <div class="ep-event-start-time">
                                <input type="text" name="em_event_add_more_dates[<?php echo $note_count;?>][label]" placeholder="<?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>" class="ep-form-control ep-ad-event-label" autocomplete="off" value="<?php echo isset($event_dates['label']) ? $event_dates['label']   : '';?>">                
                            </div>
                        </div>

                        <div class="ep-box-col-3 ">
                            <a href="javascript:void(0)" data-parent_id="ep-additional-date-row<?php echo esc_attr( $note_count );?>" class="ep-remove-additional-date ep-item-delete"><?php esc_html_e( 'Delete', 'eventprime-event-calendar-management' ); ?></a>
                        </div>
                    </div><?php 
                    $note_count++;
                } 
            }else{?>
                <div class=" ep-box-row ep-py-3 ep-mb-3 ep-items-end ep-additional-date-row" id="ep-additional-date-row1">
                    <input type="hidden" name="em_event_add_more_dates[1][uid]" value="<?php echo esc_html( time() );?>">
                    <div class="ep-box-col-3 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Date', 'eventprime-event-calendar-management' ); ?></label>
                        <div class="ep-event-start-time">
                            <input type="text" name="em_event_add_more_dates[1][date]" class="ep-form-control ep-ad-event-date epDatePicker" autocomplete="off">                
                        </div>
                    </div>

                    <div class="ep-box-col-3 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Time (Optional)', 'eventprime-event-calendar-management' ); ?>
                        </label>
                        <div class="ep-event-start-time">
                            <input type="text" name="em_event_add_more_dates[1][time]" class="ep-form-control ep-ad-event-time epTimePicker" autocomplete="off">                
                        </div>
                    </div>

                    <div class="ep-box-col-3 ep-meta-box-data">
                        <label class="ep-form-label"><?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>
                        </label>
                        <div class="ep-event-start-time">
                            <input type="text" name="em_event_add_more_dates[1][label]" placeholder="<?php esc_html_e( 'Label', 'eventprime-event-calendar-management' ); ?>" class="ep-form-control ep-ad-event-label" autocomplete="off">                
                        </div>
                    </div>

                    <div class="ep-box-col-3 ">
                        <a href="javascript:void(0)" data-parent_id="ep-additional-date-row<?php echo esc_attr( $note_count );?>" class="ep-remove-additional-date ep-item-delete">Delete</a>
                    </div>
                </div><?php 
            }?>
        </div>
    </div>
</div>